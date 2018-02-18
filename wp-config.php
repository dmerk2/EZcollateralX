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
define('DB_NAME', 'EZcollateralXDB');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost:8889');

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
define( 'AUTH_KEY', 'e^G}}HK).}h+_,RJm7%P`nc92#$>kdT{ZF!hhFWiNi!yyh`1`9foqj7s>Sh@Y( +' );
define( 'SECURE_AUTH_KEY', '?261}ji=$?F)[>C8k_S<.op}_sDS9{;%:!xGr)6ah+y7; &DUl)f7VLh&uh%$@p-' );
define( 'LOGGED_IN_KEY', '[9xdX]!pC}4U1>q[g)y+cNLNa`&(Jb|A_7Vo.LSZ@_,7T!-L,o,_2Zxsm1!UnXG[' );
define( 'NONCE_KEY', 'v.I[Il.C~/.TH^g%j4]%*uo)#4?3Vx:5Z{`}<>v;)[S(RRjT^*H&N8ByXrn4a1-%' );
define( 'AUTH_SALT', 'j+A3qR`x1>w*?dcEW|55(WE ~2V]9uyL:atigvbI&+Lhq|{Gu>E*]@v-`f}JO^D4' );
define( 'SECURE_AUTH_SALT', 'TW8b-R^o[wWa.V.|0]aTiVdQ|;wl8-NP}%})~yF?UXwq5VXr7AXhFf7JXb?vM>af' );
define( 'LOGGED_IN_SALT', '#s-WIAQ}aY~<&5lDFh8Smp|2,W$10 kYL.B+1]<Sr8Cx@Ynkq}e-uwJ@IpZ9YW7T' );
define( 'NONCE_SALT', '~}(B<Zj{VL}s!!(%l[nr-6HZX1,p%~?9jM^9;z,P#*@J,$B|bfJnSgpLYch9]m`7' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

define( 'DISALLOW_FILE_EDIT', true );


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
define('WP_DEBUG', true);

define('WP_CACHE', true); // Added by WP Hummingbird
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
