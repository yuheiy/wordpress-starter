<?php

namespace WordPressStarter\Theme;

use Timber;

add_action("init", function () {
	$json = file_get_contents(dirname(__DIR__) . "/data/lzb-export-blocks.json");
	$exported_blocks = json_decode($json, true);

	foreach ($exported_blocks as $block) {
		lazyblocks()->add_block($block);
	}
});

add_filter(
	"allowed_block_types_all",
	function ($allowed_block_types, $block_editor_context) {
		return $allowed_block_types;
	},
	10,
	2
);

foreach (glob(dirname(__DIR__) . "/views/blocks/lazyblock/*", GLOB_ONLYDIR) as $dir) {
	$block_name = basename($dir);

	add_filter(
		"lazyblock/$block_name/frontend_callback",
		__NAMESPACE__ . "\\frontend_block_output",
		10,
		2
	);
	add_filter(
		"lazyblock/$block_name/editor_callback",
		__NAMESPACE__ . "\\editor_block_output",
		10,
		2
	);
}

function frontend_block_output($output, $attributes)
{
	ob_start();

	$block_name = $attributes["lazyblock"]["slug"];

	$context = Timber::context();
	$context["attributes"] = $attributes;

	Timber::render("blocks/$block_name/block.twig", $context);

	return ob_get_clean();
}

function editor_block_output($output, $attributes)
{
	ob_start();

	$block_name = $attributes["lazyblock"]["slug"];

	$context = Timber::context();
	$context["attributes"] = $attributes;

	Timber::render("blocks/$block_name/block.twig", $context);

	return ob_get_clean();
}
