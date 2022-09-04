<?php

namespace WordPressStarter\Theme\Main;

add_action("init", function () {
	register_post_type("main_news", [
		"label" => "お知らせ",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
		"rewrite" => ["slug" => "news"],
	]);

	register_taxonomy(
		"main_news_category",
		["main_news"],
		[
			"label" => "カテゴリー",
			"hierarchical" => true,
			"show_in_rest" => true,
			"rewrite" => ["slug" => "news/category"],
		]
	);

	add_rewrite_rule(
		'news/category/([^/]+)/?$',
		'index.php?post_type=main_news&main_news_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (is_post_type_archive("main_news") || is_tax("main_news_category")) {
		$query->set("posts_per_page", 12);
	}
});
