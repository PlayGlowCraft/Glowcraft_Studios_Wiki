<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Newses
 */
?>
<!--==================== MISSED AREA ====================-->
<div class="missed-section"><?php do_action('newses_action_footer_missed_section')?></div>
<!--==================== FOOTER AREA ====================-->
<?php $newses_footer_widget_background = get_theme_mod('newses_footer_widget_background');
    $newses_footer_overlay_color = get_theme_mod('newses_footer_overlay_color'); 
    if(($newses_footer_widget_background != '') || ($newses_footer_overlay_color != '')) { ?>
    <footer class="footer back-img" style="background-image:url('<?php echo esc_url($newses_footer_widget_background);?>');">
        <div class="overlay" style="background-color: <?php echo esc_attr($newses_footer_overlay_color);?>;">
     <?php } else { ?>
    <footer class="footer"> 
        <div class="overlay">
    <?php } ?>
                <!--Start mg-footer-widget-area-->
                <?php if ( is_active_sidebar( 'footer_widget_area' ) ) { ?>
                <div class="mg-footer-widget-area">
                    <div class="container">
                        <div class="row">
                          <?php  dynamic_sidebar( 'footer_widget_area' ); ?>
                        </div>
                        <!--/row-->
                    </div>
                    <!--/container-->
                </div>
                <?php } ?>
                <!--End mg-footer-widget-area-->
                
                <?php do_action('newses_action_footer_bottom_area'); ?>
                <div class="mg-footer-copyright">
                    <?php do_action('newses_action_footer_copyright_section'); ?>
                </div>
            </div>
            <!--/overlay-->
        </footer>
        <!--/footer-->
    </div>
    <!--/wrapper-->
    <!--Scroll To Top-->
    <a href="#" class="ta_upscr bounceInup animated"><i class="fa-solid fa-angle-up"></i></a>
    <!--/Scroll To Top-->
<!-- /Scroll To Top -->
<?php wp_footer(); ?>
</body>
</html>