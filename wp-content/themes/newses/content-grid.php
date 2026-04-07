<?php
/**
 * The template for displaying the content.
 * @package Newses
 */
?>
<div id="grid" class="row" >
    <?php while(have_posts()){ the_post();  
    $newses_content_layout = esc_attr(get_theme_mod('newses_content_layout','align-content-right')); ?>

    <div id="post-<?php the_ID(); ?>" <?php if($newses_content_layout == "grid-fullwidth") { echo post_class('col-lg-4 col-md-6'); } else { echo post_class('col-md-6'); } ?>>
    <!-- mg-posts-sec mg-posts-modul-6 -->
        <div class="mg-blog-post-box mb-30"> 
            <?php $url = newses_get_freatured_image_url($post->ID, 'full'); ?>
            <div class="mg-blog-thumb back-img md" style="background-image: url('<?php echo esc_url($url); ?>');">
                <?php echo newses_post_format_type($post); ?>
                <a href="<?php the_permalink();?>" class="link-div"></a>
            </div> 
            <article class="small p-3">
                <?php newses_post_categories(); ?> 
                <h4 class="entry-title title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                <?php newses_post_meta(); ?>
                <?php $newses_excerpt = newses_the_excerpt( absint( 20 ) );
                    if ( !empty( $newses_excerpt ) ) {              
                        echo wp_kses_post( wpautop( $newses_excerpt ) );
                    }
                ?>
            </article>
        </div>
    </div>
    <?php } ?>
    <div class="col-md-12 text-center d-md-flex justify-content-center">
        <?php //Previous / next page navigation
                the_posts_pagination( array(
                'prev_text'          => '<i class="fa-solid fa-angle-left"></i>',
                'next_text'          => '<i class="fa-solid fa-angle-right"></i>',
                ) ); 
        ?>
    </div>
</div>