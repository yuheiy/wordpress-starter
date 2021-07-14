<?php

add_action("init", function () {
	register_post_type("feature", [
		"label" => "特集",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
	]);

	register_taxonomy(
		"feature_category",
		["feature"],
		[
			"label" => "カテゴリー",
			"rewrite" => ["slug" => "feature/category"],
			"show_in_rest" => true,
			"hierarchical" => true,
		]
	);

	add_rewrite_rule(
		'feature/category/([^/]+)/?$',
		'index.php?post_type=feature&feature_category=$matches[1]',
		"top"
	);
});

add_action("pre_get_posts", function ($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	if (is_post_type_archive("feature") || is_tax("feature_category")) {
		$query->set("posts_per_page", 12);
	}
});
