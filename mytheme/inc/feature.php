<?php

add_action("init", function () {
	register_post_type("mytheme_feature", [
		"label" => "特集",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
		"rewrite" => ["slug" => "features"],
	]);

	register_taxonomy(
		"mytheme_feature_category",
		["mytheme_feature"],
		[
			"label" => "カテゴリー",
			"show_in_rest" => true,
			"rewrite" => ["slug" => "features/categories"],
			"hierarchical" => true,
		]
	);

	add_rewrite_rule(
		'features/categories/([^/]+)/?$',
		'index.php?post_type=mytheme_feature&mytheme_feature_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (
		is_post_type_archive("mytheme_feature") ||
		is_tax("mytheme_feature_category")
	) {
		$query->set("posts_per_page", 12);
	}
});
