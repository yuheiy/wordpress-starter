<?php get_header(); ?>

<div id="app" data-route="404" data-props="<?php echo esc_attr(
  json_encode(array_merge(default_app_props(), []))
); ?>"></div>

<?php get_footer();
