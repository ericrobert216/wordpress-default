<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_default');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ':Yf:5#4B>Q)NyS9Ct~(* UZx[P:^xzy|BVlrO1W:!T^<+]>LmX65vq CPY(5WXW)');
define('SECURE_AUTH_KEY',  '_#$W/2vwOpX],0uo@doYVMdi&2k>,#%&%R7m0E]fGeE~k&QB%4JX/o )9*#pTfw+');
define('LOGGED_IN_KEY',    'I&jL+rSzKBC:ro;dH9tyA5Ew4`f*<:7^3QT;tsPXLj; &^d0v_gxCq_R4dcuB?$f');
define('NONCE_KEY',        ' O4UUJK;l{ W7PBK4g>%iLaXrX 668XI:ZQrN3YC:utql+32Xu-B>SK_96/s+&C+');
define('AUTH_SALT',        'P}-hHmdIG}%G2=[9;Sjfam{VPhEtDrpp!|FUm [Z:mPzu7$y#Py%^gP#L0<Y-x1X');
define('SECURE_AUTH_SALT', '3I3=E/oA8$,lW?s?)%W_tfk^1]dt*J*~p 9ly;._vXOU_qNu9%wo_{@1-ZZSFh/m');
define('LOGGED_IN_SALT',   '%W[hcso&3eXv:PS/07W-/W=|&5L[x}EY+u~3PupET!8l3V-[V4Jt!GZ];$HO)&(m');
define('NONCE_SALT',       'k>_p{!c8<-@:8zV(K</^_qz9p/X0%y%:e&RyHHaYPZ0<$[1R,Y.vv5hmg}/;%_|(');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
