<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetCode;
use App\Models\User;
use App\Services\TransactionalEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            Log::info('Login realizado com sucesso', [
                'user_id' => Auth::id(),
                'email' => $request->email
            ]);

            return redirect()->intended('/dashboard');
        }

        Log::warning('Tentativa de login falhada', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não coincidem com nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Create admin user (for initial setup)
     */
    public function createAdmin()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@reviewsplatform.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Usuário admin criado com sucesso!',
            'credentials' => [
                'email' => 'admin@reviewsplatform.com',
                'password' => 'admin123'
            ]
        ]);
    }

    /**
     * Mostrar formulário de solicitação de recuperação de senha
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar código de recuperação por email
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Sempre retornar sucesso (por segurança, não revelar se email existe)
        if (!$user) {
            return back()->with('success', 'Se o email existir em nosso sistema, você receberá um código de recuperação.');
        }

        // Limpar códigos expirados
        PasswordResetCode::cleanExpired();

        // Invalidar códigos anteriores não usados do mesmo email
        PasswordResetCode::where('email', $request->email)
            ->where('used', false)
            ->delete();

        // Gerar código de 6 dígitos
        $code = str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Salvar código no banco (válido por 15 minutos)
        PasswordResetCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'used' => false,
        ]);

        // Enviar email usando a mesma lógica do sistema de avaliação
        // Tenta SMTP primeiro, se falhar usa SendGrid API via HTTP
        $this->sendEmailNotification($user, $code);
        
        Log::info('Código de recuperação gerado e processado', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'code' => $code,
            'code_saved' => true
        ]);

        // Salvar email na sessão para próxima etapa
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.reset.code')->with('success', 'Código de recuperação enviado para seu email!');
    }

    /**
     * Mostrar formulário para inserir código
     */
    public function showResetCodeForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.forgot')->with('error', 'Por favor, solicite um novo código de recuperação.');
        }

        return view('auth.reset-password-code');
    }

    /**
     * Verificar código e mostrar formulário de redefinição
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.forgot')->with('error', 'Sessão expirada. Por favor, solicite um novo código.');
        }

        // Buscar código válido
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.'])->withInput();
        }

        // Marcar código como usado
        $resetCode->markAsUsed();

        // Salvar token na sessão para redefinição
        session(['password_reset_verified' => true]);

        return redirect()->route('password.reset')->with('success', 'Código verificado com sucesso! Agora você pode definir uma nova senha.');
    }

    /**
     * Mostrar formulário de redefinição de senha
     */
    public function showResetPasswordForm()
    {
        if (!session('password_reset_verified') || !session('password_reset_email')) {
            return redirect()->route('password.forgot')->with('error', 'Por favor, verifique o código primeiro.');
        }

        return view('auth.reset-password');
    }

    /**
     * Redefinir senha
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        if (!session('password_reset_verified') || !session('password_reset_email')) {
            return redirect()->route('password.forgot')->with('error', 'Sessão expirada. Por favor, solicite um novo código.');
        }

        $email = session('password_reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'Usuário não encontrado.');
        }

        // Atualizar senha
        $user->password = Hash::make($request->password);
        $user->save();

        // Limpar sessão
        session()->forget(['password_reset_email', 'password_reset_verified']);

        Log::info('Senha redefinida com sucesso', [
            'email' => $email,
            'user_id' => $user->id
        ]);

        return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
    }

    /**
     * Send email notification for password reset.
     * Uses SendGrid first; if it fails, falls back to Resend.
     */
    private function sendEmailNotification($user, $code)
    {
        $subject = 'Código de Recuperação de Senha - ' . config('app.name');
        $htmlContent = View::make('emails.password-reset-code', [
            'user' => $user,
            'code' => $code,
            'expiresIn' => 15,
        ])->render();

        try {
            app(TransactionalEmailService::class)->send(
                $user->email,
                $subject,
                $htmlContent,
                $user->name
            );
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de recuperação de senha', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            throw $e; // Re-throw para que o usuário veja mensagem de erro
        }
    }
}