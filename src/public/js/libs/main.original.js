'use strict';

$("#open_menu").click(function () {
    $('#menu__side').css('display', 'block');
    $('#overlay').fadeIn('fast');
    $('#side__nav').addClass('animated slideInLeft').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass('animated slideInLeft');
    });
});

$("#overlay").click(function () {
    $('#overlay').fadeOut('slow');
    $('#side__nav').addClass('animated slideOutLeft').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass('animated slideOutLeft');
        $('#menu__side').css('display', 'none');
    });
});

$('.footer__heading').click(function () {
    $(this).parent().find('.toggle').slideToggle(200);
});

$('li.dropdown > a').click(function (e) {
    e.preventDefault();
    $(this).parent().find('ul').toggle('slow');
});

$(document).ready(function () {
    $('.slider').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 3000,
    });
    $('.featured__items').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        responsive: [
            {
                breakpoint: 900,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 720,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });
});

let search = false;
$('.open__search_modal').click(function () {
    let offset = $('.pre__header').outerHeight();
    let form = $('#search__form');
    let right = $(window).width() - $(this).offset().left;
    form.css('right', right + 'px');
    //form.css('top', offset + 'px');
    if (search == true) {
        form.addClass('animated fadeOutRight');
        form.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('animated fadeOutRight');
            $(this).css('display', 'none');
        });
    } else {
        form.css('display', 'flex');
        form.addClass('animated fadeInRight');
        form.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this).removeClass('animated fadeInRight');
        });
    }
    search = !search;
});

$("#open_filters__btn").click(function () {
    $('#overlay_filters').fadeIn('slow');
    $('#filters__container').css('display', 'block').addClass('animated slideInRight').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass('animated slideInRight');
    });
});

$("#overlay_filters").click(function () {
    $('#overlay_filters').fadeOut('slow');
    $('#filters__container').addClass('animated slideOutRight').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass('animated slideOutRight').css('display', 'none');
    });
});

var actions = null;
$('#open__actions').click(function () {
    actions = $(this).parent().find('.actions');
    actions.show();
});


$(document).mouseup(function (e) {
    if (actions !== null && actions.has(e.target).length === 0) {
        actions.hide();
    }
});

var pics = [];
$('#fullscreen').click(function () {
    let src = $('#full__img').attr('src');
    let thumbnails = $('.thumbnail');
    let activeID = null;
    thumbnails.each(function (k, val) {
        pics[k] = $(this).data('full');
        if (pics[k] == src) {
            activeID = k;
        }
    });
    let last = pics.length - 1;
    if (activeID == 0) {
        $('#prev').hide();
        if (pics.length == 1) {
            $('#next').hide();
        } else {
            $('#next').show();
        }
    } else if (activeID == last) {
        $('#next').hide();
        $('#prev').show();
    } else {
        $('#next').show();
        $('#prev').show();
    }

    $('#fullscreen__img').attr('src', src).data('array', pics).data('current', activeID);
    $('#fullscreen_img__popup').fadeIn('fast');
});
$('#close').click(function () {
    pics = [];
    $('#fullscreen_img__popup').fadeOut('fast');
});
//fullscreen imgs carousel
$('.arrow').click(function () {
    let IMG = $('#fullscreen__img');
    let array = IMG.data('array');
    let current = IMG.data('current');
    if ($(this).attr('id') == 'prev') {
        current--;
        IMG.attr('src', array[current]).data('current', current);
    } else if ($(this).attr('id') == 'next') {
        current++;
        IMG.attr('src', array[current]).data('current', current);
    }
    let last = array.length - 1;
    if (current == 0) {
        $('#prev').hide();
        $('#next').show();
    } else if (current == last) {
        $('#next').hide();
        $('#prev').show();
    } else {
        $('#next').show();
        $('#prev').show();
    }
});