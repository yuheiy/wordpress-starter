<?php

$context = Timber::context();
$timber_post = Timber::get_post();
$context["post"] = $timber_post;

if (post_password_required($timber_post->ID)) {
	Timber::render("templates/single-password.twig", $context);
} else {
	Timber::render(
		[
			"templates/single-" . $timber_post->ID . ".twig",
			"templates/single-" . $timber_post->post_type . ".twig",
			"templates/single-" . $timber_post->slug . ".twig",
			"templates/single.twig",
		],
		$context
	);
}
