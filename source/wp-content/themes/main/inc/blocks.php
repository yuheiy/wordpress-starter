<?php

namespace WordPressStarter\Theme\Main;

use Timber;

add_filter(
	"allowed_block_types_all",
	function ($allowed_block_types, $block_editor_context) {
		return $allowed_block_types;
	},
	10,
	2
);

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
