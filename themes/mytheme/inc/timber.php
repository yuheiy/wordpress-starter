<?php

if (file_exists($composer_autoload)) {
	$timber = new Timber\Timber();
}

if (!class_exists("Timber")) {
	add_action("admin_notices", function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' .
			esc_url(admin_url("plugins.php#timber")) .
			'">' .
			esc_url(admin_url("plugins.php")) .
			"</a></p></div>";
	});

	add_filter("template_include", function ($template) {
		return get_stylesheet_directory() . "/static/no-timber.html";
	});
	return;
}

Timber::$dirname = ["views"];

add_filter("timber/twig", function ($twig) {
	foreach (
		// https://github.com/timber/timber/blob/91f4a50d8fe2e1139dc145c8752571ecf0d0c518/lib/Twig.php#L55-L87
		[
			"get_post" => [
				"callable" => [Timber::class, "get_post"],
			],
			"get_image" => [
				"callable" => [Timber::class, "get_image"],
			],
			"get_attachment" => [
				"callable" => [Timber::class, "get_attachment"],
			],
			"get_posts" => [
				"callable" => [Timber::class, "get_posts"],
			],
			"get_attachment_by" => [
				"callable" => [Timber::class, "get_attachment_by"],
			],
			"get_term" => [
				"callable" => [Timber::class, "get_term"],
			],
			"get_terms" => [
				"callable" => [Timber::class, "get_terms"],
			],
			"get_user" => [
				"callable" => [Timber::class, "get_user"],
			],
			"get_users" => [
				"callable" => [Timber::class, "get_users"],
			],
			"get_comment" => [
				"callable" => [Timber::class, "get_comment"],
			],
			"get_comments" => [
				"callable" => [Timber::class, "get_comments"],
			],
		]
		as $name => $function
	) {
		$twig->addFunction(new Timber\Twig_Function($name, $function["callable"]));
	}

	$twig->addFunction(
		new Timber\Twig_Function("get_menu", function ($slug, $options = []) {
			return new Timber\Menu($slug, $options);
		})
	);

	$twig->addFunction(
		new Timber\Twig_Function("sprite", function ($context, $id, $attr = "") {
			$sprite_file_path = sprintf("/build/images/sprites/%s.svg", $context);

			$sprite = simplexml_load_string(
				file_get_contents(get_theme_file_path($sprite_file_path))
			);

			$default_attr = [];

			foreach ($sprite->symbol as $symbol) {
				$symbol_attr = $symbol->attributes();

				if ($id === (string) $symbol_attr->id) {
					$viewBox = (string) $symbol_attr->viewBox;
					list($min_x, $min_y, $width, $height) = array_map(
						"floatval",
						explode(" ", $viewBox)
					);
					$default_attr["width"] = $width - $min_x;
					$default_attr["height"] = $height - $min_y;

					break;
				}
			}

			if (!(isset($default_attr["width"]) && isset($default_attr["height"]))) {
				return false;
			}

			$attr = wp_parse_args($attr, $default_attr);
			$attr = array_map("esc_attr", $attr);
			$html = "<svg";

			foreach ($attr as $name => $value) {
				$html .= " $name=" . '"' . $value . '"';
			}

			$html .= ">";

			$use_href = get_theme_file_uri($sprite_file_path) . "#" . $id;
			$html .= '<use href="' . $use_href . '" />';

			$html .= "</svg>";

			return $html;
		})
	);

	return $twig;
});

add_filter("timber/context", function ($context) {
	$context["options"] = get_fields("options");

	$context["about_post"] = Timber::get_post([
		"post_name" => "about",
		"post_type" => "page",
	]);

	return $context;
});
