<?php

namespace WordPressStarter\Theme;

use Timber;

$context = Timber::context();
$context["title"] = "Search results for " . get_search_query();
$context["posts"] = new Timber\PostQuery();

Timber::render("search.twig", $context);
