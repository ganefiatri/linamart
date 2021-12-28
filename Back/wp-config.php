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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u5021240_wp88' );

/** MySQL database username */
define( 'DB_USER', 'u5021240_wp88' );

/** MySQL database password */
define( 'DB_PASSWORD', 'S@v73z!8p5' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '3bjsujhgu5jqodxlauv5cuxr31kvzswkch96igoqapl7keujbtwfm4z08rtdehni' );
define( 'SECURE_AUTH_KEY',  'fccajn0utp2iqnw5ln1niy3slow3nk9z51siouzj7jcmga5nsfarupbuou66hx62' );
define( 'LOGGED_IN_KEY',    '4vseojsfqpyigt30yblfjlfkce5ncz7l2cgajerkyp3a1nkgwnrbi4ysu0jrsdik' );
define( 'NONCE_KEY',        'm1veokybnshxsmfiurbikhpxpludupgtccuyruoovw549ijwounct8gdi8zlzlmd' );
define( 'AUTH_SALT',        'cx9bejdpuz0xp74mzukhlbs9dlnv1vfgyujlrm2rjldcbuhnp8w1fk6b7rttpd9k' );
define( 'SECURE_AUTH_SALT', 'kcphhqeubc1bwhng2tpomajybnjl2ej1c9autep8y0yg7paibu82roroiazec4rh' );
define( 'LOGGED_IN_SALT',   'nf85gki73bdebeepzgphuz0rbi2npab9hmfyf0ckfdevlc8opxdahiqfuntibf2x' );
define( 'NONCE_SALT',       '0mafzovh74qf7s4ho0so9xvaly0fcci0olgpizqfesbogqnoc0orqtwgnu9ea2ps' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
