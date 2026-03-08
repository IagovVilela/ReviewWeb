<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use App\Models\StoreRequest;
use App\Models\StoreSetting;
use App\Models\User;
use App\Services\TransactionalEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class StoreController extends Controller
{
    /**
     * Catálogo da loja (material de coleta). Produtos do sistema ou link externo se configurado e sem produtos.
     */
    public function index()
    {
        try {
            if (!Schema::hasTable('store_products')) {
                $products = collect();
                $storeUrl = config('store.url');
                return view('store.index', compact('products', 'storeUrl'));
            }
            $products = StoreProduct::active()->ordered()->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Store index query failed', ['error' => $e->getMessage()]);
            $products = collect();
        }
        $storeUrl = config('store.url');
        return view('store.index', compact('products', 'storeUrl'));
    }

    /**
     * Enviar solicitação de material (itens + mensagem opcional).
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:store_products,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'message' => 'nullable|string|max:2000',
            'phone' => 'nullable|string|max:20',
        ]);

        $items = collect($request->items)
            ->filter(fn ($row) => (int) ($row['quantity'] ?? 0) > 0)
            ->map(fn ($row) => [
                'product_id' => (int) $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity' => (int) $row['quantity'],
            ])
            ->values()
            ->all();

        if (empty($items)) {
            return redirect()->route('store')->with('error', __('store.select_at_least_one'));
        }

        $phoneDigits = StoreRequest::phoneToDigits($request->input('phone'));
        $storeRequest = StoreRequest::create([
            'user_id' => auth()->id(),
            'items' => $items,
            'message' => $request->input('message'),
            'phone' => $phoneDigits,
            'status' => 'pending',
        ]);

        $storeRequest->load('user');

        $recipients = [];
        $notificationEmail = StoreSetting::getNotificationEmail();
        if ($notificationEmail !== null && $notificationEmail !== '') {
            $recipients[] = ['email' => $notificationEmail, 'name' => null];
        } else {
            $proprietarios = User::where('role', 'proprietario')
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();
            foreach ($proprietarios as $p) {
                $recipients[] = ['email' => $p->email, 'name' => $p->name];
            }
        }

        if (!empty($recipients)) {
            try {
                $htmlContent = View::make('emails.store-request-notification', ['request' => $storeRequest])->render();
                $subject = __('store.email_subject_request');
                $emailService = app(TransactionalEmailService::class);

                foreach ($recipients as $r) {
                    try {
                        $emailService->send($r['email'], $subject, $htmlContent, $r['name']);
                    } catch (\Throwable $e) {
                        Log::warning('Falha ao enviar e-mail de solicitação da loja', [
                            'email' => $r['email'],
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Erro ao preparar/enviar e-mail de solicitação da loja', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('store')->with('success', __('store.request_submitted'));
    }
}
