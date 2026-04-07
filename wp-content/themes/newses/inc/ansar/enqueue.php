<?php function newses_scripts() {

	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');

	wp_style_add_data('bootstrap', 'rtl', 'replace' );

	wp_enqueue_style('newses-style', get_stylesheet_uri() );

	wp_style_add_data('newses-style', 'rtl', 'replace' );

	wp_enqueue_style('newses-default', get_template_directory_uri() . '/css/colors/default.css');

	wp_enqueue_style('all-font-awesome',get_template_directory_uri().'/css/font-awesome.css');
	
	wp_enqueue_style('smartmenus',get_template_directory_uri().'/css/jquery.smartmenus.bootstrap.css');	

	wp_enqueue_style('newses-swiper.min',get_template_directory_uri().'/css/swiper.min.css');	

	/* Js script */
	
	wp_enqueue_script('newses-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'));

	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'));

	wp_enqueue_script('newses-marquee-js', get_template_directory_uri() . '/js/jquery.marquee.js' , array('jquery'));

	wp_enqueue_script('newses-swiper.min.js', get_template_directory_uri() . '/js/swiper.min.js' , array('jquery'));

	wp_enqueue_script('smartmenus-js', get_template_directory_uri() . '/js/jquery.smartmenus.js');
	
	wp_enqueue_script('bootstrap-smartmenus-js', get_template_directory_uri() . '/js/bootstrap-smartmenus.js');


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	newses_custom_colors();
}
add_action('wp_enqueue_scripts', 'newses_scripts');

//Custom js for time
function newses_custom_js() {

 	wp_enqueue_script('newses-custom', get_template_directory_uri() . '/js/custom.js' , array('jquery')); 

	$header_time_enable = get_theme_mod('header_time_enable','true'); 
	if($header_time_enable == 'true') { 

	$newses_date_time_show_type = get_theme_mod('newses_date_time_show_type','newses_default'); 
		if($newses_date_time_show_type == 'newses_default'){
			wp_enqueue_script('newses-custom-time', get_template_directory_uri() . '/js/custom-time.js' , array('jquery')); 
		}
	} 
}
add_action('wp_footer','newses_custom_js');


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function newses_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'newses_skip_link_focus_fix' );

//Footer widget text color
function newses_footer_text_color() {
	$newses_footer_text_color = get_theme_mod('newses_footer_text_color');
	if($newses_footer_text_color){ ?>
		<style>
			footer .bs-widget p, .site-title-footer a, .site-title-footer a:hover, .site-description-footer, .site-description-footer:hover, footer .bs-widget h6, footer .mg_contact_widget .bs-widget h6, footer .mg-widget ul li a {
				color: <?php echo esc_attr($newses_footer_text_color); ?> !important;
			}
		</style>

	<?php }
}
add_action('wp_footer','newses_footer_text_color');

function newses_menu(){ ?>
<script>
jQuery('a,input').bind('focus', function() {
    if(!jQuery(this).closest(".menu-item").length && ( jQuery(window).width() <= 992) ) {
    jQuery('.navbar-collapse').removeClass('show');
}})
</script>
<?php }
add_action( 'wp_print_footer_scripts', 'newses_menu' );

if ( ! function_exists( 'newses_admin_scripts' ) ) :
function newses_admin_scripts() {
    
    wp_enqueue_script( 'newses-admin-script', get_template_directory_uri() . '/inc/ansar/customizer-admin/js/newses-admin-script.js', array( 'jquery' ), '', true );
    
    wp_localize_script( 'newses-admin-script', 'newses_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
    wp_enqueue_style('newses-admin-style-css', get_template_directory_uri() . '/css/customizer-controls.css');
}
endif;
add_action( 'admin_enqueue_scripts', 'newses_admin_scripts' );

if ( ! function_exists( 'newses_custom_colors' ) ) :
function newses_custom_colors() {
	$skin_color = get_theme_mod('newses_skin_color','defaultcolor');

    $primary_color = $skin_color == 'purple' ? '#6001d2' : '#C70909';

    $custom_css = "
        :root {
            --pri-color: {$primary_color};
        }
    ";

    wp_add_inline_style( 'newses-style', $custom_css );
}
endif;