<?php

namespace App\Helpers;

class EmailTemplateHelper
{
    /**
     * Template de email para confirmação de conta (candidato ou empresa)
     */
    public static function contaConfirmada(string $nome): string
    {
        return '
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
                <style>
                    @media only screen and (max-width: 600px) {
                        .email-container { width: 100% !important; padding: 20px !important; }
                    }
                </style>
                <tr>
                    <td align="center">
                        <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px;">
                            <tr>
                                <td align="center" style="padding-bottom: 20px;">
                                    <img src="https://static.vecteezy.com/ti/vetor-gratis/p1/4891104-logotipo-para-encontrar-emprego-simples-e-moderno-gratis-vetor.jpg"
                                         alt="Emprega.me Logo"
                                         style="max-width: 100%; height: auto; display: block; margin: auto;">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #333; font-size: 16px;">
                                    <p style="margin-bottom: 20px;">Olá <strong>' . htmlspecialchars($nome) . '</strong>,</p>
                                    <p style="margin-bottom: 20px;">A sua conta foi <strong>confirmada com sucesso</strong>!</p>
                                    <p style="margin-bottom: 20px;">A partir de agora pode fazer login e começar a utilizar a nossa plataforma.</p>
                                    <p style="margin-bottom: 30px;">Boa sorte na sua procura! 🚀</p>
                                    <hr style="border: none; border-top: 1px solid #ddd; margin: 40px 0;">
                                    <p style="font-size: 14px; color: #555;">Com os melhores cumprimentos,<br><strong>Equipa Emprega.me</strong></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';
    }

    /**
     * Template para envio de link de recuperação de senha
     */
    public static function recuperacaoSenha(string $nome, string $link): string
    {
        return '
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
                <style>
                    @media only screen and (max-width: 600px) {
                        .email-container { width: 100% !important; padding: 20px !important; }
                        .btn { display: block !important; width: 100% !important; text-align: center !important; }
                    }
                </style>
                <tr>
                    <td align="center">
                        <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px;">
                            <tr>
                                <td align="center" style="padding-bottom: 20px;">
                                    <img src="https://static.vecteezy.com/ti/vetor-gratis/p1/4891104-logotipo-para-encontrar-emprego-simples-e-moderno-gratis-vetor.jpg"
                                         alt="Emprega.me Logo"
                                         style="max-width: 100%; height: auto; display: block; margin: auto;">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #333; font-size: 16px;">
                                    <p style="margin-bottom: 20px;">Olá <strong>' . htmlspecialchars($nome) . '</strong>,</p>
                                    <p style="margin-bottom: 20px;">Recebemos um pedido para recuperar a sua senha.</p>
                                    <p style="margin-bottom: 20px;">
                                        <a href="' . htmlspecialchars($link) . '" style="
                                                display: inline-block;
                                                width: auto;
                                                max-width: 100%;
                                                padding: 12px 24px;
                                                background-color: #007bff;
                                                color: #ffffff;
                                                text-decoration: none;
                                                border-radius: 6px;
                                                font-weight: bold;
                                                font-size: 16px;
                                                text-align: center;
                                                word-break: break-word;
                                            ">Redefinir Senha
                                        </a>

                                    </p>
                                    <p>Se não solicitou esta recuperação, pode ignorar este email.</p>
                                    <hr style="border: none; border-top: 1px solid #ddd; margin: 40px 0;">
                                    <p style="font-size: 14px; color: #555;">Com os melhores cumprimentos,<br><strong>Equipa Emprega.me</strong></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';
    }

    /**
     * Template para senha alterada com sucesso
     */
    public static function senhaAlterada(string $nome): string
    {
        return '
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
                <style>
                    @media only screen and (max-width: 600px) {
                        .email-container { width: 100% !important; padding: 20px !important; }
                    }
                </style>
                <tr>
                    <td align="center">
                        <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px;">
                            <tr>
                                <td align="center" style="padding-bottom: 20px;">
                                    <img src="https://static.vecteezy.com/ti/vetor-gratis/p1/4891104-logotipo-para-encontrar-emprego-simples-e-moderno-gratis-vetor.jpg"
                                         alt="Emprega.me Logo"
                                         style="max-width: 100%; height: auto; display: block; margin: auto;">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #333; font-size: 16px;">
                                    <p style="margin-bottom: 20px;">Olá <strong>' . htmlspecialchars($nome) . '</strong>,</p>
                                    <p style="margin-bottom: 20px;">A sua senha foi <strong>alterada com sucesso</strong>.</p>
                                    <p style="margin-bottom: 30px;">Se não reconhece esta alteração, por favor contacte o suporte imediatamente.</p>
                                    <hr style="border: none; border-top: 1px solid #ddd; margin: 40px 0;">
                                    <p style="font-size: 14px; color: #555;">Com os melhores cumprimentos,<br><strong>Equipa Emprega.me</strong></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';
    }

    /**
     * Template para confirmar conta
     */
    public static function confirmacaoRegisto(string $link, string $nome): string
    {
        return '
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
                <style>
                    @media only screen and (max-width: 600px) {
                        .email-container { width: 100% !important; padding: 20px !important; }
                    }
                </style>
                <tr>
                    <td align="center">
                        <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px;">
                            <tr>
                                <td align="center" style="padding-bottom: 20px;">
                                    <img src="https://static.vecteezy.com/ti/vetor-gratis/p1/4891104-logotipo-para-encontrar-emprego-simples-e-moderno-gratis-vetor.jpg"
                                         alt="Emprega.me Logo"
                                         style="max-width: 100%; height: auto; display: block; margin: auto;">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #333; font-size: 16px;">
                                    <p style="margin-bottom: 20px;">Olá <strong>' . htmlspecialchars($nome) . '</strong>,</p>
                                    <p style="margin-bottom: 20px;">Obrigado por se registar na <strong>Emprega.me</strong>!</p>
                                    <p style="margin-bottom: 20px;">Clique no botão abaixo para confirmar a sua conta:</p>
            
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
                                        <tr>
                                            <td align="center">
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td bgcolor="#28a745" style="border-radius: 6px;">
                                                            <a href="' . htmlspecialchars($link) . '" style="
                                                                display: inline-block;
                                                                padding: 12px 24px;
                                                                font-size: 16px;
                                                                color: #ffffff;
                                                                text-decoration: none;
                                                                border-radius: 6px;
                                                                font-weight: bold;
                                                                font-family: Arial, sans-serif;
                                                                text-align: center;
                                                                width: 100%;
                                                                box-sizing: border-box;
                                                            ">Confirmar Registo</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
            
                                    <p style="margin-bottom: 10px;">Se o botão não funcionar, copie e cole o seguinte link no seu navegador:</p>
                                    <p style="word-break: break-all; color: #007bff;">' . htmlspecialchars($link) . '</p>
                                    <hr style="border: none; border-top: 1px solid #ddd; margin: 40px 0;">
                                    <p style="font-size: 14px; color: #555;">Com os melhores cumprimentos,<br><strong>Equipa Emprega.me</strong></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';
    }
}