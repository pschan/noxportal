<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Join extends CI_Controller {
    
    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('join_model');
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
    }
    
    public function index(){
        
        # 로그인 체크
        $this->app_lib->need_not_login();
        
        # 뷰 파일 로딩
        $this->load->complete('join_index', $this->DATA);
    }
    
    public function join_act(){
        
        # 로그인 체크
        $this->app_lib->need_not_login();

        try {
            if ($this->join_model->join_process()){
                $this->app_lib->go_url($this->GLOBAL_VARS['url']['main'], 
                                       '가입되었습니다.');
            } else {
                $this->app_lib->go_back('에러 : 알 수 없는 이유.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_back('에러 : '.$ex->getMessage());
        }
    }
}