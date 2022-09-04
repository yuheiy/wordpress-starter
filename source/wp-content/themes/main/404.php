<?php

namespace WordPressStarter\Theme\Main;

use Timber;

$context = Timber::context();
Timber::render("404.twig", $context);
