<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function make_user_search_query(){

        if ($this->DATA['search_query']['range']){
            
            $split_range = explode(' - ', $this->DATA['search_query']['range']);
            $start = $split_range[0].' 00:00:00';
            $end   = $split_range[1].' 23:59:59';
            
            $this->db->where("mb_reg_dt between '$start' and '$end'");
        }
        if ($this->DATA['search_query']['field'] 
                && $this->DATA['search_query']['key']){
            
            $this->db->like(
                    $this->DATA['search_query']['field'], 
                    $this->DATA['search_query']['key']);
        }
        if ($this->DATA['search_query']['state']){
            
            $this->db->where_in(
                    'mb_state', 
                    $this->DATA['search_query']['state']);
        }
    }
    
    public function get_user_list_num($num, $per_page){
        
        $this->make_user_search_query();
        
        $this->db->order_by('mb_no', 'DESC');
        $this->db->limit($per_page, $num);
        $this->db->from($this->GLOBAL_VARS['db']['tables']['members']);
        $QUERY_USER_LIST = $this->db->get();
        
        return $QUERY_USER_LIST;
    }
    
    public function get_total_user_rows(){
        
        $this->make_user_search_query();
        
        $QUERY_TOTAL_USER_ROWS = $this->db->get(
                $this->GLOBAL_VARS['db']['tables']['members']);
        
        return $QUERY_TOTAL_USER_ROWS->num_rows();
    }
    
    public function get_search_field_array(){
        
        # 검색 필드 라디오박스
        return array(
            'mb_no'     => '회원번호',
            'mb_id'     => '아이디',
            'mb_name'   => '이름',
            'mb_nick'   => '닉네임',
            'mb_email'  => '이메일',
            'mb_hp'     => '휴대폰',
            'mb_reg_ip' => '가입IP'
        );
    }
    
    public function get_search_field(){
        
        return $this->get_search_field_array();
    }
    
    public function get_search_state_array(){
        
        return array(
            '일반'   => FALSE,
            '관리자' => FALSE,
            '차단'   => FALSE,
            '휴면'   => FALSE,
            '탈퇴'   => FALSE
        );
    }
    
    public function get_search_state(){
        
        # 회원상태 체크박스
        $search_state_array = $this->get_search_state_array();
        
        if (is_array($this->DATA['search_query']['state'])){
            foreach ($search_state_array as $key => $value){
                if (in_array($key, $this->DATA['search_query']['state'])){
                    $search_state_array[$key] = TRUE;
                }
            }
        }
        
        return $search_state_array;
    }
    
    # 회원목록의 상세검색 쿼리 값 설정
    public function set_search_query(&$search_query){
        
        # 날짜 범위
        $search_query['range'] = $this->app_lib
                ->admin_check_exp('range', $this->input->get('range', TRUE))
                ? $this->input->get('range', TRUE) : '';
        # 검색어
        $search_query['key']   = $this->input->get('key',   TRUE);
        # 필드
        $search_query['field'] = $this->input->get('field', TRUE) 
                ? $this->input->get('field', TRUE) : 'mb_no';
        # 회원 상태
        $search_query['state'] = $this->input->get('state', TRUE);
    }
    
    # 회원목록의 상세검색 창 설정 유지를 위한 style 설정
    public function set_search_detail(&$DATA){
        
        if ($this->input->cookie('search_detail', TRUE)=='block'){
            $DATA['search_display'] = 'block';
            $DATA['search_button']  = 'btn-danger';
            $DATA['search_icon']    = 'fa-minus';
        } else {
            $DATA['search_display'] = 'none';
            $DATA['search_button']  = 'btn-primary';
            $DATA['search_icon']    = 'fa-plus';
        }
    }
    
    public function get_user_detail(&$return){
        
        $user_mb_no = $this->input->get('mb_no', TRUE);
        if (!is_numeric($user_mb_no)){
            throw new Exception('회원번호가 잘못되었습니다.');
        }
        
        $return = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['members'],
                array('mb_no'=>$user_mb_no))->result_array();
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. 잠시 후 다시 이용해주십시요.');
        } else {
            return TRUE;
        }
    }
    
    public function save_user_detail($user_mb_no){
        
        $user_mb_nick = $this->app_lib->input_validation(
                trim($this->input->post('mb_nick',  TRUE)), 'nick');
        $user_mb_name = $this->app_lib->input_validation(
                trim($this->input->post('mb_name',  TRUE)), 'name');
        $user_mb_email = $this->app_lib->input_validation(
                trim($this->input->post('mb_email', TRUE)), 'email');
        $user_mb_hp = $this->app_lib->input_validation(
                trim(str_replace('-', '', $this->input->post('mb_hp', TRUE))), 'hp');
        
        # 제한문자 확인
        if ($this->app_lib->limit_check($user_mb_nick, 'text')){
            throw new Exception('사용할 수 없는 닉네임 입니다.');
        }
        if ($this->app_lib->limit_check($user_mb_name, 'text')){
            throw new Exception('사용할 수 없는 이름 입니다.');
        }
        
        $UPDATE_USER_DETAIL = array(
            'mb_nick'  => $user_mb_nick,
            'mb_name'  => $user_mb_name,
            'mb_email' => $user_mb_email,
            'mb_hp'    => $user_mb_hp
        );
        
        $this->db->where('mb_no', $user_mb_no);
        $this->db->update($this->GLOBAL_VARS['db']['tables']['members'], 
                $UPDATE_USER_DETAIL);
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. 잠시 후 다시 이용해주십시요.');
        } else {
            return TRUE;
        }
    }
}