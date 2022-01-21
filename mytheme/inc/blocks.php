<?php

add_action("acf/init", function () {
	if (!function_exists("acf_register_block_type")) {
		return;
	}

	$block_types = [
		[
			"name" => "testimonial",
			"title" => __("Testimonial"),
			"description" => __("A custom testimonial block."),
			"category" => "formatting",
			"icon" => "admin-comments",
			"keywords" => ["testimonial", "quote"],
		],
	];

	foreach ($block_types as $block_type) {
		acf_register_block_type(
			array_merge($block_type, [
				"render_callback" => mytheme_create_block_render_callback($block_type["name"]),
			])
		);
	}
});

function mytheme_create_block_render_callback($block_name)
{
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
}
