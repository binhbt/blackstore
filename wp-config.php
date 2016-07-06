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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_vietapk');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^iJ/|n8 ~8F,DRltfDk{WAyr;5;TQjbU<|of~KY4-P$$)TOW !xv`se3O0>r{jL^');
define('SECURE_AUTH_KEY',  '7OVx75w:b[GA([|f.w4l./GX )0-Qp-@OP-6[2zp4x!aJ;Z75&O}rPP6e03A:&IX');
define('LOGGED_IN_KEY',    'SuMHKs-|jzk$[.->iKVDGnD(]`u-4lHe2iC5R150}hs^X^1]X2l@* |^d:RY5g4,');
define('NONCE_KEY',        ')RF#+B*k@NmA$Q5~|ynz0idjn$3%ipTRLO]7,gZ^Ui~6O[_dCv;=<PYeN=fv;w{A');
define('AUTH_SALT',        'y7}V>4>o)fbto$zum@j&Q/]caV|?yG;/-lu7eD6g -15zI+2|@%96!7a_6-jef4m');
define('SECURE_AUTH_SALT', 'p9,zS3J?^=};q+gL8*Ds}.5 6n>]?UrV[wB=~@b>I8j-GA,ajiG7xvB*n=VZ@i$[');
define('LOGGED_IN_SALT',   '!fw@AFR-j`pNADQ&Xpygfa]V&c?+z>6[%QB4#fD4mt#+8Cv5<Rdx|;pX %>&dF*@');
define('NONCE_SALT',       '>LTcK0tPfjS6Xjs8htSx`FTeu`M$OrAyA9 v#>]h -7{|qySNa|Iiw+zxCy*4es|');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
