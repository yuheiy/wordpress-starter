<?php

declare(strict_types=1);

add_action("init", function (): void {
	register_post_type("news", [
		"label" => "ニュース",
		"public" => true,
		"supports" => ["title", "editor"],
		"has_archive" => true,
		"show_in_rest" => true,
	]);

	register_taxonomy(
		"news_category",
		["news"],
		[
			"label" => "カテゴリー",
			"rewrite" => ["slug" => "news/category"],
			"show_in_rest" => true,
			"hierarchical" => true,
		]
	);

	add_rewrite_rule(
		'news/category/([^/]+)/?$',
		'index.php?post_type=news&news_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function (WP_Query $query): void {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (is_post_type_archive("news") || is_tax("news_category")) {
		$query->set("posts_per_page", 12);
	}
});
