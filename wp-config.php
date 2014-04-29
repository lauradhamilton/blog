<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

define('WP_SITEURL','/blog');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wpdb');

/** MySQL database username */
define('DB_USER', 'ubuntu');

/** MySQL database password */
define('DB_PASSWORD', 'pumpkin ravioli');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Source: http://updraftplus.com/faqs/my-scheduled-backups-and-pressing-backup-now-does-nothing-however-pressing-debug-backup-does-produce-a-backup/ */
define('ALTERNATE_WP_CRON', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '9@|dzZmFom+CC6{XPfJU]DjSk4g-wd7#7)Mp|L1AB^Y94c[0SJI61Mp;I|7Lg1ET');
define('SECURE_AUTH_KEY',  'AkT3J>e.S<F~.$*1iQ!+|w-uwjH<e[}xs;aFfs-E^A!Dp6c+AKW(T*|~lChs,L7H');
define('LOGGED_IN_KEY',    'P@[|z/K[-|yYh;+j7/+Vl3j2gFr&aU<>~0~0>3I*Y1NBZrZM1p#[KAV$Mwc,,V8F');
define('NONCE_KEY',        '9};|OPAAzI6~DBEpg4@zt++H#V,9/f#MVXYYav!^e2k*qMC=_]E{#ANzCg?)7~Ww');
define('AUTH_SALT',        '6|kf]2RgEof&4K&Mh)DAd[ *Za[li-+.T8YU(WRaW];WEb*^crBPUJ|;zuolK+ux');
define('SECURE_AUTH_SALT', '&t-pONn{}`gXjE^1]55&1|P;|/HxQs,K],R<2Ij$3UiMzt uI6}-U^kFv9&f.@-1');
define('LOGGED_IN_SALT',   'eo>jtHZjlJpHkSRs8zHa3mriq~ll>I|.U:05V^Gku(n?Ivogvme}ss%j&3VcqT.R');
define('NONCE_SALT',       ',T?-&=YsMjk%G#4|G3^`^xo>V/GsS<D+7>z%8J>}qMM_r!Q{51 #%6=]T~-2&~+-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define('WP_ALLOW_MULTISITE', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
