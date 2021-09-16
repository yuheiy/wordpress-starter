---
to: wordpress/wp-setup.sh
---
#!/bin/bash

set -eu

WPINSTALLDIR=/var/www/html


if [ ! -e '/check' ]; then
	wp core install --url='http://localhost:8888' --title='新規サイト'  --admin_user='admin'  --admin_password='password' --admin_email='wp@example.com' --path=${WPINSTALLDIR} --allow-root

  wp plugin install 'https://downloads.wordpress.org/plugin/intuitive-custom-post-order.3.1.2.zip' --activate --allow-root
  wp plugin install 'https://downloads.wordpress.org/plugin/timber-library.1.18.2.zip' --activate --allow-root
  wp plugin install 'https://downloads.wordpress.org/plugin/custom-taxonomy-order-ne.3.2.1.zip' --activate --allow-root
  wp plugin activate advanced-custom-fields-pro --allow-root

#	wp language core install ja --allow-root
#	wp site switch-language ja --allow-root

	wp option update timezone_string 'Asia/Tokyo' --allow-root
  wp option update permalink_structure "/%postname%/" --allow-root
  wp option update timezone_string "Asia/Tokyo" --allow-root
  wp post create --post_type=page --post_status=publish --post_title="私たちについて" --post_name="about" --allow-root
  wp post update 3 --post_title="プライバシーポリシー" --post_status=publish --allow-root
  wp language core install ja --allow-root
  wp site switch-language ja --allow-root
  wp theme activate mytheme --allow-root

  touch /check
fi