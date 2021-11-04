<?php

if (!isset($content_width)) {
	$content_width = 1280;
}

add_action("after_setup_theme", function () {
	add_theme_support("automatic-feed-links");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");

	add_image_size("full-width", 1980, 9999);
	add_image_size("ogp", 1200, 630, true);

	register_nav_menus([
		"page-foot-menu" => "フッターメニュー",
	]);

	add_theme_support("html5", [
		"search-form",
		"comment-form",
		"comment-list",
		"gallery",
		"caption",
		"style",
		"script",
		"navigation-widgets",
	]);

	add_theme_support("customize-selective-refresh-widgets");

	add_theme_support("responsive-embeds");
});

add_filter(
	"script_loader_tag",
	function ($tag, $handle, $src) {
		if ("mytheme-main-script" === $handle) {
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
	$asset_file = include get_theme_file_path("/build/main.ts.asset.php");

	wp_enqueue_style(
		"mytheme-main-style",
		get_theme_file_uri("/build/main.ts.css"),
		[],
		$asset_file["version"]
	);

	wp_enqueue_script(
		"mytheme-main-script",
		get_theme_file_uri("/build/main.ts.js"),
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
	$default_image_url = get_theme_file_uri("assets/images/ogp.png");

	// Set our final defaults.
	$card_title = $default_title;
	$card_description = $default_base_description;
	$card_long_description = $default_base_description;
	$card_url = $default_url;
	$card_image = $default_image_url;
	$card_type = $default_type;
	$card_twitter_card = "summary_large_image";

	$locale = get_locale();

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
		$card_long_description = sprintf(
			esc_html__("Search results for %s.", "_s"),
			$search_term
		);
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
	<meta name="description" content="<?php echo esc_attr(
 	$card_long_description
 ); ?>" />
 	<meta name="twitter:card" content="<?php echo esc_attr(
  	$card_twitter_card
  ); ?>">
	<meta property="og:title" content="<?php echo esc_attr($card_title); ?>" />
	<meta property="og:description" content="<?php echo esc_attr(
 	$card_description
 ); ?>" />
	<meta property="og:url" content="<?php echo esc_url($card_url); ?>" />
	<?php if ($card_image): ?>
		<meta property="og:image" content="<?php echo esc_url($card_image); ?>" />
	<?php endif; ?>
	<meta property="og:site_name" content="<?php echo esc_attr(
 	$default_title
 ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr($card_type); ?>" />
	<meta property="og:locale" content="<?= esc_attr($locale) ?>">
	<?php
});

add_filter("xmlrpc_enabled", "__return_false");

require get_theme_file_path("/inc/timber.php");
require get_theme_file_path("/templates/blocks/editor.php");
require get_theme_file_path("/inc/work.php");
