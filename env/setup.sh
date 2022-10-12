#!/bin/bash

set -e

root=$(dirname "$(wp config path)")
ip_address=$1

wp site switch-language ja

wp theme activate mytheme

wp rewrite structure "/%postname%/"
wp rewrite flush --hard

rm -rf "${root}/wp-content/uploads/"*
cp -r "${root}/env/uploads/." "${root}/wp-content/uploads/"

# rewrite localhost to internal IP address because localhost causes cURL error 7
sed -e "s/<wp:attachment_url>http:\/\/localhost:8888\//<wp:attachment_url>http:\/\/${ip_address}:8888\//g" "${root}/env/data.xml" >"${root}/env/data.edited.xml"
wp import "${root}/env/data.edited.xml" --authors=create
rm "${root}/env/data.edited.xml"

wp option update blogname "wordpress-starter"
wp option update blogdescription "WordPressテーマ構築のための開発環境"
wp option update timezone_string "Asia/Tokyo"
wp option update date_format "Y年n月j日"
wp option update time_format "H:i"
wp option update site_icon 9

wp option patch update wpseo content_analysis_active false --format=json
wp option patch update wpseo keyword_analysis_active false --format=json
wp option patch update wpseo enable_admin_bar_menu false --format=json
wp option patch update wpseo enable_cornerstone_content false --format=json
wp option patch update wpseo enable_text_link_counter false --format=json
wp option patch update wpseo enable_metabox_insights false --format=json
wp option patch update wpseo enable_link_suggestions false --format=json

wp option patch update wpseo_social og_default_image "http://localhost:8888/wp-content/uploads/2022/09/ogp.png"
wp option patch update wpseo_social og_default_image_id 11
