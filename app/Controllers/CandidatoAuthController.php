<?php
namespace App\Controllers;

use App\Helpers\EmailTemplateHelper;
use App\Helpers\SessionHelper;
use App\Models\Candidato;
use App\Services\EmailService;
use App\Helpers\CsrfHelper;
use App\Helpers\FlashHelper;
use DateTime;
use GuzzleHttp\Client;
use Dotenv\Dotenv;

class CandidatoAuthController
{
    public function registerAjax(): void
    {
        SessionHelper::start();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $emailConfirmacao = trim($_POST['email_confirm'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirm'] ?? '';
        $tokenCSRF = $_POST['csrf_token'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        if (!CsrfHelper::validarToken($tokenCSRF)) {
            $this->jsonResponse(false, 'Token CSRF inválido.');
        }

        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        if (!isset($_ENV['NOCAPTCHA_SECRET'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            $this->jsonResponse(false, 'Chave secreta reCAPTCHA não configurada no servidor.');
        }

        try {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]);

            $body = json_decode((string)$response->getBody(), true);

            if (empty($body['success'])) {
                $this->jsonResponse(false, 'Por favor, confirme o reCAPTCHA.');
            }

        } catch (\Exception $e) {
            $this->jsonResponse(false, 'Erro ao validar o reCAPTCHA.');
        }

        if ($email !== $emailConfirmacao) {
            $this->jsonResponse(false, 'Os e-mails não coincidem.');
        }

        if ($senha !== $senhaConfirmacao) {
            $this->jsonResponse(false, 'As senhas não coincidem.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'E-mail inválido.');
        }

        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($regex, $senha)) {
            $this->jsonResponse(false, 'A senha deve ter no mínimo 8 caracteres, incluindo uma maiúscula, uma minúscula, um número e um símbolo.');
        }

        $model = new Candidato();

        if ($model->emailExiste($email)) {
            $this->jsonResponse(false, 'Este e-mail já está registado. Por favor, faça login ou recupere a senha.');
        }

        $token = bin2hex(random_bytes(16));

        if ($model->criar($nome, $email, $senha, $token)) {
            // Guardar o email na sessão para mostrar botão de reenviar
            $_SESSION['email_candidato_para_confirmacao'] = $email;

            $model = new \App\Models\Candidato();
            $user = $model->buscarPorEmail($email); // 🔹 Buscar os dados do candidato

            if (!$user) {
                return; // Ou podes fazer log de erro se preferires
            }

            $link = "http://localhost:8000/confirmar-candidato?token=$token";

            $mensagem = EmailTemplateHelper::confirmacaoRegisto($link, $user['nome']);

            EmailService::enviarEmail($email, '[CANDIDATO] Confirmação de Registo', $mensagem);

            $_SESSION['flash_type'] = 'info';
            $_SESSION['flash_message'] = 'Registo efetuado! Verifique o seu e-mail antes de fazer login.';

            $this->jsonResponse(true, 'Registo efetuado! Verifique o seu e-mail.', '/dashboard');

        } else {
            $this->jsonResponse(false, 'Erro ao criar conta. Por favor, tente novamente.');
        }
    }

    public function confirmar(): void
    {
        $token = $_GET['token'] ?? '';

        $model = new \App\Models\Candidato();
        $user = $model->encontrarPorToken($token);

        if ($user) {
            // NOVO: Evita múltiplos cliques no link do email
            if ((int)$user['confirmado'] === 1) {
                FlashHelper::set('info', 'A sua conta já foi confirmada anteriormente.');
                header('Location: /dashboard');
                exit;
            }

            // Verificar se o token expirou
            if (!empty($user['token_expires_at']) && strtotime($user['token_expires_at']) < time()) {
                FlashHelper::set('error', 'O link de confirmação expirou. Solicite um novo.');
                header('Location: /dashboard');
                exit;
            }

            $model->confirmar($token);

            // Limpar email da sessão para esconder botão "reenviar"
            unset($_SESSION['email_candidato_para_confirmacao']);

            $mensagem = EmailTemplateHelper::contaConfirmada($user['nome']);
            EmailService::enviarEmail($user['email'], '[CANDIDATO] Bem-vindo ao Emprega.me!', $mensagem);

            FlashHelper::set('success', 'Conta confirmada com sucesso! Já pode fazer login.');
            header('Location: /dashboard');
            exit;

        } else {
            FlashHelper::set('error', 'Token inválido ou conta já confirmada anteriormente.');
            header('Location: /dashboard');
            exit;
        }
    }

    public function verificarEmailSessaoCandidato(): void
    {
        SessionHelper::start();

        $email = $_SESSION['email_candidato_para_confirmacao'] ?? '';

        if (!$email) {
            echo json_encode(['success' => false, 'showButton' => false]);
            exit;
        }

        $model = new \App\Models\Candidato();
        $candidato = $model->buscarPorEmail($email);

        if ($candidato && (int)$candidato['confirmado'] === 0) {
            echo json_encode([
                'success' => true,
                'showButton' => true,
                'email' => $email
            ]);
        } else {
            echo json_encode(['success' => false, 'showButton' => false]);
        }
    }

    public function reenviarEmailConfirmacaoSessaoCandidato(): void
    {
        SessionHelper::start();

        $email = $_SESSION['email_candidato_para_confirmacao'] ?? '';

        if (!$email) {
            $this->jsonResponse(false, 'Email não disponível na sessão.');
            return;
        }

        // ✅ PROTEÇÃO: tempo mínimo de 60 segundos entre reenvios
        $ultimoEnvio = $_SESSION['ultimo_reenvio_confirmacao_candidato'] ?? 0;
        if (time() - $ultimoEnvio < 60) {
            $this->jsonResponse(false, 'Aguarde alguns segundos antes de reenviar o email.');
            return;
        }

        $model = new \App\Models\Candidato();
        $candidato = $model->buscarPorEmail($email);

        if (!$candidato) {
            $this->jsonResponse(false, 'Email não encontrado.', null, ['email_confirmado' => false]);
            return;
        }

        if ((int)$candidato['confirmado'] === 1) {
            $this->jsonResponse(false, 'Conta já confirmada. Faça o seu login.', null, ['email_confirmado' => true]);
            return;
        }

        // Gerar novo token e expiração
        $novoToken = bin2hex(random_bytes(16));
        $novaExpiracao = (new DateTime())->modify('+24 hours')->format('Y-m-d H:i:s');

        // Atualizar via model
        $model->atualizarTokenConfirmacao($email, $novoToken, $novaExpiracao);

        // Reenviar email com novo token
        $this->enviarEmailConfirmacaoCandidato($email, $novoToken);

        // ✅ Guardar hora do último envio
        $_SESSION['ultimo_reenvio_confirmacao_candidato'] = time();

        $this->jsonResponse(true, 'Email de confirmação reenviado com sucesso.', null, ['email_confirmado' => false]);
    }

    private function enviarEmailConfirmacaoCandidato(string $email, string $token): void
    {
        $model = new \App\Models\Candidato();
        $user = $model->buscarPorEmail($email); // 🔹 Buscar os dados do candidato

        if (!$user) {
            return; // Ou podes fazer log de erro se preferires
        }

        $link = "http://localhost:8000/confirmar-candidato?token=$token";
        $mensagem = EmailTemplateHelper::confirmacaoRegisto($link, $user['nome']);

        EmailService::enviarEmail($email, '[CANDIDATO] Confirmação de Registo', $mensagem);
    }

    public function loginAjax(): void
    {
        SessionHelper::start();

        // 🔐 Preservar variáveis da sessão atual (caso tenham sido setadas anteriormente)
        $emailCandidatoConfirmacao = $_SESSION['email_candidato_para_confirmacao'] ?? null;
        $emailEmpresaConfirmacao   = $_SESSION['email_empresa_para_confirmacao'] ?? null;

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->jsonResponse(false, 'Email e senha são obrigatórios.');
        }

        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        if (!isset($_ENV['NOCAPTCHA_SECRET'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            echo json_encode(['success' => false, 'message' => 'Chave secreta reCAPTCHA não configurada no servidor.']);
            return;
        }

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
            $body = json_decode($response->getBody(), true);

            if (empty($body['success'])) {
                echo json_encode(['success' => false, 'message' => 'Por favor, confirme o reCAPTCHA.']);
                return;
            }

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao validar o reCAPTCHA.']);
            return;
        }

        $model = new Candidato();
        $user = $model->verificarLogin($email, $senha);

        if ($user) {
            // ✅ Iniciar sessão limpa
            session_unset();
            session_destroy();
            SessionHelper::start();

            // 🔁 Restaurar variáveis anteriores
            if ($emailCandidatoConfirmacao) {
                $_SESSION['email_candidato_para_confirmacao'] = $emailCandidatoConfirmacao;
            }
            if ($emailEmpresaConfirmacao) {
                $_SESSION['email_empresa_para_confirmacao'] = $emailEmpresaConfirmacao;
            }

            $_SESSION['candidato'] = $user;
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = 'Login realizado com sucesso.';

            echo json_encode(['success' => true, 'redirect' => '/dashboard']);
            exit;
        } else {
            $this->jsonResponse(false, 'Credenciais inválidas ou conta não confirmada.');
        }
    }

    public function logout(): void
    {
        SessionHelper::start();

        // 🔐 Guardar variáveis da sessão antiga
        $emailCandidatoConfirmacao = $_SESSION['email_candidato_para_confirmacao'] ?? null;
        $emailEmpresaConfirmacao   = $_SESSION['email_empresa_para_confirmacao'] ?? null;

        $tipo = 'success';
        $mensagem = 'Logout realizado com sucesso.';

        session_unset();
        session_destroy();

        SessionHelper::start();

        // 🔁 Restaurar variáveis
        if ($emailCandidatoConfirmacao) {
            $_SESSION['email_candidato_para_confirmacao'] = $emailCandidatoConfirmacao;
        }
        if ($emailEmpresaConfirmacao) {
            $_SESSION['email_empresa_para_confirmacao'] = $emailEmpresaConfirmacao;
        }

        $_SESSION['flash_type'] = $tipo;
        $_SESSION['flash_message'] = $mensagem;

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

        require __DIR__ . '/../Views/auth/redefinir_senha_candidato.php';
    }

    public function enviarLinkRecuperacao(): void
    {
        SessionHelper::start();

        $email = trim($_POST['email'] ?? '');
        $tokenCSRF = $_POST['csrf_token'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // ✅ BLOQUEIO de múltiplos envios seguidos (ex: 60 segundos)
        $ultimoEnvio = $_SESSION['ultimo_envio_recuperacao'] ?? 0;
        if (time() - $ultimoEnvio < 60) {
            echo json_encode(['success' => false, 'message' => 'Aguarde alguns segundos antes de reenviar.']);
            exit;
        }

        // Validar CSRF
        if (!CsrfHelper::validarToken($tokenCSRF)) {
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            exit;
        }

        if (empty($recaptchaResponse)) {
            $this->jsonResponse(false, 'Por favor, complete o reCAPTCHA.');
        }

        $recaptchaSecret = $_ENV['NOCAPTCHA_SECRET'] ?? null;
        if (empty($recaptchaSecret)) {
            echo json_encode(['success' => false, 'message' => 'Chave secreta reCAPTCHA não configurada no servidor.']);
            return;
        }

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

            $body = json_decode($response->getBody(), true);

            if (empty($body['success'])) {
                echo json_encode(['success' => false, 'message' => 'Por favor, confirme o reCAPTCHA.']);
                return;
            }

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao validar o reCAPTCHA.']);
            return;
        }

        $model = new Candidato();
        $user = $model->buscarPorEmail($email);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Conta inválida.']);
            exit;
        }

        if ((int)$user['confirmado'] === 0) {
            echo json_encode(['success' => false, 'message' => 'Conta inválida.']);
            exit;
        }

        $token = bin2hex(random_bytes(16));
        $model->salvarTokenRecuperacao($user['id'], $token);

        $link = "http://localhost:8000/redefinir-senha-candidato?token=$token";
        $mensagem = EmailTemplateHelper::recuperacaoSenha($user['nome'], $link);

        EmailService::enviarEmail($email, '[CANDIDATO] Recuperação de Senha', $mensagem);

        // ✅ Gravar o momento do envio para bloquear reenvios seguidos
        $_SESSION['ultimo_envio_recuperacao'] = time();

        FlashHelper::set('success', 'Link de recuperação enviado! Verifique o seu e-mail.');
        echo json_encode(['success' => true, 'redirect' => '/dashboard']);
        exit;
    }

    public function processarNovaSenha(): void
    {
        SessionHelper::start();

        $token = $_POST['token'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $senhaConfirm = $_POST['senha_confirm'] ?? '';
        $tokenCSRF = $_POST['csrf_token'] ?? '';

        // CSRF
        if (!CsrfHelper::validarToken($tokenCSRF)) {
            FlashHelper::set('error', 'Token CSRF inválido.');
            header("Location: /redefinir-senha-candidato?token=$token");
            exit;
        }

        // Validação de campos
        if (empty($token) || empty($senha) || empty($senhaConfirm)) {
            FlashHelper::set('error', 'Todos os campos são obrigatórios.');
            header("Location: /redefinir-senha-candidato?token=$token");
            exit;
        }

        if ($senha !== $senhaConfirm) {
            FlashHelper::set('error', 'As senhas não coincidem.');
            header("Location: /redefinir-senha-candidato?token=$token");
            exit;
        }

        // Regex de segurança
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($regex, $senha)) {
            FlashHelper::set('error', 'A senha deve ter no mínimo 8 caracteres, incluindo: maiúscula, minúscula, número e símbolo.');
            header("Location: /redefinir-senha-candidato?token=$token");
            exit;
        }

        $model = new \App\Models\Candidato();
        $user = $model->encontrarPorTokenRecuperacao($token);

        if (
            !$user ||
            empty($user['token_recuperacao_expires_at']) ||
            strtotime($user['token_recuperacao_expires_at']) < time()
        ) {
            FlashHelper::set('error', 'Token expirado ou inválido. Solicite um novo link.');
            header("Location: /dashboard");
            exit;
        }

        // Atualizar a senha no banco
        if ($model->atualizarSenhaPorToken($token, $senha)) {

            $mensagem = EmailTemplateHelper::senhaAlterada($user['nome']);
            EmailService::enviarEmail($user['email'], '[CANDIDATO] Senha alterada com sucesso', $mensagem);

            FlashHelper::set('success', 'Senha atualizada com sucesso! Faça login.');
            header('Location: /dashboard');
            exit;
        } else {
            FlashHelper::set('error', 'Erro ao atualizar a senha.');
            header("Location: /redefinir-senha-candidato?token=$token");
            exit;
        }
    }

    private function jsonResponse(bool $success, string $message, string $redirect = null, array $extra = []): void
    {
        $response = [
            'success' => $success,
            'message' => $message
        ];

        if ($redirect !== null) {
            $response['redirect'] = $redirect;
        }

        // Esta linha garante que `email_confirmado` (e outros) sejam incluídos
        $response = array_merge($response, $extra);

        \App\Helpers\ResponseHelper::json($response);
    }

}
