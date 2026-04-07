<?php 

if (!function_exists('newses_single_top_content')) :
    /**
     *  Single Page Top 
     *
     * @since Newses
     *
     */
    function newses_single_top_content() { 
        $newses_single_post_category = esc_attr(get_theme_mod('newses_single_post_category','true'));
        $newses_single_post_admin_details = esc_attr(get_theme_mod('newses_single_post_admin_details','true'));
        $newses_single_post_date = esc_attr(get_theme_mod('newses_single_post_date','true'));
        $newses_single_post_tag = esc_attr(get_theme_mod('newses_single_post_tag','true'));
        if(have_posts()) {
            while(have_posts()) { the_post();
              if($newses_single_post_category == true){ newses_post_categories(); } ?>
              <h1 class="title"><?php the_title(); ?></h1>
              <?php
              $tags = get_the_tags();
               if (($newses_single_post_admin_details == true) || ($newses_single_post_date == true) || ($newses_single_post_tag == true)) { ?>
                <div class="media mg-info-author-block"> 
                    <?php if($newses_single_post_admin_details == true){ ?>
                        <a class="mg-author-pic" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"> <?php echo get_avatar( get_the_author_meta( 'ID') , 150); ?> </a>
                    <?php } ?>
                    <div class="media-body">
                        <?php if($newses_single_post_admin_details == true){ ?>
                            <h4 class="media-heading"><span><?php esc_html_e('By','newses'); ?></span><a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php the_author(); ?></a></h4>
                        <?php } if($newses_single_post_date == true){ ?>
                            <span class="mg-blog-date"><i class="fa-regular fa-clock"></i> 
                                <?php echo esc_html(get_the_date('M j, Y')); ?>
                            </span>
                        <?php }
                        if($newses_single_post_tag == true){
                        $tag_list = get_the_tag_list();
                            if($tag_list){ ?>
                            <span class="newses-tags"><i class="fa-solid fa-tags"></i>
                            <?php $keys = array_keys($tags);
                            foreach ($tags as $key => $tag) {
                                    $tag_link = get_tag_link($tag->term_id);
                                    if ($key === end($keys)) {
                                        echo '<a href="'.esc_url($tag_link).'">#'.esc_html($tag->name).'</a>';
                                    } else {
                                        echo ' <a href="'.esc_url($tag_link).'">#'.esc_html($tag->name).'</a>, ';
                                    }
                                }  ?>
                            </span>
                        <?php } } ?>
                    </div>
                </div>
                <?php } 
            } 
        }
    }
endif;
add_action('newses_action_single_top_content', 'newses_single_top_content');

if (!function_exists('newses_single_author_box')) :
    function newses_single_author_box() { 

        $newses_enable_single_post_admin_details = esc_attr(get_theme_mod('newses_enable_single_post_admin_details',true));
        if($newses_enable_single_post_admin_details == true) { ?>
        <div class="media mg-info-author-block">
        <?php  ?>
        <a class="mg-author-pic" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php echo get_avatar( get_the_author_meta( 'ID') , 150); ?></a>
            <div class="media-body">
              <h4 class="media-heading"><?php esc_html_e('By','newses'); ?> <a href ="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php the_author(); ?></a></h4>
              <p><?php the_author_meta( 'description' ); ?></p>
            </div>
        </div>
        <?php }
    }
endif;
add_action('newses_action_single_author_box', 'newses_single_author_box', 40);

if (!function_exists('newses_single_related_box')) :
    function newses_single_related_box() { 
        $newses_enable_related_post = esc_attr(get_theme_mod('newses_enable_related_post','true'));
        $newses_enable_single_post_category = esc_attr(get_theme_mod('newses_enable_single_post_category','true'));
        $newses_related_post_title = get_theme_mod('newses_related_post_title', esc_html__('Related Post','newses'));
        $newses_enable_single_post_date = esc_attr(get_theme_mod('newses_enable_single_post_date','true'));
        $newses_enable_single_post_author = esc_attr(get_theme_mod('newses_enable_single_post_author','true'));
        if($newses_enable_related_post == true){ ?>
        <div class="wd-back">
            <!--Start mg-realated-slider -->
            <!-- mg-sec-title -->
            <div class="mg-sec-title st3">
                <h4 class="related-title"><span class="bg"><?php echo esc_html($newses_related_post_title);?></span></h4>
            </div>
            <!-- // mg-sec-title -->
            <div class="small-list-post row">
                <!-- featured_post -->
                <?php  global $post;
                $categories = get_the_category($post->ID);
                $number_of_related_posts = 3;

                if ($categories) {
                    $cat_ids = array();
                    foreach ($categories as $category) $cat_ids[] = $category->term_id;
                    $args = array(
                        'category__in' => $cat_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $number_of_related_posts, // Number of related posts to display.
                        'ignore_sticky_posts' => 1
                    );
                    $related_posts = new wp_query($args);

                    while ($related_posts->have_posts()) {
                        $related_posts->the_post();
                        global $post;
                        $url = newses_get_freatured_image_url($post->ID, 'full'); ?>
                        <!-- blog -->
                        <div class="small-post media col-md-6 col-sm-6 col-xs-12">
                            <div class="img-small-post back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                            <a href="<?php echo esc_url(get_the_permalink())?>" class="link-div"></a>
                            </div>
                            <div class="small-post-content media-body">
                            <?php if($newses_enable_single_post_category == true){ newses_post_categories(); } ?>
                                <!-- small-post-content -->
                                <h5 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array('before' => 'Permalink to: ','after'  => '') ); ?>">
                                    <?php the_title(); ?></a></h5>
                                <!-- // title_small_post -->
                                <div class="mg-blog-meta"> 
                                    <?php if($newses_enable_single_post_date == true){ ?>
                                    <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
                                    <?php echo esc_html(get_the_date('M j, Y')); ?></a>
                                    <?php } if($newses_enable_single_post_author == true) { ?>
                                    <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"> <i class="fa fa-user-circle-o"></i> <?php the_author(); ?></a>
                                    <?php }
                                    edit_post_link( __( 'Edit', 'newses' ), '<span class="post-edit-link"><i class="fa-regular fa-pen-to-square"></i>', '</span>' ); ?>
                                </div>
                            </div>
                        </div>
                    <!-- blog -->
                    <?php }
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <!--End mg-realated-slider -->
        <?php } 
    }
endif;
add_action('newses_action_single_related_box', 'newses_single_related_box', 40);

if (!function_exists('newses_single_comments_box')) :
    function newses_single_comments_box() { 
        $newses_enable_single_post_comments = esc_attr(get_theme_mod('newses_enable_single_post_comments',true));
        if($newses_enable_single_post_comments == true) {
            if (comments_open() || get_comments_number()) {
                comments_template();
            } 
        }
    }
endif;
add_action('newses_action_single_comments_box', 'newses_single_comments_box', 40);

if (!function_exists('newses_single_main_content')) :
    function newses_single_main_content() { 
        while (have_posts()) : the_post(); ?>
        <div class="mg-blog-post-box"> 
            <?php
            $single_show_featured_image = esc_attr(get_theme_mod('single_show_featured_image','true'));
            if($single_show_featured_image == true) {
                if(has_post_thumbnail()){
                    echo the_post_thumbnail( '', array( 'class'=>'img-responsive' ) );
                    
                    $thumbnail_id = get_post_thumbnail_id();
                    $caption = get_post($thumbnail_id)->post_excerpt;

                    if (!empty($caption)) {
                        echo '<span class="featured-image-caption">' . esc_html($caption) . '</span>';
                    }
                } 
            } ?>
            <article class="small single p-3">
                <?php the_content();
                newses_edit_link();
                newses_social_share_post();
                wp_link_pages(array(
                    'before' => '<div class="single-nav-links">',
                    'after' => '</div>',
                ));
                ?>
                <div class="clearfix mb-3"></div>
                    <?php
                    $prev =  (is_rtl()) ? "left" : "right";
                    $next =  (is_rtl()) ? "right" : "left";
                    the_post_navigation(array(
                        'prev_text' => '<span>%title</span><div class="fa fa-angle-double-'.$prev.'"></div>',
                        'next_text' => '<div class="fa fa-angle-double-'.$next.'"></div><span>%title</span>',
                        'in_same_term' => true,
                    ));
                ?>
            </article>
        </div>
        <div class="clearfix mb-4"></div>
        <?php 
        do_action('newses_action_single_author_box'); 
        do_action('newses_action_single_related_box'); 
        do_action('newses_action_single_comments_box'); 
      
    
    endwhile; // End of the loop.
    }
endif;
add_action('newses_action_single_main_content', 'newses_single_main_content', 40);