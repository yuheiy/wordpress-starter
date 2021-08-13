<?php

$templates = [
	"templates/search.twig",
	"templates/archive.twig",
	"templates/index.twig",
];

$context = Timber::context();
$context["title"] = "Search results for " . get_search_query();
$context["posts"] = new Timber\PostQuery();

Timber::render($templates, $context);
