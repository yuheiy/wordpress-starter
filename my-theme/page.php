<?php

$context = defaultTimberContext();

$post = Timber::get_post();
forceRelPath($post);
$context['post'] = $post;

Timber::render('page-' . $post->post_name . '.twig', $context);
