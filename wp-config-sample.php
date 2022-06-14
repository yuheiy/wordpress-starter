<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define("DB_NAME", "database_name_here");

/** Database username */
define("DB_USER", "username_here");

/** Database password */
define("DB_PASSWORD", "password_here");

/** Database hostname */
define("DB_HOST", "localhost");

/** Database charset to use in creating database tables. */
define("DB_CHARSET", "utf8");

/** The database collate type. Don't change this if in doubt. */
define("DB_COLLATE", "");

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define("AUTH_KEY", "put your unique phrase here");
define("SECURE_AUTH_KEY", "put your unique phrase here");
define("LOGGED_IN_KEY", "put your unique phrase here");
define("NONCE_KEY", "put your unique phrase here");
define("AUTH_SALT", "put your unique phrase here");
define("SECURE_AUTH_SALT", "put your unique phrase here");
define("LOGGED_IN_SALT", "put your unique phrase here");
define("NONCE_SALT", "put your unique phrase here");

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = "wp_";

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

// https://ja.wordpress.org/support/article/editing-wp-config-php/#%e3%82%a8%e3%83%a9%e3%83%bc%e3%83%ad%e3%82%b0%e3%81%ae%e8%a8%ad%e5%ae%9a
@ini_set("log_errors", "On");
@ini_set("display_errors", "Off");
@ini_set("error_log", "/home/example.com/logs/php_error.log");
define("WP_DEBUG", false);

if (WP_DEBUG) {
	@ini_set("log_errors", "Off");
	@ini_set("display_errors", "On");
	define("SAVEQUERIES", true);
	define("WP_DEBUG_LOG", true);
	define("WP_DEBUG_DISPLAY", true);
	define("WP_DISABLE_FATAL_ERROR_HANDLER", true);
	define("SCRIPT_DEBUG", true);
}

/* Add any custom values between this line and the "stop editing" line. */

// https://ja.wordpress.org/support/article/editing-wp-config-php/#wp_siteurl
define("WP_SITEURL", is_ssl() ? "https://" : "http://" . $_SERVER["HTTP_HOST"]);

// https://ja.wordpress.org/support/article/editing-wp-config-php/#%e3%83%96%e3%83%ad%e3%82%b0%e3%81%ae%e3%82%a2%e3%83%89%e3%83%ac%e3%82%b9-url
define("WP_HOME", is_ssl() ? "https://" : "http://" . $_SERVER["HTTP_HOST"]);

// https://ja.wordpress.org/support/article/editing-wp-config-php/#%e3%83%97%e3%83%a9%e3%82%b0%e3%82%a4%e3%83%b3%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%83%bc%e3%80%81%e3%83%86%e3%83%bc%e3%83%9e%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%83%bc%e3%81%ae%e7%84%a1%e5%8a%b9
define("DISALLOW_FILE_EDIT", true);

// https://www.advancedcustomfields.com/resources/how-to-activate/
define("ACF_PRO_LICENSE", "put your acf pro license key here");

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined("ABSPATH")) {
	define("ABSPATH", __DIR__ . "/");
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . "wp-settings.php";
