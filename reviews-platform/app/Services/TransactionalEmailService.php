<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de envio de emails transacionais.
 * Tenta SendGrid primeiro; se falhar, usa Resend como fallback.
 */
class TransactionalEmailService
{
    protected string $fromEmail;
    protected string $fromName;

    public function __construct()
    {
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        $this->fromName = env('MAIL_FROM_NAME', 'Avalie e Ganhe');
    }

    /**
     * Envia email via SendGrid primeiro; se falhar, tenta Resend.
     *
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $htmlContent Conteúdo HTML
     * @param string|null $toName Nome do destinatário (opcional)
     * @return bool true se enviado com sucesso
     * @throws \Exception se ambos os provedores falharem
     */
    public function send(string $to, string $subject, string $htmlContent, ?string $toName = null): bool
    {
        $context = ['to' => $to, 'subject' => $subject];

        // 1. Tentar SendGrid primeiro
        try {
            if ($this->sendViaSendGrid($to, $subject, $htmlContent, $toName)) {
                Log::info('Email enviado com sucesso via SendGrid', $context);
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('SendGrid falhou, tentando Resend como fallback', array_merge($context, [
                'sendgrid_error' => $e->getMessage(),
            ]));
        }

        // 2. Fallback: Resend
        try {
            if ($this->sendViaResend($to, $subject, $htmlContent, $toName)) {
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('Resend também falhou', array_merge($context, [
                'resend_error' => $e->getMessage(),
            ]));
            throw new \Exception(
                'Falha ao enviar email: SendGrid e Resend falharam. ' . $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Send welcome email with temporary password and login link.
     */
    public function sendWelcomeWithTemporaryPassword(string $to, string $name, string $temporaryPassword): bool
    {
        $loginUrl = url('/login');
        $subject = 'Sua conta foi criada - ' . $this->fromName;
        $html = "
        <!DOCTYPE html>
        <html>
        <head><meta charset=\"utf-8\"><title>Acesso à plataforma</title></head>
        <body style=\"font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;\">
            <h2 style=\"color: #8b5cf6;\">Bem-vindo(a), {$name}!</h2>
            <p>Sua conta foi criada. Use os dados abaixo para acessar a plataforma:</p>
            <p><strong>E-mail:</strong> {$to}</p>
            <p><strong>Senha temporária:</strong> <code style=\"background: #f3f4f6; padding: 4px 8px; border-radius: 4px;\">{$temporaryPassword}</code></p>
            <p><a href=\"{$loginUrl}\" style=\"display: inline-block; background: #8b5cf6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; margin-top: 10px;\">Acessar a plataforma</a></p>
            <p style=\"color: #6b7280; font-size: 14px; margin-top: 24px;\">Recomendamos que você altere sua senha após o primeiro acesso (Perfil).</p>
            <p style=\"color: #6b7280; font-size: 12px;\">— {$this->fromName}</p>
        </body>
        </html>
        ";
        return $this->send($to, $subject, $html, $name);
    }

    /**
     * Envia via API SendGrid
     */
    private function sendViaSendGrid(string $to, string $subject, string $htmlContent, ?string $toName = null): bool
    {
        $apiKey = env('SENDGRID_API_KEY');
        if (!$apiKey) {
            throw new \Exception('SENDGRID_API_KEY não configurada');
        }

        $toPayload = ['email' => $to];
        if ($toName) {
            $toPayload['name'] = $toName;
        }

        $client = new Client();
        $response = $client->post('https://api.sendgrid.com/v3/mail/send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'personalizations' => [
                    [
                        'to' => [$toPayload],
                        'subject' => $subject,
                    ],
                ],
                'from' => [
                    'email' => $this->fromEmail,
                    'name' => $this->fromName,
                ],
                'content' => [
                    ['type' => 'text/html', 'value' => $htmlContent],
                ],
            ],
        ]);

        $status = $response->getStatusCode();
        if ($status >= 200 && $status < 300) {
            return true;
        }
        throw new \Exception('SendGrid retornou status: ' . $status);
    }

    /**
     * Envia via API Resend (fallback)
     * Usa RESEND_FROM_ADDRESS / RESEND_FROM_NAME se definidos (dominio verificado no Resend).
     */
    private function sendViaResend(string $to, string $subject, string $htmlContent, ?string $toName = null): bool
    {
        $apiKey = env('RESEND_API_KEY');
        if (!$apiKey) {
            throw new \Exception('RESEND_API_KEY não configurada (necessária como fallback quando SendGrid falha)');
        }

        $fromEmail = env('RESEND_FROM_ADDRESS') ?: $this->fromEmail;
        $fromName = env('RESEND_FROM_NAME') ?: $this->fromName;
        $from = $fromName . ' <' . $fromEmail . '>';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.resend.com/emails', [
            'from' => $from,
            'to' => [$to],
            'subject' => $subject,
            'html' => $htmlContent,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $emailId = $data['id'] ?? null;
            Log::info('Resend aceitou o email para entrega', [
                'to' => $to,
                'subject' => $subject,
                'resend_id' => $emailId,
                'msg' => 'Rastreie em https://resend.com/emails',
            ]);
            return true;
        }
        throw new \Exception('Resend retornou status: ' . $response->status() . ' - ' . $response->body());
    }
}
