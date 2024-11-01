<?php
/**
 * Plugin Name: Turbo Video
 * Plugin URI: https://metaedge.io/docs/wordpress
 * Description: Welcome to our Edge CDN WordPress Plug-in, a robust solution designed to optimize video delivery on your WordPress site. This plug-in seamlessly integrates with Edge CDN, an advanced video player and unlimited hosting platform tailored for WordPress users. Elevate your website's video experience with faster load times, optimal playback, and seamless integration with our global delivery network.
 * Author: metaedge.io
 * Author URI: https://metaedge.io/
 * Version: 1.1.21
 * License: AGPL-3.0
 * License URI: https://www.gnu.org/licenses/agpl.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*Some Set-up*/
define('TURBOVIDIEO_PLUGIN_DIR', plugin_dir_url(__FILE__) ); 
define('TURBOVIDIEO_VER', '1.1.21' ); 
/**
 * Block Initializer.
 */
require_once(__DIR__.'/helper/Functions.php');

require_once(__DIR__.'/helper/DefaultArgs.php');

require_once(__DIR__.'/model/AnalogSystem.php');

require_once(__DIR__.'/model/AdvanceSystem.php');

require_once(__DIR__.'/services/Shortcode.php');
require_once(__DIR__.'/services/EnqueueAssets.php');
require_once(__DIR__.'/services/VideoTemplate.php');

require_once(__DIR__.'/elementor-widget.php');

require_once plugin_dir_path( __FILE__ ) . 'src/block.php';
