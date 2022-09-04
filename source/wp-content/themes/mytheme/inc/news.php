<?php

namespace WordPressStarter\Theme;

add_action("init", function () {
	register_post_type("mytheme_news", [
		"label" => "お知らせ",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
		"rewrite" => ["slug" => "news"],
	]);

	register_taxonomy(
		"mytheme_news_category",
		["mytheme_news"],
		[
			"label" => "カテゴリー",
			"hierarchical" => true,
			"show_in_rest" => true,
			"rewrite" => ["slug" => "news/category"],
		]
	);

	add_rewrite_rule(
		'news/category/([^/]+)/?$',
		'index.php?post_type=mytheme_news&mytheme_news_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (is_post_type_archive("mytheme_news") || is_tax("mytheme_news_category")) {
		$query->set("posts_per_page", 12);
	}
});
