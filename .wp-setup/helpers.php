<?php

function wp($args)
{
	$command = "wp " . $args;
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

function create_post($args)
{
	$args += [
		"post_status" => "publish",
		"tax" => [],
		"meta" => [],
	];

	$command = "post create";

	foreach ($args as $key => $value) {
		if ($key === "content") {
			$command .= " " . $value;
			continue;
		}

		if ($key === "tax") {
			continue;
		}

		if ($key === "meta") {
			$key = "meta_input";
			$value = json_encode($value);
		}

		$command .= sprintf(' --%s="%s"', $key, addslashes($value));
	}

	$id = wp($command . " --porcelain");

	foreach ($args["tax"] as $taxonomy => $terms) {
		$command = sprintf('post term add %s "%s"', $id, $taxonomy);

		foreach ($terms as $term) {
			$command .= sprintf(' "%s"', $term);
		}

		wp($command);
	}

	return $id;
}

function fixture_path($path)
{
	return __DIR__ . "/fixtures/" . $path;
}

$fixture_ids_cache = [];

function fixture_id($path)
{
	global $fixture_ids_cache;

	$filename = fixture_path($path);

	if (isset($fixture_ids_cache[$filename])) {
		$id = $fixture_ids_cache[$filename];
		return $id;
	}

	$id = wp(sprintf("media import %s --porcelain", $filename));
	$fixture_ids_cache[$filename] = $id;

	return $id;
}

$image_ids_cache = [];

function image_id($width, $height)
{
	global $image_ids_cache;

	$filename = __DIR__ . "/.image-cache/" . sprintf("%sx%s.jpg", $width, $height);

	if (isset($image_ids_cache[$filename])) {
		$id = $image_ids_cache[$filename];
		return $id;
	}

	if (!file_exists($filename)) {
		mkdir(dirname($filename), 0755, true);

		$url = sprintf("https://picsum.photos/%s/%s", $width, $height);
		$file = file_get_contents($url);
		file_put_contents($filename, $file);
	}

	$id = wp(sprintf("media import %s --porcelain", $filename));
	$image_ids_cache[$filename] = $id;

	return $id;
}

function get_acf_meta($data)
{
	$meta = [];

	$walk_fields = function ($paths, $data, $is_repeater = false) use (&$walk_fields, &$meta) {
		$meta_key = implode("_", $paths);

		if (!is_array($data)) {
			$meta[$meta_key] = $data;
			return;
		}

		if ($data === []) {
			return;
		}

		if (is_associative($data)) {
			// assume the $data　is for the group field
			if (!$is_repeater) {
				$meta[$meta_key] = "";
			}

			foreach ($data as $key => $value) {
				$child_paths = array_merge($paths, [$key]);
				$walk_fields($child_paths, $value);
			}
		} else {
			// assume the $data is for the repeater field
			$meta[$meta_key] = count($data);

			foreach ($data as $key => $value) {
				$child_paths = array_merge($paths, [$key]);
				$walk_fields($child_paths, $value, true);
			}
		}
	};

	foreach ($data as $key => $value) {
		$walk_fields([$key], $value);
	}

	return $meta;
}

function is_associative(array $array)
{
	if ($array === []) {
		return false;
	}

	return array_keys($array) !== range(0, count($array) - 1);
}
