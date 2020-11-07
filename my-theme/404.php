<?php

declare(strict_types=1);

$context = defaultTimberContext();

Timber::render('404.twig', $context);
