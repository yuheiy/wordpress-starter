<?php

declare(strict_types=1);

// canonical
add_action("wp_head", function (): void {
	if (is_singular()) {
		// デフォルトで出力される
		// https://github.com/WordPress/WordPress/blob/106a6ba9e1e7bfc15a177aeef3e140433d7e47df/wp-includes/link-template.php#L3830-L3852
		return;
	}

	if (is_home()) {
		$url = home_url("/");
	}

	if (isset($url)) {
		echo sprintf('<link rel="canonical" href="%s">', esc_url($url)) . "\n";
	}
});

// ogp
add_action("wp_head", function (): void {
	if (is_singular()) {
		$title = get_the_title();
	} elseif (
		($postType = get_query_var("post_type")) &&
		is_post_type_archive($postType)
	) {
		$title = get_post_type_object($postType)->label;
	} else {
		$title = get_bloginfo("name");
	}

	$description = get_bloginfo("description");

	if (is_single()) {
		$type = "article";
	} else {
		$type = "website";
	}

	$manifest = webpackManifest();
	$image = home_url($manifest["ogp.png"]);

	$siteName = get_bloginfo("name");

	$locale = get_locale();

	if (is_home()) {
		$url = home_url("/");
	} elseif (is_singular()) {
		$url = get_permalink();
	} else {
		$url = Timber\URLHelper::get_current_url();
	}

	$twitterCard = "summary_large_image";

	foreach (
		[
			sprintf(
				'<meta name="description" content="%s">',
				esc_attr($description)
			),
			sprintf(
				'<meta name="twitter:card" content="%s">',
				esc_attr($twitterCard)
			),
			sprintf(
				'<meta property="og:title" content="%s">',
				esc_attr($title)
			),
			sprintf('<meta property="og:type" content="%s">', esc_attr($type)),
			sprintf('<meta property="og:image" content="%s">', esc_url($image)),
			sprintf('<meta property="og:url" content="%s">', esc_url($url)),
			sprintf(
				'<meta property="og:description" content="%s">',
				esc_attr($description)
			),
			sprintf(
				'<meta property="og:site_name" content="%s">',
				esc_attr($siteName)
			),
			sprintf(
				'<meta property="og:locale" content="%s">',
				esc_attr($locale)
			),
		]
		as $html
	) {
		echo $html . "\n";
	}
});

// resources
add_action("wp_head", function (): void {
	$manifest = webpackManifest();

	foreach (
		[
			sprintf(
				'<link rel="icon" href="%s">',
				esc_url($manifest["favicon.ico"])
			),
			sprintf(
				'<link rel="apple-touch-icon" href="%s">',
				esc_url($manifest["apple-touch-icon.png"])
			),
			array_key_exists("main.css", $manifest)
				? sprintf(
					'<link rel="stylesheet" href="%s">',
					esc_url($manifest["main.css"])
				)
				: null,
			sprintf(
				'<script defer src="%s"></script>',
				esc_url($manifest["main.js"])
			),
		]
		as $html
	) {
		if ($html) {
			echo $html . "\n";
		}
	}
});

add_filter("document_title_parts", function (array $title): array {
	if (array_key_exists("tagline", $title)) {
		unset($title["tagline"]);
	}
	return $title;
});

add_filter("document_title_separator", function (): string {
	return "|";
});
