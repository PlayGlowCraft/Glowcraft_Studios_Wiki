<?php
if (!class_exists('Newses_Tab_Posts')) :
    /**
     * Adds Newses_Tab_Posts widget.
     */
    class Newses_Tab_Posts extends Newses_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newses-tabbed-popular-posts-title', 'newses-tabbed-latest-posts-title', 'newses-tabbed-categorised-posts-title', 'newses-excerpt-length', 'newses-posts-number');
            $this->select_fields = array('newses-show-excerpt', 'newses-enable-categorised-tab', 'newses-select-category');

            $widget_ops = array(
                'classname' => 'newses-tabbed-posts-widget',
                'description' => __('Displays tabbed posts lists from selected settings.', 'newses'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newses_tab_posts', __('AR: Tabbed Posts', 'newses'), $widget_ops);
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
            $tab_id = 'tabbed-' . $this->number;


            /** This filter is documented in wp-includes/default-widgets.php */

            $show_excerpt = 'false';
            $excerpt_length = '20';
            $number_of_posts =  '5';


            $popular_title = isset($instance['newses-tabbed-popular-posts-title']) ? $instance['newses-tabbed-popular-posts-title'] : __('newses Popular', 'newses');
            $latest_title = isset($instance['newses-tabbed-latest-posts-title']) ? $instance['newses-tabbed-latest-posts-title'] : __('newses Latest', 'newses');


            $enable_categorised_tab = isset($instance['newses-enable-categorised-tab']) ? $instance['newses-enable-categorised-tab'] : 'true';
            $categorised_title = isset($instance['newses-tabbed-categorised-posts-title']) ? $instance['newses-tabbed-categorised-posts-title'] : __('Trending', 'newses');
            $category = isset($instance['newses-select-category']) ? $instance['newses-select-category'] : '0';


            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="tabbed-container">
                <div class="tabbed-head top-right-area">
                    <ul class="nav nav-tabs ta-tabs tab-warpper" role="tablist">
                        <li class="nav-item tab tab-recent active">
                            <a class="nav-link active" href="#<?php echo esc_attr($tab_id); ?>-recent"
                               aria-controls="<?php esc_attr_e('Recent', 'newses'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1">
                                <i class="fa fa-bolt" aria-hidden="true"></i>  <?php echo esc_html($latest_title); ?>
                            </a>
                        </li>
                        <li role="presentation" class="nav-item tab tab-popular">
                            <a class="nav-link" href="#<?php echo esc_attr($tab_id); ?>-popular"
                               aria-controls="<?php esc_attr_e('Popular', 'newses'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1">
                               <i class="fa-regular fa-clock" aria-hidden="true"></i>  <?php echo esc_html($popular_title); ?>
                            </a>
                        </li>

                        <?php if ($enable_categorised_tab == 'true'): ?>
                            <li class="nav-item tab tab-categorised">
                                <a class="nav-link" href="#<?php echo esc_attr($tab_id); ?>-categorised"
                                   aria-controls="<?php esc_attr_e('Categorised', 'newses'); ?>" role="tab"
                                   data-toggle="tab" class="font-family-1">
                                   <i class="fa fa-fire" aria-hidden="true"></i>  <?php echo esc_html($categorised_title); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="<?php echo esc_attr($tab_id); ?>-recent" role="tabpanel" class="tab-pane fade active show">
                        <?php
                        newses_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <div id="<?php echo esc_attr($tab_id); ?>-popular" role="tabpanel" class="tab-pane fade">
                        <?php
                        newses_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <?php if ($enable_categorised_tab == 'true'): ?>
                        <div id="<?php echo esc_attr($tab_id); ?>-categorised" role="tabpanel" class="tab-pane fade">
                            <?php
                            newses_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
                            ?>
                        </div>
                    <?php endif; ?>
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
            $enable_categorised_tab = array(
                'true' => __('Yes', 'newses'),
                'false' => __('No', 'newses')

            );



            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            ?><h4><?php _e('Latest Posts', 'newses'); ?></h4><?php
            echo parent::newses_generate_text_input('newses-tabbed-latest-posts-title', __('Title', 'newses'), __('Latest', 'newses'));

            ?><h4><?php _e('Popular Posts', 'newses'); ?></h4><?php
            echo parent::newses_generate_text_input('newses-tabbed-popular-posts-title', __('Title', 'newses'), __('Popular', 'newses'));

            $categories = newses_get_terms();
            if (isset($categories) && !empty($categories)) {
                ?><h4><?php _e('Categorised Posts', 'newses'); ?></h4>
                <?php
                echo parent::newses_generate_select_options('newses-enable-categorised-tab', __('Enable Categorised Tab', 'newses'), $enable_categorised_tab);
                echo parent::newses_generate_text_input('newses-tabbed-categorised-posts-title', __('Title', 'newses'), __('Trending', 'newses'));
                echo parent::newses_generate_select_options('newses-select-category', __('Select category', 'newses'), $categories);

            }

        }
    }
endif;