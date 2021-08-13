<?php

$context = Timber::context();

$context["feature_posts"] = Timber::get_posts([
	"post_type" => "mytheme_feature",
	"posts_per_page" => 5,
]);

$templates = ["templates/index.twig"];

if (is_home()) {
	array_unshift(
		$templates,
		"templates/front-page.twig",
		"templates/home.twig"
	);
}

Timber::render($templates, $context);
