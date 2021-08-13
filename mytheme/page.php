<?php

$context = Timber::context();

$timber_post = new Timber\Post();
$context["post"] = $timber_post;
Timber::render(
	[
		"templates/page-" . $timber_post->post_name . ".twig",
		"templates/page.twig",
	],
	$context
);
