<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        
    }
    
    # 로그인 처리
    public function login_process($userid, $userpw){

        # 아이디 또는 비밀번호가 없을 경우
        if (!$userid || !$userpw){
            throw new Exception('아이디 또는 비밀번호를 모두 입력해주십시요.');
        }
        
        # DB 조회
        $this->db->select(
            $this->GLOBAL_VARS['db']['tables']['members'].'.mb_no, '
            . 'mb_id, '
            . 'mb_pw, '
            . 'mb_nick, '
            . 'mb_name, '
            . 'mb_certi_key, '
            . 'mb_state, '
            . 'mb_pw_edit_dt, '
            . 'mb_login_dt,'
            . 'ifnull(am_group,null) as am_group,'
            . 'ifnull(am_login_dt,null) as am_login_dt');
        $this->db->from($this->GLOBAL_VARS['db']['tables']['members']);
        $this->db->join($this->GLOBAL_VARS['db']['tables']['admin_members'], 
                $this->GLOBAL_VARS['db']['tables']['members'].'.mb_no = '
                .$this->GLOBAL_VARS['db']['tables']['admin_members'].'.mb_no',
                'left');
        $this->db->where($this->GLOBAL_VARS['db']['tables']['members'].'.mb_id', $userid);
        
        $QUERY_LOGIN  = $this->db->get();
        $RESULT_LOGIN = $QUERY_LOGIN->result_array();
        
        # DB 조회 결과에 따른 처리
        if ($RESULT_LOGIN){
            
            # 비밀번호가 맞지 않음
            if (!password_verify($userpw, $RESULT_LOGIN[0]['mb_pw'])){
                throw new Exception('로그인 정보가 잘못되었습니다.');
            }
            
            # 회원 상태에 따른 로그인 처리 여부
            if ($this->app_lib
                    ->login_possible_check($RESULT_LOGIN[0]['mb_state'])!==TRUE){
                throw new Exception($this->app_lib
                        ->login_possible_check($RESULT_LOGIN[0]['mb_state']));
            }
            
            # 로그인 DB 처리
            $UPDATE_MEMBERS_DATA = array(
                'mb_login_dt' => date('Y-m-d H:i:s')
            );
            $this->db->where('mb_no', $RESULT_LOGIN[0]['mb_no']);
            $this->db->update($this->GLOBAL_VARS['db']['tables']['members'], 
                    $UPDATE_MEMBERS_DATA);
            
            # 로그인 세션 처리
            $SESSION_USERDATA = array(
                'is_login'      => TRUE,
                'mb_no'         => $RESULT_LOGIN[0]['mb_no'],
                'mb_id'         => $RESULT_LOGIN[0]['mb_id'],
                'mb_nick'       => $RESULT_LOGIN[0]['mb_nick'],
                'mb_name'       => $RESULT_LOGIN[0]['mb_name'],
                'mb_state'      => $RESULT_LOGIN[0]['mb_state'],
                'mb_pw_edit_dt' => $RESULT_LOGIN[0]['mb_pw_edit_dt'],
                'mb_login_dt'   => $RESULT_LOGIN[0]['mb_login_dt'],
                'am_group'      => $RESULT_LOGIN[0]['am_group'],
                'am_login_dt'   => $RESULT_LOGIN[0]['am_login_dt']
            );
            $this->session->set_userdata($SESSION_USERDATA);

            $db_error = $this->db->error();
            if ($db_error['message']){
                throw new Exception('DB 에러. 잠시 후 다시 이용해주십시요.');
            } else {
                return TRUE;
            }
            
        } else {
            throw new Exception('로그인 정보가 잘못되었습니다.');
        }
    }
    
    # 로그아웃 처리
    public function logout_process(){
        
        $this->session->sess_destroy();
    }
}