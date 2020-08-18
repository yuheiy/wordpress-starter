wp-env run cli "wp option update blogname \"The Boilerplate for WordPress\""
wp-env run cli "wp option update permalink_structure \"/%postname%/\""
wp-env run cli "wp option update timezone_string \"Asia/Tokyo\""

wp-env run cli "wp language core install ja"
wp-env run cli "wp language core activate ja"

wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 1\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 2\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 3\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 4\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 5\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 6\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 7\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 8\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 9\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 10\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 11\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 12\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 13\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 14\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"News 15\""

wp-env run cli "wp term create news_category \"Media\""
wp-env run cli "wp term create news_category \"Event\""
wp-env run cli "wp term create news_category \"Press Release\""



# 管理画面から手動で行う必要がある操作：

# リライトルールをフラッシュするために、「パーマリンク設定」の「変更を保存」を実行する
# http://localhost:8888/wp-admin/options-permalink.php

# News投稿に本文・Categoryを入力する
