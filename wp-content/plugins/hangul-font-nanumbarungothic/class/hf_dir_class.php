<?php
final class hf_dir_class {
    private $plugin_dir;
    private $inc_dir;
    private $plugin_url;
    private $css_url;
    private $js_url;
    private $font_url;
    private $css_dir;
    
    function __construct($dir){
        $this->plugin_dir = dirname($dir);
        $this->inc_dir = $this->plugin_dir . '/include';
        $this->css_dir = $this->plugin_dir . '/css';

        $this->plugin_url = plugins_url('', $dir);
        $this->css_url = $this->plugin_url . '/css';
        $this->js_url = $this->plugin_url . '/js';
        $this->font_url = $this->plugin_url . '/font';
    }
    
    public function plugin_dir(){
        return $this->plugin_dir;
    }
    public function inc_dir(){
        return $this->inc_dir;
    }
    public function css_dir(){
        return $this->css_dir;
    }
    public function font_url(){
        return $this->font_url;
    }
    public function plugin_url(){
        return $this->plugin_url;
    }
    public function css_url(){
        return $this->css_url;
    }
    public function js_url(){
        return $this->js_url;
    }
}
?>