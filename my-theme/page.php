<?php

$context = default_timber_context();

$post = Timber::get_post();
force_rel_path($post);
$context['post'] = $post;

Timber::render('page-' . $post->post_name . '.twig', $context);
