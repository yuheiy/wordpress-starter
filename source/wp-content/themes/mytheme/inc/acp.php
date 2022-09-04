<?php

namespace WordPressStarter\Theme;

add_filter("acp/storage/file/directory", function () {
	return dirname(__DIR__) . "/acp-settings";
});

if (wp_get_environment_type() === "local") {
	add_filter("acp/storage/file/directory/migrate", "__return_true");
} else {
	add_filter("acp/storage/file/directory/writable", "__return_false");
}
