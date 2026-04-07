<?php
if (!class_exists('Newses_Posts_Carousel')) :
    /**
     * Adds Newses_Posts_Carousel widget.
     */
    class Newses_Posts_Carousel extends Newses_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 0.1
         */
        function __construct()
        {
            $this->text_fields = array('newses-posts-slider-title', 'newses-posts-slider-subtitle', 'newses-posts-slider-number');
            $this->select_fields = array('newses-select-category');

            $widget_ops = array(
                'classname' => 'mg-posts-sec mg-posts-modul-3',
                'description' => __('Displays posts carousel from selected category.', 'newses'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newses_posts_carousel', __('AR: Posts Carousel', 'newses'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */

        public function widget($args, $instance)
        {
            $instance = parent::newses_sanitize_data($instance, $instance);
            /** This filter is documented in wp-includes/default-widgets.php */

            $title = apply_filters('widget_title', $instance['newses-posts-slider-title'], $instance, $this->id_base);

            $number_of_posts = 5;
            $category = isset($instance['newses-select-category']) ? $instance['newses-select-category'] : '0';

            // open the widget container
            echo $args['before_widget'];
            ?>
            <!-- mg-posts-sec mg-posts-modul-3 -->
            <div class="mg-posts-sec mg-posts-modul-3 wd-back">
                <?php if (!empty($title)): ?>
                <!-- mg-sec-title -->
                <div class="mg-sec-title st3">
                    <?php if (!empty($title)): ?>
                        <h4><span class="bg"><?php echo esc_html($title);  ?></span></h4>
                    <?php endif; ?>
                </div> <!-- // mg-sec-title -->
                <?php endif; ?>                    
                <?php
                $all_posts = newses_get_posts($number_of_posts, $category);
                ?>
                <!-- mg-posts-sec-inner -->
                <div class="mg-posts-sec-inner">
                    <!-- featured_cat_slider -->
                    <div class="featuredcat swiper-container">
                <div class="swiper-wrapper">
                            <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = newses_get_freatured_image_url($post->ID, 'newses-medium'); ?>
                            <!-- item -->
                            <div class="swiper-slide">
                        <div class="mg-blog-post-3 back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                            <a class="link-div" href="<?php the_permalink(); ?>"></a>
                            <div class="mg-blog-inner">
                            <?php newses_post_categories(); ?>
                                <h4 class="title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <?php newses_post_meta(); ?>
                            </div>
                        </div>
                    </div>
                            <!-- // item -->
                        <?php
                        endwhile;
                        endif;
                        wp_reset_postdata(); ?>   
                        </div>
                        <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                    </div> <!-- // featured_cat_slider -->
                </div> <!-- // mg-posts-sec-inner -->
            </div>
            <!-- // mg-posts-sec mg-posts-modul-3 --> 

                
                

            <?php
            //print_pre($all_posts);

            // close the widget container
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $this->form_instance = $instance;
            $categories = newses_get_terms();
            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newses_generate_text_input('newses-posts-slider-title', 'Title', 'Posts Carousel');
                echo parent::newses_generate_select_options('newses-select-category', __('Select category', 'newses'), $categories);



            }
        }
    }
endif;