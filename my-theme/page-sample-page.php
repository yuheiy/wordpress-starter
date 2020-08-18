<?php get_header(); ?>

<div id="app" data-route="page-sample-page" data-props="<?php echo esc_attr(
  json_encode(
    array_merge(default_app_props(), [
      'post' => get_post(),
    ])
  )
); ?>"></div>

<?php get_footer();
