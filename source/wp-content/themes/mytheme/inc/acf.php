<?php

add_action("acf/init", function () {
	$parent = acf_add_options_page([
		"page_title" => "テーマ全般設定",
		"menu_title" => "テーマ設定",
		"menu_slug" => "theme-general-settings",
		"position" => "5.5",
		"redirect" => false,
	]);

	acf_add_options_page([
		"page_title" => "ホーム",
		"menu_slug" => "theme-home-settings",
		"parent_slug" => $parent["menu_slug"],
	]);
});

add_action("acf/init", function () {
	$block_types = [
		[
			"name" => "testimonial",
			"title" => "お客様の声",
			"category" => "formatting",
			"icon" => "admin-comments",
			"keywords" => ["testimonial", "quote"],
		],
	];

	$create_render_callback = function ($block_name) {
		return function ($block, $content = "", $is_preview = false) use ($block_name) {
			$context = Timber::context();

			// Store block values.
			$context["block"] = $block;

			// Store field values.
			$context["fields"] = get_fields();

			// Store $is_preview value.
			$context["is_preview"] = $is_preview;

			// Render the block.
			Timber::render("blocks/" . $block_name . ".twig", $context);
		};
	};

	foreach ($block_types as $block_type) {
		acf_register_block_type(
			array_merge($block_type, [
				"render_callback" => $create_render_callback($block_type["name"]),
			])
		);
	}
});
