<?php

Timber::$dirname = ["templates", "assets"];

if (!isset($content_width)) {
	$content_width = 1280;
}

add_action("after_setup_theme", function () {
	add_theme_support("automatic-feed-links");

	add_theme_support("title-tag");

	add_theme_support("post-thumbnails");
	add_image_size("ogp", 1200, 630);

	register_nav_menus([
		"page-head-menu" => "Menu in page-head",
		"page-foot-menu" => "Menu in page-foot",
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
		if (
			in_array(
				$handle,
				["mytheme-vite-script", "mytheme-main-script"],
				true
			)
		) {
			$tag = sprintf(
				"<script type='module' src='%s' id='%s-js'></script>\n",
				$src,
				esc_attr($handle)
			);
		}

		return $tag;
	},
	10,
	3
);

if (wp_get_environment_type() === "local" && SCRIPT_DEBUG) {
	add_action("wp_enqueue_scripts", function () {
		wp_enqueue_script(
			"mytheme-vite-script",
			"http://localhost:3000/@vite/client",
			[],
			null
		);

		wp_enqueue_script(
			"mytheme-main-script",
			"http://localhost:3000/mytheme/assets/main.ts",
			["mytheme-vite-script"],
			null
		);
	});
} else {
	add_action("wp_enqueue_scripts", function () {
		$manifest = mytheme_vite_manifest();

		foreach (
			$manifest["mytheme/assets/main.ts"]["css"]
			as $key => $css_path
		) {
			wp_enqueue_style(
				sprintf("mytheme-main-%s-style", $key),
				get_theme_file_uri("assets/build/" . $css_path),
				[],
				null
			);
		}

		wp_enqueue_script(
			"mytheme-main-script",
			get_theme_file_uri(
				"assets/build/" . $manifest["mytheme/assets/main.ts"]["file"]
			),
			[],
			null
		);
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

	$image = get_theme_file_uri("assets/images/ogp.png");

	$site_name = get_bloginfo("name");

	$locale = get_locale();

	$url = Timber\URLHelper::get_current_url();

	$twitter_card = "summary_large_image";
	?>
	<meta name="description" content="<?= esc_attr($description) ?>">
	<meta name="twitter:card" content="<?= esc_attr($twitter_card) ?>">
	<meta property="og:title" content="<?= esc_attr($title) ?>">
	<meta property="og:type" content="<?= esc_attr($type) ?>">
	<meta property="og:image" content="<?= esc_url($image) ?>">
	<meta property="og:url" content="<?= esc_url($url) ?>">
	<meta property="og:description" content="<?= esc_attr($description) ?>">
	<meta property="og:site_name" content="<?= esc_attr($site_name) ?>">
	<meta property="og:locale" content="<?= esc_attr($locale) ?>">
	<?php
});

add_action("timber/context", function ($context) {
	$context["feature_post_type"] = new MyPostType("mytheme_feature");

	$context["page_head_menu"] = new Timber\Menu("page-head-menu");

	$context["page_foot_menu"] = new Timber\Menu("page-foot-menu");

	return $context;
});

class MyPostType extends Timber\PostType
{
	public function archive_link()
	{
		return get_post_type_archive_link($this->slug);
	}
}

function mytheme_vite_manifest()
{
	return json_decode(
		file_get_contents(get_theme_file_path("assets/build/manifest.json")),
		true
	);
}

require get_theme_file_path("inc/feature.php");
