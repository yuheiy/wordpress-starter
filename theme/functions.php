<?php

Timber::$dirname = ["templates", "assets"];

// remove emoji outputs
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
add_filter("emoji_svg_url", "__return_false");

add_action("after_setup_theme", function () {
	load_theme_textdomain("my-theme", get_theme_file_path("languages"));

	add_theme_support("automatic-feed-links");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");

	add_theme_support("html5", [
		"comment-form",
		"comment-list",
		"gallery",
		"caption",
		"style",
		"script",
		"navigation-widgets",
	]);

	add_theme_support("customize-selective-refresh-widgets");
});

add_action(
	"after_setup_theme",
	function () {
		$GLOBALS["content_width"] = 1280;
	},
	0
);

if (SCRIPT_DEBUG) {
	add_action("wp_head", function () {
		echo '<script type="module" src="http://localhost:3000/@vite/client"></script>' .
			"\n";
		echo '<script type="module" src="http://localhost:3000/theme/assets/main.ts"></script>' .
			"\n";
	});
} else {
	add_action("wp_head", function () {
		$manifest = vite_manifest();

		foreach ($manifest["theme/assets/main.ts"]["css"] as $css_path) {
			echo sprintf(
				'<link rel="stylesheet" href="%s">',
				get_theme_file_uri("build/" . $css_path)
			) . "\n";
		}

		echo sprintf(
			'<script type="module" src="%s"></script>',
			get_theme_file_uri(
				"build/" . $manifest["theme/assets/main.ts"]["file"]
			)
		) . "\n";
	});
}

add_action("wp_head", function () {
	if (!($title = trim(wp_title("", false)))) {
		$title = get_bloginfo("name");
	}

	$description = get_bloginfo("description");

	if (is_single()) {
		$type = "archive";
	} else {
		$type = "website";
	}

	$image = get_theme_file_uri("assets/ogp.png");

	$site_name = get_bloginfo("name");

	$locale = get_locale();

	$url = Timber\URLHelper::get_current_url();

	$twitter_card = "summary_large_image";

	echo sprintf(
		'<meta name="description" content="%s">',
		esc_attr($description)
	) . "\n";

	echo sprintf(
		'<meta name="twitter:card" content="%s">',
		esc_attr($twitter_card)
	) . "\n";

	echo sprintf('<meta property="og:title" content="%s">', esc_attr($title)) .
		"\n";

	echo sprintf('<meta property="og:type" content="%s">', esc_attr($type)) .
		"\n";

	echo sprintf('<meta property="og:image" content="%s">', esc_url($image)) .
		"\n";

	echo sprintf('<meta property="og:url" content="%s">', esc_url($url)) . "\n";

	echo sprintf(
		'<meta property="og:description" content="%s">',
		esc_attr($description)
	) . "\n";

	echo sprintf(
		'<meta property="og:site_name" content="%s">',
		esc_attr($site_name)
	) . "\n";

	echo sprintf(
		'<meta property="og:locale" content="%s">',
		esc_attr($locale)
	) . "\n";
});

add_action("timber/context", function ($context) {
	$feature_post_type = new MyPostType("feature");
	$context["feature_post_type"] = $feature_post_type;

	$privacy_policy_post = Timber::get_post([
		"post_type" => "page",
		"title" => "プライバシーポリシー",
	]);

	$context["menus"] = [
		"page_head" => [
			[
				"label" => $feature_post_type->label,
				"link" => $feature_post_type->archive_link(),
			],
		],
		"page_foot" => [
			[
				"label" => $feature_post_type->label,
				"link" => $feature_post_type->archive_link(),
			],
			[
				"label" => $privacy_policy_post->title,
				"link" => $privacy_policy_post->link,
			],
		],
	];

	return $context;
});

class MyPostType extends Timber\PostType
{
	public function archive_link()
	{
		return get_post_type_archive_link($this->slug);
	}
}

function vite_manifest()
{
	return json_decode(
		file_get_contents(get_theme_file_path("build/manifest.json")),
		true
	);
}

require get_theme_file_path("inc/feature.php");
