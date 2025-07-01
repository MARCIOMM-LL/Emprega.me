
<?php
use App\Helpers\FlashHelper;
use App\Helpers\CsrfHelper;

session_start();

// Token CSRF
$csrfToken = CsrfHelper::gerarToken();

// Verificar se o user existe (se foi apagado da base, desloga automaticamente)
$userCandidato = \App\Helpers\AuthHelper::userOrLogout('candidato');
$userEmpresa = \App\Helpers\AuthHelper::userOrLogout('empresa');

// Flash Messages
$flash = FlashHelper::get();
?>

<?php //$flash = FlashHelper::get(); ?>
<?php if (isset($flash['type'], $flash['message']) && $flash['type'] && $flash['message']): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '<?= $flash['type'] ?>',  // success | error | info | warning
            title: '<?= $flash['message'] ?>',
            timer: 5000,  // ⏱️ Muda aqui o tempo se quiseres
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timerProgressBar: true,
            customClass: {
                popup: 'custom-swal-spacing'
            }
        });
    });
</script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <style>
        .custom-swal-spacing {
            margin-top: 5rem;  /* Distância do topo */
            background: linear-gradient(360deg, #d0e4ff 590%, #91caff 90%) !important ;
        }
        /*-----------------------------SCROLL TO TOP-----------------------------*/
        @keyframes fadeInRight {
            0% {
                opacity: 0;
                /*-webkit-transform: translateX(20px);*/
                /*-ms-transform: translateX(20px);*/
                transform: translateX(20px)
            }
            100% {
                opacity: 1;
                /*-webkit-transform: translateX(0);*/
                /*-ms-transform: translateX(0);*/
                transform: translateX(0)
            }
        }
        /*-----------------------------SCROLL TO TOP-----------------------------*/

        /*-----------------------------TO AVOID CONFLICT WITH scroll-behavior: smooth-----------------------------*/
        html {
            scroll-behavior: auto !important;
        }
        /*-----------------------------TO AVOID CONFLICT WITH scroll-behavior: smooth-----------------------------*/
    </style>

    <title>Emprega.me - O maior portal de Emprego de Portugal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="content-language" content="pt-pt" />

    <meta charset="ISO-8859-1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="/../assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css"/>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Internal CSS libraries -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

</head>

<body>
<div class="page_loader" style="display: none;"></div>

<!-- NAVBAR START -->
<header class="header">
    <div class="container">
        <nav class="navbar">
            <a class="logo" href="" style="width: 135px">
                <img class="image-logo" id="image-logo" src="/../assets/img/logo_novo_small.png" alt="net-empregos logo" style="transition: 0.5s;">
            </a>

            <div class="navbar-collapse" id="navbarSupportedContent" style="height: 80px">
                <ul class="sub-navbar-collapse sub-navbar-ml">
                    <li>
                        <a class="nav-link" href="" style="font-weight:bold;color: #374151;">
                            <i class="fa fa-search"></i>
                            Pesquisar Ofertas
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link" href="javascript:void(0)" style="color: #374151;">
                            <i class="fa fa-users"></i>
                            Candidato
                            <i id="remove-1" class="fa fa-caret-down"></i>
                        </a>

                        <ul class="dropdown-content">
                            <li><a class="item-dropdown" style="font-weight:bold" href=""><i class="fa fa-address-card-o"></i> Perfil</a></li>
                            <li><a class="item-dropdown" href=><i class="fa fa-user-circle-o"></i> Registo</a></li>
                            <li><a class="item-dropdown" href=><i class="fa fa-star"></i> Ofertas Favoritas</a></li>
                        </ul>
                    </li>

                    <li class="dropdown-specific-size">
                        <a class="nav-link" href="javascript:void(0)" style="color: #374151;">
                            <i class="fa-regular fa-building"></i>
                            Empresa
                            <i id="remove-2" class="fa fa-caret-down"></i>
                        </a>

                        <ul class="dropdown-content-specific-size">
                            <li><a class="item-dropdown" style="font-weight:bold;" href=""><i class="fa fa-plus-square"></i> Publicar Oferta Grátis</a></li>
                            <li><a class="item-dropdown" style="font-weight:bold;" href=""><i class="fa fa-list-ul"></i> Ofertas Activas</a></li>
                            <li><a class="item-dropdown green" style="font-weight:bold; color:green" href=""><i class="fa fa-arrow-up"></i>  Pacotes Destaques</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-folder-open-o"></i> Ofertas Arquivadas</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-graduation-cap"></i> Ofertas de Formação</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-user-circle-o"></i> Registo</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-info-circle"></i> Logo / Info + API</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-file-text-o"></i> Facturas</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" style="color: #374151;">
                            <i class="fa fa-graduation-cap"></i>
                            Formação
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" style="color: #0ea5e9; /* Azul claro com tom de comunicação */
">
                            <i class="fa fa-envelope"></i>
                            <span class="espaçar">Contactos</span>
                        </a>
                    </li>
                </ul>

                <ul class="sub-navbar-collapse sub-navbar-ml-auto white">
                    <?php if (isset($_SESSION['candidato']) || isset($_SESSION['empresa'])): ?>
                        <li class="dropdown">
                            <a class="nav-link" style="color: black; background: none; border: none; cursor: pointer;">
                                <i class="fa fa-user-circle-o"></i>
                                <?= isset($_SESSION['candidato']) ? htmlspecialchars($_SESSION['candidato']['nome']) :
                                                                     htmlspecialchars($_SESSION['empresa']['nome']); ?>
                            </a>
                            <ul class="dropdown-content">
                                <li>
                                    <a class="item-dropdown red" href="/perfil" style="color: #374151;">
                                        <i class="fa-regular fa-building"></i>
                                        Perfil
                                    </a>
                                </li>
                                <li>
                                    <a href="/logout-candidato" class="item-dropdown red" style="color: #374151;">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a class="nav-link" href="javascript:void(0)" style="color: #374151;">
                                <i class="fa fa-user"></i>
                                Login
                                <i id="remove-3" class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-content">
                                <li>
                                    <a href="javascript:void(0);" onclick="abrirModalLoginCandidato();" class="item-dropdown red" style="color: #374151;">
                                        <i class="fa fa-users"></i>
                                        Candidato
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="abrirModalLoginEmpresa();" class="item-dropdown red" style="color: #374151;">
                                        <i class="fa-regular fa-building"></i>
                                        Empresa
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <a href="" style="width: 50px;">
                    <img src="/../assets/img/blank.png" alt="logo">
                </a>
            </div>
        </nav>
    </div>
</header>
<!-- NAVBAR END -->

<!-- BODY PAGE START -->
<div class="body-page">
    <div class="container">
        <div class="row">
            <!-------------------------------- FORM START --------------------------------->
            <div class="col-sm-4 col-lg-12 banner">
                <div class="search-info" style="border-radius: 15px;">
                    <div class="banner-inner text-left">
                        <div class="banner-text">

                            <br>

                            <h2>O maior portal de emprego de Portugal</h2>

                            <form action="/" id="pesquisa">
                                <div class="inline-search-area ml-auto mr-auto">
                                    <div class="search-boxs">
                                        <div class="search-col">
                                            <div class="icon-search-input">
                                                <div class="easy-autocomplete eac-blue-light" style="width: 100%;">
                                                    <input type="text" id="profissao" name="profissao" class="form-control"
                                                           placeholder="O que?" title="Pesquisar por profissao, empresa, referencia ..."
                                                           autocomplete="off">
                                                    <div class="easy-autocomplete-container" id="eac-container-chaves">
                                                        <ul style="color: #535353 !important;"></ul>
                                                    </div>
                                                </div>
                                                <i class="fa fa-search text-warning"></i>
                                            </div>
                                        </div>

                                        <div class="search-col fontuser">
                                            <div class="icon-search-input">
                                                <div class="easy-autocomplete eac-blue-light" style="width: 100%;">
                                                    <input id="cidade" name="cidade" type="text" class="form-control" placeholder="Onde?" title="Pesquisar por cidade, vila, regiao, localidade ..." autocomplete="off">
                                                    <div class="easy-autocomplete-container" id="eac-container-cidade">
                                                        <ul style="color: #535353 !important;"></ul>
                                                    </div>
                                                </div>
                                                <i class="fa fa-map-marker text-warning"></i>
                                            </div>
                                        </div>

                                        <div class="search-col">
                                            <b class="b" style="color: #fff;line-height: 1.5;font-size: 1rem;">Ou filtrar por categoria e zona:</b>
                                            <div class="icon-search-input-combo">
                                                <select style="text-indent:20px;" id="categoria" name="categoria" class="form-control input-text font-weight-bold">
                                                    <option value="0">( Todas as Categorias )</option><option value="29">Administração / Secretariado</option><option value="39">Agricultura / Florestas / Pescas</option><option value="22">Arquitectura / Design</option><option value="40">Artes / Entretenimento / Media</option><option value="16">Banca / Seguros / Serviços Financeiros</option><option value="47">Beleza / Moda / Bem Estar</option><option value="57">Call Center / Help Desk</option><option value="53">Comercial / Vendas</option><option value="8">Comunicação Social / Media</option><option value="51">Conservação / Manutenção / Técnica</option><option value="23">Construção Civil</option><option value="15">Contabilidade / Finanças</option><option value="28">Desporto / Ginásios</option><option value="44">Direito / Justiça</option><option value="11">Educação / Formação</option><option value="54">Engenharia ( Ambiente )</option><option value="45">Engenharia ( Civil )</option><option value="46">Engenharia ( Eletrotecnica )</option><option value="24">Engenharia ( Mecanica )</option><option value="50">Engenharia ( Química / Biologia )</option><option value="41">Farmácia / Biotecnologia</option><option value="26">Gestão de Empresas / Economia</option><option value="32">Gestão RH</option><option value="9">Hotelaria / Turismo</option><option value="12">Imobiliário</option><option value="6">Indústria / Produção</option><option value="38">Informática ( Analise de Sistemas )</option><option value="34">Informática ( Formação )</option><option value="37">Informática ( Gestão de Redes )</option><option value="35">Informática ( Internet )</option><option value="36">Informática ( Multimedia )</option><option value="5">Informática ( Programação )</option><option value="49">Informática ( Técnico de Hardware )</option><option value="56">Informática (Comercial/Gestor de Conta)</option><option value="58">Limpezas / Domésticas</option><option value="30">Lojas / Comércio / Balcão</option><option value="19">Publicidade / Marketing</option><option value="18">Relações Públicas</option><option value="42">Restauração / Bares / Pastelarias</option><option value="14">Saúde / Medicina / Enfermagem</option><option value="55">Serviços Sociais</option><option value="52">Serviços Técnicos</option><option value="1">Telecomunicações</option><option value="43">Transportes / Logística</option>
                                                </select>
                                                <i class="fa fa-university text-warning icon"></i>
                                            </div>
                                        </div>

                                        <div class="search-col">
                                            <div class="icon-search-input-combo">
                                                <select style="text-indent:20px;" id="zona" name="zona" class="form-control input-text font-weight-bold">
                                                    <option value="0">( Todas as Zonas )</option><option value="25">Açores</option><option value="4">Aveiro</option><option value="15">Beja</option><option value="3">Braga</option><option value="5">Bragança</option><option value="10">Castelo Branco</option><option value="9">Coimbra</option><option value="14">Evora</option><option value="17">Faro</option><option value="7">Guarda</option><option value="11">Leiria</option><option value="1">Lisboa</option><option value="26">Madeira</option><option value="16">Portalegre</option><option value="2">Porto</option><option value="12">Santarem</option><option value="13">Setubal</option><option value="28">Viana do Castelo</option><option value="6">Vila Real</option><option value="8">Viseu</option><option value="20">Estrangeiro - Angola</option><option value="18">Estrangeiro - Brasil</option><option value="24">Estrangeiro - Cabo Verde</option><option value="22">Estrangeiro - Guine Bissau</option><option value="21">Estrangeiro - Moçambique</option><option value="23">Estrangeiro - São Tome e Principe</option><option value="27">Estrangeiro - Timor</option><option value="29">Outros Locais - Estrangeiro</option>
                                                </select>
                                                <i class="different fa fa-map-marker text-warning"></i>
                                            </div>
                                        </div>

                                        <div class="search-col">
                                            <b class="b" style="color: #fff;line-height: 1.5;font-size: 1rem;">Tipo horario</b>
                                            <div class="icon-search-input-combo">
                                                <select style="text-indent:20px;" id="tipo" name="tipo" class="form-control input-text font-weight-bold">
                                                    <option value="0">( Todos os Tipos )</option><option value="1">Tempo Inteiro</option><option value="2">Part-Time</option><option value="3">Estágio</option><option value="4">Teletrabalho</option>
                                                </select>
                                                <i class="fa fa-clock-o text-warning clock"></i>
                                            </div>
                                        </div>

                                        <div class="find">
                                            <button class="btn button-theme btn-search btn-block  btn-ripple btn-lg" id="pesquisar">
                                                <i class="fa fa-search"></i>
                                                <strong> Pesquisar</strong>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- ATUANDO NAS MEDIAS QUERIES START -->
                            <div class="clearfix"></div>
                            <!-- ATUANDO NAS MEDIAS QUERIES END -->

                            <div class="browse-jobs text-nowrap" style="font-weight: normal; color: #fff">
                                Ofertas por <a href="/">Categoria</a> ou <a href="/">Cidade</a>
                                <br>
                                <br>
                                Ofertas por <a href="/">Distrito e Categoria</a>

                                <br>

                                <br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------- FORM END --------------------------------->

            <!-------------------------------- ADVERTISEMENT AND CAROUSEL OF JOBS --------------------------------->
            <div class="col-md-8 col-lg-12">
                <div class="row" style="padding: 0; min-height: 250px;">
                    <div class="col-lg-6 text-left">

                        <!-- Net-Empregos -- Home -->
                        <ins class="adsbygoogle" style="display: block; height: 250px;" data-full-width-responsive="true" data-ad-client="ca-pub-9487857958358554" data-ad-slot="6083659402" data-ad-format="auto" data-adsbygoogle-status="done" data-ad-status="filled"><div id="aswift_1_host" style="border: none; height: 250px; width: 290px; margin: 0px; padding: 0px; position: relative; visibility: visible; background-color: transparent; display: inline-block; overflow: visible;"><iframe id="aswift_1" name="aswift_1" browsingtopics="true" style="left:0;position:absolute;top:0;border:0;width:290px;height:250px;" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" width="290" height="250" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allow="attribution-reporting; run-ad-auction" src="https://googleads.g.doubleclick.net/pagead/ads?us_privacy=1---&amp;client=ca-pub-9487857958358554&amp;output=html&amp;h=250&amp;slotname=6083659402&amp;adk=2598333509&amp;adf=211772039&amp;pi=t.ma~as.6083659402&amp;w=290&amp;abgtt=6&amp;fwrn=4&amp;fwrnh=100&amp;lmt=1738560496&amp;rafmt=1&amp;format=290x250&amp;url=https%3A%2F%2Fwww.net-empregos.com%2Findex.asp&amp;fwr=0&amp;fwrattr=true&amp;rpe=1&amp;resp_fmts=3&amp;wgl=1&amp;uach=WyJBbmRyb2lkIiwiNi4wIiwiIiwiTmV4dXMgNSIsIjEzMi4wLjY4MzQuMTYwIixudWxsLDEsbnVsbCwiNjQiLFtbIk5vdCBBKEJyYW5kIiwiOC4wLjAuMCJdLFsiQ2hyb21pdW0iLCIxMzIuMC42ODM0LjE2MCJdLFsiR29vZ2xlIENocm9tZSIsIjEzMi4wLjY4MzQuMTYwIl1dLDBd&amp;dt=1738560494944&amp;bpp=2&amp;bdt=1547&amp;idt=1247&amp;shv=r20250129&amp;mjsv=m202501230101&amp;ptt=9&amp;saldr=aa&amp;abxe=1&amp;cookie=ID%3Ddb6162d2c7b30227%3AT%3D1738039727%3ART%3D1738560185%3AS%3DALNI_MZkqkhIdmovRenSOpqb4sZ5xoGKKA&amp;gpic=UID%3D00000fd8f21d2699%3AT%3D1738039727%3ART%3D1738560185%3AS%3DALNI_MbV5K01ZOs8jAlTBdnooZLnhXUc7Q&amp;eo_id_str=ID%3Df0694a843bf886af%3AT%3D1738039727%3ART%3D1738560185%3AS%3DAA-AfjamSWjF_nmDGv0Ad0arvYqY&amp;prev_fmts=0x0&amp;nras=1&amp;correlator=8777837074233&amp;frm=20&amp;pv=1&amp;u_tz=-180&amp;u_his=5&amp;u_h=700&amp;u_w=993&amp;u_ah=700&amp;u_aw=993&amp;u_cd=24&amp;u_sd=2&amp;dmc=8&amp;adx=351&amp;ady=110&amp;biw=993&amp;bih=700&amp;scr_x=0&amp;scr_y=0&amp;eid=31090070%2C95344789%2C95351059%2C31088249%2C95347432&amp;oid=2&amp;pvsid=911957375836336&amp;tmod=504084473&amp;uas=0&amp;nvt=1&amp;ref=https%3A%2F%2Fwww.net-empregos.com%2F&amp;fc=1920&amp;brdim=0%2C0%2C0%2C0%2C993%2C0%2C993%2C700%2C993%2C700&amp;vis=1&amp;rsz=%7Cm%7CpEe%7C&amp;abl=NS&amp;pfx=0&amp;fu=128&amp;bc=31&amp;bz=1&amp;td=1&amp;tdf=2&amp;psd=W251bGwsbnVsbCxudWxsLDNd&amp;nt=1&amp;ifi=2&amp;uci=a!2&amp;fsb=1&amp;dtd=1281" data-google-container-id="a!2" tabindex="0" title="Advertisement" aria-label="Advertisement" data-google-query-id="CKefpcbiposDFVw5uQYdxQU75A" data-load-complete="true"></iframe></div></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>

                    </div>
                    <div class="col-lg-6 text-left">

                    <!-- Net-Empregos -- Home -->
                    <ins class="adsbygoogle" style="display: block; height: 250px;" data-full-width-responsive="true" data-ad-client="ca-pub-9487857958358554" data-ad-slot="6083659402" data-ad-format="auto" data-adsbygoogle-status="done" data-ad-status="filled"><div id="aswift_1_host" style="border: none; height: 250px; width: 290px; margin: 0px; padding: 0px; position: relative; visibility: visible; background-color: transparent; display: inline-block; overflow: visible;"><iframe id="aswift_1" name="aswift_1" browsingtopics="true" style="left:0;position:absolute;top:0;border:0;width:290px;height:250px;" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" width="290" height="250" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allow="attribution-reporting; run-ad-auction" src="https://googleads.g.doubleclick.net/pagead/ads?us_privacy=1---&amp;client=ca-pub-9487857958358554&amp;output=html&amp;h=250&amp;slotname=6083659402&amp;adk=2598333509&amp;adf=211772039&amp;pi=t.ma~as.6083659402&amp;w=290&amp;abgtt=6&amp;fwrn=4&amp;fwrnh=100&amp;lmt=1738560496&amp;rafmt=1&amp;format=290x250&amp;url=https%3A%2F%2Fwww.net-empregos.com%2Findex.asp&amp;fwr=0&amp;fwrattr=true&amp;rpe=1&amp;resp_fmts=3&amp;wgl=1&amp;uach=WyJBbmRyb2lkIiwiNi4wIiwiIiwiTmV4dXMgNSIsIjEzMi4wLjY4MzQuMTYwIixudWxsLDEsbnVsbCwiNjQiLFtbIk5vdCBBKEJyYW5kIiwiOC4wLjAuMCJdLFsiQ2hyb21pdW0iLCIxMzIuMC42ODM0LjE2MCJdLFsiR29vZ2xlIENocm9tZSIsIjEzMi4wLjY4MzQuMTYwIl1dLDBd&amp;dt=1738560494944&amp;bpp=2&amp;bdt=1547&amp;idt=1247&amp;shv=r20250129&amp;mjsv=m202501230101&amp;ptt=9&amp;saldr=aa&amp;abxe=1&amp;cookie=ID%3Ddb6162d2c7b30227%3AT%3D1738039727%3ART%3D1738560185%3AS%3DALNI_MZkqkhIdmovRenSOpqb4sZ5xoGKKA&amp;gpic=UID%3D00000fd8f21d2699%3AT%3D1738039727%3ART%3D1738560185%3AS%3DALNI_MbV5K01ZOs8jAlTBdnooZLnhXUc7Q&amp;eo_id_str=ID%3Df0694a843bf886af%3AT%3D1738039727%3ART%3D1738560185%3AS%3DAA-AfjamSWjF_nmDGv0Ad0arvYqY&amp;prev_fmts=0x0&amp;nras=1&amp;correlator=8777837074233&amp;frm=20&amp;pv=1&amp;u_tz=-180&amp;u_his=5&amp;u_h=700&amp;u_w=993&amp;u_ah=700&amp;u_aw=993&amp;u_cd=24&amp;u_sd=2&amp;dmc=8&amp;adx=351&amp;ady=110&amp;biw=993&amp;bih=700&amp;scr_x=0&amp;scr_y=0&amp;eid=31090070%2C95344789%2C95351059%2C31088249%2C95347432&amp;oid=2&amp;pvsid=911957375836336&amp;tmod=504084473&amp;uas=0&amp;nvt=1&amp;ref=https%3A%2F%2Fwww.net-empregos.com%2F&amp;fc=1920&amp;brdim=0%2C0%2C0%2C0%2C993%2C0%2C993%2C700%2C993%2C700&amp;vis=1&amp;rsz=%7Cm%7CpEe%7C&amp;abl=NS&amp;pfx=0&amp;fu=128&amp;bc=31&amp;bz=1&amp;td=1&amp;tdf=2&amp;psd=W251bGwsbnVsbCxudWxsLDNd&amp;nt=1&amp;ifi=2&amp;uci=a!2&amp;fsb=1&amp;dtd=1281" data-google-container-id="a!2" tabindex="0" title="Advertisement" aria-label="Advertisement" data-google-query-id="CKefpcbiposDFVw5uQYdxQU75A" data-load-complete="true"></iframe></div></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>

                </div>
                </div>

                <br>

                <div class="col-lg-12 text-center">
                    <h4>
                        <label class="font-weight-bold" style="color: rgb(0, 88, 122);">
                            Empresas em Destaque
                        </label>
                    </h4>

                    <div class="destaques" style="display: none">
                        <div class='net-div-destaque-logo'>
                            <div class='net-div-destaque-logo'
                                 style='border: 5px solid #f8f9fa;
                                 display: flex;
                                 justify-content: center;
                                 align-items: center;
                                 margin: 0 0;'>
                                <a href='/emprego-empresa-id/268607/velis-lda/'>
                                    <img title='Ver ofertas desta empresa'
                                         alt='velis-lda'
                                         class='net-destaque-logo'
                                         loading='lazy'
                                         src='https://upload.net-empregos.com/uploads/74b33ef995994c20a5837236699da627/logo-net-empregos.png'>
                                </a>
                            </div>
                        </div>

                        <div class='net-div-destaque-logo'><div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/131552/mz-contabilistas-lda/'><img title='Ver ofertas desta empresa' alt='mz-contabilistas-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/afc2de70c5094749893c94627b77ad70/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/272775/quinta-das-fontaltas/'><img title='Ver ofertas desta empresa' alt='quinta-das-fontaltas' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/8e679ea5175c4a59a9f9a366c21adcf3/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/135330/tea-shop/'><img title='Ver ofertas desta empresa' alt='tea-shop' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/e9d60bc4d93148c2a1f53c6625760fcc/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/234468/manubela-cabelerieiros/'><img title='Ver ofertas desta empresa' alt='manubela-cabelerieiros' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/a7c308b75bd341a2aadde0aef6b46099/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/108497/interim-direct-empresa-de-trabalho-temporario/'><img title='Ver ofertas desta empresa' alt='interim-direct-empresa-de-trabalho-temporario' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/0eebdd3d860a44a58236ce18e8edd2bb/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/270447/congelados-douro-sul-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='congelados-douro-sul-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/1e3f7a9a6dd444299bfe9c605eded823/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/34543/c-r-contab-e-fisc/'><img title='Ver ofertas desta empresa' alt='c-r-contab-e-fisc' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/ef1b0ac4783140cca2169078ae6500c0/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/235327/odiveltextil-texteis-confeccoes-de-odivelas-lda/'><img title='Ver ofertas desta empresa' alt='odiveltextil-texteis-confeccoes-de-odivelas-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/c1a0663f1b5042128d7cf12e06dbc35d/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/189332/maxloja-mediacao-imobiliaria-lda/'><img title='Ver ofertas desta empresa' alt='maxloja-mediacao-imobiliaria-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/b5242b60ca174141b18e91e53283a994/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/267320/rm-group-projectos-into-reality/'><img title='Ver ofertas desta empresa' alt='rm-group-projectos-into-reality' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/af9a412894f04afead35b99c7732118a/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/76092/eurodescontos/'><img title='Ver ofertas desta empresa' alt='eurodescontos' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/bdc05605246346558a287f31234ad291/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/188533/kasas-e-kasas-algarve-property-lda/'><img title='Ver ofertas desta empresa' alt='kasas-e-kasas-algarve-property-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/aa21e935987846b2a319206c88e48340/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/161146/planeta-aprender/'><img title='Ver ofertas desta empresa' alt='planeta-aprender' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/c94e65d1172e4254b9b35f74d8d4a1ee/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/94910/grato-limite/'><img title='Ver ofertas desta empresa' alt='grato-limite' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/dac346096caa45a994de41877994251f/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/149394/psicotempos-empresa-de-trabalho-temporario-lda/'><img title='Ver ofertas desta empresa' alt='psicotempos-empresa-de-trabalho-temporario-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/7abce99af8bd4ed2a172f2b3e5be0a77/psicotempos(neg).jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/201928/sotavinhos-distribuicao-de-bebidas-s-a/'><img title='Ver ofertas desta empresa' alt='sotavinhos-distribuicao-de-bebidas-s-a' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/87a3f33cb37246bb817acbb6678bed75/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/283240/b3free-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='b3free-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/f476fc3a55144088a1646f21613e03e6/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/109766/iservices-lda/'><img title='Ver ofertas desta empresa' alt='iservices-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/18cafd8cb1f5424ea4f6c8ccf56307d7/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/208569/tcagest-lda/'><img title='Ver ofertas desta empresa' alt='tcagest-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/98b6e1e3b4254fbfa2b394e374f8e961/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/159576/ouro-vivo-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='ouro-vivo-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/c54898f831e5472a935896564e1bfc15/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/245364/dominio-blue/'><img title='Ver ofertas desta empresa' alt='dominio-blue' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/d19b342f11b4491c9a2fb9a410a2757f/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/175405/gracer-sociedade-de-turismo-do-algarve-s-a/'><img title='Ver ofertas desta empresa' alt='gracer-sociedade-de-turismo-do-algarve-s-a' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/7b2283fb3d734df591dec615abf30141/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/265865/zome-mem-martins/'><img title='Ver ofertas desta empresa' alt='zome-mem-martins' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/d71cc0ab5adc4f42a5e981ede32d9ed9/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/171493/cubique-solutions-lda/'><img title='Ver ofertas desta empresa' alt='cubique-solutions-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/452a13d773724971a953ac60e2eaad4b/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/247563/abrasigaia-imp-e-com-abrasivos-lda/'><img title='Ver ofertas desta empresa' alt='abrasigaia-imp-e-com-abrasivos-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/6c8e5877062f475390b5fd92c42ecd9d/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/20915/ankarsa-engenharia-e-construcao/'><img title='Ver ofertas desta empresa' alt='ankarsa-engenharia-e-construcao' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/f3b65a427cf94dfda35972259556b816/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/269712/rede-global-gestao-e-exploracao-de-franquias-sar/'><img title='Ver ofertas desta empresa' alt='rede-global-gestao-e-exploracao-de-franquias-sar' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/b1c6aeaa56d14f20abd5bae9599ef62d/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/268374/bodyconcept-azeitao-bibella-estetica-unip-lda/'><img title='Ver ofertas desta empresa' alt='bodyconcept-azeitao-bibella-estetica-unip-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/3c86eea542b64fb099fa034eb276e5ca/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/35451/brettecnica-lda/'><img title='Ver ofertas desta empresa' alt='brettecnica-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/3e634b1c51a64031a93903233ca4fc9b/logo-net-empregos.jpeg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/136185/sistemas-mcdonald-s-portugal/'><img title='Ver ofertas desta empresa' alt='sistemas-mcdonald-s-portugal' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/e6db6dc66a4340909be1b8bcc8d8f975/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/76155/masterideia/'><img title='Ver ofertas desta empresa' alt='masterideia' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/95c6f1f8d3704d20a9e15aeb3a0c9859/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/12960/adn-maquinas-lda/'><img title='Ver ofertas desta empresa' alt='adn-maquinas-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/965cbce301a7442492a3cdb76035b36c/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/262156/normalas-portugal-lda/'><img title='Ver ofertas desta empresa' alt='normalas-portugal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/2dd8c400d0c0435387c7ecccf53aaad3/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/226260/wake-day-spa/'><img title='Ver ofertas desta empresa' alt='wake-day-spa' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/8d94d618ca6e4908b144560c73ecf85c/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/177145/radio-popular-sa/'><img title='Ver ofertas desta empresa' alt='radio-popular-sa' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/e342978cc47e487da7b740446f2d38e3/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/253732/telhados-de-agua-construcao-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='telhados-de-agua-construcao-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/7879611699b84a57ab70b45d31c796ae/logo-net-empregos.jpeg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/199028/smart-av/'><img title='Ver ofertas desta empresa' alt='smart-av' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/6f11963e031b43c08c4acec0ec032586/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/258786/metalomecanica-agrela-lda/'><img title='Ver ofertas desta empresa' alt='metalomecanica-agrela-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/3553b254c98b4391bd2b57ac9500766b/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/288250/alesckdoom-lda/'><img title='Ver ofertas desta empresa' alt='alesckdoom-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/dded473f6e3d4f5788673de316a394e4/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/34311/bolama-supermercados/'><img title='Ver ofertas desta empresa' alt='bolama-supermercados' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/f50e54b4e218491a916db0afa7043be2/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/124100/let-s-go-ginasio/'><img title='Ver ofertas desta empresa' alt='let-s-go-ginasio' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/b48d7bad16da4c46a6e8c6b06b0da05c/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/165317/salus-consulting-lda/'><img title='Ver ofertas desta empresa' alt='salus-consulting-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/c4299fff039d4ef99c825e14c7e7e662/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/219654/vac-minerais/'><img title='Ver ofertas desta empresa' alt='vac-minerais' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/cb392d38766e4dab8989089066ca4c2c/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/288504/la-serena-by-serenfit/'><img title='Ver ofertas desta empresa' alt='la-serena-by-serenfit' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/67ffc69da5cc4422aee6382db1a52a34/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/179290/reciclimpa-lda/'><img title='Ver ofertas desta empresa' alt='reciclimpa-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/dccf4e1731a047a29bea66719de8a23b/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/202996/sps-swiss-group-portugal-tfse-lda/'><img title='Ver ofertas desta empresa' alt='sps-swiss-group-portugal-tfse-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/44795b909aa347a29fc3b7e7816d4938/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/270696/erros-e-acertos-construcao-civil-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='erros-e-acertos-construcao-civil-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/0936ffa43f6d462fbe57b9e0b67e4f48/logo-net-empregos.jpeg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/265836/dasprent-rent-a-car-lda/'><img title='Ver ofertas desta empresa' alt='dasprent-rent-a-car-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/379b46799c364f88a16d47185af165ad/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/157800/exclusivevanilla-s-a/'><img title='Ver ofertas desta empresa' alt='exclusivevanilla-s-a' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/629c76bb475346a98ce78e7ce80df7ca/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/297250/jose-almeida-fp-lda/'><img title='Ver ofertas desta empresa' alt='jose-almeida-fp-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/05efe08396654cc2aab02af636cdd94e/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/267370/alfabeto-linear-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='alfabeto-linear-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/3ab90b8e3793441d8fd3b896056ac678/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/98142/heading-recursos-humanos-portugal/'><img title='Ver ofertas desta empresa' alt='heading-recursos-humanos-portugal' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/01292c4fd0f34e2490f137f83cc3f619/logo-heading.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/85093/forgesp-formacao-e-gestao-de-empresas-lda/'><img title='Ver ofertas desta empresa' alt='forgesp-formacao-e-gestao-de-empresas-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/6aa140215c1f4eb2bea31573217deeaa/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/54702/grupoconcept/'><img title='Ver ofertas desta empresa' alt='grupoconcept' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/c2dc85fba39d4d709385c7a56aed17a8/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/296700/profisvision-lda/'><img title='Ver ofertas desta empresa' alt='profisvision-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/17bc64d075eb4505bfdd78efff78a7e5/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/73414/eqx-lda/'><img title='Ver ofertas desta empresa' alt='eqx-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/e0a7dfdecaa647ec82a54ba3c57573c1/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/80966/farmacia-serra-das-minas/'><img title='Ver ofertas desta empresa' alt='farmacia-serra-das-minas' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/28cecc4da82543cf8225ce3c4b798056/logo-net-empregos.jpg'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/284750/impetuosocasiao-lda/'><img title='Ver ofertas desta empresa' alt='impetuosocasiao-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/6feb8e6005d44a2fa2b0b5d82efaf5bb/logo-net-empregos.png'></a></div></div><div class='net-div-destaque-logo'> <div class='net-div-destaque-logo' style='border: 5px solid #f8f9fa; display: flex;justify-content: center;align-items: center;margin: 0px 0px;'><a href='/emprego-empresa-id/270541/exportugal-bike-unipessoal-lda/'><img title='Ver ofertas desta empresa' alt='exportugal-bike-unipessoal-lda' class='net-destaque-logo' loading='lazy' src='https://upload.net-empregos.com/uploads/688c6279a2ef4d2983b3bdad996f4ba3/logo-net-empregos.jpg'></a></div></div>
                    </div>
                </div>
            </div>
            <!-------------------------------- ADVERTISEMENT AND CAROUSEL OF JOBS --------------------------------->

            <!-------------------------------------- COUNTERS AND CAROUSEL OF WORKERS ----------------------------->
            <!-- COUNTERS START -->
            <div class="col-lg-12">
                <div class="counters" style="border-radius: 15px; background: linear-gradient(360deg, #d0e4ff 10%, #91caff 90%); margin: 10px 0px; padding: 20px 5px 5px 5px">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 col-sm-12">
                                <div class="counter-box">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <h1 class="counter">45,118</h1>
                                    <p>Ofertas Activas</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-sm-12">
                                <div class="counter-box">
                                    <i class="fa-regular fa-building"></i>
                                    <h1 class="counter">289,816</h1>
                                    <p>Empresas Registadas</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-sm-12">
                                <div class="counter-box">
                                    <i class="fa-regular fa-user"></i>
                                    <h1 class="counter">5,047,062</h1>
                                    <p>Candidatos Registados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- COUNTERS END -->
        </div>

        <h1 class="text-center texto-1" style="font-weight:bold; color:rgb(0, 88, 122); font-size: 2.50rem;">
            O maior portal de emprego de Portugal
        </h1>

        <!-- CAROUSEL WORKERS START -->
        <div class="text-center" style="">
            <div class="carrosel-1 text-center" id="carrosel">

            </div>
        </div>
        <!-- CAROUSEL WORKERS END -->
    </div>
</div>
<!-- BODY PAGE END -->

<!-- FOOTER START -->
<footer class="footer">
    <div class="container footer-inner">
        <div class="row">
            <div class="imagem col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="">
                <div class="footer-item">
                    <img src="/../assets/img/logo_white.png" alt="logo" class="f-logo" style="height:85px;margin-top: -.6rem;margin-left: -1.2rem;">
                </div>
            </div>

            <div class="ofertas col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="line-height: 1;">
                <div class="footer-item">
                    <h4><b>Ofertas Emprego</b></h4>

                    <br>

                    <ul class="links">
                        <li>
                            <a href="">Ultimas Ofertas</a>
                        </li>

                        <li>
                            <a href="">Ofertas por Cidades</a>
                        </li>

                        <li>
                            <a href="">Ofertas por Categoria</a>
                        </li>

                        <li>
                            <a href="">Ofertas por Distrito e Categoria</a>
                        </li>

                        <li>
                            <a href="">Pesquisas Populares</a>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="empregos col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="line-height: 1;">
                <div class="footer-item">
                    <h4 style=""><b>Emprega.me</b></h4>

                    <br>

                    <ul class="links">
                        <li>
                            <a href="" style="">Sobre Nós</a>
                        </li>

                        <li>
                            <a href="">Política de Privacidade e <br> <span class="termos">Termos de Utilização</span></a>
                        </li>

                        <li>
                            <a href="">Publicidade</a>
                        </li>

                        <li>
                            <a href="">Contactos</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="candidato-footer col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="line-height: 1;">
                <div class="footer-item">
                    <h4><b>Candidato</b></h4>

                    <br>

                    <ul class="links">
                        <li>
                            <a href="">Login Candidato</a>
                        </li>

                        <li>
                            <a href="">Registar Candidato</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="empresa-footer col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="line-height: 1;">
                <div class="footer-item clearfix">
                    <h4><b>Empresa</b></h4>

                    <br>

                    <ul class="links">
                        <li>
                            <a href="">Publicar Oferta Grátis</a>
                        </li>

                        <li>
                            <a href="">Login Empresa</a>
                        </li>

                        <li>
                            <a href="">Registar Empresa</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="siga-nos col-xs-12 col-sm-ft-4 col-md-6 col-lg-12" style="line-height: 1;">
                <div class="footer-item clearfix">
                    <h4 class="reduzir"><b>Siga-nos!</b></h4>

                    <br>

                    <ul class="links">
                        <li>
                            <a rel="noreferrer" href="" target="_self">Facebook</a>
                        </li>

                        <li>
                            <a rel="noreferrer" href="" target="_self">Twitter</a>
                        </li>

                        <li>
                            <a rel="noreferrer" href="" target="_self">LinkedIn</a>
                        </li>

                        <li>
                            <a rel="noreferrer" href="" target="_self">RSS Feed</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-12">
                    <p class="copy">2021 <a href="#">Emprega.me | Cenário Virtual Lda</a></p>
                </div>
                <div class="col-sm-4 col-md-12">
                    <ul class="social-list">
                        <li><a rel="noreferrer" href="" target="_blank"><i class="social-icons fa fa-facebook"></i></a></li>
                        <li><a rel="noreferrer" href="" target="_blank"><i class="social-icons fa fa-twitter"></i></a></li>
                        <li><a rel="noreferrer" href="" target="_blank"><i class="social-icons fa fa-linkedin-square"></i></a></li>
                        <li><a rel="noreferrer" href="" target="_blank"><i class="social-icons fa fa-rss"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER END -->

<!-------------- NAVBAR MEDIA QUERIES START ------------------>
<div class="navigation-bar-container">
    <div class="container">
        <div class="nav-mobile">
            <a href="javascript:void(0)" id="nav-toggle">
                <span></span>
            </a>

            <a class="logo" href="" style="width: 120px">
                <img class="image-logo" id="image-logo-media-queries" style="transition: 0.5s; height: 60px;" src="/../assets/img/logo_novo_small.png" alt="logo">
            </a>

            <a href="" class="anchor-wen">
                <img class="wen" src="/../assets/img/lupa.gif" alt="net-empregos search" width="38" height="38">
            </a>

            <div class="collapse navbar-media-queries sub-menu-colapsar" id="navbarSupportedConten">
                <ul class="ul-nav-lista ul-nav-1">
                    <li>
                        <a style="font-weight: bold !important;color: #374151;cursor: pointer;" class="links-navegacao" href="">
                            <i class="fa fa-search"></i>
                            Pesquisar Ofertas
                        </a>
                    </li>

                    <li>
                        <a style="font-weight: bold;color: #374151;cursor: pointer;" class="links-navegacao">
                            <i class="fa fa-users"></i>
                            Candidato
                            <i class="fa fa-caret-down"></i>
                        </a>

                        <ul class="nav-dropdown">
                            <li><a class="item-dropdown" style="font-weight:bold;" href=""><i class="fa fa-address-card-o"></i> Perfil</a></li>
                            <li><a class="item-dropdown" href=><i class="fa fa-user-circle-o"></i> Registo</a></li>
                            <li><a class="item-dropdown" href=><i class="fa fa-star"></i> Ofertas Favoritas</a></li>
                        </ul>
                    </li>

                    <li>
                        <a style="font-weight: bold;color: #374151;cursor: pointer;" class="links-navegacao">
                            <i class="fa-regular fa-building"></i>
                            Empresa
                            <i class="fa fa-caret-down"></i>
                        </a>

                        <ul class="nav-dropdown">
                            <li><a class="item-dropdown" style="font-weight:bold;" href=""><i class="fa fa-plus-square"></i> Publicar Oferta Grátis</a></li>
                            <li><a class="item-dropdown" style="font-weight:bold;" href=""><i class="fa fa-list-ul"></i> Ofertas Activas</a></li>
                            <li><a class="item-dropdown" style="font-weight:bold; color:green" href=""><i class="fa fa-arrow-up"></i> Pacotes Destaques</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-folder-open-o"></i> Ofertas Arquivadas</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-graduation-cap"></i> Ofertas de Formação</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-user-circle-o"></i> Registo</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-info-circle"></i> Logo / Info + API</a></li>
                            <li><a class="item-dropdown" href=""><i class="fa fa-file-text-o"></i> Facturas</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="" style="font-weight: bold;color: #374151;cursor: pointer;" class="links-navegacao">
                            <i class="fa fa-graduation-cap"></i>
                            Formação
                        </a>
                    </li>

                    <li>
                        <a href="" style="font-weight: bold;color: #0ea5e9;cursor: pointer;" class="links-navegacao">
                            <i class="fa fa-envelope"></i>
                            Contactos
                        </a>
                    </li>
                </ul>

                <ul class="ul-nav-lista ul-nav-2">
                    <?php if (isset($_SESSION['candidato']) || isset($_SESSION['empresa'])): ?>
                        <li style="margin-bottom:1.5rem!important;">
                            <a style="font-weight: bold; color: #374151; cursor: pointer;" class="links-navegacao">
                                <i class="fa fa-user-circle-o"></i>
                                <?= isset($_SESSION['candidato']) ? htmlspecialchars($_SESSION['candidato']['nome']) :
                                                                     htmlspecialchars($_SESSION['empresa']['nome']); ?>
                            </a>

                            <ul class="nav-dropdown">
                                <li>
                                    <a class="item-dropdown" style="color: #374151; cursor: pointer;" onclick="abrirModalRegisterCandidato()">
                                        <i class="fa-regular fa-building"></i>
                                        Registo
                                    </a>
                                </li>

                                <li>
                                    <a href="/logout-candidato" class="item-dropdown" style="color: #374151; cursor: pointer;">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li>
                            <a style="font-weight: bold; color: #374151; cursor: pointer;" class="links-navegacao">
                                <i class="fa fa-user"></i>
                                Login
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="nav-dropdown">
                                <li>
                                    <a class="item-dropdown" style="color: #374151; cursor: pointer;" onclick="abrirModalLoginCandidato()">
                                        <i class="fa fa-users"></i>
                                        Candidato
                                    </a>
                                </li>

                                <li>
                                    <a class="item-dropdown" style="color: #374151; cursor: pointer;" onclick="abrirModalLoginEmpresa()">
                                        <i class="fa-regular fa-building"></i>
                                        Empresa
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-------------- NAVBAR MEDIA QUERIES START ------------------>

<!-- Modal Login Candidato -->
<div id="modalLoginCandidato" class="modal">
    <div class="modal-content">
        <!-- Botão Fechar -->
        <button type="button" class="fechar" onclick="fecharModalLoginCandidato()" data-prevent-click>&times;</button>

        <h2>Login de Candidato</h2>

        <form id="formLoginCandidato" method="POST" action="/api/login-candidato" autocomplete="off">
            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?= CsrfHelper::gerarToken(); ?>" data-prevent-click>

            <!-- Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-login-candidato" class="recaptcha-container"></div>

            <!-- Botão Login -->
            <button type="submit" class="btn btn-primary" data-prevent-click>LOGIN</button>

            <!-- Botão Reenviar Email -->
            <div class="resend-section" data-user-type="candidato" style="display: none; margin-top: 10px;">
                <button class="btn-resend-email btn btn-warning btn-sm" type="button" data-prevent-click>
                    Reenviar email de confirmação Candidato
                </button>
                <p class="resend-message" style="margin-top: 5px; font-size: 0.9em;"></p>
            </div>

            <!-- Área de Mensagem AJAX -->
            <div id="loginMensagemCandidato" style="margin-top: 10px;"></div>
        </form>

        <!-- Link de Recuperar Senha -->
        <div class="password-reset-text" style="margin-top: 10px; text-align: center;">
            <p>
                <a href="javascript:void(0);" onclick="abrirModalRecuperarSenhaCandidato();" style="color: #007bff; font-size: 0.9em;">
                    Esqueceu a sua senha?
                </a>
            </p>
        </div>

        <!-- Link para Registo -->
        <div class="register-text" style="text-align: center; margin-top: 10px;">
            <p class="register-top">Ainda não tem conta?</p>
            <p class="register-bottom">
                <a href="javascript:void(0);" onclick="abrirModalRegisterCandidato(); fecharModalLoginCandidato();" style="color: #28a745; font-weight: bold;">
                    Registe-se como Candidato
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Registo Candidato -->
<div id="modalRegisterCandidato" class="modal">
    <div class="modal-content">
        <button type="button" class="button voltar" onclick="fecharModalRegisterCandidato(); abrirModalLoginCandidato();" data-prevent-click>
            <i class="fa fa-arrow-left"></i>
        </button>

        <button type="button" class="button fechar" onclick="fecharModalRegisterCandidato()" data-prevent-click>
            <i class="fas fa-times-circle"></i>
        </button>

        <h2>Registo de Candidato</h2>

        <form id="formRegisterCandidato" method="POST" action="/api/register-candidato" >

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= CsrfHelper::gerarToken(); ?>">

            <!-- Nome -->
            <div class="form-group">
                <input type="text" name="nome" placeholder="Nome completo" required ">
            </div>

            <!-- Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
            </div>

            <!-- Confirmar Email -->
            <div class="form-group">
                <input type="email" name="email_confirm" placeholder="Confirmar Email" >
            </div>

            <!-- Password -->
            <div class="form-group">
                <input type="password" name="senha" placeholder="Password" >
                <small style="color: gray; font-size: 0.85em;">
                    A senha deve ter no mínimo 8 caracteres, incluindo: uma letra maiúscula, uma minúscula, um número e um símbolo especial.
                </small>
            </div>

            <!-- Confirmar Password -->
            <div class="form-group">
                <input type="password" name="senha_confirm" placeholder="Confirmar Password" required">
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-register-candidato" class="recaptcha-container"></div>

            <button type="submit" class="btn btn-success" data-prevent-click>REGISTAR</button>

            <!-- Local para mensagens AJAX -->
            <div id="registerMensagemCandidato"></div>
        </form>
    </div>
</div>

<!-- Modal Recuperar Senha - Candidato -->
<div id="modalRecuperarSenhaCandidato" class="modal">
    <div class="modal-content-senha">

        <!-- Botão Fechar -->
        <button type="button" class="fechar" onclick="fecharModalRecuperarSenhaCandidato()" data-prevent-click>&times;</button>

        <h2>Recuperar Senha - Candidato</h2>

        <form id="formRecuperarSenhaCandidato" method="POST" action="/api/recuperar-senha-candidato" autocomplete="off">

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CsrfHelper::gerarToken(); ?>">

            <!-- Campo Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Digite o seu e-mail" required>
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-recuperar-candidato" class="recaptcha-container"></div>

            <!-- Botão -->
            <button type="submit" class="btn btn-primary" data-prevent-click>Enviar link de recuperação</button>

            <!-- Mensagens AJAX (Sucesso ou Erro) -->
            <div id="mensagemRecuperarSenhaCandidato" style="margin-top: 10px;"></div>
        </form>
    </div>
</div>


<!-- Modal Login Empresa -->
<div id="modalLoginEmpresa" class="modal">
    <div class="modal-content">
        <!-- Botão Fechar -->
        <button type="button" class="fechar" onclick="fecharModalLoginEmpresa()" data-prevent-click>&times;</button>

        <h2>Login de Empresa</h2>

        <form id="formLoginEmpresa" method="POST" action="/api/login-empresa" autocomplete="off">
            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?= CsrfHelper::gerarToken(); ?>">

            <!-- Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-login-empresa" class="recaptcha-container"></div>

            <!-- Botão Login -->
            <button type="submit" class="btn btn-primary" data-prevent-click>LOGIN</button>

            <!-- Botão Reenviar Email - Empresa -->
            <div class="resend-section" data-user-type="empresa" style="display: none; margin-top: 10px;">
                <button class="btn-resend-email btn btn-warning btn-sm" type="button" data-prevent-click>
                    Reenviar email de confirmação Empresa
                </button>
                <p class="resend-message" style="margin-top: 5px; font-size: 0.9em;"></p>
            </div>

            <!-- Mensagem AJAX -->
            <div id="loginMensagemEmpresa" style="margin-top: 10px;"></div>
        </form>

        <!-- Link de Recuperar Senha -->
        <div class="password-reset-text" style="margin-top: 10px; text-align: center;">
            <p>
                <a href="javascript:void(0);" onclick="abrirModalRecuperarSenhaEmpresa();" style="color: blue;font-size: 0.9rem;">
                    Esqueceu a sua senha?
                </a>
            </p>
        </div>

        <!-- Link para Registo Empresa -->
        <div class="register-text" style="text-align: center; margin-top: 10px;">
            <p class="register-top">Ainda não tem conta?</p>
            <p class="register-bottom">
                <a href="javascript:void(0);" onclick="abrirModalRegisterEmpresa(); fecharModalLoginEmpresa();" style="color: #28a745; font-weight: bold;">
                    Registe-se como Empresa
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Registo Empresa -->
<div id="modalRegisterEmpresa" class="modal">
    <div class="modal-content">
        <button type="button" class="button voltar" onclick="fecharModalRegisterEmpresa(); abrirModalLoginEmpresa();" data-prevent-click>
            <i class="fa fa-arrow-left"></i>
        </button>

        <button type="button" class="button fechar" onclick="fecharModalRegisterEmpresa()" data-prevent-click>
            <i class="fas fa-times-circle"></i>
        </button>

        <h2>Registo de Empresa</h2>

        <form id="formRegisterEmpresa" method="POST" action="/api/register-empresa">

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= CsrfHelper::gerarToken(); ?>">

            <!-- Nome -->
            <div class="form-group">
                <input type="text" name="nome" placeholder="Nome da Empresa">
            </div>

            <!-- Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
            </div>

            <!-- Confirmar Email -->
            <div class="form-group">
                <input type="email" name="email_confirm" placeholder="Confirmar Email">
            </div>

            <!-- Password -->
            <div class="form-group">
                <input type="password" name="senha" placeholder="Password" required autocomplete="new-password">
                <small style="color: gray; font-size: 0.85em;">
                    A senha deve ter no mínimo 8 caracteres, incluindo: uma letra maiúscula, uma minúscula, um número e um símbolo especial.
                </small>
            </div>

            <!-- Confirmar Password -->
            <div class="form-group">
                <input type="password" name="senha_confirm" placeholder="Confirmar Password">
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-register-empresa" class="recaptcha-container"></div>

            <button type="submit" class="btn btn-success" data-prevent-click>REGISTAR</button>

            <!-- Mensagem AJAX -->
            <div id="registerMensagemEmpresa"></div>
        </form>
    </div>
</div>

<!-- Modal Recuperar Senha Empresa -->
<div id="modalRecuperarSenhaEmpresa" class="modal">
    <div class="modal-content-senha">
        <button type="button" class="fechar" onclick="fecharModalRecuperarSenhaEmpresa()" data-prevent-click>&times;</button>
        <h2>Recuperar Senha - Empresa</h2>

        <form id="formRecuperarSenhaEmpresa" method="POST" action="/api/recuperar-senha-empresa">

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CsrfHelper::gerarToken(); ?>">

            <div class="form-group">
                <input type="email" name="email" placeholder="E-mail da empresa" required>
            </div>

            <!-- reCAPTCHA -->
            <div id="recaptcha-recuperar-empresa" class="recaptcha-container"></div>

            <button type="submit" class="btn btn-primary" data-prevent-click>ENVIAR LINK DE RECUPERAÇÃO</button>

            <div id="mensagemRecuperarSenhaEmpresa"></div>
        </form>
    </div>
</div>


<!-- JS -->
<script src="/assets/js/jquery-2.2.0.min.js"></script>
<script src="/assets/js/jquery.easy-autocomplete.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap-submenu.js"></script>
<script src="/assets/js/bootstrap-select.min.js"></script>
<script src="/assets/js/jquery.easing.1.3.js"></script>
<script src="/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/assets/js/jquery.countdown.js"></script>
<script src="/assets/js/slick.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<!-- CSS -->
<link rel="stylesheet" href="/assets/css/easy-autocomplete.css">
<link rel="stylesheet" href="/assets/css/easy-autocomplete.themes.css">
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css">

<script>

    // Mapa para armazenar os widgets de cada formulário
    var recaptchaWidgets = {};

    var onloadCallback = function() {
        recaptchaWidgets['formLoginCandidato'] = grecaptcha.render('recaptcha-login-candidato', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });
        recaptchaWidgets['formRegisterCandidato'] = grecaptcha.render('recaptcha-register-candidato', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });
        recaptchaWidgets['formRecuperarSenhaCandidato'] = grecaptcha.render('recaptcha-recuperar-candidato', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });

        recaptchaWidgets['formLoginEmpresa'] = grecaptcha.render('recaptcha-login-empresa', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });
        recaptchaWidgets['formRegisterEmpresa'] = grecaptcha.render('recaptcha-register-empresa', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });
        recaptchaWidgets['formRecuperarSenhaEmpresa'] = grecaptcha.render('recaptcha-recuperar-empresa', { 'sitekey' : '<?= $_ENV['NOCAPTCHA_SITEKEY']; ?>' });
    };


    function refreshRecaptcha() {
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.reset();
        }
    }

    function ajaxFormWithRecaptcha(formId, endpoint, mensagemDivId, onSuccess = null) {
        const form = document.getElementById(formId);
        const mensagemDiv = document.getElementById(mensagemDivId);

        const waitForRecaptcha = setInterval(() => {
            if (typeof grecaptcha !== 'undefined') {
                clearInterval(waitForRecaptcha);

                let redirecionando = false;

                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'A processar...';
                    }

                    const formData = new FormData(this);

                    const recaptchaResponse = grecaptcha.getResponse(recaptchaWidgets[formId]);

                    if (!recaptchaResponse) {
                        mostrarMensagemComFade(mensagemDiv, 'Por favor, complete o reCAPTCHA.', 'red');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = 'Submeter';
                        }
                        return;
                    }

                    formData.append('g-recaptcha-response', recaptchaResponse);

                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            body: formData
                        });

                        const textResponse = await response.text();
                        let data;

                        try {
                            data = JSON.parse(textResponse);
                        } catch (jsonError) {
                            console.error('Resposta não é JSON válido:', textResponse);
                            mostrarMensagemComFade(mensagemDiv, 'Resposta inválida do servidor.', 'red');
                            grecaptcha.reset(recaptchaWidgets[formId]);
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerText = 'Submeter';
                            }
                            return;
                        }

                        if (data.success && data.redirect) {
                            redirecionando = true;
                            mensagemDiv.innerHTML = '';
                            grecaptcha.reset(recaptchaWidgets[formId]);
                            window.location.href = data.redirect;
                            return;
                        }

                        const mensagem = data.message || 'Erro inesperado.';
                        const cor = data.success ? 'green' : 'red';

                        const ignorarMensagens = [
                            'aguarde alguns segundos',
                            'confirme o recaptcha',
                            'por favor, complete o recaptcha'
                        ];

                        const deveOcultarMensagem =
                            formId.includes('RecuperarSenha') ||
                            formId.includes('RegisterCandidato') ||
                            formId.includes('RegisterEmpresa');

                        const deveMostrarMensagem = !ignorarMensagens.some(fragmento =>
                            mensagem.toLowerCase().includes(fragmento)
                        );

                        if (!deveOcultarMensagem || deveMostrarMensagem) {
                            mostrarMensagemComFade(mensagemDiv, mensagem, cor);
                        }

                        if (data.success) {
                            if (onSuccess) onSuccess(data);
                            form.reset();
                        }

                        grecaptcha.reset(recaptchaWidgets[formId]);

                    } catch (error) {
                        console.error('Erro no fetch:', error);
                        if (!redirecionando) {
                            mostrarMensagemComFade(mensagemDiv, 'Erro ao comunicar com o servidor.', 'red');
                        }
                        grecaptcha.reset(recaptchaWidgets[formId]);

                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = 'Submeter';
                        }
                    }
                });
            }
        }, 300);
    }

    function mostrarMensagemComFade(container, texto, cor = 'red') {
        const span = document.createElement('span');
        span.className = 'msg-auto-hide';
        span.textContent = texto;
        span.style.color = cor;
        span.style.borderColor = cor;
        span.style.opacity = '0';
        span.style.transition = 'opacity 0.4s ease';
        span.style.backgroundColor = cor === 'green'
            ? 'rgba(0, 128, 0, 0.08)'
            : 'rgba(255, 0, 0, 0.08)';
        span.style.padding = '8px';
        span.style.display = 'inline-block';
        span.style.marginTop = '10px';
        span.style.borderRadius = '5px';
        span.style.textAlign = 'center';
        span.style.width = '100%';

        container.innerHTML = '';
        container.appendChild(span);

        requestAnimationFrame(() => {
            span.style.opacity = '1';

            setTimeout(() => {
                span.style.opacity = '0';

                span.addEventListener('transitionend', () => {
                    container.innerHTML = '';
                }, { once: true });

            }, 4000);
        });
    }

    function iniciarCooldown(botao, textoOriginal, segundos) {
        let tempoRestante = segundos;

        botao.disabled = true;
        botao.innerText = `Reenviar (${tempoRestante}s)`;

        const intervalo = setInterval(() => {
            tempoRestante--;
            botao.innerText = `Reenviar (${tempoRestante}s)`;

            if (tempoRestante <= 0) {
                clearInterval(intervalo);
                botao.disabled = false;
                botao.innerText = textoOriginal;
            }
        }, 1000);
    }

    // Reenviar email ( Empresa/Candidato )
    document.addEventListener('DOMContentLoaded', function () {
        // ================================
        // Ativar AJAX com reCAPTCHA
        // ================================
        ajaxFormWithRecaptcha(
            'formRecuperarSenhaCandidato',
            '/api/recuperar-senha-candidato',
            'mensagemRecuperarSenhaCandidato'
        );

        ajaxFormWithRecaptcha(
            'formRegisterCandidato',
            '/api/register-candidato',
            'registerMensagemCandidato'
        );

        ajaxFormWithRecaptcha(
            'formRegisterEmpresa',
            '/api/register-empresa',
            'registerMensagemEmpresa'
        );
    });

    function inicializarBotaoReenviarEmail(sectionSelector) {
        const section = document.querySelector(sectionSelector);
        if (!section) return;

        const resendBtn = section.querySelector('.btn-resend-email');
        const resendMessage = section.querySelector('.resend-message');
        const userType = section.dataset.userType || 'candidato';

        const verificarURL = userType === 'empresa'
            ? '/auth/verificar-email-sessao-empresa'
            : '/auth/verificar-email-sessao-candidato';

        const reenviarURL = userType === 'empresa'
            ? '/auth/reenviar-email-sessao-empresa'
            : '/auth/reenviar-email-sessao-candidato';

        fetch(verificarURL, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                section.style.display = 'block';
                if (data.email_confirmado === true || data.showButton === false) {
                    resendBtn.style.display = 'none';
                } else {
                    resendBtn.style.display = 'inline-block';
                }

                if (data.message) {
                    mostrarMensagemComFade(resendMessage, data.message, 'red');
                }
            })
            .catch(() => {
                section.style.display = 'none';
            });

        resendBtn.addEventListener('click', function () {
            const originalText = resendBtn.innerText;
            resendBtn.disabled = true;
            resendBtn.innerText = 'A processar...';
            resendMessage.innerHTML = '';

            fetch(reenviarURL, { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    const cor = data.success ? 'green' : 'red';
                    mostrarMensagemComFade(resendMessage, data.message || 'Erro ao reenviar.', cor);

                    if (data.email_confirmado === true) {
                        resendBtn.style.display = 'none';
                        section.style.display = 'none'; // ✅ Aqui está a correção!
                    } else {
                        iniciarCooldown(resendBtn, originalText, 60);
                    }
                })
                .catch(() => {
                    mostrarMensagemComFade(resendMessage, 'Erro ao reenviar email.', 'red');
                    resendBtn.disabled = false;
                    resendBtn.innerText = originalText;
                });
        }, { once: true });
    }

    function abrirModalLoginEmpresa() {
        abrirModal('modalLoginEmpresa'); // ✅ Usa a função genérica que fecha os outros modais
        inicializarBotaoReenviarEmail('#modalLoginEmpresa .resend-section');
    }

    function abrirModalLoginCandidato() {
        abrirModal('modalLoginCandidato'); // ✅ Usa a função genérica que fecha os outros modais
        inicializarBotaoReenviarEmail('#modalLoginCandidato .resend-section');
    }

    function atualizarCsrfToken(formSelector) {
        fetch('/api/csrf-token')
            .then(res => res.json())
            .then(data => {
                if (data.token) {
                    const form = document.querySelector(formSelector);
                    if (form) {
                        const input = form.querySelector('input[name="csrf_token"]');
                        if (input) input.value = data.token;
                    }
                }
            });
    }





    // -----------------------------
    // Inicialização dos Formulários
    // -----------------------------

    // Login Candidato
    ajaxFormWithRecaptcha('formLoginCandidato', '/api/login-candidato', 'loginMensagemCandidato', (data) => {
        if (data.redirect) window.location.href = data.redirect;
    });

    // Register Candidato
    ajaxFormWithRecaptcha('formRegisterCandidato', '/api/register-candidato', 'registerMensagemCandidato', (data) => {
        if (data.redirect) {
            fecharModalRegisterCandidato();  // <-- Nome correto da tua função JS de fechar o modal de candidato
            window.location.href = data.redirect;
        }
    });

    // Recuperar Senha Candidato
    ajaxFormWithRecaptcha('formRecuperarSenhaCandidato', '/api/recuperar-senha-candidato', 'mensagemRecuperarSenhaCandidato');


    // Login Empresa
    ajaxFormWithRecaptcha('formLoginEmpresa', '/api/login-empresa', 'loginMensagemEmpresa', (data) => {
        if (data.redirect) {
            fecharModalLoginEmpresa();
            window.location.href = data.redirect;
        }
    });

    // Register Empresa
    ajaxFormWithRecaptcha('formRegisterEmpresa', '/api/register-empresa', 'registerMensagemEmpresa', (data) => {
        if (data.redirect) {
            fecharModalRegisterEmpresa();
            window.location.href = data.redirect;
        }
    });

    // Recuperar Senha Empresa
    ajaxFormWithRecaptcha('formRecuperarSenhaEmpresa', '/api/recuperar-senha-empresa', 'mensagemRecuperarSenhaEmpresa');

</script>

<!-- SCROLL START -->
<a id="page_scroller" href="javascript:void(0)" style="display: none; position: fixed; z-index: 2147483647;"><i class="fa fa-chevron-up"></i></a>
<!-- SCROLL END -->

</body>
</html>