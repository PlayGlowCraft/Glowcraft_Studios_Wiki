<?php
/**
 * newses Theme Customizer
 *
 * @package Newses
 */

if (!function_exists('newses_get_option')):
/**
 * Get theme option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function newses_get_option($key) {

	if (empty($key)) {
		return;
	}

	$value = '';

	$default       = newses_get_default_theme_options();
	$default_value = null;

	if (is_array($default) && isset($default[$key])) {
		$default_value = $default[$key];
	}

	if (null !== $default_value) {
		$value = get_theme_mod($key, $default_value);
	} else {
		$value = get_theme_mod($key);
	}

	return $value;
}
endif;

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-callback.php';

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-default.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newses_customize_register($wp_customize) {

	// Load customize controls.
	require get_template_directory().'/inc/ansar/customize/customizer-control.php';

    // Load customize sanitize.
	require get_template_directory().'/inc/ansar/customize/customizer-sanitize.php';

	$wp_customize->get_setting('custom_logo')->sanitize_callback  = 'esc_url_raw';
	$wp_customize->get_setting('custom_logo')->transport  = 'postMessage';
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
	$wp_customize->get_section('title_tagline')->priority = 10;

	if (isset($wp_customize->selective_refresh)) {

		$wp_customize->selective_refresh->add_partial('custom_logo', array(
			'selector'        => '.site-logo', 
			'render_callback' => 'custom_logo_selective_refresh'
		));

		$wp_customize->selective_refresh->add_partial('blogname', array(
			'selector'        => '.site-title a, .site-title-footer a',
			'render_callback' => 'newses_customize_partial_blogname',
		));
		
		$wp_customize->selective_refresh->add_partial('blogdescription', array(
			'selector'        => '.site-description, .site-description-footer',
			'render_callback' => 'newses_customize_partial_blogdescription',
		));		

		$wp_customize->selective_refresh->add_partial('header_social_icon_enable', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_fb_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_fb_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_twt_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_twt_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_lnkd_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_lnkd_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_insta_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_insta_insta_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_youtube_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_youtube_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_pintrest_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_pintrest_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_telegram_link', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));
		$wp_customize->selective_refresh->add_partial('newses_header_telegram_target', array(
			'selector'        => '.mg-headwidget .mg-head-detail .info-right',
			'render_callback' => 'newses_customize_partial_header_social_icon_enable',
		));

		$wp_customize->selective_refresh->add_partial('banner_advertisement_section', array(
			'selector'        => '.header-ads',
			'render_callback' => 'newses_customize_partial_banner_advertisement_section',
		));

		$wp_customize->selective_refresh->add_partial('select_slider_news_category', array(
			'selector'        => '.homemain',
			'render_callback' => '.newses_customize_partial_select_slider_news_category',
		));

		$wp_customize->selective_refresh->add_partial('show_popular_tags_title', array(
			'selector'        => '.mg-tpt-txnlst strong',
			'render_callback' => 'newses_customize_partial_show_popular_tags_title',
		));
		$wp_customize->selective_refresh->add_partial('header_data_enable', array(
			'selector'        => '.mg-head-detail .info-left',
			'render_callback' => 'newses_customize_partial_newses_date_time_show_type',
		));

		$wp_customize->selective_refresh->add_partial('header_time_enable', array(
			'selector'        => '.mg-head-detail .info-left',
			'render_callback' => 'newses_customize_partial_newses_date_time_show_type',
		));
		$wp_customize->selective_refresh->add_partial('newses_date_time_show_type', array(
			'selector'        => '.mg-head-detail .info-left li',
			'render_callback' => 'newses_customize_partial_newses_date_time_show_type',
		));

		$wp_customize->selective_refresh->add_partial('flash_news_title', array(
			'selector'        => '.mg-latest-news .bn_title h2 span',
			'render_callback' => 'newses_customize_partial_flash_news_title',
		));

		$wp_customize->selective_refresh->add_partial('trending_post_title', array(
			'selector'        => '.recentarea .mg-sec-title h4',
			'render_callback' => 'newses_customize_partial_trending_section_title',
		));

		$wp_customize->selective_refresh->add_partial('you_missed_title', array(
			'selector'        => '.missed-inner .mg-sec-title h4 span',
			'render_callback' => 'newses_customize_partial_you_missed_title',
		));

		$wp_customize->selective_refresh->add_partial('newses_related_post_title', array(
			'selector'        => '.related-title span.bg',
			'render_callback' => 'newses_customize_partial_newses_related_post_title',
		));

		$wp_customize->selective_refresh->add_partial('header_search_enable', array(
			'selector'        => '.mg-search-box',
			'render_callback' => 'newses_customize_partial_header_search_enable',
		));

		$wp_customize->selective_refresh->add_partial('header_watch_btn_enable', array(
			'selector'        => 'a.btn-theme.px-3.ml-2',
			'render_callback' => 'newses_customize_partial_header_watch_btn_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_btn_link', array(
			'selector'        => 'a.btn-theme.px-3.ml-2',
			'render_callback' => 'newses_customize_partial_header_watch_btn_enable',
		));

		$wp_customize->selective_refresh->add_partial('newses_header_wth_btn_target', array(
			'selector'        => 'a.btn-theme.px-3.ml-2',
			'render_callback' => 'newses_customize_partial_header_watch_btn_enable',
		));

		$wp_customize->selective_refresh->add_partial('footer_social_icon_enable', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_fb_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_fb_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_twt_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_twt_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_lnkd_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_lnkd_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_insta_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_insta_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_youtube_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_youtube_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_pinterest_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_pinterest_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_footer_telegram_link', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));
		$wp_customize->selective_refresh->add_partial('newses_footer_telegram_target', array(
			'selector'        => 'footer .mg-footer-bottom-area .mg-social',
			'render_callback' => 'newses_customize_partial_footer_social_icon',
		));

		$wp_customize->selective_refresh->add_partial('newses_content_layout', array(
			'selector'        => '#content.home > .row, .archive-class > .row',
			'render_callback' => 'newses_customize_partial_content_layout',
		));
		
		$wp_customize->selective_refresh->add_partial('newses_page_layout', array(
			'selector'        => '.page-class > .container > .row',
			'render_callback' => 'newses_customize_partial_page_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_single_page_layout', array(
			'selector'        => '.single-class .single-main-content.row',
			'render_callback' => 'newses_customize_partial_single_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_single_post_category', array(
			'selector'        => '.single-class .mg-header',
			'render_callback' => 'newses_customize_partial_single_post_head_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_single_post_admin_details', array(
			'selector'        => '.single-class .mg-header',
			'render_callback' => 'newses_customize_partial_single_post_head_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_single_post_date', array(
			'selector'        => '.single-class .mg-header',
			'render_callback' => 'newses_customize_partial_single_post_head_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_single_post_tag', array(
			'selector'        => '.single-class .mg-header',
			'render_callback' => 'newses_customize_partial_single_post_head_layout',
		));
		$wp_customize->selective_refresh->add_partial('single_show_featured_image', array(
			'selector'        => '.single-class .single-main-content .col-lg-9.col-md-8, .single-class .single-main-content .col-md-12',
			'render_callback' => 'newses_customize_partial_single_post_main_layout',
		));
		$wp_customize->selective_refresh->add_partial('single_show_share_icon', array(
			'selector'        => '.single-class .single-main-content .col-lg-9.col-md-8, .single-class .single-main-content .col-md-12',
			'render_callback' => 'newses_customize_partial_single_post_main_layout',
		));
		$wp_customize->selective_refresh->add_partial('newses_enable_single_post_admin_details', array(
			'selector'        => '.single-class .single-main-content .col-lg-9.col-md-8, .single-class .single-main-content .col-md-12',
			'render_callback' => 'newses_customize_partial_single_post_main_layout',
		));
	}

    $default = newses_get_default_theme_options();

    $selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/*theme option panel info*/
	require get_template_directory().'/inc/ansar/customize/theme-options.php';

}
add_action('customize_register', 'newses_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function newses_customize_partial_blogname() {
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function newses_customize_partial_blogdescription() {
	bloginfo('description');
}

function newses_customize_partial_select_slider_news_category() {
	return get_theme_mod( 'select_slider_news_category' );
}

function newses_customize_partial_banner_advertisement_section() {
	return get_theme_mod( 'banner_advertisement_section' );
}

function newses_customize_partial_trending_section_title() {
	return get_theme_mod( 'trending_section_title' );
}

function newses_customize_partial_header_social_icon_enable() {
	if( get_theme_mod( 'header_social_icon_enable' ) == false ) return;
	return do_action('newses_action_header_social_icon');
}

function newses_customize_partial_newses_date_time_show_type() {
	if( get_theme_mod( 'header_data_enable' ) === false &&  get_theme_mod('header_time_enable') === false ) return;
	return newses_date_display_type();
}

function newses_customize_partial_show_popular_tags_title() {
	return get_theme_mod( 'show_popular_tags_title' );
}

function newses_customize_partial_flash_news_title() {
	return get_theme_mod( 'flash_news_title' );
}

function newses_customize_partial_you_missed_title() {
    return get_theme_mod( 'you_missed_title' );
}

function newses_customize_partial_newses_related_post_title() {
    return get_theme_mod( 'newses_related_post_title' );
}

function newses_customize_partial_footer_social_icon() {
    return do_action('newses_action_footer_social_icon');
}

function newses_customize_partial_header_search_enable() {
    return do_action('newses_action_header_search');
}

function newses_customize_partial_header_watch_btn_enable() {
    return do_action('newses_action_header_subscribe');
}

function custom_logo_selective_refresh() {
	if( get_theme_mod( 'custom_logo' ) === "" ) return;
	echo '<div class="site-logo">'.the_custom_logo().'</div>';
}

function newses_customize_partial_content_layout(){
	return do_action('newses_action_main_content_layouts');
}

function newses_customize_partial_page_layout() {
	return get_template_part('template-parts/content', 'page');
}

function newses_customize_partial_single_layout() {
	return get_template_part('template-parts/content', 'single');
}

function newses_customize_partial_single_post_head_layout() {
	return do_action('newses_action_single_top_content');
}

function newses_customize_partial_single_post_main_layout() {
	return do_action('newses_action_single_main_content');
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function newses_customize_preview_js() {
	wp_enqueue_script('newses-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'newses_customize_preview_js');

function newses_customizer_css() {
    wp_enqueue_script( 'newses-customize-controls', get_template_directory_uri() . '/assets/customizer-admin.js', array( 'customize-controls' ) );
}
add_action( 'customize_controls_enqueue_scripts', 'newses_customizer_css',0 );

/************************* Theme Customizer with Sanitize function *********************************/
function newses_theme_option( $wp_customize )
{
    function newses_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }


	/*--- Site title Font size **/
    $wp_customize->add_setting('newses_title_font_size',
        array(
            'default'           => 34,
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control('newses_title_font_size',
        array(
            'label'    => esc_html__('Site Title Size', 'newses'),
            'section'  => 'title_tagline',
            'type'     => 'number',
            'priority' => 50,
        )
    );

    $wp_customize->add_setting('newses_center_logo_title',
    array(
        'default' => false,
        'transport' => 'postMessage',
        'sanitize_callback' => 'newses_sanitize_checkbox',
    )
	);

	$wp_customize->add_control('newses_center_logo_title',
	    array(
	        'label' => esc_html__('Display Center Site Title and Tagline', 'newses'),
	        'section' => 'title_tagline',
	        'type' => 'checkbox',
	        'priority' => 55,

	    )
	);
	
    $wp_customize->remove_control('background_color');

	$wp_customize->add_setting(
        'background_color', 
		array( 
			'transport' => 'postMessage',
			'sanitize_callback' => 'newses_alpha_color_custom_sanitization_callback',
			'default' => '#eee',
		) 
	);
    $wp_customize->add_control(new Newses_Customize_Alpha_Color_Control( $wp_customize,'background_color', 
	array(
			'label'      => __('Background Color', 'newses' ),
			'section' => 'colors',
		)
    ) );
	/*--- Get Site info control ---*/
	$wp_customize->get_control( 'header_textcolor')->label = __( 'Site Title/Tagline Color', 'newses' );
	$wp_customize->get_control( 'header_textcolor')->section = 'title_tagline';
}
add_action('customize_register','newses_theme_option');