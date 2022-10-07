<?php

namespace WordPressStarter\Theme;

add_action("admin_enqueue_scripts", function () {
	wp_enqueue_style("mytheme-admin", get_template_directory_uri() . "/admin.css");
});

add_action("admin_menu", function () {
	remove_menu_page("edit-comments.php");
});

add_filter("big_image_size_threshold", function () {
	return 2 * 1920;
});

add_action("acf/init", function () {
	// $parent = acf_add_options_page([
	// 	"page_title" => "テーマ全般設定",
	// 	"menu_title" => "テーマ設定",
	// 	"menu_slug" => "theme-general-settings",
	// 	"position" => "5.5",
	// 	"redirect" => false,
	// ]);

	// acf_add_options_page([
	// 	"page_title" => "ホーム",
	// 	"menu_slug" => "theme-home-settings",
	// 	"parent_slug" => $parent["menu_slug"],
	// ]);
});
