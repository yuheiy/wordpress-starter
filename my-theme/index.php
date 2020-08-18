<?php get_header(); ?>

<div id="app" data-route="index" data-props="<?php echo esc_attr(
  json_encode(
    array_merge(default_app_props(), [
      'news_posts' => array_map(
        function ($post) {
          set_post_link($post);
          set_post_acf($post);
          return $post;
        },
        get_posts([
          'post_type' => 'news',
          'posts_per_page' => 4,
        ])
      ),
    ])
  )
); ?>"></div>

<?php get_footer();
