<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Find extends CI_Controller {
    
    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 로그인 체크
        $this->app_lib->need_not_login();
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
    }
    # 아이디 찾기
    public function id(){
        
        # 뷰 파일 로딩
        $this->load->complete('find_id', $this->DATA);
    }
    # 비밀번호 찾기
    public function pw(){
        
        # 뷰 파일 로딩
        $this->load->complete('find_pw', $this->DATA);
    }
}