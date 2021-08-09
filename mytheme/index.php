<?php

$context = Timber::context();

$context["feature_posts"] = Timber::get_posts([
	"post_type" => "mytheme_feature",
	"posts_per_page" => 5,
]);

$templates = ["pages/index.twig"];

if (is_home()) {
	array_unshift($templates, "pages/front-page.twig", "pages/home.twig");
}

Timber::render($templates, $context);
