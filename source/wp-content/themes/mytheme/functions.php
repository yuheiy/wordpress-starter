<?php

namespace WordPressStarter\Theme;

require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/inc/admin.php";
require_once __DIR__ . "/inc/blocks.php";
require_once __DIR__ . "/inc/news.php";
require_once __DIR__ . "/inc/template-tags.php";
require_once __DIR__ . "/inc/timber.php";

if (!isset($content_width)) {
	$content_width = 1280;
}

add_action("after_setup_theme", function () {
	load_theme_textdomain("mytheme", __DIR__ . "/languages");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");

	// register_nav_menus([
	// 	"primary" => "Primary",
	// ]);

	add_theme_support("html5", [
		"comment-form",
		"comment-list",
		"search-form",
		"gallery",
		"caption",
		"style",
		"script",
		"navigation-widgets",
	]);

	add_theme_support("customize-selective-refresh-widgets");

	add_theme_support("editor-styles");
	add_editor_style();

	add_theme_support("align-wide");
	add_theme_support("responsive-embeds");
});

add_filter(
	"script_loader_tag",
	function ($tag, $handle, $src) {
		if ($handle === "mytheme-script") {
			$tag = sprintf(
				"<script defer src='%s' id='%s-js'></script>\n",
				$src,
				esc_attr($handle)
			);
		}

		return $tag;
	},
	10,
	3
);

add_action("wp_enqueue_scripts", function () {
	$script_info = include __DIR__ . "/build/index.asset.php";

	wp_enqueue_style(
		"mytheme-style",
		get_template_directory_uri() . "/build/index.css",
		[],
		$script_info["version"]
	);

	wp_enqueue_script(
		"mytheme-script",
		get_template_directory_uri() . "/build/index.js",
		$script_info["dependencies"],
		$script_info["version"]
	);
});

add_filter("should_load_separate_core_block_assets", "__return_true");

add_filter("body_class", function ($classes) {
	if (is_page()) {
		$classes[] = "page-" . basename(get_permalink());
	}

	return $classes;
});

// merged to core 6.1
// https://github.com/WordPress/performance/issues/86#issuecomment-1151244992
add_filter("wp_get_attachment_image_attributes", function ($attrs) {
	$attrs["decoding"] = "async";
	return $attrs;
});

add_filter(
	"wp_lazy_loading_enabled",
	function ($default, $tag_name, $context) {
		if ($tag_name === "img") {
			return false;
		}

		return $default;
	},
	10,
	3
);

add_filter("show_admin_bar", "__return_false");
