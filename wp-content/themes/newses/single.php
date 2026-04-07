<!-- =========================
     Page Breadcrumb   
============================== -->
<?php get_header(); ?>
<!--==================== Newses breadcrumb section ====================-->
<!-- =========================
     Page Content Section      
============================== -->
<main id="content" class="single-class content">
  <!--container-->
  <div class="container">
    <!--row-->
    <div class="row">
      <div class="col-md-12">
        <div class="mg-header mb-30">
          <?php do_action('newses_action_single_top_content') ?>
        </div>
      </div>
    </div>
    <div class="single-main-content row">
      <?php get_template_part('template-parts/content', 'single'); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>