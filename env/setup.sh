#!/bin/bash

set -e

root=$(dirname "$(wp config path)")
ip_address=${1}

wp site switch-language ja

wp theme activate mytheme

wp rewrite structure /%postname%/
wp rewrite flush --hard

wp post delete $(wp post list --post_type=any --format=ids) --force

rm -rf "${root}"/wp-content/uploads/*
cp -r "${root}/env/uploads/." "${root}/wp-content/uploads/"

find "${root}/env/" -maxdepth 1 -name "wordpress.*.xml" | while read -r pathname; do
  # rewrite localhost to internal IP address because localhost causes cURL error 7
  sed -e "s/<wp:attachment_url>http:\/\/localhost:8888\//<wp:attachment_url>http:\/\/${ip_address}:8888\//g" "${pathname}" >"${pathname}.edited"
  wp import "${pathname}.edited" --authors=create
  rm "${pathname}.edited"
done

wp option update blogname wordpress-starter
wp option update blogdescription WordPressテーマ構築のための開発環境
wp option update timezone_string Asia/Tokyo
wp option update date_format Y年n月j日
wp option update time_format H:i
wp option update site_icon 5

wp option patch update wpseo disableadvanced_meta 0
wp option patch update wpseo content_analysis_active 0
wp option patch update wpseo keyword_analysis_active 0
wp option patch update wpseo enable_admin_bar_menu 0
wp option patch update wpseo enable_cornerstone_content 0
wp option patch update wpseo enable_text_link_counter 0
wp option patch update wpseo enable_metabox_insights 0
wp option patch update wpseo enable_enhanced_slack_sharing 0
wp option patch update wpseo dismiss_configuration_workout_notice 1

wp option patch update wpseo_social og_default_image http://localhost:8888/wp-content/uploads/2022/10/ogp.png
wp option patch update wpseo_social og_default_image_id 15
