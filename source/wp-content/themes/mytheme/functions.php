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

add_action("after_setup_theme", function () {
	load_theme_textdomain("mytheme", get_template_directory() . "/languages");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");

	// register_nav_menus([
	// 	"site-menu" => "サイトメニュー",
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
	$asset_file = include get_template_directory() . "/build/index.asset.php";

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

// https://github.com/WebDevStudios/wd_s/blob/3c94e66861e12632d47a7450c9011dc60e62caff/inc/hooks.php#L228-L358
add_action("wp_head", function () {
	// Set a post global on single posts. This avoids grabbing content from the first post on an archive page.
	if (is_singular()) {
		global $post;
	}

	// Get the post content.
	$post_content = !empty($post) ? $post->post_content : "";

	// Strip all tags from the post content we just grabbed.
	$default_content = $post_content
		? wp_strip_all_tags(strip_shortcodes($post_content))
		: $post_content;

	// Set our default title.
	$default_title = get_bloginfo("name");

	// Set our default URL.
	$default_url = get_permalink();

	// Set our base description.
	$default_base_description = get_bloginfo("description")
		? get_bloginfo("description")
		: esc_html__("Visit our website to learn more.", "_s");

	// Set the card type.
	$default_type = "article";

	// Get the base image.
	$default_image_url = get_template_directory_uri() . "build/images/ogp.png";

	// Set the twitter card type.
	$default_twitter_card = "summary_large_image";

	// Get the locale.
	$default_locale = get_locale();

	if ($default_locale === "ja") {
		$default_locale = "ja_JP";
	}

	// Set our final defaults.
	$card_title = $default_title;
	$card_description = $default_base_description;
	$card_url = $default_url;
	$card_image = $default_image_url;
	$card_type = $default_type;

	// Let's start overriding!
	// All singles.
	if (is_singular()) {
		if (has_post_thumbnail()) {
			$card_image = get_the_post_thumbnail_url();
		}
	}

	// Single posts/pages that aren't the front page.
	if (is_singular() && !is_front_page()) {
		$card_title = get_the_title() . " - " . $default_title;
		$card_description = $default_content
			? wp_trim_words($default_content, 53, "...")
			: $default_base_description;
		$card_long_description = $default_content
			? wp_trim_words($default_content, 140, "...")
			: $default_base_description;
	}

	// Categories, Tags, and Custom Taxonomies.
	if (is_category() || is_tag() || is_tax()) {
		$term_name = single_term_title("", false);
		$card_title = $term_name . " - " . $default_title;
		$specify = is_category()
			? esc_html__("categorized in", "_s")
			: esc_html__("tagged with", "_s");
		$queried_object = get_queried_object();
		$card_url = get_term_link($queried_object);
		$card_type = "website";

		// Translators: get the term name.
		$card_long_description = sprintf(
			esc_html__('Posts %1$s %2$s.', "_s"),
			$specify,
			$term_name
		);
		$card_description = $card_long_description;
	}

	// Search results.
	if (is_search()) {
		$search_term = get_search_query();
		$card_title = $search_term . " - " . $default_title;
		$card_url = get_search_link($search_term);
		$card_type = "website";

		// Translators: get the search term.
		$card_long_description = sprintf(esc_html__("Search results for %s.", "_s"), $search_term);
		$card_description = $card_long_description;
	}

	if (is_home()) {
		$posts_page = get_option("page_for_posts");
		$card_title = get_the_title($posts_page) . " - " . $default_title;
		$card_url = get_permalink($posts_page);
		$card_type = "website";
	}

	// Front page.
	if (is_front_page()) {
		$front_page = get_option("page_on_front");
		$card_title = $front_page
			? get_the_title($front_page) . " - " . $default_title
			: $default_title;
		$card_url = get_home_url();
		$card_type = "website";
	}

	// Post type archives.
	if (is_post_type_archive()) {
		$post_type_name = get_post_type();
		$card_title = $post_type_name . " - " . $default_title;
		$card_url = get_post_type_archive_link($post_type_name);
		$card_type = "website";
	}

	// Media page.
	if (is_attachment()) {
		$attachment_id = get_the_ID();
		$card_image = wp_attachment_is_image($attachment_id)
			? wp_get_attachment_image_url($attachment_id, "full")
			: $card_image;
	}
	?>
	<meta name="description" content="<?php echo esc_attr($card_description); ?>">
	<meta property="og:title" content="<?php echo esc_attr($card_title); ?>">
	<meta property="og:description" content="<?php echo esc_attr($card_description); ?>">
	<meta property="og:url" content="<?php echo esc_url($card_url); ?>">
	<meta property="og:image" content="<?php echo esc_url($card_image); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr($default_title); ?>">
	<meta property="og:type" content="<?php echo esc_attr($card_type); ?>">
	<meta property="og:locale" content="<?= esc_attr($default_locale) ?>">
	<meta name="twitter:card" content="<?php echo esc_attr($default_twitter_card); ?>">
	<?php
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

require get_template_directory() . "/inc/acf.php";
require get_template_directory() . "/inc/acp.php";
require get_template_directory() . "/inc/timber.php";

require get_template_directory() . "/inc/news.php";

require get_template_directory() . "/inc/template-tags.php";
