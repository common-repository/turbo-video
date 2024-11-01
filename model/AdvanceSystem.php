<?php
namespace TurboVideo\model;
use TurboVideo\model\Block;
use TurboVideo\helper\DefaultArgs;
use TurboVideo\services\VideoTemplate;

class AdvanceSystem{

    public static function html($id){
        $blocks =  Block::getBlock($id);
        $output = '';
        if(is_array($blocks)){
            foreach($blocks as $block){
                if(isset($block['attrs'])){
                    $output .= render_block($block);
                }else {
                    $data = DefaultArgs::parseArgs(self::getData($block));
                    $output .= VideoTemplate::html($data);
                }
            }
        }
        return $output;
    }

    public static function getData($block){
        $options = [
            'controls' => self::parseControls(self::i($block, 'controls')),
            'tooltips' => [
                'controls' => true,
                'seek' => true,
            ],
            'loop' => [
                'active' => (boolean) self::i($block, 'repeat') 
            ],
            'invertTime' => true,
            'autoplay' => (boolean) self::i($block, 'autoplay'),
            'muted' => (boolean) self::i($block, 'muted'),
            'download' => (boolean) self::i($block, 'download'),
            'hideControls' => self::i($block, 'autoHideControl', '', true),
            'resetOnEnd' => (boolean) self::i($block, 'resetOnEnd', '', false),
            'captions' => [
                'active' => true,
                'update' => true,
            ]
        ];

        $infos = [
            'id' => 0,
            'source' => self::i($block, 'source') ? self::i($block, 'source') : self::i($block, 'videoURL'),
            'poster' => urlencode(self::i($block, 'poster')),
            'pause_other' => self::i($block, 'pauseOther', '', false)
        ];

        $template = array(
            'class' => 'h5vp_player_initializer',
            'poster' =>  urlencode(self::i($block, 'poster')),
            'width' => self::i($block, 'width', 'number', 100)."%",
            'round' => self::i($block, 'radius', 'number', 100)."px",
            'controlsShadow' => false,
        );
        $result = [
            'options' => $options,
            'infos' => $infos,
            'template' => $template
        ];

        return $result;
    }

    public static function i($array, $key1, $key2 = '', $default = false){
        if(isset($array[$key1][$key2])){
            return $array[$key1][$key2];
        }else if (isset($array[$key1])){
            return $array[$key1];
        }
        return $default;
    }



    public static function parseControls($controls){
        $newControls = [];
        if(!is_array($controls)){
            return [ 'progress','play-large','rewind', 'play', 'fast-forward', 'current-time', 'duration', 'mute', 'volume',  'captions','settings','pip', 'airplay','download','fullscreen'];
            // return ['play', 'progress', 'current-time', 'mute', 'volume','settings', 'fullscreen'];
        }
        foreach($controls as $key => $value){
            if($value == 1){
                array_push($newControls, $key);
            }
        }
        return $newControls;
    }
}