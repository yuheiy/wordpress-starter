<?php

namespace Wpstarter\Theme;

use Timber;

$context = Timber::context();
Timber::render("404.twig", $context);
