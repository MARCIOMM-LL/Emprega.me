<?php
use App\Helpers\FlashHelper;
session_start();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - Empresa</title>
    <style>
        body {
            background: rgba(0, 0, 0, 0.6);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        h2 {
            color: #1a73e8;
            font-size: 24px;
            margin-bottom: 20px;
        }
        input[type="password"] {
            width: 93%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #e9f0ff;
        }
        button {
            background-color: #00b77d;
            color: #fff;
            border: none;
            padding: 12px 20px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #019966;
        }
        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert-error {
            background: #ffe6e6;
            color: #d8000c;
        }
        .alert-success {
            background: #e6ffea;
            color: #2e7d32;
        }
    </style>
</head>
<body>

<div class="modal-content">

    <h2>Redefinir Senha - Empresa</h2>

    <?php if ($flash = FlashHelper::get()): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/redefinir-senha-empresa">
        <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CsrfHelper::gerarToken(); ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

        <input type="password" name="senha" placeholder="Nova senha" required>
        <input type="password" name="senha_confirm" placeholder="Confirmar nova senha" required>

        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LcoWmkrAAAAAE9lr3oqwIt8BwKr24ZEsvIqoWm5"></div>

        <button type="submit">Redefinir Senha</button>
    </form>

</div>

</body>
</html>