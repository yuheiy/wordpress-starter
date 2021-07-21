<?php

Timber::$dirname = ["templates", "assets"];

// remove emoji outputs
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
add_filter("emoji_svg_url", "__return_false");

if (!isset($content_width)) {
	$content_width = 1280;
}

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

if (WP_DEBUG && SCRIPT_DEBUG) {
	add_action("wp_head", function () {
		?>
		<script type="module" src="http://localhost:3000/@vite/client"></script>
		<script type="module" src="http://localhost:3000/theme/assets/main.ts"></script>
		<?php
	});
} else {
	add_action("wp_head", function () {
		$manifest = vite_manifest();

		foreach ($manifest["theme/assets/main.ts"]["css"] as $css_path) { ?>
			<link rel="stylesheet" href="<?= esc_attr(
   	get_theme_file_uri("build/" . $css_path)
   ) ?>">
			<?php }
		?>
		<script type="module" src="<?= esc_attr(
  	get_theme_file_uri("build/" . $manifest["theme/assets/main.ts"]["file"])
  ) ?>"></script>
		<?php
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
	$feature_post_type = new MyPostType("feature");
	$context["feature_post_type"] = $feature_post_type;

	$privacy_policy_post = Timber::get_post([
		"post_type" => "page",
		"title" => "プライバシーポリシー",
	]);

	$context["page_head_menu"] = [
		[
			"label" => $feature_post_type->label,
			"link" => $feature_post_type->archive_link(),
		],
	];

	$context["page_foot_menu"] = [
		[
			"label" => $feature_post_type->label,
			"link" => $feature_post_type->archive_link(),
		],
		[
			"label" => $privacy_policy_post->title,
			"link" => $privacy_policy_post->link,
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
