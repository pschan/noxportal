<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function complete($view, $DATA){
        
        # footer 에서 출력 될 페이지별 스크립트 로딩
        $DATA['footer_script'] = $this->load_page_script($view, $DATA);
        
        $this->view('www_header', $DATA);
        $this->view($view, $DATA);
        $this->view('www_footer', $DATA);
    }
    
    # 페이지 별 하단 스크립트 로드 경로
    public function get_page_script_path($class_name, $method_name){
        
        return 'page_script/'.strtolower($class_name.'.'.$method_name.'.php');
    }
    
    # 페이지 별 하단 스크립트 파일 존재하면 로드
    public function load_page_script($view, $DATA){

        $array_view_path = explode('_', $view);
        
        $path = $this->get_page_script_path(
                $array_view_path[0], 
                $array_view_path[1]);
        
        if (file_exists(APPPATH.'/views/'.$path)){
            
            return $this->view($path, $DATA, TRUE);
        }
    }
}