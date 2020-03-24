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
if(file_exists(dirname(__FILE__) . '/local.php')){
//Local db settings
/** The name of the database for WordPress */
define( 'DB_NAME', 'amazing-college' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ableengine' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

}else {
//Live db settings
/** The name of the database for WordPress */
define( 'DB_NAME', 'oliverl8_universitydata' );

/** MySQL database username */
define( 'DB_USER', 'oliverl8_oliver' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Salmos23$' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

}
/** The name of the database for WordPress */
//define( 'DB_NAME', 'amazing-college' );

/** MySQL database username */
//define( 'DB_USER', 'root' );

/** MySQL database password */
//define( 'DB_PASSWORD', 'ableengine' );

/** MySQL hostname */
//define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '+sz3]vqx!vw:XS w%my]?[4xq$*}j@B#QJ8FE<*BPf]CC@{Szz{F8Uf|c1e)&#)m' );
define( 'SECURE_AUTH_KEY',  '/gEU4J.%CYiw_I(f>MX:5I-N3^:7y~|H-q.f|R)TYh*|4etI>/&]IvXmN*U[?Q<=' );
define( 'LOGGED_IN_KEY',    'n7No#g4tbca;>P_Sd.fjQh@NP@qka>kCJ5JCqjA|l|5KV1rh[!Sq!@:A`C]#~3<_' );
define( 'NONCE_KEY',        ']ibwj$G=X;!HGWTOrFmXf4Q6N yU5;DjVOVT}#>yZ=t8:y0oE;6CF%=toV7V}B%-' );
define( 'AUTH_SALT',        '3sAFg)o@||K`.yaN5QAnn%HW?KEB]JYa#bpwrGO,3wfB=G1Khd2w/vpMmRmXz,`&' );
define( 'SECURE_AUTH_SALT', '[R!Dt31!/[Uguj*=_Mj=t6^dp2eZwscA+AL8zz~^.;~)8N34o6,Sba90U+kwy 1,' );
define( 'LOGGED_IN_SALT',   '2h1,lTP m|W?TU`<*.G/M(k<H/CBuSbc)eBrK.FZ]hT=2J8$OA|WHAW}.:p}._O4' );
define( 'NONCE_SALT',       '^-gs-h?+n5jn,fh2nK|jr^8)v.FxcGxnp#g}e4Y?AHxPl;D)S=2b1`P aWjI}]9&' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
