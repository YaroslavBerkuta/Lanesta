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
define( 'DB_NAME', 'lanesta' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '-V#%o9<GJSJ{syQM69lBq$^ 1[iObsK`L]Qu(a4&KR %@h ad|N}PvG2OQr;3=jS' );
define( 'SECURE_AUTH_KEY',  '7Mml?K5`y!>9s#d O|&Zl D`u%(6u/A:d1bq+Y%BEvW8)mirg7IMo454Cgr2Rq#S' );
define( 'LOGGED_IN_KEY',    '($yb,R&m KqC+Q>^DyKCbm@qwLH/bh@&[$D-7xV5 >Oyps.0g$S(9UeamuP ,+uu' );
define( 'NONCE_KEY',        '+(mV~$:,PEgn2}X3l>@z?n5l7^*dx7X@+)SX4i$XtScr8P<:2f&L`LY8l[$al{tw' );
define( 'AUTH_SALT',        't(khb>%q~&/QKo{Z1Ac5[A`WvbhRFe(Jk,K]Hl|_8yI!-K.E=gRK5T)n2@);NR^r' );
define( 'SECURE_AUTH_SALT', 'f/u67jvgh]^}[>A};$]hg*>rpBZT5^7p2.#443 +Mc;LIg?[#n =,CnIG@MLtUUF' );
define( 'LOGGED_IN_SALT',   '3[(x %-X-]RGl>{tpG^Qqn|U&*lgptdgY8L R;_Gm~G4f;sb>1c_8%^A:wSXL5]N' );
define( 'NONCE_SALT',       ',iK98HnQR*&xCdVs0K8OJ(e%Yt5bbjB>OBkt-5PQ[(9@0I [xn+][7-Er7H,(ir8' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
