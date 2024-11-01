<?php
namespace TurboVideo\Services;
use TurboVideo\helper\Functions;
class VideoTemplate{
    protected static $uniqid = null;
    protected static $styles = [];

    public static function html($data){
        self::createId();
        self::style($data['template']);
        // self::enqueueAssets();

        $settings = $data;
        // print_r($settings);die;
        unset($settings['template']);
         ob_start(); 
         ?>
         <style>
             <?php echo esc_html(Functions::trim(self::renderStyle())); ?>
        </style>
        <div id="h5vp_player" style="width:<?php echo esc_html($data['template']['width']); ?>">
            <iframe id="vp_video" style="border:none;" width="100%" height="100%" src="<?php echo esc_html(TURBOVIDIEO_PLUGIN_DIR).'js/video.html?v=0.0.23&url='.urlencode(esc_html($data['infos']['source'])).'&poster='.esc_html($data['infos']['poster']).'&options='.esc_html(self::getAttrs($data['options'])).'&data-settings='.esc_attr(wp_json_encode($settings)); ?>"></iframe>
        </div>
        <script>
            var h5vpPlayer = document.querySelector('#h5vp_player');
            h5vpPlayer.style.height = h5vpPlayer.offsetWidth / 2 + 'px';
        </script>
        <?php $output = ob_get_contents(); ob_get_clean();
        return $output; //print $output; // debug 
    }

    public static function style($template){
        $id = "#".self::$uniqid;
        self::addStyle("$id.h5vp_player", [
            'width' => $template['width'],
            'border-radius' => $template['round'],
            'overflow' => 'hidden'
        ]);
    }

    public static function addStyle($selector, $styles){
        if(array_key_exists($selector, self::$styles)){
           self::$styles[$selector] = wp_parse_args(self::$styles[$selector], $styles);
        }else {
            self::$styles[$selector] = $styles;
        }
    }

    public static function renderStyle(){
        $output = '';
        foreach(self::$styles as $selector => $style){
            $new = '';
            foreach($style as $property => $value){
                // if($value == ''){
                //     $new .= $property.";";
                // }else {
                    $new .= " $property: $value;";
                // }
            }
            $output .= "$selector { $new }";
        }

        return $output;
    }

    public static function createId(){
        self::$uniqid = esc_html('player'.uniqid());
    }

    /**  
     * enqueue essential assets
     */
    public static function enqueueAssets(){
        wp_enqueue_script('mp4-player-worder');
        wp_enqueue_script('mp4-player');
        wp_enqueue_script('turbovideo-public');
        wp_enqueue_style('turbovideo-public');
    }

    /**
     * get attr ( autoplay, loop, muted)
     */
    public static function getAttrs($options){
        $attr = $options['muted'] == true ? ' muted' : '';
        $attr .= $options['autoplay'] == true ? ' autoplay' : '';
        return $attr;
    }
}