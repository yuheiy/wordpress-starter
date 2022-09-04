<?php

namespace WordPressStarter\Theme;

use Timber;

$context = Timber::context();
Timber::render("404.twig", $context);
