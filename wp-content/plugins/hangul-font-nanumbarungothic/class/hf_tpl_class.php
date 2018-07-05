<?php

class hf_tpl_class {

    private $dir;
    private $main;

    function __construct($mainClass) {
        $this->dir = $mainClass->dir;
        $this->main = $mainClass;
    }

    public function admin_style_js_add($page) {
        add_action('admin_print_styles-' . $page, array($this, 'admin_styles'), 11);
        add_action('admin_print_scripts-' . $page, array($this, 'admin_scripts'), 11);
    }

    public function admin_styles() {
        wp_enqueue_style('hangulfont-admin', $this->dir->css_url() . '/admin.css', array(), $this->main->plugin_version);
    }

    public function admin_scripts($prefix) {
        wp_enqueue_script('hangulfont-admin', $this->dir->js_url() . '/common.js', array(), $this->main->plugin_version);
    }

    public function webfontloader() {
        if($this->main->options['sync']!='y') return;
        add_action('wp_enqueue_scripts', array($this, 'webfontloader_script'));
    }
    
    public function webfontloader_script(){
        wp_deregister_script( 'webfontloader' );
        wp_register_script( 'webfontloader', '//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js');
        wp_enqueue_script( 'webfontloader' );
    }

    public function set_font_css() {
        add_action('wp_enqueue_scripts', array($this, 'queue_font_css'));
    }
    
    public function queue_font_css(){
        wp_enqueue_style('hangulfont-'. $this->main->fontNmEng, $this->dir->css_url() . "/font.css", array(), $this->main->plugin_version);
    }
    
    public function set_script_style(){
        if($this->main->options['imp']=='y')
            $important = ' !important';
        
        $tag = $this->main->options['tag'];
        if($tag){
            if($this->main->options['sync']=='y'){
                echo "\n<script type='text/javascript'>WebFont.load({custom: {families: ['{$this->main->fontNmEng}']}});</script>";
                echo "\n<style type='text/css' media='all'>";
                echo "{$tag}{visibility:hidden;}";
                
                $arr_tag = explode(',', $tag);
                $arr_ld = array();
                $arr_ai = array();
                
                foreach($arr_tag as $value){
                    $item = trim($value);
                    if(!$item) continue;
                    array_push($arr_ld, $item);
                    array_push($arr_ai, '.wf-active '.$item);
                    array_push($arr_ai, '.wf-inactive '.$item);
                }
                
                if(count($arr_ld)>0)
                    echo implode(',', $arr_ld).'{visibility:hidden;}';
                if(count($arr_ai)>0)
                    echo implode(',', $arr_ai)."{visibility:visible;font-family:'{$this->main->fontNmEng}'{$important}}";
                    
                echo "</style>";
            }
            else{
                echo "\n<style type='text/css' media='all'>";
                echo "{$tag}{font-family:'{$this->main->fontNmEng}'{$important}}";
                echo "</style>";
            }
        }
        if($this->main->options['css']){
            echo "\n<style type='text/css' media='all' >\n";
            echo $this->main->options['css'];
            echo "\n</style>";
        }
    }
    
    public function btn_refresh() {
        echo "<form method='post' action='{$_SERVER["REQUEST_URI"]}'>";
        echo "<input type='submit' value='새로고침' class='button button-primary' />";
        echo "</form>";
    }
}
?>