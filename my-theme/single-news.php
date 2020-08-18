<?php get_header(); ?>

<div id="app" data-route="single-news" data-props="<?php
$post = get_post();
set_post_acf($post);

echo esc_attr(
  json_encode(
    array_merge(default_app_props(), [
      'post' => $post,
    ])
  )
);
?>"></div>

<?php get_footer();
