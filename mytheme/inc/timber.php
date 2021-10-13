<?php

Timber::$dirname = ["templates"];

add_filter("timber/twig", function ($twig) {
	$twig->addFilter(
		new Timber\Twig_Filter("is_external", function ($url) {
			return Timber\URLHelper::is_external($url);
		})
	);

	return $twig;
});

add_action("timber/context", function ($context) {
	$context["work_post_type"] = new MyPostType("mytheme_work");

	$context["page_foot_menu"] = new Timber\Menu("page-foot-menu");

	return $context;
});

class MyPostType extends Timber\PostType
{
	public function link()
	{
		return get_post_type_archive_link($this->slug);
	}
}
