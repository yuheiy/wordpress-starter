<?php

namespace WordPressStarter\Theme;

use Timber;

$context = Timber::context();
$timber_post = Timber::query_post();
$context["post"] = $timber_post;

if (post_password_required($timber_post->ID)) {
	Timber::render("single-password.twig", $context);
} else {
	Timber::render(
		[
			"single-" . $timber_post->ID . ".twig",
			"single-" . $timber_post->post_type . ".twig",
			"single-" . $timber_post->slug . ".twig",
			"single.twig",
		],
		$context
	);
}
