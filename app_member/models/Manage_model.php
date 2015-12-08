<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_my_info($mb_no){
        
        if (!$mb_no || !is_numeric($mb_no)){
            return FALSE;
        }
        
        $QUERY_MY_INFO = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['members'], 
                array('mb_no'=>$mb_no));
        
        if ($QUERY_MY_INFO){
            return $QUERY_MY_INFO->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function save_my_info(){
        
        $user_nick = $this->app_lib->input_validation(
                trim($this->input->post('nick',    TRUE)), 'nick');
        $user_name = $this->app_lib->input_validation(
                trim($this->input->post('name',    TRUE)), 'name');
        $user_hp = $this->app_lib->input_validation(
                trim(str_replace('-', '', $this->input->post('hp',    TRUE))), 'hp');
        $user_email = $this->app_lib->input_validation(
                trim($this->input->post('email',   TRUE)), 'email');
        
        # 제한문자 확인
        if ($this->app_lib->limit_check($user_nick, 'text')){
            throw new Exception('사용할 수 없는 닉네임 입니다.');
        }
        if ($this->app_lib->limit_check($user_name, 'text')){
            throw new Exception('사용할 수 없는 이름 입니다.');
        }
        
        $UPDATE_MY_INFO = array(
            'mb_nick'  => $user_nick,
            'mb_name'  => $user_name,
            'mb_hp'    => $user_hp,
            'mb_email' => $user_email
        );
        $this->db->where('mb_no', $this->session->userdata('mb_no'));
        $this->db->update($this->GLOBAL_VARS['db']['tables']['members'], 
                $UPDATE_MY_INFO);

        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 저장 에러. 잠시 후 다시 이용해주십시요.');
        } else {
            return TRUE;
        }
    }
    
    public function save_pw(){
        
        $user_old_pw = $this->app_lib->input_validation(
                trim($this->input->post('old_pw',    FALSE)), 'pw');
        $user_new_pw = $this->app_lib->input_validation(
                trim($this->input->post('new_pw', FALSE)), 'pw');
        $user_new_pw_re = $this->app_lib->input_validation(
                trim($this->input->post('new_pw_re', FALSE)), 'pw');
        
        # 신규 비밀번호 입력 값 동일한지 확인
        if ($user_new_pw != $user_new_pw_re){
            throw new Exception('입력하신 비빌번호와 재입력 비밀번호가 다릅니다.');
        }
        
        # 기존 비밀번호가 맞는지 확인
        $QUERY_USER_PW = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['members'], 
                array('mb_no'=>$this->session->userdata('mb_no')));
        
        $RESULT_USER_PW = $QUERY_USER_PW->result_array();
        
        if ($RESULT_USER_PW){
            
            # 비밀번호가 맞지 않음
            if (!password_verify($user_old_pw, $RESULT_USER_PW[0]['mb_pw'])){
                throw new Exception('기존 비밀번호가 잘못되었습니다.');
            }
            
            # 비밀번호 변경 DB 처리
            $UPDATE_CHANGE_PW = array(
                'mb_pw'         => $this->app_lib->create_password_hash($user_new_pw),
                'mb_pw_edit_dt' => date('Y-m-d H:i:s')
            );
            $this->db->where('mb_no', $RESULT_USER_PW[0]['mb_no']);
            $this->db->update($this->GLOBAL_VARS['db']['tables']['members'], 
                    $UPDATE_CHANGE_PW);
            
            $db_error = $this->db->error();
            if ($db_error['message']){
                throw new Exception('DB 에러. 잠시 후 다시 이용해주십시요.');
            } else {
                return TRUE;
            }
            
        } else {
            throw new Exception('기존 비밀번호가 잘못되었습니다.');
        }
    }
}