<?php

$context = Timber::context();

$context["mytheme_feature_posts"] = Timber::get_posts([
	"post_type" => "mytheme_feature",
	"posts_per_page" => 5,
]);

$templates = ["index.twig"];

if (is_home()) {
	array_unshift($templates, "front-page.twig", "home.twig");
}

Timber::render($templates, $context);
