<?php
namespace App\Controllers;

use App\Models\Empresa;
use App\Services\EmailService;
use App\Helpers\CsrfHelper;
use App\Helpers\FlashHelper;
use GuzzleHttp\Client;
use Dotenv\Dotenv;

class EmpresaAuthController
{
    public function registerAjax(): void
    {
        header('Content-Type: application/json');
        session_start();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $emailConfirmacao = trim($_POST['email_confirm'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirm'] ?? '';
        $tokenCSRF = $_POST['csrf_token'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // CSRF
        if (!CsrfHelper::validarToken($tokenCSRF)) {
            $this->jsonResponse(false, 'Token CSRF inválido.');
        }

        // Primeira validação: Se o reCAPTCHA veio preenchido
        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        // Garantir que o .env é carregado mesmo se o front controller falhar
        if (!isset($_ENV['NOCAPTCHA_SECRET'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            echo json_encode(['success' => false, 'message' => 'Chave secreta reCAPTCHA não configurada no servidor.']);
            return;
        }

        // Segunda validação: Verificar com o Google
        try {
            $client = new Client();
            $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;

            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]);

            // Salvar resposta crua do Google num ficheiro para debug
            $bodyRaw = (string)$response->getBody();
            file_put_contents(__DIR__ . '/../../debug_recaptcha.txt', $bodyRaw);

            $body = json_decode($response->getBody(), true);

            if (empty($body['success'])) {
                echo json_encode(['success' => false, 'message' => 'Por favor, confirme o reCAPTCHA.']);
                return;
            }

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao validar o reCAPTCHA.']);
            return;
        }

        // Verificar igualdade dos emails
        if ($email !== $emailConfirmacao) {
            $this->jsonResponse(false, 'Os e-mails não coincidem.');
        }

        // Verificar igualdade das senhas
        if ($senha !== $senhaConfirmacao) {
            $this->jsonResponse(false, 'As senhas não coincidem.');
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'E-mail inválido.');
        }

        // Regex de segurança de senha
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($regex, $senha)) {
            $this->jsonResponse(false, 'A senha deve ter no mínimo 8 caracteres, 
                                                         incluindo uma letra maiúscula, uma minúscula, 
                                                         um número e um caractere especial.');
        }

        $model = new Empresa();

        // Verificar se email já existe
        if ($model->emailExiste($email)) {
            $this->jsonResponse(false, 'Este e-mail já está registado. 
                                                         Por favor, faça login ou 
                                                         recupere a senha.');
        }

        // Criar token de confirmação
        $token = bin2hex(random_bytes(16));

        if ($model->criar($nome, $email, $senha, $token)) {
            $link = "http://localhost:8000/confirmar-empresa?token=$token";

            $mensagem = "
                <h1>Confirmação de Registo</h1>
                <p>Olá {$nome},</p>
                <p>Por favor, confirme o seu e-mail clicando no botão abaixo:</p>
                <p>
                    <a href='{$link}' style='
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #28a745;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    '>Confirmar Registo</a>
                </p>
                <p>Se não consegue clicar, copie e cole o link no navegador:</p>
                <p>{$link}</p>
            ";

            EmailService::enviarEmail($email, 'Confirmação de Registo', $mensagem);

            $_SESSION['flash_type'] = 'info';
            $_SESSION['flash_message'] = 'Registo efetuado! Verifique o seu e-mail antes de fazer login.';

            echo json_encode(['success' => true, 'redirect' => '/dashboard']);
            exit;
        } else {
            $this->jsonResponse(false, 'Erro ao criar conta. Por favor, tente novamente.');
        }
    }

    public function confirmar(): void
    {
        $token = $_GET['token'] ?? '';

        $model = new \App\Models\Empresa();
        $user = $model->encontrarPorToken($token);

        if ($user) {
            $model->confirmar($token);

            // ✅ Enviar e-mail de boas-vindas para Empresa
            $mensagem = "
            <h1>Bem-vindo ao Emprega.me!</h1>
            <p>Olá {$user['nome']},</p>
            <p>A sua conta de empresa foi confirmada com sucesso.</p>
            <p>Agora pode publicar vagas e encontrar candidatos para a sua empresa.</p>
            <p>Obrigado por se juntar a nós!</p>
        ";
            EmailService::enviarEmail($user['email'], 'Bem-vindo ao Emprega.me!', $mensagem);

            FlashHelper::set('success', 'Conta confirmada com sucesso! Já pode fazer login.');
            header('Location: /dashboard');
            exit;
        } else {
            FlashHelper::set('error', 'Token inválido ou conta já confirmada anteriormente.');
            header('Location: /dashboard');
            exit;
        }
    }

    public function loginAjax(): void
    {
        header('Content-Type: application/json');
        session_start();

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->jsonResponse(false, 'Email e senha são obrigatórios.');
        }

        // Primeira validação: Se o reCAPTCHA veio preenchido
        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        // Garantir que o .env é carregado mesmo se o front controller falhar
        if (!isset($_ENV['NOCAPTCHA_SECRET'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            echo json_encode(['success' => false, 'message' => 'Chave secreta reCAPTCHA não configurada no servidor.']);
            return;
        }

        // Segunda validação: Verificar com o Google
        try {
            $client = new Client();
            $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;

            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]);

            // Salvar resposta crua do Google num ficheiro para debug
            $bodyRaw = (string)$response->getBody();
            file_put_contents(__DIR__ . '/../../debug_recaptcha.txt', $bodyRaw);

            $body = json_decode($response->getBody(), true);

            if (empty($body['success'])) {
                echo json_encode(['success' => false, 'message' => 'Por favor, confirme o reCAPTCHA.']);
                return;
            }

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao validar o reCAPTCHA.']);
            return;
        }

        $model = new Empresa();
        $user = $model->verificarLogin($email, $senha);

        if ($user) {
            $_SESSION['empresa'] = $user;

            // Flash de sucesso
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Login realizado com sucesso.';

            // Enviar redirect no JSON
            echo json_encode(['success' => true, 'redirect' => '/dashboard']);
            exit;
        } else {
            $this->jsonResponse(false, 'Credenciais inválidas ou conta não confirmada.');
        }
    }

    public function logout(): void
    {
        session_start();

        // Guardar flash antes de destruir a sessão
        $tipo = 'success';
        $mensagem = 'Logout realizado com sucesso.';

        // Destruir a sessão completamente
        session_unset();
        session_destroy();

        // Iniciar nova sessão apenas para guardar o flash
        session_start();
        $_SESSION['flash_type'] = $tipo;
        $_SESSION['flash_message'] = $mensagem;

        // Redirecionar
        header('Location: /dashboard');
        exit;
    }

    public function formRedefinirSenha(): void
    {
        $token = $_GET['token'] ?? '';

        // Verificar se token veio na URL
        if (empty($token)) {
            FlashHelper::set('error', 'Token inválido.');
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../Views/auth/redefinir_senha_empresa.php';
    }

    public function enviarLinkRecuperacao(): void
    {
        header('Content-Type: application/json');
        session_start();

        $email = trim($_POST['email'] ?? '');
        $tokenCSRF = $_POST['csrf_token'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // Validar CSRF
        if (!CsrfHelper::validarToken($tokenCSRF)) {
            $this->jsonResponse(false, 'Token CSRF inválido.');
        }

        // Validar se o reCAPTCHA foi preenchido
        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        // Garantir que .env foi carregado
        if (!isset($_ENV['NOCAPTCHA_SECRET'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            $this->jsonResponse(false, 'Chave secreta reCAPTCHA não configurada no servidor.');
        }

        // Verificar com o Google
        try {
            $client = new Client();

            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]);

            $bodyRaw = (string)$response->getBody();
            file_put_contents(__DIR__ . '/../../debug_recaptcha.txt', $bodyRaw);

            $body = json_decode($bodyRaw, true);

            if (empty($body['success'])) {
                $this->jsonResponse(false, 'Por favor, confirme o reCAPTCHA.');
            }

        } catch (\Exception $e) {
            $this->jsonResponse(false, 'Erro ao validar o reCAPTCHA.');
        }

        $model = new Empresa();
        $user = $model->buscarPorEmail($email);

        if (!$user) {
            $this->jsonResponse(false, 'Nenhuma conta encontrada com este e-mail.');
        }

        // Gerar token e salvar
        $token = bin2hex(random_bytes(16));
        $model->salvarTokenRecuperacao($user['id'], $token);

        // Montar link e mensagem
        $link = "http://localhost:8000/redefinir-senha-empresa?token=$token";
        $mensagem = "
            <h1>Recuperação de Senha</h1>
            <p>Olá {$user['nome']},</p>
            <p>Clique no link abaixo para criar uma nova senha:</p>
            <p><a href='{$link}'>Redefinir Senha</a></p>
            <p>Se você não solicitou esta recuperação, ignore este e-mail.</p>
        ";

        EmailService::enviarEmail($email, 'Recuperação de Senha', $mensagem);

        // Flash + Redirect via JSON
        FlashHelper::set('success', 'Link de recuperação enviado! Verifique o seu e-mail.');
        echo json_encode(['success' => true, 'redirect' => '/dashboard']);
        exit;
    }

    public function processarNovaSenha(): void
    {
        session_start();

        $token = $_POST['token'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $senhaConfirm = $_POST['senha_confirm'] ?? '';
        $tokenCSRF = $_POST['csrf_token'] ?? '';

        // CSRF
        if (!CsrfHelper::validarToken($tokenCSRF)) {
            FlashHelper::set('error', 'Token CSRF inválido.');
            header("Location: /redefinir-senha-empresa?token=$token");
            exit;
        }

        // Validação de campos
        if (empty($token) || empty($senha) || empty($senhaConfirm)) {
            FlashHelper::set('error', 'Todos os campos são obrigatórios.');
            header("Location: /redefinir-senha-empresa?token=$token");
            exit;
        }

        if ($senha !== $senhaConfirm) {
            FlashHelper::set('error', 'As senhas não coincidem.');
            header("Location: /redefinir-senha-empresa?token=$token");
            exit;
        }

        // Regex de segurança
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($regex, $senha)) {
            FlashHelper::set('error', 'A senha deve ter no mínimo 8 caracteres, incluindo: maiúscula, minúscula, número e símbolo.');
            header("Location: /redefinir-senha-empresa?token=$token");
            exit;
        }

        $model = new \App\Models\Empresa();
        $user = $model->encontrarPorToken($token);

        if (!$user) {
            FlashHelper::set('error', 'Token inválido ou expirado.');
            header("Location: /dashboard");
            exit;
        }

        // Atualizar a senha no banco
        if ($model->atualizarSenhaPorToken($token, $senha)) {
            // Enviar e-mail de sucesso
            EmailService::enviarEmail($user['email'], 'Senha Alterada com Sucesso', "
                <h1>Alteração de Senha</h1>
                <p>Olá {$user['nome']},</p>
                <p>A sua senha foi alterada com sucesso.</p>
            ");

            FlashHelper::set('success', 'Senha atualizada com sucesso! Faça login.');

            header('Location: /dashboard');
            exit;
        } else {
            FlashHelper::set('error', 'Erro ao atualizar a senha.');
            header("Location: /redefinir-senha-empresa?token=$token");
            exit;
        }
    }

    private function jsonResponse(bool $success, string $message): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message
        ]);
        exit;
    }
}
