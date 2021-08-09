<?php

$context = Timber::context();
$timber_post = Timber::get_post();
$context["post"] = $timber_post;

if (post_password_required($timber_post->ID)) {
	Timber::render("pages/single-password.twig", $context);
} else {
	Timber::render(
		[
			"pages/single-" . $timber_post->ID . ".twig",
			"pages/single-" . $timber_post->post_type . ".twig",
			"pages/single-" . $timber_post->slug . ".twig",
			"pages/single.twig",
		],
		$context
	);
}
