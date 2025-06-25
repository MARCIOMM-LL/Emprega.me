/*------------------------------- MODALS LOGIN E REGISTER "START SECTION" -------------------------------*/
// Função genérica para abrir qualquer modal
function abrirModal(idModal) {
    fecharTodosOsModais();
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.style.display = 'block';
    }
}

// Função genérica para fechar qualquer modal
function fecharModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Função para fechar todos os modais abertos
function fecharTodosOsModais() {
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.style.display = 'none';
    });
}


// Funções abrir e fechar (Modais Candidato)
function abrirModalLoginCandidato() {
    abrirModal('modalLoginCandidato');
}

function fecharModalLoginCandidato() {
    fecharModal('modalLoginCandidato');
}

function abrirModalRegisterCandidato() {
    abrirModal('modalRegisterCandidato');
}

function fecharModalRegisterCandidato() {
    fecharModal('modalRegisterCandidato');
}

function abrirModalRecuperarSenhaCandidato() {
    document.getElementById('modalRecuperarSenhaCandidato').style.display = 'block';
}

function fecharModalRecuperarSenhaCandidato() {
    document.getElementById('modalRecuperarSenhaCandidato').style.display = 'none';
}


// Funções abrir e fechar (Modais Empresa)
function abrirModalLoginEmpresa() {
    abrirModal('modalLoginEmpresa');
}

function fecharModalLoginEmpresa() {
    fecharModal('modalLoginEmpresa');
}

function abrirModalRegisterEmpresa() {
    fecharModalLoginEmpresa();
    abrirModal('modalRegisterEmpresa');
}

function fecharModalRegisterEmpresa() {
    fecharModal('modalRegisterEmpresa');
}

function abrirModalRecuperarSenhaEmpresa() {
    const modal = document.getElementById('modalRecuperarSenhaEmpresa');
    if (modal) {
        modal.style.display = 'block';
    }
}

function fecharModalRecuperarSenhaEmpresa() {
    const modal = document.getElementById('modalRecuperarSenhaEmpresa');
    if (modal) {
        modal.style.display = 'none';
    }
}


// Fechar ao clicar fora de qualquer modal
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        fecharTodosOsModais();
    }
}
/*------------------------------- MODALS LOGIN E REGISTER "END SECTION" -------------------------------*/

/*------------------------------- SEARCH FIELDS CITY AND PROFESSIONS "START SECTION" -------------------------------*/
function configurarAutocomplete(idCampo, tabela_database) {
    let options = {
        url: function(phrase) {
            return "/api/autocomplete/pesquisar?tabela_database=" +
                encodeURIComponent(tabela_database) +
                "&q=" + encodeURIComponent(phrase);
        },
        getValue: "nome",
        theme: "blue-light",
        requestDelay: 300,
        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: false
            }
        },
        adjustWidth: false
    };

    $(idCampo).easyAutocomplete(options);
}

configurarAutocomplete("#profissao", "profissoes");
configurarAutocomplete("#cidade", "cidades");
/*------------------------------- SEARCH FIELDS CITY AND PROFESSIONS "END SECTION" -------------------------------*/

/*------------------------------- SCROLL PAGE "START SECTION" -------------------------------*/
$(document).on('ready',function(){
    $(window).on('scroll',function () {
        if ($(this).scrollTop() > 200) {
            $('#page_scroller').fadeIn();
        } else {
            $('#page_scroller').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#page_scroller').on('click',function () {
        $('body,html').animate({
            scrollTop: 0
        },
            500);
        return false;
    });
});
/*------------------------------- SCROLL PAGE "END SECTION" -------------------------------*/

/*------------------------------- SHRINK NAVBAR "START SECTION" -------------------------------*/
// // When the user scrolls down 50px from the top of the document, resize the header's font size
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
        document.getElementById("image-logo").style.height = "40px";
        document.getElementById("image-logo-media-queries").style.height = "40px";
        document.getElementById("navbarSupportedContent").style.height = "50px";
        document.getElementById("navbarSupportedConten").style.height = "50px";
    } else {
        document.getElementById("image-logo").style.height = "60px";
        document.getElementById("image-logo-media-queries").style.height = "60px";
        document.getElementById("navbarSupportedContent").style.height = "80px";
        document.getElementById("navbarSupportedConten").style.height = "80px";
    }
}
/*------------------------------- SHRINK NAVBAR "END SECTION" -------------------------------*/

/*------------------------------- CAROUSEL NAME OF WORKS "START SECTION" -------------------------------*/
$(document).on('ready',function(){
    $('.destaques').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: false,
        cssEase: 'linear',
        rows:2,
        waitForAnimate: false,
    });

    $('.destaques').show();
});
/*------------------------------- CAROUSEL NAME OF WORKS "END SECTION" -------------------------------*/

/*------------------------------- CAROUSEL OF WORKERS "START SECTION" -------------------------------*/
let posicao = 0;
let velocidade = 0.8; // Podes ajustar a velocidade aqui (quanto maior, mais rápido)

function animarCarrosel() {
    posicao -= velocidade;
    document.getElementById('carrosel').style.backgroundPositionX = posicao + "px";
    requestAnimationFrame(animarCarrosel);
}

requestAnimationFrame(animarCarrosel);
/*------------------------------- CAROUSEL OF WORKERS "END SECTION" -------------------------------*/

/*------------------------------- COUNTER OF PEOPLE "START SECTION" -------------------------------*/
function isCounterElementVisible($elementToBeChecked) {
    var TopView = $(window).scrollTop();
    var BotView = TopView + $(window).height();
    var TopElement = $elementToBeChecked.offset().top;
    var BotElement = TopElement + $elementToBeChecked.height();
    return ((BotElement <= BotView) && (TopElement >= TopView));
}

function CounterAnimation() {
    $(".counter").each(function () {
        var isOnView = isCounterElementVisible($(this));
        if (isOnView && !$(this).hasClass('Starting')) {
            $(this).addClass('Starting');
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text().replace(/,/g, "")
            }, {
                duration: 2000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now).toLocaleString('en'));
                }
            });
        }
    });
}

$(window).on('mouseover', function () {
    CounterAnimation();
});

$(window).on('scroll', function () {
    CounterAnimation();
});
/*------------------------------- COUNTER OF PEOPLE "END SECTION" -------------------------------*/

/*------------------------------- MEDIA QUERIES NAVBAR "START SECTION"-------------------------------*/
(function($){
    $(function(){
        $('.navbar-media-queries ul li a').on( "click",function(e){
            $('.nav-dropdown').toggle().not($(this).siblings()).hide();
            e.stopPropagation();
        });

        $('html').on( "click",function(){
            $('.nav-dropdown').hide();
        });

        $('#nav-toggle').on( "click",function(e){
            e.preventDefault()
            if(isDoubleClicked($(this))) return;

            $('.navbar-media-queries').css('height', 'unset');
            $(".navbar-media-queries").toggle();
            this.classList.toggle('active');
        });
    });
})(jQuery);

function isDoubleClicked(element) {
    //if already clicked return TRUE to indicate this click is not allowed
    if (element.data("isclicked")) return true;
    element.data("isclicked", true);

    // console.log(element.data("isclicked"));

    //mark as clicked for half second
    setTimeout(function () {
        element.removeData("isclicked");
    }, 500);

    //return FALSE to indicate this click was allowed
    return false;
}
/*------------------------------- MEDIA QUERIES NAVBAR "END SECTION" -------------------------------*/

/*------------------------------- REQUEST AJAX OF REGISTER AND LOGIN "START SECTION" -------------------------------*/



/*------------------------------- REQUEST AJAX OF REGISTER AND LOGIN "END SECTION" -------------------------------*/