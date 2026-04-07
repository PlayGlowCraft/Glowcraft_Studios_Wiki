<?php
if (!class_exists('Newses_Posts_Slider')) :
    /**
     * Adds Newses_Posts_Slider widget.
     */
    class Newses_Posts_Slider extends Newses_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newses-posts-slider-title', 'newses-excerpt-length', 'newses-posts-slider-number');
            $this->select_fields = array('newses-select-category', 'newses-show-excerpt');

            $widget_ops = array(
                'classname' => 'newses_posts_slider_widget',
                'description' => __('Displays posts slider from selected category.', 'newses'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newses_posts_slider', __('AR: Posts Slider', 'newses'), $widget_ops);
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
            $category = isset($instance['newses-select-category']) ? $instance['newses-select-category'] : 0;
            $number_of_posts = 5;

            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="wd-back">
            <?php if (!empty($title)): ?>
            <div class="mg-sec-title st3">
            <!-- mg-sec-title -->
                    <h4><span class="bg"><?php echo esc_html($title); ?></span></h4>
            </div>
            <!-- // mg-sec-title -->
            <?php endif; ?>
            <?php

            $all_posts = newses_get_posts($number_of_posts, $category);
            ?>
            
            <div class="postcrousel swiper-container">
                <div class="swiper-wrapper">
                <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = newses_get_freatured_image_url($post->ID, 'newses-slider-full');
                            ?>
                    <div class="swiper-slide">
                        <div class="mg-blog-post-3 lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                            <a class="link-div" href="<?php the_permalink(); ?>"></a>
                            <div class="mg-blog-inner">
                            <?php newses_post_categories(); ?>
                                <h4 class="title lg">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <?php newses_post_meta(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
                <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
            </div>
        </div>
            <?php
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
            $options = array(
                'true' => __('Yes', 'newses'),
                'false' => __('No', 'newses')

            );
            $categories = newses_get_terms();
            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newses_generate_text_input('newses-posts-slider-title', __('Title', 'newses'), 'Posts Slider');

                echo parent::newses_generate_select_options('newses-select-category', __('Select category', 'newses'), $categories);


            }
        }
    }
endif;