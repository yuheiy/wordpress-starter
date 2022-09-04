<?php

namespace WordPressStarter\Theme\MyTheme;

use Timber;

$context = Timber::context();
$context["title"] = "Search results for " . get_search_query();
$context["posts"] = new Timber\PostQuery();

Timber::render("search.twig", $context);
