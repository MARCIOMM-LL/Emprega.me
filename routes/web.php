<?php
use App\Core\Router;

$router = new Router();

// Rota pública de dashboard (exemplo de rota web normal)
$router->get('/api/csrf-token', 'CsrfController@token');
$router->get('dashboard', 'HomeController@index');

// API de autocomplete (exemplo de rota de API)
$router->get('api/autocomplete/pesquisar', 'AutocompleteController@pesquisar');

// Autenticação de Candidato
$router->get('register-candidato', 'CandidatoAuthController@register');
$router->post('register-candidato', 'CandidatoAuthController@register');
$router->get('login-candidato', 'CandidatoAuthController@login');
$router->post('login-candidato', 'CandidatoAuthController@login');
$router->get('confirmar-candidato', 'CandidatoAuthController@confirmar');
$router->get('logout-candidato', 'CandidatoAuthController@logout');

// Endpoints AJAX para Candidato
$router->post('api/login-candidato', 'CandidatoAuthController@loginAjax');
$router->post('api/register-candidato', 'CandidatoAuthController@registerAjax');


$router->post('auth/verificar-email-sessao-candidato', 'CandidatoAuthController@verificarEmailSessaoCandidato');
$router->post('auth/reenviar-email-sessao-candidato', 'CandidatoAuthController@reenviarEmailConfirmacaoSessaoCandidato');


// Recuperação de Senha - Candidato
$router->post('api/recuperar-senha-candidato', 'CandidatoAuthController@enviarLinkRecuperacao');
$router->get('redefinir-senha-candidato', 'CandidatoAuthController@formRedefinirSenha');
$router->post('redefinir-senha-candidato', 'CandidatoAuthController@processarNovaSenha');

// Autenticação de Empresa
$router->get('register-empresa', 'EmpresaAuthController@register');
$router->post('register-empresa', 'EmpresaAuthController@register');
$router->get('login-empresa', 'EmpresaAuthController@login');
$router->post('login-empresa', 'EmpresaAuthController@login');
$router->get('confirmar-empresa', 'EmpresaAuthController@confirmar');
$router->get('logout-empresa', 'EmpresaAuthController@logout');

// Endpoints AJAX para Empresa
$router->post('api/login-empresa', 'EmpresaAuthController@loginAjax');
$router->post('api/register-empresa', 'EmpresaAuthController@registerAjax');

// Recuperação de senha da Empresa
$router->post('api/recuperar-senha-empresa', 'EmpresaAuthController@enviarLinkRecuperacao');
$router->get('redefinir-senha-empresa', 'EmpresaAuthController@formRedefinirSenha');
$router->post('redefinir-senha-empresa', 'EmpresaAuthController@processarNovaSenha');


$router->post('auth/verificar-email-sessao-empresa', 'EmpresaAuthController@verificarEmailSessaoEmpresa');
$router->post('auth/reenviar-email-sessao-empresa', 'EmpresaAuthController@reenviarEmailConfirmacaoSessaoEmpresa');


return $router;

