<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Join_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function join_process(){
        
        # 입력 값 XSS 필터링
        $user_id = $this->app_lib->input_validation(
                trim($this->input->post('id',    TRUE)), 'id');
        $user_pw = $this->app_lib->input_validation(
                trim($this->input->post('pw',    FALSE)), 'pw');
        $user_pw_re = $this->app_lib->input_validation(
                trim($this->input->post('pw_re', FALSE)), 'pw');
        $user_name = $this->app_lib->input_validation(
                trim($this->input->post('name',  TRUE)), 'name');
        $user_hp = $this->app_lib->input_validation(
                trim(str_replace('-', '', $this->input->post('hp',    TRUE))), 'hp');
        $user_email = $this->app_lib->input_validation(
                trim($this->input->post('email', TRUE)), 'email');
        
        # 비밀번호 입력 값 동일한지 확인
        if ($user_pw != $user_pw_re){
            throw new Exception('입력하신 비빌번호와 재입력 비밀번호가 다릅니다.');
        }
        # 아이디 중복 확인
        $QUERY_ID_SAME_CHECK = $this->db->get_where(
            $this->GLOBAL_VARS['db']['tables']['members'], array('mb_id'=>$user_id));
        $RESULT_ID_SAME_CHECK = $QUERY_ID_SAME_CHECK->result_array();
        
        if (count($RESULT_ID_SAME_CHECK)){
            throw new Exception('이미 가입 된 아이디 입니다.');
        }
        
        # 제한문자 확인
        if ($this->app_lib->limit_check($user_id, 'id')){
            throw new Exception('사용할 수 없는 아이디 입니다.');
        }
        if ($this->app_lib->limit_check($user_name, 'text')){
            throw new Exception('사용할 수 없는 이름 입니다.');
        }
        
        # DB 입력
        $INS_USER_DATA = array(
            'mb_id'         => $user_id,
            'mb_pw'         => $this->app_lib->create_password_hash($user_pw),
            'mb_nick'       => '',
            'mb_name'       => $user_name,
            'mb_email'      => $user_email,
            'mb_hp'         => $user_hp,
            'mb_certi_key'  => '',
            'mb_certi_dt'   => '0000-00-00 00:00:00',
            'mb_state'      => '일반',
            'mb_reg_ip'     => $this->input->ip_address(),
            'mb_reg_dt'     => date('Y-m-d H:i:s'),
            'mb_pw_edit_dt' => '0000-00-00 00:00:00',
            'mb_login_dt'   => '0000-00-00 00:00:00'
        );
        $this->db->insert(
            $this->GLOBAL_VARS['db']['tables']['members'], $INS_USER_DATA);
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. 잠시 후 다시 이용해주십시요.');
        } else {
            return TRUE;
        }
    }
}