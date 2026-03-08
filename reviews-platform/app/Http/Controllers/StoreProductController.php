<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use App\Models\StoreRequest;
use App\Models\StoreSetting;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StoreProductController extends Controller
{
    public function __construct(
        private CloudinaryService $cloudinary
    ) {
    }

    private function ensureProprietor(): void
    {
        if (!auth()->user()->isProprietario()) {
            abort(403, __('store.only_proprietor_can_manage'));
        }
    }

    public function index()
    {
        $this->ensureProprietor();
        if (!Schema::hasTable('store_products')) {
            $products = collect();
            $notificationEmail = null;
            return view('store.products.index', compact('products', 'notificationEmail'));
        }
        $products = StoreProduct::ordered()->get();
        $notificationEmail = StoreSetting::getNotificationEmail();
        return view('store.products.index', compact('products', 'notificationEmail'));
    }

    public function create()
    {
        $this->ensureProprietor();
        $notificationEmail = StoreSetting::getNotificationEmail();
        return view('store.products.create', compact('notificationEmail'));
    }

    public function store(Request $request)
    {
        $this->ensureProprietor();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'store_notification_email' => 'nullable|email|max:255',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($request->input('sort_order', 0));
        $notificationEmail = $request->filled('store_notification_email')
            ? $request->input('store_notification_email')
            : null;
        unset($validated['store_notification_email']);

        $imageUrl = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageUrl = $this->cloudinary->upload($request->file('image'), 'store');
        }
        unset($validated['image']);
        $validated['image'] = $imageUrl;

        StoreProduct::create($validated);
        StoreSetting::setNotificationEmail($notificationEmail);

        return redirect()->route('store.products.index')->with('success', __('store.product_created'));
    }

    public function edit($id)
    {
        $this->ensureProprietor();
        $product = StoreProduct::findOrFail($id);
        return view('store.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureProprietor();
        $product = StoreProduct::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($request->input('sort_order', 0));

        $imageUrl = $product->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $url = $this->cloudinary->upload($request->file('image'), 'store');
            if ($url) {
                $imageUrl = $url;
            }
        }
        unset($validated['image']);
        $validated['image'] = $imageUrl;

        $product->update($validated);
        return redirect()->route('store.products.index')->with('success', __('store.product_updated'));
    }

    public function destroy($id)
    {
        $this->ensureProprietor();
        $product = StoreProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('store.products.index')->with('success', __('store.product_deleted'));
    }

    /** Lista de solicitações (apenas proprietário). */
    public function requests()
    {
        $this->ensureProprietor();
        if (!Schema::hasTable('store_requests')) {
            return view('store.requests.index', ['requests' => collect()]);
        }
        $requests = StoreRequest::with('user')->latest()->get();
        return view('store.requests.index', compact('requests'));
    }

    /** Atualizar status de uma solicitação. */
    public function updateRequestStatus(Request $request, $id)
    {
        $this->ensureProprietor();
        $storeRequest = StoreRequest::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,contacted,completed,cancelled']);
        $storeRequest->update(['status' => $request->status]);
        return redirect()->route('store.requests.index')->with('success', __('store.request_status_updated'));
    }

    /** Exibir formulário de configuração da loja (e-mail de solicitações). */
    public function settings()
    {
        $this->ensureProprietor();
        $notificationEmail = StoreSetting::getNotificationEmail();
        return view('store.settings', compact('notificationEmail'));
    }

    /** Salvar configuração da loja (e-mail de solicitações). */
    public function updateSettings(Request $request)
    {
        $this->ensureProprietor();
        $request->validate([
            'store_notification_email' => 'nullable|email|max:255',
        ]);
        StoreSetting::setNotificationEmail($request->input('store_notification_email'));
        return redirect()->route('store.products.index')->with('success', __('store.notification_email_saved'));
    }
}
