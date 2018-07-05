<?php
global $_HFPMenu;
final class hf_Main_class {
    public $debug = false;
    public $options;

    public $title = '한글폰트 설정';
    public $require_wp_version = '3.7.1';
    public $plugin_version = '1.1';
    public $settings_group = 'hangulfont-settings-group';
    public $settings_field = 'hangulfont-setting-field';
    public $menu_id = 'hangulfont-menu';
    public $errorMessage;
    public $errorNaverMessage;
    public $errorNaverCode;

    public $fontName;
    public $fontNmEng;
    
    public $tpl;
    public $dir;
    public $lib;

    /**
     * 새로운 인스턴스
     */
    public static function instance($fontName, $fontNmEng, $dir) {
        $hfmp = new hf_Main_class($fontName, $fontNmEng, $dir);
        $hfmp->hooks();
    }

    public function __construct($fontName, $fontNmEng, $dir) {
        if($this->debug){
            ini_set('display_errors', 'On');
            error_reporting(E_ALL ^ E_NOTICE);            
        }
        
        $this->fontName = $fontName;
        $this->fontNmEng = $fontNmEng;
        $this->settings_group .= $fontNmEng;
        $this->settings_field .= $fontNmEng;
        $this->menu_id .= $fontNmEng;
        
        $this->dir = new hf_dir_class($dir);
        $this->tpl = new hf_tpl_class($this);
        $this->lib = new hf_lib_class($this);

        // init
        $this->options = get_option($this->settings_field);
    }

    public function checking_update() {
        if($this->get_update_checktime() == date('Ymd')){
            if (!version_compare($this->get_new_version(), $this->plugin_version, '>')) return;
        }
        $this->set_update_checktime();

        $http_args = array(
            'headers'          => array(
                'Referer'      => home_url(),
                'User-Agent'   => 'nws_class'
            ),
            'httpversion'      => '1.0',
            'timeout'          => 5
        );
        $res = wp_remote_get('http://update.iamgood.co.kr/font', $http_args);
        if($res['body']){
            $body = explode('||', $res['body']);
            $this->set_new_version($body[0]);
            if (version_compare($body[0], $this->plugin_version, '>')){
                $this->message($body[1]);
            }
        }
    }
    
    public function message($msg) {
        $this->Msg = $msg;
    }
    
    public function error($errorMsg, $returnErrorMsg = '', $returnErrorCode = 0) {
        $this->errorMessage = $errorMsg;
        if ($returnErrorMsg)
            $this->errorNaverMessage = $returnErrorMsg;
        if ($returnErrorCode)
            $this->errorNaverCode = $returnErrorCode;
    }

    public function get_update_checktime(){
        return date('Ymd', get_option('hf_update_checktime'));
    }

    public function set_update_checktime(){
        update_option('hf_update_checktime', time());
    }
    
    public function set_new_version($ver){
        update_option('hf_update_new_version', $ver);
    }
    
    public function get_new_version(){
        return get_option('hf_update_new_version');
    }
    
    public function hooks() {
        if (is_admin()) {
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'register_admin_menu'));
        }
        
        if($this->options['active']=='y'){
            add_action('init', array($this->tpl, 'webfontloader'));
            add_action('init', array($this->tpl, 'set_font_css'));
            add_action('wp_head', array($this->tpl, 'set_script_style'), 9999);
        }
    }

    public function admin_init() {
        register_setting($this->settings_group, $this->settings_field, array($this, 'sanitize_op'));  // 옵션체크
        if (version_compare(get_bloginfo('version'), $this->require_wp_version, '<')){
            $this->error('워드프레스 버전 3.7.1 이상 필요로 합니다.');
        }
        $this->checking_update();
    }

    public function register_admin_menu() {
        global $_HFPMenu;
        if(!$_HFPMenu){
            $_HFPMenu = $this->menu_id;
            add_menu_page( $this->title, $this->title, 'manage_options', $_HFPMenu, array($this, 'admin_menu'), $this->dir->plugin_url() . '/images/hangul.png');
        }
        $page = add_submenu_page( $_HFPMenu, $this->fontName.' 설정', $this->fontName, 'manage_options', $this->menu_id, array($this, 'admin_menu') );

        $this->tpl->admin_style_js_add($page);
    }
    
    public function sanitize_op($input) {
        $input['tag'] = trim($input['tag']);
        $input['css'] = trim($input['css']);
        if($input['active']!='y') $input['active'] = 'n';
        return $input;
    }

    public function admin_menu() {
        global $wpdb;
        include_once $this->dir->inc_dir() . '/message.php';
        include_once $this->dir->inc_dir() . '/error_message.php';
        include_once $this->dir->inc_dir() . '/admin_page.php';
    }
}
?>