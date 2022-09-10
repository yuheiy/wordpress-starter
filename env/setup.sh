#!/bin/bash

root=$(dirname "$(wp config path)")
ip_address=$1

wp site switch-language ja

wp theme activate mytheme

wp rewrite structure "/%postname%/"
wp rewrite flush --hard

rm -rf "${root}/wp-content/uploads/*/"
cp -r "${root}/env/uploads/." "${root}/wp-content/uploads/"

sed -e "s/<wp:attachment_url>http:\/\/localhost:8888\//<wp:attachment_url>http:\/\/${ip_address}:8888\//g" "${root}/env/data.xml" >"${root}/env/data.edited.xml"
wp import "${root}/env/data.edited.xml" --authors=create
rm "${root}/env/data.edited.xml"

wp option update blogname "wordpress-starter"
wp option update blogdescription "WordPressテーマ構築のための開発環境"
wp option update timezone_string "Asia/Tokyo"
wp option update date_format "Y年n月j日"
wp option update time_format "H:i"
wp option update site_icon 21
