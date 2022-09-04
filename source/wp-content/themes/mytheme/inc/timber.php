<?php

namespace WordPressStarter\Theme;

use Timber;

new Timber\Timber();

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
			$sprite_path = sprintf("/build/sprites/%s.svg", $context);

			$sprite = simplexml_load_string(file_get_contents(dirname(__DIR__) . $sprite_path));

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

			$use_href = get_template_directory_uri() . $sprite_path . "#" . $id;
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
		"name" => "about",
		"post_type" => "page",
	]);

	return $context;
});
