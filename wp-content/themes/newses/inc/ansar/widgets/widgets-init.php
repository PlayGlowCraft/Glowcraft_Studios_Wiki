<?php
/* Theme Widget sidebars. */

// Load widget base.
require_once get_template_directory() . '/inc/ansar/widgets/widgets-base.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/ansar/widgets/widgets-common-functions.php';

/* Theme Widgets*/ 

require get_template_directory() . '/inc/ansar/widgets/widget-latest-news.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-design-slider.php'; 
require get_template_directory() . '/inc/ansar/widgets/featured-post-widget.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-posts-carousel.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-posts-double-category.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-posts-list.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-posts-slider.php'; 
require get_template_directory() . '/inc/ansar/widgets/widget-posts-tabbed.php'; 


/* Register site widgets */
if ( ! function_exists( 'newses_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function newses_widgets() {  
        register_widget( 'Newses_Latest_Post' ); 
        register_widget( 'Newses_Design_Slider' ); 
        register_widget( 'Newses_lite_horizontal_vertical_posts' ); 
        register_widget( 'Newses_Posts_Carousel' ); 
        register_widget( 'Newses_Dbl_Col_Cat_Posts' ); 
        register_widget( 'Newses_Posts_List' ); 
        register_widget( 'Newses_Posts_Slider' ); 
        register_widget( 'Newses_Tab_Posts' ); 
    }
endif;
add_action( 'widgets_init', 'newses_widgets' );
