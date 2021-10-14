<?php

foreach (glob(__DIR__ . "/*/index.php") as $filename) {
	require $filename;
}

add_action("enqueue_block_editor_assets", function () {
	$asset_file = include get_theme_file_path("/build/editor.ts.asset.php");

	wp_enqueue_style(
		"mytheme-editor-style",
		get_theme_file_uri("/build/editor.ts.css"),
		["wp-edit-blocks"],
		$asset_file["version"]
	);

	wp_enqueue_script(
		"mytheme-editor-script",
		get_theme_file_uri("/build/editor.ts.js"),
		$asset_file["dependencies"],
		$asset_file["version"],
		true
	);
});
