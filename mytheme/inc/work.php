<?php

add_action("init", function () {
	register_post_type("mytheme_work", [
		"label" => "実績",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
		"rewrite" => ["slug" => "work"],
	]);

	register_taxonomy(
		"mytheme_work_category",
		["mytheme_work"],
		[
			"label" => "カテゴリー",
			"show_in_rest" => true,
			"rewrite" => ["slug" => "work/category"],
		]
	);

	add_rewrite_rule(
		'work/category/([^/]+)/?$',
		'index.php?post_type=mytheme_work&mytheme_work_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (
		is_post_type_archive("mytheme_work") ||
		is_tax("mytheme_work_category")
	) {
		$query->set("posts_per_page", 12);
	}
});
