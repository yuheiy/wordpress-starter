<?php

function get_attachment_source($attachment_id, $size = "thumbnail", $icon = false, $attr = "")
{
	$html = "";
	$image = wp_get_attachment_image_src($attachment_id, $size, $icon);

	if ($image) {
		list($src, $width, $height) = $image;

		$attachment = get_post($attachment_id);
		$hwstring = image_hwstring($width, $height);

		$attr = wp_parse_args($attr);

		// Generate 'srcset' and 'sizes' if not already present.
		if (empty($attr["srcset"])) {
			$image_meta = wp_get_attachment_metadata($attachment_id);

			if (is_array($image_meta)) {
				$size_array = [absint($width), absint($height)];
				$srcset = wp_calculate_image_srcset($size_array, $src, $image_meta, $attachment_id);
				$sizes = wp_calculate_image_sizes($size_array, $src, $image_meta, $attachment_id);

				if ($srcset && ($sizes || !empty($attr["sizes"]))) {
					$attr["srcset"] = $srcset;

					if (empty($attr["sizes"])) {
						$attr["sizes"] = $sizes;
					}
				}
			}
		}

		$attr = array_map("esc_attr", $attr);
		$html = rtrim("<source $hwstring");

		foreach ($attr as $name => $value) {
			$html .= " $name=" . '"' . $value . '"';
		}

		$html .= " />";
	}

	return $html;
}
