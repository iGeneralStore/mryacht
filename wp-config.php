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
define('DB_NAME', 'rent');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         ':|_z,03N/adT7SUzeo-v6>jm7DPdEo9@ l[d!gDPF5C:-UBEYsqHY[3xI5,K;m:x');
define('SECURE_AUTH_KEY',  '.YPa6l$BTBmm6^+noF+[m&~:)^=E[/:zNBb1zZMMrD;h39T57n`ib.9K^CJBkBks');
define('LOGGED_IN_KEY',    'enxBee^P?%6?s(Ej1$HeH3}LRuP,NgM]H{-[iM_{(s)C>4nA[%pQ*5mP)SpwJ4Wv');
define('NONCE_KEY',        'r J5LhO^qeV*Y;.>X]-ahs~z+J6{Va^*C&m<3rW8!rG COq]iZ,PQL.k/2jWc8`G');
define('AUTH_SALT',        'z?]Q;NcJMk3ti3/S$/;6!i9Nq9Na[]0U%LgHaHdSBi?IZ}(02M`AlgVfCxP64||F');
define('SECURE_AUTH_SALT', '?i5;^_xY-|Yp0P%T|2/ip4JQVI|QtbgFE<X70# Gk7_7sdRl[N+sx*>N(#7^o65$');
define('LOGGED_IN_SALT',   'WLf+&tv<GX.+_&35(2=59vf4pY*bL)vcF157Mj!-P:Pn!b]/XAsKvF,2($C:do1}');
define('NONCE_SALT',       '3`RYN;JRG4sNzC-/hJgRf+05b@>NP7Sp^Z9%O.3}EP@O Iz79]~I_O@P,@L4=}>n');

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
