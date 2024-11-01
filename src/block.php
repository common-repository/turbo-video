<?php
namespace TurboVideo\Block;
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use TurboVideo\Helper\DefaultArgs;
use TurboVideo\Model\AdvanceSystem;
use TurboVideo\Services\VideoTemplate;

if(!class_exists('TurboVideo_Block')){
	class TurboVideo_block {
		function __construct()
		{
			// add_action('init', [$this, 'enqueue_block_css_js']);
			add_action('init', [$this, 'enqueue_script']);
			if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
				wp_add_privacy_policy_content(
					__( 'TurboVideo', 'turbovideo' ),
					__( 'We collect information about visitors who comment on Sites that use our turbovideo service. The information we collect depends on how the User sets up Akismet for the Site, but typically includes the commenter\'s IP address, user agent, referrer, and Site URL (along with other information directly provided by the commenter such as their name, username, email address, and the comment itself).', 'turbovideo' )
				);
			}
		}
		function enqueue_script() {
			wp_register_style(
				'turbovideo-cgb-style-css', // Handle.
				plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
				is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
				null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
			);
		
			wp_register_script( 'bplugins-plyrio', TURBOVIDIEO_PLUGIN_DIR .'js/plyr.js' , array(), TURBOVIDIEO_VER, false );
			wp_register_script( 'mp4-player-worker',  TURBOVIDIEO_PLUGIN_DIR . 'mp4-proxy.js', array(), TURBOVIDIEO_VER , false );
			wp_register_script( 'mp4-player', TURBOVIDIEO_PLUGIN_DIR . 'js/me-player.js', array(), TURBOVIDIEO_VER , false );
			// wp_register_script( 'turbovideo-public', TURBOVIDIEO_PLUGIN_DIR . 'dist/public.js' , array('jquery', 'bplugins-plyrio'), TURBOVIDIEO_VER, true );
			
			wp_register_style( 'bplugins-plyrio', TURBOVIDIEO_PLUGIN_DIR . 'css/player-style.css', array(), TURBOVIDIEO_VER, 'all' );
			wp_register_style( 'turbovideo-editor', TURBOVIDIEO_PLUGIN_DIR . 'dist/editor.css', array('bplugins-plyrio'), TURBOVIDIEO_VER, 'all' );
		
			// Register block editor script for backend.
			wp_register_script(
				'turbovideo-cgb-block-js', // Handle.
				plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'bplugins-plyrio', 'mp4-player'), // Dependencies, defined above.
				TURBOVIDIEO_VER, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
				true // Enqueue the script in the footer.
			);
		
			// Register block editor styles for backend.
			// wp_register_style(
			// 	'turbovideo-cgb-block-editor-css', // Handle.
			// 	plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
			// 	array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			// 	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
			// );
		
			// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
			wp_localize_script(
				'turbovideo-cgb-block-js',
				'cgbGlobal', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access from `cgbGlobal` object.
				]
			);
		
			/**
			 * Register Gutenberg block on server-side.
			 *
			 * Register the block on server-side to ensure that the block
			 * scripts and styles for both frontend and backend are
			 * enqueued when the editor loads.
			 *
			 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
			 * @since 1.16.0
			 */
			register_block_type(
				'turbovideo-block/turbovideo-player', array(
					// Enqueue blocks.style.build.css on both frontend & backend.
					// 'editor_script' => 'turbovideo-public',
					'editor_script' => 'mp4-player',
					// 'editor_style' => 'turbovideo-editor',
					'style'         => 'turbovideo-cgb-style-css',
					// Enqueue blocks.build.js in the editor only.
					'editor_script' => 'turbovideo-cgb-block-js',
					// 'editor_script' => 'mp4-player',
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'  => 'turbovideo-editor',
					// 'editor_style'  => 'bplugins-plyrio',
					'render_callback' => [$this, 'render_callback']
				)
			);
		}

		function render_callback($atts) {
			// print_r($atts);die;
			$data = DefaultArgs::parseArgs(AdvanceSystem::getData($atts));
			
            return VideoTemplate::html($data);
		}
	}

	new TurboVideo_block();
}
/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */

