<?php 
/**
PHP functions & Hooks:
*/

//Banner Tabed Section
if (!function_exists('newszone_banner_tabbed_posts')):
    /**
     *
     * @since NewsZone 1.0.0
     *
     */
    function newszone_banner_tabbed_posts()
    {
            
            if(is_front_page() || is_home())
            { 
            ?>
            <div class="col-md-3 top-right-area">
                    <div class="tabarea wd-back">

                        <?php
                        
                        $number_of_posts = '2';
                        $newses_editor_news_category = newses_get_option('select_editor_news_category');
                        $newses_all_posts_main = newses_get_posts($number_of_posts, $newses_editor_news_category);
                        ?>
                        <?php if ($newses_all_posts_main->have_posts()) :
                        while ($newses_all_posts_main->have_posts()) : $newses_all_posts_main->the_post();
                        global $post;
                        $newses_url = newses_get_freatured_image_url($post->ID, 'newses-slider-full'); ?>
                        <div class="mg-blog-post-3 sm  back-img mb-3" style="background-image: url('<?php echo esc_url($newses_url); ?>'); ">
                              <div class="mg-blog-inner">
                                  <div class="mg-blog-category">
                                  <?php newses_post_categories(); ?> 
                              </div>
                              <span class="post-form"><i class="fa fa-camera"></i></span>
                                  <h4 class="title sm"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>     
                              </div>
                          </div>
                          <?php endwhile;
                                endif;
                                wp_reset_postdata();
                           ?>
                </div>
            </div>
        <?php

    } }
endif;

add_action('newszone_action_banner_tabbed_posts', 'newszone_banner_tabbed_posts', 10);


//Front Page Banner
if (!function_exists('newszone_front_page_banner_section')) :
    /**
     *
     * @since NewsZone
     *
     */
    function newszone_front_page_banner_section()
    {
        if (is_front_page() || is_home()) {
        $newses_enable_main_slider = newses_get_option('show_main_news_section');
        $select_vertical_slider_news_category = newses_get_option('select_vertical_slider_news_category');
        $vertical_slider_number_of_slides = newses_get_option('vertical_slider_number_of_slides');
        $all_posts_vertical = newses_get_posts($vertical_slider_number_of_slides, $select_vertical_slider_news_category);
        $newses_select_trending_setting = newses_get_option('newses_select_trending_setting');
        if (($newses_select_trending_setting == 'left')) {
        do_action('newszone_action_front_page_trending_post');
        }
         ?>

                <div class="col-md-6">
               <div class="wd-back"> 
                
                <div class="homemain swiper-container">
                    <div class="swiper-wrapper">
                        <?php newses_get_block('list', 'banner'); ?>
                   </div>
                  <!-- Add Arrows -->
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
                </div>
                </div>
            </div>
                <?php do_action('newszone_action_banner_tabbed_posts'); ?>
                <?php if (($newses_select_trending_setting == 'right')) {
                  do_action('newszone_action_front_page_trending_post');
                  } ?>

        <!--==/ Home Slider ==-->
        
        <!-- end slider-section -->
        <?php }
    }
endif;
add_action('newszone_action_front_page_main_section_1', 'newszone_front_page_banner_section', 40);


//Front Page Trending Post
if (!function_exists('newszone_front_page_trending_post_section')) :
    /**
     *
     * @since News-zone
     *
     */
    function newszone_front_page_trending_post_section()
    {
       if (is_front_page() || is_home()) {
                $trending_post_section_enable = newses_get_option('trending_post_section_enable');
            if ($trending_post_section_enable):

                $number_of_posts = '4';

                $trending_category = newses_get_option('select_trending_post_category');
                $all_trending_posts = newses_get_posts( $number_of_posts, $trending_category);
                global $post;
                ?>

        <div class="col-md-3">
              <div class="mg-bigp wd-back">
               <div class="small-list-post">
                    <?php if ($all_trending_posts->have_posts()) :
                          while ($all_trending_posts->have_posts()) : $all_trending_posts->the_post();
                          $url = newses_get_freatured_image_url($post->ID, 'thumbnail');
                      ?>
                    
                      <div class="small-post media">
                        <?php if($url) { ?>
                        <div class="img-small-post back-img" style="background-image: url('<?php echo $url; ?>');">
                                          <a href="<?php the_permalink(); ?>" class="link-div"></a>
                        </div>
                        <?php } ?>
                        <div class="small-post-content media-body">
                        <div class="mg-blog-category"> <?php newses_post_categories(); ?> </div>
                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        </div>
                      </div>
                    <?php  endwhile;
                           endif;
                           wp_reset_postdata();
                      ?>
                    </div>
                </div>
           </div>
        <?php endif; }
    }
endif;
add_action('newszone_action_front_page_trending_post', 'newszone_front_page_trending_post_section', 30);