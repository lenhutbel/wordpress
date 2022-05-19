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
define( 'DB_NAME', 'lenhutbel_database' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         ';|4Jk+p5x2W+NSB*{R 4y:d2!Yr%{5K>%uY;oqsz7[5l<E:p3OOTV Yso~PP] u~' );
define( 'SECURE_AUTH_KEY',  'Mmm&3qdER5O7u+S[qf;-a#a;I_]SR<oQfYK5<Lrj_zRI1p<$*4hizM9QJuI]M?u)' );
define( 'LOGGED_IN_KEY',    '-?;N&Tr6HYOe*T!)QX <g,jHq(AbB,|0$*Cj@s&:_,B~=R<S*F@>T*NBhY]q6%w<' );
define( 'NONCE_KEY',        '[Hf7bb(]b+KoU0Ls~t]&=fN{rNhkx4AtF.WA!^xmsnI=B8kkF_cz Xj]>Ik9+.C}' );
define( 'AUTH_SALT',        '{3QR`HM,4:P3Q1g:tkHe$K0(_Fg@_czI %q3A>fWsshDLG/t|GC+FzfHFJuaDvvr' );
define( 'SECURE_AUTH_SALT', 'H#PI[C!73dS cc`pD 7UI N]7t_q;q(%ihel4IH=y<FIk$QNfIs`AkU0P49mnd0]' );
define( 'LOGGED_IN_SALT',   't]kRh?i!Qmwe9]dKH.hU0n}Z?skiq8p=d5LeA`5C@B:9/x#T#C;%{d@irM< VY=)' );
define( 'NONCE_SALT',       '|c,cAnH^|>At%X<HG!HYj3C0l%+:Ux(I%cI67-$0!~M~n )ue#ToB6257k4iaP$v' );

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
