<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community_model extends CI_Model {
    
    # 생성자에서 변수 할당
    private $bo_app_name;
    private $bo_code;
    
    # set_board에서 변수 할당
    private $bo_no;
    public  $bo_title;
    
    public function __construct(){
        parent::__construct();
        
        # board 테이블의 bo_app_name
        $this->bo_app_name = $this->GLOBAL_VARS['app_name'];
        # board 테이블의 bo_code : 없을 경우 기본 값 notice
        $this->bo_code = $this->uri->segment(3, 'notice');
        # 게시판 정보 셋팅
        $this->set_board();
    }
    
    # board_content 에서 게시글 리스트를 가져오기 위한 bo_no 값 셋팅
    public function set_board(){
        
        $QUERY_SET_BOARD = $this->bo_no = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['board'],
                array(
                    'bo_app_name' => $this->bo_app_name,
                    'bo_code'     => $this->bo_code
                ));
        $RESULT_SET_BOARD = $QUERY_SET_BOARD->row_array();
        
        $db_error = $this->db->error();
        if ($db_error['message'] || !count($RESULT_SET_BOARD)){
            throw new Exception('DB 에러. 게시판 셋팅 정보 불러오기 실패.');
        }
        
        $this->bo_no             = $RESULT_SET_BOARD['bo_no'];
        $this->bo_title          = $RESULT_SET_BOARD['bo_title'];
        
        return TRUE;
    }
    
    # 게시판 검색 쿼리 셋팅
    public function set_search_query(&$search_query){
        
        # 날짜 범위
        $search_query['range'] = $this->app_lib
                ->admin_check_exp('range', $this->input->get('range', TRUE))
                ? $this->input->get('range', TRUE) : '';
        # 검색어
        $search_query['key']   = $this->input->get('key',   TRUE);
        # 필드
        $search_query['field'] = $this->input->get('field', TRUE) 
                ? $this->input->get('field', TRUE) : 'bc_subject';
    }
    
    public function make_board_search_query(){

        # 삭제 된 게시글 제외
        $this->db->where('bc_is_del', 'N');
        
        if ($this->DATA['search_query']['range']){
            
            $split_range = explode(' - ', $this->DATA['search_query']['range']);
            $start = $split_range[0].' 00:00:00';
            $end   = $split_range[1].' 23:59:59';
            
            $this->db->where("bc_reg_dt between '$start' and '$end'");
        }
        if ($this->DATA['search_query']['field'] 
                && $this->DATA['search_query']['key']){
            
            $this->db->like(
                    $this->DATA['search_query']['field'], 
                    $this->DATA['search_query']['key']);
        }
    }
    
    public function get_total_board_rows(){
        
        $this->make_board_search_query();
        
        $QUERY_TOTAL_USER_ROWS = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['board_content'],
                array('bc_bo_no' =>  $this->bo_no));
        
        return $QUERY_TOTAL_USER_ROWS->num_rows();
    }
    
    public function get_board_list($num, $per_page){
        
        $this->make_board_search_query();
        
        $this->db->where('bc_bo_no', $this->bo_no);
        $this->db->order_by('bc_no', 'DESC');
        $this->db->limit($per_page, $num);
        $this->db->from($this->GLOBAL_VARS['db']['tables']['board_content']);
        $QUERY_USER_LIST = $this->db->get();
        
        return $QUERY_USER_LIST;
    }
    
    public function get_board_content($bc_no){
        
        if (!is_numeric($bc_no)){
            throw new Exception('게시글 번호가 잘못되었습니다.');
        }
        
        $QUERY_BOARD_CONTENT = $this->db->get_where(
                $this->GLOBAL_VARS['db']['tables']['board_content'],
                array('bc_no' =>  $bc_no));
        return $QUERY_BOARD_CONTENT->result_array();
    }
    
    public function create_board_content(){
        
        $_input_bc_subject = $this->input->post('bc_subject', TRUE);
        $_input_bc_content = $this->input->post('bc_content', TRUE);
        
        if ($_input_bc_subject=='' || $_input_bc_content==''){
            throw new Exception('제목과 내용을 모두 입력해주십시요.');
        }
        
        $INSERT_BOARD_CONTENT = array(
            'bc_bo_no'   => $this->bo_no,
            'bc_is_del'  => 'N',
            'bc_mb_no'   => $this->session->userdata('mb_no'),
            'bc_subject' => htmlspecialchars($_input_bc_subject),
            'bc_content' => $_input_bc_content,
            'bc_view'    => 0,
            'bc_good'    => 0,
            'bc_reg_ip'  => $this->input->ip_address(),
            'bc_reg_dt'  => date('Y-m-d H:i:s'),
            'bc_edit_dt' => '0000-00-00 00:00:00'
        );
        $this->db->insert($this->GLOBAL_VARS['db']['tables']['board_content'],
                $INSERT_BOARD_CONTENT);
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. '.$db_error['message']);
        }
        return TRUE;
    }
    
    public function save_board_content(){
        
        $_input_bc_no      = $this->input->input_stream('bc_no', TRUE);
        $_input_bc_subject = $this->input->input_stream('bc_subject', TRUE);
        $_input_bc_content = $this->input->input_stream('bc_content', TRUE);

        if (!is_numeric($_input_bc_no) || $_input_bc_no < 1){
            throw new Exception('게시글 번호가 잘못되었습니다.');
        }
        if ($_input_bc_subject=='' || $_input_bc_content==''){
            throw new Exception('제목과 내용을 모두 입력해주십시요.');
        }
        
        $UPDATE_BOARD_CONTENT = array(
            'bc_subject' => htmlspecialchars($_input_bc_subject),
            'bc_content' => $_input_bc_content,
            'bc_edit_dt' => date('Y-m-d H:i:s')
        );
        $this->db->where('bc_no', $_input_bc_no);
        $this->db->update($this->GLOBAL_VARS['db']['tables']['board_content'],
                $UPDATE_BOARD_CONTENT);
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. '.$db_error['message']);
        }
        return TRUE;
    }
    
    public function delete_board_content(){
        
        $_input_bc_no = $this->input->input_stream('bc_no', TRUE);
        
        if (!is_numeric($_input_bc_no) || $_input_bc_no < 1){
            throw new Exception('게시글 번호가 잘못되었습니다.');
        }
        
        $UPDATE_DELETE_BOARD = array('bc_is_del' => 'Y');
        
        $this->db->where('bc_no', $_input_bc_no);
        $this->db->update($this->GLOBAL_VARS['db']['tables']['board_content'],
                $UPDATE_DELETE_BOARD);
        
        $db_error = $this->db->error();
        if ($db_error['message']){
            throw new Exception('DB 에러. '.$db_error['message']);
        }
        return TRUE;
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
    
    public function get_search_field_array(){
        
        # 검색 필드 라디오박스
        return array(
            'bc_subject' => '제목',
            'bc_content' => '내용',
            'bc_mb_no'   => '회원번호'
        );
    }
    
    public function get_search_field(){
        
        return $this->get_search_field_array();
    }
}