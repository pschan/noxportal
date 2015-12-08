<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 로그인 체크
        $this->app_lib->need_login();
        
        # 관리자 체크
        if ($this->app_lib->is_admin()!==TRUE){
            $this->app_lib->go_url($this->GLOBAL_VARS['url']['main'], 
                                   '관리자 페이지에 접근 하실 수 없습니다..');
        }
        
        # 관리자 메뉴 가져오기
        $this->DATA['admin']['menus'] = 
                $this->app_lib->get_admin_menu_array(get_class($this));
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
        $this->app_lib->set_app_data($this->DATA);
    }
    
    public function index(){
        
        # 페이지 본문 상단 경고, 알림 메세지 박스
        $this->DATA['content_top_message'] .= 
                $this->app_lib->content_top_message('매우 위험 합니다.', 'alert');
        # 뷰 파일 로딩
		$this->load->complete('setting_index', $this->DATA);
    }
}