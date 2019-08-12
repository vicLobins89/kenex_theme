/*
 * Scripts File
 * Author: Vic Lobins
 *
*/

/*
 * Get Viewport Dimensions
*/
function updateViewportDimensions() {
	"use strict";
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();

jQuery(document).ready(function($) {
	
	"use strict";
	
	viewport = updateViewportDimensions();
	
	function limitText(limitField, limitNum) {
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		}
	}
    
    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('s');
    $('input[type="search"]').click(function(){
        $(this).val(myParam);
    });
	
    // Qty fix
	$('.qty').on('keyup keydown change blur', function(){
		limitText(this, 2);
	});
    
    $('.variations select').on('change', function(){
        $('.quantity input').val(1);
    });
	
    // Menu
	$('.menu-button').on('click', function(){
		$(this).parents('.header').toggleClass('active');
	});
	
	$('#menu-main-menu > .menu-item-has-children > a').on('click', function(e){
		e.preventDefault();
		$('#menu-main-menu > .menu-item-has-children > a').not( $(this) ).removeClass('active');
		$(this).toggleClass('active');
		$('.sub-menu').not( $(this).next('.sub-menu') ).removeClass('active');
		$(this).next('.sub-menu').toggleClass('active');
	});
	
	// Slick
	$('.multiple-objects:not(.slider-nav):not(.slider-for)').slick({
		dots: true,
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 4
	});
    
    var countThumbs = $('.gallery-thumbs .woocommerce-product-gallery__image:not(.slick-cloned)').length;
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.slider-nav'
    });
    if( countThumbs < 4 ) {
        $('.slider-nav').slick({
            slidesToShow: countThumbs,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            focusOnSelect: true,
        });
        $('.gallery-thumbs').addClass('no-scroll');
    } else {
        $('.slider-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            dots: true,
            centerMode: true,
            focusOnSelect: true,
        });
    }
    
    $('.main-gallery div[data-video]').each(function(){
        var videoLink = $(this).data('video');
        videoLink = videoLink.replace('https://vimeo.com/', 'https://player.vimeo.com/video/');
        var video ='<iframe src="'+videoLink+'?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
        
        $(this).find('a').attr('href', videoLink);
    });
    
    $('.main-gallery a').each(function(){
        var caption = $(this).find('img').attr('title');
        $(this).append('<span class="caption">'+caption+'</span>');
    });
    
    $('.main-gallery').slickLightbox({ 
        itemSelector: 'a' 
    });
    
    $('.gallery-thumbs a').click(function(e){
        e.preventDefault();
    });
});