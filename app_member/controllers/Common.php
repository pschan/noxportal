<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {
    
    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
    }
    
    public function login(){
                
        # 로그인 체크
        $this->app_lib->need_not_login();
        
        # 로그인 후 이동 될 페이지
        $this->DATA['redirect'] = $this->input->get('redirect', TRUE)
                ? urlencode($this->input->get('redirect', TRUE)) : '';

        # 뷰 파일 로딩
        $this->load->complete('common_login', $this->DATA);
    }
    
    public function login_act(){
        
        # 로그인 체크
        $this->app_lib->need_not_login();
        
        # 입력 값 XSS 필터링
        $_input_id       = $this->input->post('id', TRUE);
        $_input_pw       = $this->input->post('pw', FALSE);
        $_input_redirect = $this->input->post('redirect', TRUE)
                ? $this->input->post('redirect', TRUE)
                : $this->GLOBAL_VARS['url']['main'];
        
        # 로그인 처리
        try {
            if ($this->Auth_model->login_process($_input_id, $_input_pw)){
                $this->app_lib->go_url($_input_redirect);
            } else {
                $this->app_lib->go_back('에러 : 알 수 없는 이유.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_back('에러 : '.$ex->getMessage());
        }
    }
    
    public function logout(){
        
        # 로그아웃 처리 후 www 루트페이지로 이동
        $this->Auth_model->logout_process();
        $this->app_lib->go_url($this->GLOBAL_VARS['url']['main']);
    }
}