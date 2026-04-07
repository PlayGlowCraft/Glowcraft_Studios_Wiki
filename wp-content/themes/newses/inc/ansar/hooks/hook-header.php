<?php
if (!function_exists('newses_header_section')) :
/**
 *  Slider
 *
 * @since newses
 *
 */
function newses_header_section()
{
    $header_social_icon_enable = esc_attr(get_theme_mod('header_social_icon_enable','true'));
    $header_data_enable = esc_attr(get_theme_mod('header_data_enable','true'));
    $header_time_enable = esc_attr(get_theme_mod('header_time_enable','true'));
    if ( ($header_social_icon_enable == true) || ($header_data_enable == true) || ($header_time_enable == true) ) {
?>
<div class="mg-head-detail d-none d-md-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-xs-12 col-sm-6">
                <ul class="info-left">
                    <?php newses_date_display_type(); ?>
                </ul>
            </div>
            <div class="col-md-6 col-xs-12">
                <ul class="mg-social info-right">
                <?php if($header_social_icon_enable == true) { do_action('newses_action_header_social_icon'); } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
} }
endif;
add_action('newses_action_header_section', 'newses_header_section', 5);


if (!function_exists('newses_header_search')) :
    /**
     *  Header Search
     *
     * @since Newses
     *
     */
    function newses_header_search() { 
        $header_search_enable = get_theme_mod('header_search_enable','true');
        if($header_search_enable == true) { ?>
            <div class="dropdown show mg-search-box">
                <a class="dropdown-toggle msearch ml-auto" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <div class="dropdown-menu searchinner" aria-labelledby="dropdownMenuLink">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php }
    }
endif;
add_action('newses_action_header_search', 'newses_header_search');

if (!function_exists('newses_header_subscribe')) :
    /**
     *  Header subscribe
     *
     * @since Newses
     *
     */
    function newses_header_subscribe() { 
        $header_watch_btn_enable = get_theme_mod('header_watch_btn_enable','true');
        $newses_header_btn_link = get_theme_mod('newses_header_btn_link','#');
        $newses_header_wth_btn_target = get_theme_mod('newses_header_wth_btn_target','true') == 'true' ? 'target="_blank"':'';
        if($header_watch_btn_enable == true) { ?>
          <a href="<?php echo esc_url($newses_header_btn_link); ?>" <?php echo $newses_header_wth_btn_target ?> class="btn-theme px-3 ml-2">
            <i class="fa-solid fa-tv"></i>
        </a>
        <?php }
    }
endif;
add_action('newses_action_header_subscribe', 'newses_header_subscribe');