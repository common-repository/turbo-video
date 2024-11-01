<?php
namespace TurboVideo\Services;
class EnqueueAssets{
    protected static $_instance = null;

    public function __construct(){
        add_action("wp_enqueue_scripts", [$this, 'publicAssets']);
        // add_action( 'admin_enqueue_scripts', [$this, 'html5_enqueue_custom_admin_style'] );
    }

    /**
     * Create Instance
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Enqueue public assets
     */
    public function publicAssets(){
        wp_register_script( 'bplugins-plyrio', TURBOVIDIEO_PLUGIN_DIR . 'js/plyr.js', array(), TURBOVIDIEO_VER , false );
        wp_register_script( 'turbovideo-public', TURBOVIDIEO_PLUGIN_DIR . 'dist/public.js', array('bplugins-plyrio'), TURBOVIDIEO_VER , false );

        wp_register_script( 'mp4-player', TURBOVIDIEO_PLUGIN_DIR . 'js/mp4-player.min.js', array(), TURBOVIDIEO_VER , false );

        wp_register_style( 'bplugins-plyrio', TURBOVIDIEO_PLUGIN_DIR . 'css/player-style.css', array(), TURBOVIDIEO_VER , 'all' );
        wp_register_style( 'turbovideo-public', TURBOVIDIEO_PLUGIN_DIR . 'dist/public.css', array('bplugins-plyrio'), TURBOVIDIEO_VER , 'all' );

        // $option = get_option('h5vp_option');

        // wp_localize_script( 'h5vp-public', 'h5vp', [
        //     'pauseOther' => (boolean) isset($option['h5vp_pause_other_player']) ? $option['h5vp_pause_other_player'] : false,
        // ]);
    }

    /**
     * enqueue admin assets
     **/    
    function html5_enqueue_custom_admin_style($hook_suffix) {
        
        wp_enqueue_script('mp4-player');
        wp_enqueue_script('turbovideo-public');
        wp_enqueue_style('turbovideo-public');
        wp_enqueue_style( 'turbovideo-admin', TURBOVIDIEO_PLUGIN_DIR . 'dist/admin.css', false, TURBOVIDIEO_VER );
        wp_enqueue_script( 'turbovideo-admin', TURBOVIDIEO_PLUGIN_DIR . 'dist/admin.js', false, TURBOVIDIEO_VER );

    }
}

EnqueueAssets::instance();