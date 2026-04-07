<?php class Newses_lite_horizontal_vertical_posts  extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'newses-widget-horizontal-vertical-posts',
			'description' => __('Display Featured Posts', 'newses')
		);
		parent::__construct(false, $name = __('AR: Featured Posts', 'newses') , $widget_ops);
	}

	function form($instance) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'widget_title' => 'Featured Posts',
				'category' => '',
				'type' => 1,
			)
		);
		$title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1; ?>
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>">
				<?php esc_html_e('Title: ', 'newses'); ?>
			</label>
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<input type="radio" id="<?php echo ($this->get_field_id('type') . '-1'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="1" <?php checked($type == 1, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-1'); ?>" class="input-label"><?php esc_html_e('Latest Posts', 'newses'); ?></label>
			<br>
			<input type="radio" id="<?php echo ($this->get_field_id( 'type') . '-2'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="2" <?php checked($type == 2, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-2'); ?>" class="input-label"><?php esc_html_e('Show Posts from Category', 'newses'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>">
				<?php esc_html_e('Choose Category:', 'newses'); ?>
			</label>
			<?php wp_dropdown_categories(
				array(
					'show_option_none' => 'Choose Category',
					'name' => $this->get_field_name('category') ,
					'selected' => $instance['category']
				)
			); ?>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['category'] = absint($new_instance['category']);
		$instance['style'] = absint($new_instance['style']);
		$instance['widget_title'] = sanitize_text_field($new_instance['widget_title']);
		$instance['type'] = ( isset($new_instance['type']) && $new_instance['type'] > 0 && $new_instance['type'] < 3 ) ? (int) $new_instance['type'] : 1;
		return $instance;
	}

	function widget($args, $instance) {

		$category = isset($instance['category']) ? $instance['category'] : '';
		$style = empty($instance['style']) ? '' : $instance['style'];
		$widget_title = apply_filters( 'widget_title', empty( $instance['widget_title'] ) ? '' : $instance['widget_title'], $instance, $this->id_base );
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1;
		global $post;

		$post_type = array(
			'posts_per_page' => 4,
			'post_type' => array('post'),
			'post__not_in' => get_option('sticky_posts'),
		);
		if ( $type == 2 ) {
			$post_type['category__in'] = $category;
		}

		$get_featured_posts = new WP_Query($post_type);

		//echo $args['before_widget']; ?>
		<!-- mg-posts-sec mg-posts-modul-1 -->
		<div class="mg-posts-sec mg-posts-modul-1 wd-back featured-posts-widgets">
        	<!-- mg-sec-title -->                    
       		<?php if($widget_title !='') { ?>
       		<div class="mg-sec-title st3"> 
       			<h4><span class="bg"><?php echo $widget_title; ?></span></h4>
        	</div> <!-- // mg-sec-title -->
           <?php } ?>

        	<div class="mg-posts-sec-inner row">
            	<!-- mg-posts-sec-inner -->
            	<div class="<?php echo ($style == 1) ? 'col-12' : 'col-md-6' ;?>">
                    <!--  post lg -->   
                    <div class="mg-blog-post-box mb-0">
					<?php
					$i=1;
					while ($get_featured_posts->have_posts()):$get_featured_posts->the_post(); ?>
					<?php if ( $i == 1 ) { ?>
						<?php if ( has_post_thumbnail() ) { 
							$url = newses_get_freatured_image_url($post->ID, 'newses-featured'); ?>
						<div class="mg-blog-thumb lg back-img hlg" style="background-image: url('<?php echo esc_url($url); ?>');">
							<a class="link-div" href="<?php the_permalink(); ?>">
		                    	<?php if (!empty($url)): ?>
		                    	<?php endif; ?>
		                	</a>
		                	<?php newses_post_categories(); ?>
						</div>
						<?php } ?>
						<article class="small p-0">
                        	<h4 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        	 <?php newses_post_meta(); ?>
                     	</article>
					</div> <!-- /post lg -->             
				</div> <!-- /col-md-6 -->

				<div class="small-list-post <?php echo ($style == 1) ? 'col-12' : 'col-md-6' ;?> mt-4 mt-md-0">
					<!-- small-list-post -->
					<?php } ?>
						<!-- small_post -->
						<div class="small-post media featured-post-<?php echo esc_attr($i); ?> clearfix">
						<?php if ( has_post_thumbnail() ) { 
						$url = newses_get_freatured_image_url($post->ID, 'newses-featured'); ?>
							<!-- img-small-post -->
							<div class="img-small-post back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
								<a href="<?php the_permalink(); ?>">
	                            	<?php if (!empty($url)): ?>
	                            	<?php endif; ?>
	                        	</a>
							</div>
						<?php } ?>
						<!-- // img-small-post -->
						<!-- small-post-content -->
						<div class="small-post-content media-body">
						<?php newses_post_categories(); ?>
							<h5 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
							<?php newses_post_meta(); ?>
						</div>
						<!-- /small-post-content -->
						</div><!-- /small_post -->
					<?php
				$i++;
				endwhile;
				// Reset Post Data
				wp_reset_postdata(); ?>
				<?php if ( $style == 0 ) { ?>
				<?php } ?>
				<!-- // small-list-post -->
        	</div>
        	<!-- // mg-posts-sec-inner -->
        </div>

		<?php echo $args['after_widget'] . '<!-- .widget_featured_post -->';
	}
}