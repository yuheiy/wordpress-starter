<?php

delete_sample_posts();
theme();
options();
post();
work();
page_foot_menu();

function delete_sample_posts()
{
	foreach (
		[
			wp("post list --name=hello-world --post_type=post --format=ids"),
			wp("post list --name=sample-page --post_type=page --format=ids"),
		]
		as $post_id
	) {
		wp(sprintf("post delete %s", $post_id));
	}
}

function theme()
{
	wp("theme activate mytheme");
}

function options()
{
	wp('option update blogname "boilerplate-wordpress"');

	$site_icon_attachment_id = wp(
		sprintf("media import %s --porcelain", fixture("site-icon-512x512.png"))
	);
	wp(sprintf('option update site_icon "%s"', $site_icon_attachment_id));

	wp('rewrite structure "/%postname%/"');
}

function post()
{
	$thumbnail_attachment_id = wp(
		sprintf(
			'media import "%s" --porcelain',
			fixture("post-thumbnail-1280x720.jpg")
		)
	);

	foreach (
		[
			["羅生門", ["芥川竜之介", "大正"]],
			["銀河鉄道の夜", ["宮沢賢治", "昭和"]],
			["走れメロス", ["太宰治", "昭和"]],
		]
		as list($title, $tags)
	) {
		$meta = [
			"_thumbnail_id" => $thumbnail_attachment_id,
		];

		wp(
			sprintf(
				'post create %s --post_title="%s" --post_status=publish --tags_input="%s" --meta_input="%s"',
				fixture("post-content.txt"),
				$title,
				implode(",", $tags),
				addslashes(json_encode($meta))
			)
		);
	}
}

function work()
{
	foreach (["foo", "bar", "baz"] as $title) {
		wp(
			sprintf(
				'post create %s --post_title="%s" --post_status=publish --post_type=mytheme_work',
				fixture("post-content-work.txt"),
				$title
			)
		);
	}
}

function page_foot_menu()
{
	$menu_name = "フッター";
	$menu_location = "page-foot-menu";

	wp(sprintf('menu create "%s"', $menu_name));
	wp(sprintf('menu location assign "%s" %s', $menu_name, $menu_location));

	foreach (
		explode(" ", wp("post list --post_type=post --format=ids"))
		as $post_id
	) {
		wp(sprintf('menu item add-post "%s" %s', $menu_name, $post_id));
	}
}

// helpers

function wp($args)
{
	$command = sprintf("wp %s", $args);
	echo $command . "\n";

	$output = null;
	$result_code = null;
	exec($command, $output, $result_code);
	$output = implode("\n", $output);

	if ($result_code !== 0) {
		throw new Exception($output, $result_code);
	}

	if ($output) {
		echo $output . "\n";
	}

	return $output;
}

function fixture($path)
{
	return __DIR__ . "/fixtures/" . $path;
}
