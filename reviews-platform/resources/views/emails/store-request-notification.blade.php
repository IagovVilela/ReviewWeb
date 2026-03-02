<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('store.email_subject_request') }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; }
        .container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-top: 5px solid #2563eb; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #e5e7eb; }
        .badge { display: inline-block; padding: 10px 18px; border-radius: 8px; font-weight: bold; font-size: 15px; background: #2563eb; color: white; }
        .info-card { background-color: #f8fafc; padding: 16px; border-radius: 8px; margin: 16px 0; border-left: 4px solid #2563eb; }
        .info-row { padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #64748b; }
        table.items { width: 100%; border-collapse: collapse; margin: 12px 0; }
        table.items th, table.items td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table.items th { background: #f1f5f9; color: #475569; font-size: 13px; }
        .message-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 12px; margin-top: 12px; }
        .footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="badge">{{ __('store.email_badge') }}</span>
            <h1 style="margin: 16px 0 0; font-size: 20px; color: #1e293b;">{{ __('store.email_title') }}</h1>
        </div>
        <div class="info-card">
            <div class="info-row">
                <span class="label">{{ __('store.email_requested_by') }}:</span>
                {{ $request->user->name ?? '—' }} ({{ $request->user->email ?? '—' }})
            </div>
            <div class="info-row">
                <span class="label">{{ __('store.email_date') }}:</span>
                {{ $request->created_at->format('d/m/Y H:i') }}
            </div>
            @if($request->formatted_phone)
            <div class="info-row">
                <span class="label">{{ __('store.phone') }}:</span>
                {{ $request->formatted_phone }}
                @if($request->whatsapp_url)
                    <a href="{{ $request->whatsapp_url }}" style="margin-left: 8px; color: #25D366;">WhatsApp</a>
                @endif
            </div>
            @endif
        </div>
        <p style="margin: 0 0 8px; font-weight: 600; color: #475569;">{{ __('store.email_items') }}:</p>
        <table class="items">
            <thead>
                <tr>
                    <th>{{ __('store.product_name') }}</th>
                    <th>{{ __('store.quantity') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items ?? [] as $item)
                    <tr>
                        <td>{{ $item['product_name'] ?? '—' }}</td>
                        <td>{{ $item['quantity'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(!empty($request->message))
            <div class="message-box">
                <strong>{{ __('store.message_optional') }}:</strong><br>
                {{ $request->message }}
            </div>
        @endif
        <p style="margin-top: 20px;">
            <a href="{{ url('/store/requests') }}" style="display: inline-block; background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;">{{ __('store.email_view_requests') }}</a>
        </p>
        <div class="footer">
            {{ config('app.name') }} — {{ __('store.email_footer') }}
        </div>
    </div>
</body>
</html>
