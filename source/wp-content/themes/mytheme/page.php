<?php

namespace WordPressStarter\Theme\MyTheme;

use Timber;

$context = Timber::context();

$timber_post = new Timber\Post();
$context["post"] = $timber_post;
Timber::render(["page-" . $timber_post->post_name . ".twig", "page.twig"], $context);
