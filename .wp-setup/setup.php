<?php

require_once __DIR__ . "/helpers.php";

cleanup();
language();
theme();
plugin();
option();
rewrite();
about();
news();
// site_menu();

function cleanup()
{
	$post_ids = wp("post list --post_type=post --format=ids");
	$page_ids = wp("post list --post_type=page --format=ids");

	wp(sprintf("post delete %s", implode(" ", [$post_ids, $page_ids])));
}

function language()
{
	$language = "ja";

	wp(sprintf("language core install %s --activate", $language));

	$plugins = wp("plugin list --format=json");
	$plugins = json_decode($plugins, true);

	foreach ($plugins as $plugin) {
		wp(sprintf("language plugin install %s %s", $plugin["name"], $language));
	}
}

function theme()
{
	wp("theme activate mytheme");
}

function plugin()
{
	$plugins = wp("plugin list --format=json");
	$plugins = json_decode($plugins, true);
	$exclude_plugins = ["akismet", "hello"];

	foreach ($plugins as $plugin) {
		if (!in_array($plugin["name"], $exclude_plugins) && $plugin["status"] === "inactive") {
			wp(sprintf("plugin activate %s", $plugin["name"]));
		}
	}
}

function option()
{
	$options = [
		"blogname" => "wordpress-starter",
		"blogdescription" => "WordPressテーマ構築のための開発環境",
		"timezone_string" => "Asia/Tokyo",
		"date_format" => "Y年n月j日",
		"time_format" => "H:i",
		"site_icon" => fixture_id("site-icon.png"),
	];

	foreach ($options as $key => $value) {
		wp(sprintf('option update %s "%s" --format=json', $key, addslashes(json_encode($value))));
	}
}

function rewrite()
{
	wp('rewrite structure "/%postname%/"');
	wp("rewrite flush --hard");
}

function about()
{
	$post = [
		"post_title" => "私たちについて",
		"post_name" => "about",
		"post_type" => "page",
		"content" => fixture_path("about-content.txt"),
		"meta" => [
			"_thumbnail_id" => image_id(1920, ceil(1920 * (9 / 16))),
		],
	];

	create_post($post);
}

function news()
{
	$category_terms = [
		["イベント", "event"],
		["メディア", "media"],
		["プレスリリース", "press-release"],
	];

	foreach ($category_terms as $term) {
		wp(sprintf('term create mytheme_news_category %s --slug="%s"', $term[0], $term[1]));
	}

	$posts = [
		[
			"post_title" => "睡そうに眼をこすってのぞいて",
			"post_type" => "mytheme_news",
			"content" => fixture_path("news-content.txt"),
			"tax" => [
				"mytheme_news_category" => ["イベント"],
			],
			"meta" => [
				"_thumbnail_id" => image_id(1920, ceil(1920 * (9 / 16))),
			],
		],
		[
			"post_title" => "黒いびろうどばかりひかっていました",
			"post_type" => "mytheme_news",
			"content" => fixture_path("news-content.txt"),
			"tax" => [
				"mytheme_news_category" => ["メディア"],
			],
			"meta" => [
				"_thumbnail_id" => image_id(1920, ceil(1920 * (9 / 16))),
			],
		],
	];

	foreach ($posts as $post) {
		create_post($post);
	}
}

function site_menu()
{
	$menu_name = "サイト";
	$menu_location = "site_menu";

	wp(sprintf('menu create "%s"', $menu_name));
	wp(sprintf('menu location assign "%s" %s', $menu_name, $menu_location));

	// wp(sprintf('menu item add-post "%s" %s', $menu_name, $post_id));
}
