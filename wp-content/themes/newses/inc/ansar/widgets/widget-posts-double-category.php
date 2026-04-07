<?php
if (!class_exists('Newses_Dbl_Col_Cat_Posts')) :
    /**
     * Adds Newses_Dbl_Col_Cat_Posts widget.
     */
    class Newses_Dbl_Col_Cat_Posts extends Newses_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newses-categorised-posts-title-1', 'newses-categorised-posts-title-2', 'newses-posts-number-1', 'newses-posts-number-2');
            $this->select_fields = array('newses-select-category-1', 'newses-select-category-2', 'newses-select-layout-1', 'newses-select-layout-2');

            $widget_ops = array(
                'classname' => 'newses_dbl_col_cat_posts',
                'description' => __('Displays posts from 2 selected categories in double column.', 'newses'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newses_dbl_col_cat_posts', __('AR: Double Categories Posts', 'newses'), $widget_ops);
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

            $title_1 = apply_filters('widget_title', $instance['newses-categorised-posts-title-1'], $instance, $this->id_base);
            $title_2 = apply_filters('widget_title', $instance['newses-categorised-posts-title-2'], $instance, $this->id_base);
            $category_1 = isset($instance['newses-select-category-1']) ? $instance['newses-select-category-1'] : '0';
            $category_2 = isset($instance['newses-select-category-2']) ? $instance['newses-select-category-2'] : '0';
            $layout_1 = isset($instance['newses-select-layout-1']) ? $instance['newses-select-layout-1'] : 'full-plus-list';
            $layout_2 = isset($instance['newses-select-layout-2']) ? $instance['newses-select-layout-2'] : 'list';
            $number_of_posts_1 =  4;
            $number_of_posts_2 =  4;


            // open the widget container
            echo $args['before_widget'];
            ?>


            <div class="mg-posts-sec mg-posts-modul-4 wd-back double-category-posts-widget">
                <div class="mg-posts-sec-inner row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php if (!empty($title_1)): ?>
                            <div class="mg-sec-title st3">
                            <h4><span class="bg"><?php echo esc_html($title_1); ?> </span></h4>
                            </div>
                        <?php endif; ?>
                        <div class="small-list-post d-flex flex-column">
                            <?php $all_posts = newses_get_posts($number_of_posts_1, $category_1);
                            $count_1 = 1;
                            if ($all_posts->have_posts()) {
                                while ($all_posts->have_posts()) { $all_posts->the_post(); 
                                    if ($count_1 == 1) {
                                        $thumbnail_size = 'newses-medium';
                                    } else {
                                        $thumbnail_size = 'thumbnail';
                                    }
                                    global $post;
                                    $url = newses_get_freatured_image_url($post->ID, $thumbnail_size);
                                    if ($url == '') {
                                        $img_class = 'no-image';
                                    } ?>
                                        <div class="small-post media mg-post-<?php echo esc_attr($count_1); ?>">
                                            <?php $url = newses_get_freatured_image_url($post->ID, 'newses-featured'); ?>
                                            <div class="img-small-post back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                                            </div>
                                            <!-- // img-small-post -->
                                            <div class="small-post-content media-body">
                                            <?php newses_post_categories(); ?>
                                                <!-- small-post-content -->
                                                <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                                <?php if($count_1 == 1) { 
                                                    newses_post_meta();
                                                } ?>
                                            </div>
                                            <!-- // small-post-content -->
                                        </div>
                                        <?php
                                    $count_1++;
                                }
                            }
                            wp_reset_postdata(); ?>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 <?php echo esc_attr($layout_2); ?> col-sm-12 col-xs-12">
                        <?php if (!empty($title_2)): ?>
                        <!-- mg-sec-title -->
                        <div class="mg-sec-title st3">
                            <h4><span class="bg"><?php echo esc_html($title_2); ?></span></h4>
                        </div>
                        <!-- // mg-sec-title -->
                        <?php endif; ?>
                        <div class="small-list-post d-flex flex-column">
                            <?php $all_posts = newses_get_posts($number_of_posts_2, $category_2); ?>
                            <?php
                            $count_2 = 1;


                            if ($all_posts->have_posts()) :
                                while ($all_posts->have_posts()) : $all_posts->the_post();
                                        if ($count_2 == 1) {
                                            $thumbnail_size = 'newses-medium';

                                        } else {
                                            $thumbnail_size = 'thumbnail';
                                        }



                                    global $post;
                                    $url = newses_get_freatured_image_url($post->ID, $thumbnail_size);

                                    if ($url == '') {
                                        $img_class = 'no-image';
                                    }

                                    global $post;

                                    ?>
                                        <div class="small-post media mg-post-<?php echo esc_attr($count_2); ?>">
                                            <?php $url = newses_get_freatured_image_url($post->ID, 'newses-featured'); ?>
                                            <div class="img-small-post back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                                            </div>
                                            <!-- // img-small-post -->
                                            <div class="small-post-content media-body">
                                            <?php newses_post_categories(); ?>
                                                <!-- small-post-content -->
                                                <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                                <?php if($count_2 == 1) { ?>
                                                    <?php newses_post_meta(); ?>
                                                    <?php } ?>
                                            </div>
                                            <!-- // small-post-content -->
                                        </div>
                                        
                                        <?php
                                    $count_2++;
                                endwhile;
                                ?>
                            <?php endif;
                            wp_reset_postdata(); ?>
                        </div>                          
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

            $categories = newses_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newses_generate_text_input('newses-categorised-posts-title-1', __('Title 1', 'newses'), 'Double Categories Posts 1');
                echo parent::newses_generate_select_options('newses-select-category-1', __('Select category 1', 'newses'), $categories);
                echo parent::newses_generate_text_input('newses-categorised-posts-title-2', __('Title 2', 'newses'), 'Double Categories Posts 2');
                echo parent::newses_generate_select_options('newses-select-category-2', __('Select category 2', 'newses'), $categories);
            }
        }
    }
endif;