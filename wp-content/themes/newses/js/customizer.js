/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
	
	// Site Title Font Size.
	wp.customize( 'newses_title_font_size', function( value ) {
		value.bind( function( newVal ) {
			$( '.site-title a' ).css( {
				'font-size': newVal+'px',
			} );
		} );
	} );

	// Header Banner, Site Title and Site Tagline Cent Alignment.
	wp.customize( 'newses_center_logo_title', function( value ) {
		value.bind( function( newVal ) {
			var firstChild = $('.mg-nav-widget-area > .row.align-items-center').children(':nth-child(1)');
			if(newVal == true){
				firstChild.parent().addClass('justify-content-center');
				if(firstChild.hasClass('col-md-4 text-center-xs')){
					firstChild.removeClass('col-md-4 text-center-xs');
				} 
				firstChild.addClass('col-md-12 text-center mx-auto');

			}else{
				if(firstChild.parent().hasClass('justify-content-center')){
					firstChild.parent().removeClass('justify-content-center');
				} 
				if(firstChild.hasClass('col-md-12 text-center mx-auto')){
					firstChild.removeClass('col-md-12 text-center mx-auto');
				} 
				firstChild.addClass('col-md-4 text-center-xs');

			}
			console.log(newVal);
		} );
	} );

	// Footer all Text color.
	wp.customize( 'newses_footer_column_layout', function( value ) {
		var colum = 12 / value();
		var wclass = $('.mg-footer-widget-area .mg-widget').parent();
		if(wclass.hasClass('col-md-12')){
			wclass.removeClass('col-md-12');
		}else if(wclass.hasClass('col-md-6')){
			wclass.removeClass('col-md-6');
		}else if(wclass.hasClass('col-md-4')){
			wclass.removeClass('col-md-4');
		}else if(wclass.hasClass('col-md-3')){
			wclass.removeClass('col-md-3');
		}
		wclass.addClass(`col-md-${colum}`);

		value.bind( function( newVal ) {
			colum = 12 / newVal;
			wclass = $('.mg-footer-widget-area .mg-widget').parent();
			if(wclass.hasClass('col-md-12')){
				wclass.removeClass('col-md-12');
			}else if(wclass.hasClass('col-md-6')){
				wclass.removeClass('col-md-6');
			}else if(wclass.hasClass('col-md-4')){
				wclass.removeClass('col-md-4');
			}else if(wclass.hasClass('col-md-3')){
				wclass.removeClass('col-md-3');
			}
			wclass.addClass(`col-md-${colum}`);
			console.log(wclass);
		} );
	} );
	// Footer Background Image
	wp.customize( 'newses_footer_widget_background', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('footer.footer').css('background-image', 'url(' + newVal + ')');
				$('footer.footer').addClass('back-img');
			}else{
				$('footer.footer').removeAttr('style');
				$('footer.footer').removeClass('back-img');
			}
		});
	});

	function customizePreviewStyle(settingId, selector, property) {
		wp.customize(settingId, function(value) {
			value.bind(function(newVal) {
				let cssProperties = {};
				cssProperties[property] = newVal;
				$(selector).css(cssProperties);
			});
		});
	}
	customizePreviewStyle('newses_footer_overlay_color', 'footer .overlay', 'background');
	customizePreviewStyle('newses_footer_text_color', 'footer .bs-widget p, .site-title-footer a, .site-title-footer a:hover, .site-description-footer, .site-description-footer:hover, footer .bs-widget h6, footer .mg_contact_widget .bs-widget h6, footer .mg-widget ul li a', 'color');

} )( jQuery );
