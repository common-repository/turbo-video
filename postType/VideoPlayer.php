<?php
namespace TurboVideo\PostType;
class TurboVideoPlayer{
    protected static $_instance = null;
    protected $post_type = 'videoplayer';

    public function __construct(){
        add_action('init', [$this, 'init']);
        if(is_admin()){
            add_filter( 'post_row_actions',[$this, 'h5vp_remove_row_actions'], 10, 2 );
            add_filter( 'gettext', [$this, 'h5vp_change_publish_button'], 10, 2 );

            add_filter('post_updated_messages', [$this, 'h5vp_updated_messages']);
            add_action('edit_form_after_title', [$this, 'h5vp_shortcode_area']);
            add_filter('manage_videoplayer_posts_columns', [$this, 'ST4_columns_head_only_videoplayer'], 10);
            add_action('manage_videoplayer_posts_custom_column', [$this, 'ST4_columns_content_only_videoplayer'], 10, 2);
            
            add_action('admin_head-post.php', [$this, 'h5vp_hide_publishing_actions']);
            add_action('admin_head-post-new.php', [$this, 'h5vp_hide_publishing_actions']);

            add_action('use_block_editor_for_post', [$this, 'forceGutenberg'], 10, 2);
            add_filter( 'filter_block_editor_meta_boxes', [$this, 'remove_metabox'] );
        }
    }

    /**
     * create instance
     */
    public static function instance(){
        if(self::$_instance == null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * register post type
     */
    public function init(){
        register_post_type( 'videoplayer',
            array(
            'labels' => array(
                'name' => esc_html__( 'Turbo Video Player', 'TurboVideo' ),
                'singular_name' => esc_html__( 'Video Player', 'TurboVideo' ),
                'add_new' => esc_html__( 'Add New Player', 'TurboVideo' ),
                'add_new_item' => esc_html__( 'Add New Player', 'TurboVideo' ),
                'edit_item' => esc_html__( 'Edit Player', 'TurboVideo' ),
                'new_item' => esc_html__( 'New Player', 'TurboVideo' ),
                'view_item' => esc_html__( 'View Player', 'TurboVideo' ),
                'search_items'       => esc_html__( 'Search Player', 'TurboVideo' ),
                'not_found' => esc_html__( 'Sorry, we couldn\'t find the Player you are looking for.', 'TurboVideo' )
            ),
            'public' => false,
            'show_ui' => true, 									
            // 'publicly_queryable' => true,
            // 'exclude_from_search' => true,
            'show_in_rest' => true,
            'menu_position' => 14,
            'has_archive' => false,
            'hierarchical' => false,
            'capability_type' => 'page',
            'rewrite' => array( 'slug' => 'videoplayer' ),
            'supports' => array('title', 'editor'),
            'template' => [
                ['html5-player/parent']
            ],
            'template_lock' => 'all',
            )
		);
    }

    function h5vp_remove_row_actions( $idtions ) {
        global $post;
        if( $post->post_type == 'videoplayer' ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }

    function h5vp_updated_messages( $messages ) {
        $messages['videoplayer'][1] = esc_html__('Player updated ', 'TurboVideo' );
        return $messages;
    }

    function h5vp_change_publish_button( $translation, $text ) {
        if ( 'videoplayer' == get_post_type())
        if ( $text == 'Publish' )
            return 'Save';
        
        return $translation;
    }

    function h5vp_shortcode_area(){
        global $post;
        if($post->post_type=='videoplayer'){ ?>
        <div class="h5vp_playlist_shortcode">
            <div class="shortcode-heading">
                <div class="icon"><span class="dashicons dashicons-video-alt3"></span> <?php esc_html_e("Turbo Video Player", "h5vp"); ?></div>
                <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php esc_html_e("Supports", "h5vp"); ?></a></div>
            </div>
            <div class="shortcode-left">
                <h3><?php esc_html_e("Shortcode", "h5vp") ?></h3>
                <p><?php esc_html_e("Copy and paste this shortcode into your posts, pages and widget content:", "h5vp") ?></p>
                <div class="shortcode" selectable>[video id='<?php echo esc_attr($post->ID); ?>']</div>
            </div>
            <div class="shortcode-right">
                <h3><?php esc_html_e("Template Include", "h5vp") ?></h3>
                <p><?php esc_html_e("Copy and paste the PHP code into your template file:", "h5vp"); ?></p>
                <div class="shortcode">&lt;?php echo do_shortcode('[video id="<?php echo esc_attr($post->ID); ?>"]');
                ?&gt;</div>
            </div>
        </div>
        <?php   
        }
    }

    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function ST4_columns_head_only_videoplayer($defaults) {
        $defaults['shortcode'] = 'Shortcode';
        $defaults['shortcode_deprecated'] = 'Shortcode Deprecated';
        $v = $defaults['date'];
        unset($defaults['date']);
        $defaults['date'] = $v;
        return $defaults;
    }

    function ST4_columns_content_only_videoplayer($column_name, $post_id) {
        if ($column_name == 'shortcode_deprecated') {
            echo '<div class="h5vp_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[video id=' . esc_attr($post_id) . ']" > Deprecated<span class="htooltip">Copy To Clipboard</span></div>';
        }

        if ($column_name == 'shortcode') {
            echo '<div class="h5vp_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[html5_video id=' . esc_attr($post_id) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
        }
    }


    function h5vp_hide_publishing_actions(){
        $my_post_type = 'videoplayer';
        global $post;
        if($post->post_type == $my_post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
    }

    
    function remove_metabox($metaboxs) {
        global $post;
        $screen = get_current_screen();

        if($screen->post_type === $this->post_type){
            return false;
        }
        return $metaboxs;
    }
    
    /**
     * Force gutenberg in case of classic editor
     */
    public function forceGutenberg($use, $post)
    {

        if ($this->post_type === $post->post_type) {
            $isGutenberg = get_post_meta($post->ID, 'isGutenberg', true);
            $gutenberg = get_option('h5vp_option', ['h5vp_gutenberg_enable' => true]);
            if(isset($gutenberg['h5vp_gutenberg_enable'])){
                $gutenberg = (boolean) $gutenberg['h5vp_gutenberg_enable'];
            }else {
                $gutenberg = true;
            }

            if($gutenberg){
                if($post->post_status == 'auto-draft' ){
                    update_post_meta($post->ID, 'isGutenberg', true);
                    return true;
                }
                if($isGutenberg){
                    return true;
                }else {
                    remove_post_type_support($this->post_type, 'editor');
                    return false;
                }
                return $use;
            }else {
                if($isGutenberg){
                    return true;
                }else {
                    remove_post_type_support($this->post_type, 'editor');
                    return false;
                }
            }
        }

        return $use;
    }
}

TurboVideoPlayer::instance();