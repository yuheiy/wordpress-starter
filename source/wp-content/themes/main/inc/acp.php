<?php

namespace WordPressStarter\Theme\Main;

add_filter("acp/storage/file/directory", function () {
	return get_stylesheet_directory() . "/acp-settings";
});

if (wp_get_environment_type() === "local") {
	add_filter("acp/storage/file/directory/migrate", "__return_true");
} else {
	add_filter("acp/storage/file/directory/writable", "__return_false");
}
