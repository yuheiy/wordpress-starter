<?php

namespace WordPressStarter\MU_Plugins\Disable_Trackbacks;

add_action("init", __NAMESPACE__ . "\init");

function init()
{
	add_filter("xmlrpc_methods", __NAMESPACE__ . "\disable_pingback");
	add_filter("wp_headers", __NAMESPACE__ . "\\remove_pingback_headers");
	add_filter("bloginfo_url", __NAMESPACE__ . "\\remove_pingback_url", 10, 2);
	add_filter("xmlrpc_call", __NAMESPACE__ . "\\remove_pingback_xmlrpc");
	add_filter("rewrite_rules_array", __NAMESPACE__ . "\\remove_trackback_rewrite_rules");
}

function disable_pingback($methods)
{
	unset($methods["pingback.ping"]);
	return $methods;
}

function remove_pingback_headers($headers)
{
	unset($headers["X-Pingback"]);
	return $headers;
}

function remove_pingback_url($output, $show)
{
	return $show === "pingback_url" ? "" : $output;
}

function remove_pingback_xmlrpc($action)
{
	if ($action === "pingback.ping") {
		wp_die("Pingbacks are not supported", "Not Allowed!", ["response" => 403]);
	}
}

function remove_trackback_rewrite_rules($rules)
{
	foreach (array_keys($rules) as $rule) {
		if (preg_match('/trackback\/\?\$$/i', $rule)) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}
