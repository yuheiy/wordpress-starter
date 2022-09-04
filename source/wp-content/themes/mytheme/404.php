<?php

namespace WordPressStarter\Theme\MyTheme;

use Timber;

$context = Timber::context();
Timber::render("404.twig", $context);
