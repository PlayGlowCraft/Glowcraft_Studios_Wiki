<?php
if (!class_exists('Newses_Posts_List')) :
    /**
     * Adds Newses_Posts_List widget.
     */
    class Newses_Posts_List extends Newses_Widget_Base {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct() {
            $this->text_fields = array('newses-categorised-posts-title', 'newses-excerpt-length', 'newses-posts-number');
            $this->select_fields = array('newses-select-category', 'newses-show-excerpt');

            $widget_ops = array(
                'classname' => 'mg-posts-sec mg-posts-modul-2',
                'description' => __('Displays posts from selected category in a list.', 'newses'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newses_posts_list', __('AR: Posts List', 'newses'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */

        public function widget($args, $instance) {

            $instance = parent::newses_sanitize_data($instance, $instance);

            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters('widget_title', $instance['newses-categorised-posts-title'], $instance, $this->id_base);
            $category = isset($instance['newses-select-category']) ? $instance['newses-select-category'] : '0';
            $number_of_posts = isset($instance['newses-posts-number']) ? $instance['newses-posts-number'] : 10;

            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="mg-posts-sec mg-posts-modul-2 posts-list-widget wd-back">
                <?php if (!empty($title)): ?>
                    <?php if (!empty($title)): ?> 
                    <div class="mg-sec-title st3">
                    <!-- mg-sec-title -->
                    <h4><span class="bg"><?php echo esc_html($title); ?></span></h4>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php
                $all_posts = newses_get_posts($number_of_posts, $category);
                ?>
                <!-- mg-posts-sec-inner -->
                <div class="mg-posts-sec-inner">
                    <div class="small-list-post row">
                        <?php
                        $count = 1;
                        if ($all_posts->have_posts()) :
                            while ($all_posts->have_posts()) : $all_posts->the_post();
                                global $post;
                                $url = newses_get_freatured_image_url($post->ID, 'thumbnail');

                                ?>
                                <!-- small-list-post -->
                                <div class="small-post media col-md-6">
                                    <!-- small_post -->
                                    <div class="img-small-post back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                                        <a href="<?php the_permalink(); ?>" class="link-div"></a>
                                    </div>
                                    <!-- // img-small-post -->
                                    <div class="small-post-content media-body">
                                    <?php newses_post_categories(); ?>
                                        <!-- small-post-content -->
                                        <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <!-- // title_small_post -->
                                    </div>
                                    <!-- // small-post-content -->
                                </div>
                                <!-- // small_post -->
                                <?php
                                $count++;
                            endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>    
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
        public function form($instance) {
            $this->form_instance = $instance;
            $options = array(
                'true' => __('Yes', 'newses'),
                'false' => __('No', 'newses')

            );

            $categories = newses_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newses_generate_text_input('newses-categorised-posts-title', __('Title', 'newses'), __('Posts List', 'newses'));
                echo parent::newses_generate_select_options('newses-select-category', __('Select category', 'newses'), $categories);

            }
        }
    }
endif;