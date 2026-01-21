/*===================================
Author       : KlbTheme.
Template Name: Shopwise - eCommerce WordPress Theme
Version      : 1.0
===================================*/

/*===================================*
PAGE JS
*===================================*/
jQuery.noConflict();
(function($) {
	'use strict';
	
	/*===================================*
	01. LOADING JS
	/*===================================*/
	$(window).on('load', function() {
		setTimeout(function () {
			$(".preloader").delay(100).fadeOut(100).addClass('loaded');
		}, 100);
	});

	/*===================================*
	02. Background BG
	/*===================================*/
	$(".background_bg").each(function() {
		var attr = $(this).attr('data-img-src');
		if (typeof attr !== typeof undefined && attr !== false) {
			$(this).css('background-image', 'url(' + attr + ')');
		}
	});

	$('[data-toggle="offcanvas"]').on('click', function () {
		$('body').toggleClass('toggled');
	});  

	/*===================================*
	03. ANIMATION JS
	*===================================*/
	$(function() {
	
		function ckScrollInit(items, trigger) {
			items.each(function() {
				var ckElement = $(this),
					AnimationClass = ckElement.attr('data-animation'),
					AnimationDelay = ckElement.attr('data-animation-delay');
	
				ckElement.css({
					'-webkit-animation-delay': AnimationDelay,
					'-moz-animation-delay': AnimationDelay,
					'animation-delay': AnimationDelay,
					opacity: 0
				});
	
				var ckTrigger = (trigger) ? trigger : ckElement;
	
				ckTrigger.waypoint(function() {
					ckElement.addClass("animated").css("opacity", "1");
					ckElement.addClass('animated').addClass(AnimationClass);
				}, {
					triggerOnce: true,
					offset: '90%',
				});
			});
		}
	
		ckScrollInit($('.animation'));
		ckScrollInit($('.staggered-animation'), $('.staggered-animation-wrap'));
	
	});
	
	/*===================================*
	04. MENU JS
	*===================================*/
	//Main navigation scroll spy for shadow
	$(window).on('scroll', function() {
		var scroll = $(window).scrollTop();

	    if (scroll >= 150) {
	        $('header.fixed-top').addClass('nav-fixed');
	    } else {
	        $('header.fixed-top').removeClass('nav-fixed');
	    }

	});
	
	//Show Hide dropdown-menu Main navigation 
	$( document ).on('ready', function () {
		$( '.dropdown-menu a.dropdown-toggler' ).on( 'click', function () {
			//var $el = $( this );
			//var $parent = $( this ).offsetParent( ".dropdown-menu" );
			if ( !$( this ).next().hasClass( 'show' ) ) {
				$( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
			}
			var $subMenu = $( this ).next( ".dropdown-menu" );
			$subMenu.toggleClass( 'show' );
			
			$( this ).parent( "li" ).toggleClass( 'show' );
	
			$( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function () {
				$( '.dropdown-menu .show' ).removeClass( "show" );
			} );
			
			return false;
		});
	});
	
	//Hide Navbar Dropdown After Click On Links
	var navBar = $(".header_wrap");
	var navbarLinks = navBar.find(".navbar-collapse ul li a.page-scroll");

    $.each( navbarLinks, function() {

      var navbarLink = $(this);

        navbarLink.on('click', function () {
          navBar.find(".navbar-collapse").collapse('hide');
		  $("header").removeClass("active");
        });

    });
	
	//Main navigation Active Class Add Remove
	$('.navbar-toggler').on('click', function() {
		$("header").toggleClass("active");
		if($('.search-overlay').hasClass('open'))
		{
			$(".search-overlay").removeClass('open');
			$(".search_trigger").removeClass('open');
		}
	});
	
	$( document ).on('ready', function() {
		if ($('.header_wrap').hasClass("fixed-top") && !$('.header_wrap').hasClass("transparent_header") && !$('.header_wrap').hasClass("no-sticky")) {
			$(".header_wrap").before('<div class="header_sticky_bar d-none"></div>');
		}
	});
	
	$(window).on('scroll', function() {
		var scroll = $(window).scrollTop();

	    if (scroll >= 150) {
	        $('.header_sticky_bar').removeClass('d-none');
			$('header.no-sticky').removeClass('nav-fixed');
			
	    } else {
	        $('.header_sticky_bar').addClass('d-none');
	    }

	});
	
	var setHeight = function() {
		var height_header = $(".header_wrap").height();
		$('.header_sticky_bar').css({'height':height_header});
	};
	
	$(window).on('load', function() {
	  setHeight();
	});
	
	$(window).on('resize', function() {
	  setHeight();
	});
	
	$('.sidetoggle').on('click', function () {
		$(this).addClass('open');
		$('body').addClass('sidetoggle_active');
		$('.sidebar_menu').addClass('active');
		$("body").append('<div id="header-overlay" class="header-overlay"></div>');
	});
	
	$(document).on('click', '#header-overlay, .sidemenu_close',function() {
		$('.sidetoggle').removeClass('open');
		$('body').removeClass('sidetoggle_active');
		$('.sidebar_menu').removeClass('active');
		$('#header-overlay').fadeOut('3000',function(){
			$('#header-overlay').remove();
		});  
		 return false;
	});
	
	$(".categories_btn").on('click', function() {
		$('.side_navbar_toggler').attr('aria-expanded', 'false');
		$('#navbarSidetoggle').removeClass('show');
	});
	
	$(".side_navbar_toggler").on('click', function() {
		$('.categories_btn').attr('aria-expanded', 'false');
		$('#navCatContent').removeClass('show');
	});
	
	$(".pr_search_trigger").on('click', function() {
		$(this).toggleClass('show');
		$('.product_search_form').toggleClass('show');
	});
	
	var rclass = true;
	
	$("html").on('click', function () {
		if (rclass) {
			$('.categories_btn').addClass('collapsed');
			$('.categories_btn,.side_navbar_toggler').attr('aria-expanded', 'false');
			$('#navCatContent,#navbarSidetoggle').removeClass('show');
		}
		rclass = true;
	});
	
	$(".categories_btn,#navCatContent,#navbarSidetoggle .navbar-nav,.side_navbar_toggler").on('click', function() {
		rclass = false;
	});
	
	/*===================================*
	05. SMOOTH SCROLLING JS
	*===================================*/
	// Select all links with hashes
	
	var topheaderHeight = $(".top-header").innerHeight();
	var mainheaderHeight = $(".header_wrap").innerHeight();
	var headerHeight = mainheaderHeight - topheaderHeight - 20;
    $('a.page-scroll[href*="#"]:not([href="#"])').on('click', function() {
		$('a.page-scroll.active').removeClass('active');
		$(this).closest('.page-scroll').addClass('active');
        // On-page links
        if ( location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname ) {
          // Figure out element to scroll to
          var target = $(this.hash),
              speed= $(this).data("speed") || 800;
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

          // Does a scroll target exist?
          if (target.length) {
            // Only prevent default if animation is actually gonna happen
            event.preventDefault();
            $('html, body').animate({
              scrollTop: target.offset().top - headerHeight
            }, speed);
          }
        }
    });
	$(window).on('scroll', function(){
		var lastId,
			// All list items
			menuItems = $(".header_wrap").find("a.page-scroll"),
			topMenuHeight = $(".header_wrap").innerHeight() + 20,
			// Anchors corresponding to menu items
			scrollItems = menuItems.map(function(){
			  var items = $($(this).attr("href"));
			  if (items.length) { return items; }
			});
		var fromTop = $(this).scrollTop()+topMenuHeight;
	   
	   // Get id of current scroll item
		var cur = scrollItems.map(function(){
		 if ($(this).offset().top < fromTop)
		   return this;
	   });
	   // Get the id of the current element
	   cur = cur[cur.length-1];
	   var id = cur && cur.length ? cur[0].id : "";
	   
	   if (lastId !== id) {
		   lastId = id;
		   // Set/remove active class
		   menuItems.closest('.page-scroll').removeClass("active").end().filter("[href='#"+id+"']").closest('.page-scroll').addClass("active");
	   }  
		
	});
	
	$('.more_slide_open').slideUp();	
    $('.more_categories').on('click', function (){
		$(this).toggleClass('show');
		$('.more_slide_open').slideToggle();
    });
	
	/*===================================*
	06. SEARCH JS
	*===================================*/
    
	$(".close-search").on("click", function() {
		$(".search_wrap,.search_overlay").removeClass('open');
		$("body").removeClass('search_open');
	});
	
	var removeClass = true;
	$(".search_wrap").after('<div class="search_overlay"></div>');
	$(".search_trigger").on('click', function () {
		$(".search_wrap,.search_overlay").toggleClass('open');
		$("body").toggleClass('search_open');
		removeClass = false;
		if($('.navbar-collapse').hasClass('show'))
		{
			$(".navbar-collapse").removeClass('show');
			$(".navbar-toggler").addClass('collapsed');
			$(".navbar-toggler").attr("aria-expanded", false);
		}
	});
	$(".search_wrap form").on('click', function() {
		removeClass = false;
	});
	$("html").on('click', function () {
		if (removeClass) {
			$("body").removeClass('open');
			$(".search_wrap,.search_overlay").removeClass('open');
			$("body").removeClass('search_open');
		}
		removeClass = true;
	});
	
	/*===================================*
	07. SCROLLUP JS
	*===================================*/
	$(window).on('scroll', function() {
		if ($(this).scrollTop() > 150) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});
	
	$(".scrollup").on('click', function (e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, 600);
		return false;
	});
	
	/*===================================*
	08. Hover Parallax Js
	*===================================*/
	if ($(".scene").length > 0){
		var sceneElements = document.querySelectorAll('.scene');
		var parallaxScenes = [];
		for (var i = 0; i < sceneElements.length; i++) {
		  parallaxScenes.push(new Parallax(sceneElements[i]));
		}
		var scene = $(".scene")[0];
		var parallax = new Parallax(scene, {
			scalarX: 5,
			scalarY: 5
		});
	}
	
	/*===================================*
	09. PARALLAX JS
	*===================================*/
	$(window).on('load', function() {
        $('.parallax_bg').parallaxBackground();
	});
	
	/*===================================*
	11. MASONRY JS
	*===================================*/
	$( window ).on( "load", function() {
		var $grid_selectors  = $(".grid_container");
		var filter_selectors = ".grid_filter > li > a";
		if( $grid_selectors.length > 0 ) {
			$grid_selectors.imagesLoaded(function(){
				if ($grid_selectors.hasClass("masonry")){
					$grid_selectors.isotope({
						itemSelector: '.grid_item',
						percentPosition: true,
						layoutMode: "masonry",
						masonry: {
							columnWidth: '.grid-sizer'
						},
					});
				} 
				else {
					$grid_selectors.isotope({
						itemSelector: '.grid_item',
						percentPosition: true,
						layoutMode: "fitRows",
					});
				}
			});
		}
	
		//isotope filter
		$(document).on( "click", filter_selectors, function() {
			$(filter_selectors).removeClass("current");
			$(this).addClass("current");
			var dfselector = $(this).data('filter');
			if ($grid_selectors.hasClass("masonry")){
				$grid_selectors.isotope({
					itemSelector: '.grid_item',
					layoutMode: "masonry",
					masonry: {
						columnWidth: '.grid_item'
					},
					filter: dfselector
				});
			} 
			else {
				$grid_selectors.isotope({
					itemSelector: '.grid_item',
					layoutMode: "fitRows",
					filter: dfselector
				});
			}
			return false;
		});
		
		$('.portfolio_filter').on('change', function() {
			$grid_selectors.isotope({
			  filter: this.value
			});
		});

		$(window).on("resize", function () {
			setTimeout(function () {
				$grid_selectors.find('.grid_item').removeClass('animation').removeClass('animated'); // avoid problem to filter after window resize
				$grid_selectors.isotope('layout');
			}, 300);
		});
	});
	
	$('.link_container').each(function () {
		$(this).magnificPopup({
			delegate: '.image_popup',
			type: 'image',
			mainClass: 'mfp-zoom-in',
			removalDelay: 500,
			gallery: {
				enabled: true
			}
		});
	});
	
	/*===================================*
	14. POPUP JS
	*===================================*/
	$('.content-popup').magnificPopup({
		type: 'inline',
		preloader: true,
		mainClass: 'mfp-zoom-in',
	});
	
	$('.image_gallery').each(function() { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: 'a', // the selector for gallery item
			type: 'image',
			gallery: {
			  enabled: true,
			},
		});
	});
	
	$('.video_popup, .iframe_popup').magnificPopup({
		type: 'iframe',
		removalDelay: 160,
		mainClass: 'mfp-zoom-in',
		preloader: false,
		fixedContentPos: false
	});
	
	/*===================================*
	15. Select dropdowns
	*===================================*/
	
	if ($('select').length) {
	// Traverse through all dropdowns
	$.each($('select'), function (i, val) {
		var $el = $(val);
		
		if ($el.val()===""){ 
			$el.addClass('first_null'); 
		}
		
		if (!$el.val()) {
			$el.addClass('not_chosen');
		}
		
		$el.on('change', function () {
			if (!$el.val())
				$el.addClass('not_chosen');
			else
				$el.removeClass('not_chosen');
		});
		
	  });
	}
	
	/*===================================*
	18. PROGRESS BAR JS
	*===================================*/
	$('.progress-bar').each(function(){
		var width = $(this).attr('aria-valuenow');
		$(this).appear(function() {
			$(this).css('width', width + '%');
			$(this).children('.count_pr').css('left', width + '%');
			$(this).find('.count').countTo({
				from: 0,
                to: width,
				time: 3000,
				refreshInterval: 50,
			});
		});
	});
	

	
	/*===================================*
	22. TOOLTIP JS
	*===================================*/
	$(function () {
		$('[data-toggle="tooltip"]').tooltip({
			trigger: 'hover',
		});
	});
	$(function () {
		$('[data-toggle="popover"]').popover();
	});
	
	/*===================================*
	24. QUICKVIEW POPUP + ZOOM IMAGE + PRODUCT SLIDER JS
	*===================================*/
	var image = $('#product_img');
	//var zoomConfig = {};
	var zoomActive = false;
	
    zoomActive = !zoomActive;
	if(zoomActive) {
		if ($(image).length > 0){
			$(image).elevateZoom({
				cursor: "crosshair",
				easing : true, 
				gallery:'pr_item_gallery',
				zoomType: "inner",
				galleryActiveClass: "active"
			}); 
		}
	}
	else {
		$.removeData(image, 'elevateZoom');//remove zoom instance from image
		$('.zoomContainer:last-child').remove();// remove zoom container from DOM
	}
	
	$.magnificPopup.defaults.callbacks = {
    open: function() {
      $('body').addClass('zoom_image');
    },
    close: function() {
      // Wait until overflow:hidden has been removed from the html tag
      setTimeout(function() {
        $('body').removeClass('zoom_image');
		$('body').removeClass('zoom_gallery_image');
		$('.zoomContainer').slice(1).remove();
      	}, 100);
      }
  	};
	
	// Set up gallery on click
	var galleryZoom = $('#pr_item_gallery');
	galleryZoom.magnificPopup({
		delegate: 'a',
		type: 'image',
		gallery:{
			enabled: true
		},
		callbacks: {
			elementParse: function(item) {
				item.src = item.el.attr('data-zoom-image');
			}
		}
	});
	
	// Zoom image when click on icon
	$('.product_img_zoom').on('click', function(){
		var atual = $('#pr_item_gallery a').attr('data-zoom-image');
		$('body').addClass('zoom_gallery_image');
		$('#pr_item_gallery .item').each(function(){
			if( atual == $(this).find('.product_gallery_item').attr('data-zoom-image') ) {
				return galleryZoom.magnificPopup('open', $(this).index());
			}
		});
	});

	
	/*===================================*
	27. CHECKBOX CHECK THEN ADD CLASS JS
	*===================================*/
	$('.create-account,.different_address').hide();
	$('#createaccount:checkbox').on('change', function(){
		if($(this).is(":checked")) {
			$('.create-account').slideDown();
		} else {
			$('.create-account').slideUp();
		}
	});
	$('#differentaddress:checkbox').on('change', function(){
		if($(this).is(":checked")) {
			$('.different_address').slideDown();
		} else {
			$('.different_address').slideUp();
		}
	});
	
	/*===================================*
	30. SHOW HIDE PASSWORD
	*===================================*/
	
	$(".toggle-password").on('click', function () {

	  $(this).toggleClass("fas fa-eye-slash fas fa-eye");
	  var input = $($(this).attr("data-toggle"));
	  if (input.attr("type") == "password") {
		input.attr("type", "text");
	  } else {
		input.attr("type", "password");
	  }
	});

	$(".pr_detail .woocommerce-product-rating, .pr_detail p.price" ).wrapAll( "<div class='klb-detail-price'></div>" );
	$(".pr_detail form.cart, .pr_detail a.compare.button" ).wrapAll( "<div class='klb-form-cart'></div>" );
	$(".sidebar select, .klb-post select, .klbfooterwidget select" ).wrap( "<div class='custom_select'></div>" );



	$(document).ready(function () {
		$('.flex-control-thumbs').each( function() {
			var $slick_carousel = $(this);
			$slick_carousel.slick({
				infinite: false,
				slidesToShow: 4,
				slidesToScroll: 1,
			});	
		});
		
		/*===================================*
			MEGA MENU DROPDOWN FULL WİDTH
		*===================================*/
		if ($(window).width() > 960) {
			$("li.submenu_full_width > ul.mega_dropdown").css("width", $("nav.navbar.navbar-expand-lg").width());
			
			$("li.submenu_full_width > ul.mega_dropdown").position({
				my:        "left top",
				at:        "left bottom",
				of:        "nav.navbar.navbar-expand-lg",
			});


		}

	});

	$(window).resize(function() {
		   $('.klb-page.section').css({'min-height':($(window).height() - $('header').innerHeight() - $('footer').innerHeight() - $('#wpadminbar').innerHeight())+'px'});
	}).resize();


})(jQuery);

// Copiar link del producto
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.getElementById('copyLinkBtn');
    
    if (copyBtn) {
        copyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = window.location.href;
            
            navigator.clipboard.writeText(url).then(function() {
                // Cambiar ícono temporalmente
                const icon = copyBtn.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'bi bi-check-lg';
                copyBtn.classList.add('btn-success');
                copyBtn.classList.remove('btn-outline-secondary');
                
                // Restaurar después de 2 segundos
                setTimeout(function() {
                    icon.className = originalClass;
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                console.error('Error al copiar:', err);
            });
        });
    }
});

// ==========================================
// BARRA SUPERIOR DE ENVÍOS - FUNCIONALIDAD
// ==========================================

jQuery(document).ready(function($) {
    
    // Función para actualizar el progreso del envío
    function updateShippingProgress() {
        const $progressData = $('.shipping-progress-data');
        
        if ($progressData.length === 0) return;
        
        const cartTotal = parseFloat($progressData.attr('data-cart-total')) || 0;
        const remaining = parseFloat($progressData.attr('data-remaining')) || 0;
        const percentage = parseInt($progressData.attr('data-percentage')) || 0;
        const isFree = $progressData.attr('data-is-free') === 'true';
        
        // Actualizar barra de progreso
        const $progressBar = $('.shipping-progress-bar .progress-bar');
        const $progressText = $('.shipping-progress-text strong');
        
        if ($progressBar.length) {
            $progressBar.css('width', percentage + '%');
            $progressBar.attr('aria-valuenow', percentage);
            $progressBar.text(percentage + '%');
        }
        
        if ($progressText.length && !isFree) {
            $progressText.text('$' + remaining.toLocaleString('es-AR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }));
        }
        
        // Mostrar/ocultar mensaje de envío gratis
        if (isFree) {
            $('.shipping-progress-wrapper').hide();
            $('.shipping-free-message').show();
        } else {
            $('.shipping-progress-wrapper').show();
            $('.shipping-free-message').hide();
        }
    }
    
    // Actualizar al cargar la página
    updateShippingProgress();
    
    // Actualizar cuando se actualiza el carrito
    $(document.body).on('updated_cart_totals', function() {
        updateShippingProgress();
    });
    
    // Actualizar cuando se añade al carrito (fragmentos de WooCommerce)
    $(document.body).on('wc_fragments_refreshed', function() {
        updateShippingProgress();
    });
    
    // Cerrar barra
    $('#topbarClose').on('click', function() {
        $('#topbarShippingBanner').slideUp(300, function() {
            $(this).addClass('closed');
            $('body').addClass('topbar-closed');
            // Guardar preferencia en sessionStorage
            sessionStorage.setItem('topbarClosed', 'true');
        });
    });
    
    // Verificar si fue cerrada previamente
    if (sessionStorage.getItem('topbarClosed') === 'true') {
        $('#topbarShippingBanner').addClass('closed').hide();
        $('body').addClass('topbar-closed');
    }
    
    // Duplicar contenido del ticker para loop infinito
    $('.ticker-content').each(function() {
        const $this = $(this);
        const content = $this.html();
        $this.append(content); // Duplicar para loop sin saltos
    });
    
});