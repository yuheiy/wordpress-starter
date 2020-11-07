<?php

$context = defaultTimberContext();

$newsPosts = Timber::get_posts([
  'post_type' => 'news',
  'posts_per_page' => 3,
]);
foreach ($newsPosts as $newsPost) {
  forceRelPath($newsPost);
}
$context['news_posts'] = $newsPosts;

Timber::render('index.twig', $context);
