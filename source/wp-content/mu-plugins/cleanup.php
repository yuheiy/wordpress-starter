<?php

namespace WordPressStarter\MU_Plugins\Cleanup;

add_action("init", __NAMESPACE__ . "\init");

function init()
{
	wp_obscurity();
	disable_emojis();
	disable_extra_rss();
	disable_recent_comments_css();
	disable_gallery_css();
}

function wp_obscurity()
{
	add_filter("the_generator", "__return_false");
	remove_action("wp_head", "rsd_link");
	remove_action("wp_head", "wlwmanifest_link");
	remove_action("wp_head", "wp_generator");
	remove_action("wp_head", "wp_shortlink_wp_head", 10);
	remove_action("wp_head", "rest_output_link_wp_head", 10);
	remove_action("wp_head", "wp_oembed_add_discovery_links");
}

function disable_emojis()
{
	remove_action("wp_head", "print_emoji_detection_script", 7);
	remove_action("admin_print_scripts", "print_emoji_detection_script");
	remove_action("wp_print_styles", "print_emoji_styles");
	remove_action("admin_print_styles", "print_emoji_styles");
	remove_filter("the_content_feed", "wp_staticize_emoji");
	remove_filter("comment_text_rss", "wp_staticize_emoji");
	remove_filter("wp_mail", "wp_staticize_emoji_for_email");
	add_filter("emoji_svg_url", "__return_false");
}

function disable_extra_rss()
{
	add_filter("feed_links_show_comments_feed", "__return_false");
	remove_action("wp_head", "feed_links_extra", 3);
}

function disable_recent_comments_css()
{
	add_filter("show_recent_comments_widget_style", "__return_false");
}

function disable_gallery_css()
{
	add_filter("use_default_gallery_style", "__return_false");
}
