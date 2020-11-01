<?php

$context = default_timber_context();

$news_posts = Timber::get_posts([
  'post_type' => 'news',
  'posts_per_page' => 3,
]);
foreach ($news_posts as $post) {
  force_rel_path($post);
}
$context['news_posts'] = $news_posts;

Timber::render('index.twig', $context);
