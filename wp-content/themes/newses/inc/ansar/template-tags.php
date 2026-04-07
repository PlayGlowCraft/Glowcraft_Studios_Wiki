<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Newses
 */

if (!function_exists('newses_post_categories')) :
    function newses_post_categories($separator = '&nbsp') {
        $global_show_categories = newses_get_option('global_show_categories');
        if ($global_show_categories == 'no') {
            return;
        }
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            global $post;
            $post_categories = get_the_category($post->ID);
            if ($post_categories) {
                echo '<div class="mg-blog-category">';
                $output = '';
                foreach ($post_categories as $post_category) {
                    $t_id = $post_category->term_id;
                    $color_id = "category_color_" . $t_id;

                    // retrieve the existing value(s) for this meta field. This returns an array
                    $term_meta = get_option($color_id);
                    $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';

                    $output .= '<a class="newses-categories ' . esc_attr($color_class) . '" href="' . esc_url(get_category_link($post_category)) .'"> 
                                 ' . esc_html($post_category->name) . '
                             </a>';
                }
                $output .= '';
                echo $output;
                echo '</div>';
            }
        }
    }
endif;

if (!function_exists('newses_get_category_color_class')) :

    function newses_get_category_color_class($term_id) {

        $color_id = "category_color_" . $term_id;
        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option($color_id);
        $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : '';
        return $color_class;

    }
endif;

if ( ! function_exists( 'newses_date_content' ) ) :
    function newses_date_content() { ?>
        <span class="mg-blog-date"><i class="fa-regular fa-clock"></i>
            <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
                <?php echo esc_html(get_the_date('M j, Y')); ?>
            </a>
        </span>
    <?php }
endif;

if ( ! function_exists( 'newses_author_content' ) ) :
    function newses_author_content() { ?>
        <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>">
            <i class="fa-regular fa-user"></i> 
            <?php the_author(); ?>
        </a>
    <?php }
endif;

if ( ! function_exists( 'newses_edit_link' ) ) :
    function newses_edit_link() { 
        edit_post_link( __( 'Edit', 'newses' ), '<span class="post-edit-link"><i class="fa-regular fa-pen-to-square"></i>', '</span>' );
    }
endif;

if ( ! function_exists( 'newses_post_comment' ) ) :
    function newses_post_comment() { ?>
        <span class="comments-link"><i class="fa-regular fa-comments"></i>
            <a href="<?php the_permalink(); ?>"><?php
                if ( get_comments_number() == 0 ) {
                    esc_html_e(  __('No Comments', 'newses') );
                } else {
                    echo get_comments_number() . ' ';
                    esc_html_e( get_comments_number() == 1 ? __('Comment', 'newses') : __('Comments', 'newses') );
                } ?>
            </a> 
        </span>  
    <?php }
endif;

if (!function_exists('newses_post_meta')) :

    function newses_post_meta()
    {
    $global_post_date = get_theme_mod('global_post_date_author_setting','show-date-author'); 
    $all_post_comment_disable = get_theme_mod('all_post_comment_disable',false); ?>
    <div class="mg-blog-meta">
    <?php if($global_post_date =='show-date-author') {
       newses_date_content();
       newses_author_content();         
    } elseif($global_post_date =='show-date-only') { 
        newses_date_content();
    } elseif($global_post_date =='show-author-only') {
        newses_author_content();         
    } elseif($global_post_date =='hide-date-author') { }
    if($all_post_comment_disable == true) { 
        newses_post_comment(); 
    }
    newses_edit_link();
    echo '</div>';
}
endif;

function newses_read_more() {
    
    global $post;
    
    $readbtnurl = '<br><a class="btn btn-theme post-btn" href="' . get_permalink() . '">'.__('Read More','newses').'</a>';
    
    return $readbtnurl;
}
add_filter( 'the_content_more_link', 'newses_read_more' );

if ( ! function_exists( 'newses_the_excerpt' ) ) :

    /**
     * Generate excerpt.
     *
     */
    function newses_the_excerpt( $length = 0, $post_obj = null ) {

        global $post;

        if ( is_null( $post_obj ) ) {
            $post_obj = $post;
        }

        $length = absint( $length );

        if ( 0 === $length ) {
            return;
        }

        $source_content = $post_obj->post_content;

        if ( ! empty( get_the_excerpt($post_obj) ) ) {
            $source_content = get_the_excerpt($post_obj);
        } 
        // Check if non-breaking space exists in the text with variations
        if (preg_match('/\s*(&nbsp;|\xA0)\s*/u', $source_content)) {
            // Remove non-breaking space and its variations from the text
            $source_content = preg_replace('/\s*(&nbsp;|\xA0)\s*/u', ' ', $source_content);
        }

        $source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
        $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );
        return $trimmed_content;

    }
endif;