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
	$('.multiple-objects').slick({
		dots: true,
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 3
	});
	
	$('.woocommerce-product-gallery__wrapper a').click(function(e){
		e.preventDefault();
	});
});