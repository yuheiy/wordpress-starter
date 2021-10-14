<?php

foreach (glob(__DIR__ . "/*/index.php") as $filename) {
	require $filename;
}

add_action("enqueue_block_editor_assets", function () {
	$asset_file = include get_template_directory() .
		"/build/blocks.ts.asset.php";

	wp_enqueue_style(
		"mytheme-editor-style",
		get_template_directory_uri() . "/build/blocks.ts.css",
		["wp-edit-blocks"],
		$asset_file["version"]
	);

	wp_enqueue_script(
		"mytheme-editor-script",
		get_template_directory_uri() . "/build/blocks.ts.js",
		$asset_file["dependencies"],
		$asset_file["version"],
		true
	);
});
