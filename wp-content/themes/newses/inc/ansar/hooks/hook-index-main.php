<?php if (!function_exists('newses_content_layouts')) :
    function newses_content_layouts() { 
        $newses_content_layout = esc_attr(get_theme_mod('newses_content_layout','align-content-right'));

        if($newses_content_layout == "align-content-right" || $newses_content_layout == "align-content-left"){ ?>
            <div class="col-md-8">
                <?php get_template_part('content',''); ?>
            </div>
        <?php } elseif($newses_content_layout == "full-width-content") { ?>
            <div class="col-md-12">
                <?php get_template_part('content',''); ?>
            </div>
        <?php }  if($newses_content_layout == "grid-left-sidebar" || $newses_content_layout == "grid-right-sidebar"){ ?>
            <div class="col-md-8">
                <?php get_template_part('content','grid'); ?>
            </div>
        <?php } elseif($newses_content_layout == "grid-fullwidth") { ?>
            <div class="col-md-12">
                <?php get_template_part('content','grid'); ?>
            </div>
        <?php } 
    }
endif;
add_action('newses_action_content_layouts', 'newses_content_layouts', 40);


if (!function_exists('newses_main_content_layouts')) :
    function newses_main_content_layouts() { 
        $newses_content_layout = esc_attr(get_theme_mod('newses_content_layout','align-content-right'));

        if($newses_content_layout == "align-content-left" || $newses_content_layout == "grid-left-sidebar" ){ ?>
            <aside class="col-md-4">
                <?php get_sidebar();?>
            </aside>
        <?php } ?>
        <?php do_action('newses_action_content_layouts'); ?>
        <?php if($newses_content_layout == "align-content-right" || $newses_content_layout == "grid-right-sidebar")  { ?>
            <aside class="col-md-4">
                <?php get_sidebar();?>
            </aside>
        <?php }
    }
endif;
add_action('newses_action_main_content_layouts', 'newses_main_content_layouts', 40);