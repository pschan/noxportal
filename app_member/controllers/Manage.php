<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
    
    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 로그인 체크
        $this->app_lib->need_login();
        
        $this->load->model('manage_model');
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
    }
    # 개인정보 변경
    public function changemyinfo(){

        #사용자 정보 가져오기
        $this->DATA['myinfo'] = 
            $this->manage_model->get_my_info($this->session->userdata('mb_no'));
        
        # 뷰 파일 로딩
        $this->load->complete('manage_changemyinfo', $this->DATA);
    }
    # 개인정보 변경 - 저장
    public function changemyinfo_act(){
        
        try {
            if ($this->manage_model->save_my_info()){
                $this->app_lib->go_back('저장 되었습니다.');
            } else {
                $this->app_lib->go_back('에러 : 알 수 없는 이유.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_back('에러 : '.$ex->getMessage());
        }
    }
    # 비밀번호 변경
    public function changepw(){
        
        #사용자 정보 가져오기
        $this->DATA['myinfo'] = 
            $this->manage_model->get_my_info($this->session->userdata('mb_no'));
        
        # 뷰 파일 로딩
        $this->load->complete('manage_changepw', $this->DATA);
    }
    # 비밀번호 변경 - 저장
    public function changepw_act(){
        
        try {
            if ($this->manage_model->save_pw()){
                $this->app_lib->go_back('변경 되었습니다.');
            } else {
                $this->app_lib->go_back('에러 : 알 수 없는 이유.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_back('에러 : '.$ex->getMessage());
        }
    }
    #회원 탈퇴
    public function leave(){
        
        # 뷰 파일 로딩
        $this->load->complete('manage_leave', $this->DATA);
    }
}