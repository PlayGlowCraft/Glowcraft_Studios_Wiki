<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package Newses
 */
get_header(); ?>
<!--==================== Newses breadcrumb section ====================-->
<?php get_template_part('index','banner'); ?>
<!--container-->
<div id="content" class="container archive-class">
    <!--row-->
    <div class="row">
        <?php do_action('newses_action_main_content_layouts'); ?>
    </div>
    <!--/row-->
</div>
<!--/container-->
<?php
get_footer();