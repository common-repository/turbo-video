<?php
namespace TurboVideo\services;
use TurboVideo\model\AnalogSystem;
use TurboVideo\model\AdvanceSystem;

class Shortcode{
    protected static $_instance = null;

    public function __construct(){
        // $option = get_option('h5vp_option');
        // if(!isset($option['h5vp_disable_video_shortcode']) || $option['h5vp_disable_video_shortcode'] !== '1'){
        //     add_shortcode('video', [$this, 'video']);
        // }
        add_shortcode('turbo_video', [$this, 'video']);
    }

    /**
     * Shortcode [video]
     */

     public function video($atts){
        if(!isset($atts['id'])){
            return false;
        }

        $isGutenberg = get_post_meta($atts['id'], 'isGutenberg', true);

        ob_start();
        if($isGutenberg){
            echo esc_html(AdvanceSystem::html(esc_html($atts['id'])));
        }else {
            echo esc_html(AnalogSystem::html(esc_html($atts['id'])));
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
     }
    /**
     * Create instance
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

Shortcode::instance();