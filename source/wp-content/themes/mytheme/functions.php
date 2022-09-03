<?php

$composer_autoload = __DIR__ . "/vendor/autoload.php";
if (file_exists($composer_autoload)) {
	require_once $composer_autoload;
}

if (!isset($content_width)) {
	$content_width = 1280;
}

remove_action("wp_head", "rest_output_link_wp_head", 10);
remove_action("wp_head", "feed_links_extra", 3);
remove_action("wp_head", "rsd_link");
remove_action("wp_head", "wlwmanifest_link");
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_head", "wp_generator");
remove_action("wp_head", "wp_shortlink_wp_head", 10);
remove_action("wp_print_styles", "print_emoji_styles");
remove_action("wp_head", "wp_oembed_add_discovery_links");

add_filter("emoji_svg_url", "__return_false");
add_filter("show_admin_bar", "__return_false");
add_filter("xmlrpc_enabled", "__return_false");
add_filter("should_load_separate_core_block_assets", "__return_true");

add_filter("jetpack_enable_open_graph", "__return_true");
// https://jetpack.com/blog/add-a-default-image-open-graph-tag-on-home-page/

add_action("after_setup_theme", function () {
	load_theme_textdomain("mytheme", __DIR__ . "/languages");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");

	// register_nav_menus([
	// 	"site_menu" => "サイトメニュー",
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
		if ("mytheme-script" === $handle) {
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
	$asset_file = include __DIR__ . "/build/index.asset.php";

	wp_enqueue_style(
		"mytheme-style",
		get_template_directory_uri() . "/build/index.css",
		[],
		$asset_file["version"]
	);

	wp_enqueue_script(
		"mytheme-script",
		get_template_directory_uri() . "/build/index.js",
		$asset_file["dependencies"],
		$asset_file["version"]
	);
});

add_action("admin_enqueue_scripts", function () {
	wp_enqueue_style("mytheme-admin", get_template_directory_uri() . "/admin.css");
});

add_action("admin_menu", function () {
	remove_menu_page("edit.php");
	remove_menu_page("edit-comments.php");
});

add_filter("body_class", function ($classes) {
	if (is_page()) {
		$classes[] = "page-" . basename(get_permalink());
	}

	return $classes;
});

add_filter("wp_lazy_loading_enabled", "__return_false");

add_filter("wp_get_attachment_image_attributes", function ($attrs) {
	$attrs["decoding"] = "async";
	return $attrs;
});

add_filter(
	"allowed_block_types_all",
	function ($allowed_block_types, $block_editor_context) {
		return $allowed_block_types;
	},
	10,
	2
);

add_filter("big_image_size_threshold", function () {
	return 1920 * 2;
});

require_once __DIR__ . "/inc/acf.php";
require_once __DIR__ . "/inc/acp.php";
require_once __DIR__ . "/inc/timber.php";

require_once __DIR__ . "/inc/news.php";

require_once __DIR__ . "/inc/template-tags.php";
