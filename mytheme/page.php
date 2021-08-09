<?php

$context = Timber::context();

$timber_post = new Timber\Post();
$context["post"] = $timber_post;
Timber::render(
	["pages/page-" . $timber_post->post_name . ".twig", "pages/page.twig"],
	$context
);
