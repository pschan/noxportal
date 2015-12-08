<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

    public $DATA = array();
    
    public function __construct() {
        parent::__construct();
        
        # 로그인 체크
        $this->app_lib->need_login();
        
        # 관리자 체크
        if ($this->app_lib->is_admin()!==TRUE){
            $this->app_lib->go_url($this->GLOBAL_VARS['url']['main'], 
                                   '관리자 페이지에 접근 하실 수 없습니다.');
        }
        
        # 모델 LOAD
        $this->load->model('member_model');
        
        # 관리자 메뉴 가져오기
        $this->DATA['admin']['menus'] = 
                $this->app_lib->get_admin_menu_array(get_class($this));
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
        $this->app_lib->set_app_data($this->DATA);
    }
    
    public function userlist(){
        
        # 리스트 출력 시작 숫자
        $num = is_numeric($this->input->get('num', TRUE)) 
                ? $this->input->get('num', TRUE) : 0;
        # 리스트 출력 페이지 제한 숫자
        $this->DATA['list_limit'] = 
                in_array($this->input->get('list_limit', TRUE), array(10,20,30,50,100)) 
                ? $this->input->get('list_limit', TRUE) : 10;

        # 검색 쿼리
        $this->member_model->set_search_query($this->DATA['search_query']);
        
        # 검색 필드 배열
        $this->DATA['search_field_array'] = 
                $this->member_model->get_search_field();
        # 검색 회원 상태 배열
        $this->DATA['search_state_array'] = 
                $this->member_model->get_search_state();
        
        # 전체 검색 결과 수
        $this->DATA['total_num'] = $this->member_model->get_total_user_rows();
        # 넘버링 시작 숫자
        $this->DATA['numbering'] = $this->DATA['total_num'] - (int)$num;
        
        # 사용자 정보 가져오기
        $this->DATA['userlist_query'] = 
                $this->member_model->get_user_list_num($num, $this->DATA['list_limit']);

        # 페이징
        $this->DATA['pagination'] = 
                $this->app_lib->get_pagination(
                        $this->GLOBAL_VARS['base_url'].'/member/userlist/',
                        $this->DATA['total_num'],
                        $this->DATA['list_limit']);
        
        # 상세검색 창 상태 유지 설정
        $this->member_model->set_search_detail($this->DATA);
        
        # 콘텐츠 상단 서브 헤더 설정
        $this->DATA['admin_sub_header'] = 
                $this->app_lib->admin_sub_header(
                        '회원목록',
                        '회원목록 검색 및 조회',
                        array('회원관리',
                              '<a href="/member/userlist/">회원목록</a>'));
        
        # 페이지 본문 상단 경고, 알림 메세지 박스
        $this->DATA['content_top_message'] .= 
                $this->app_lib->content_top_message('매우 위험 합니다.', 'alert');
        $this->DATA['content_top_message'] .= 
                $this->app_lib->content_top_message('주의 하십시요.', 'warning');
        # 뷰 파일 로딩
		$this->load->complete('member_userlist', $this->DATA);
    }
    
    public function userdetail(){
        
        try {
            if ($this->member_model->get_user_detail($this->DATA['userdetail'])!==TRUE){
                
                $this->app_lib->go_url('/member/userlist/', 
                                       '에러 : 알 수 없는 이유.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_url('/member/userlist/', 
                                   '에러 : '.$ex->getMessage());
        }
        
        # 콘텐츠 상단 서브 헤더 설정
        $this->DATA['admin_sub_header'] = 
                $this->app_lib->admin_sub_header(
                        '회원상세정보',
                        '회원상세정보 조회 및 수정',
                        array('회원관리',
                              '<a href="/member/userlist/">회원목록</a>'));
        
        # 페이지 본문 상단 경고, 알림 메세지 박스
        $this->DATA['content_top_message'] .= 
                $this->app_lib->content_top_message('경고 메세지 테스트', 'alert');
        # 뷰 파일 로딩
		$this->load->complete('member_userdetail', $this->DATA);
    }
    
    public function userdetail_act(){
        
        $user_mb_no = $this->input->post('mb_no', TRUE);
        if (!is_numeric($user_mb_no)){
            $this->app_lib->go_back('회원번호가 잘못되었습니다.');
        }
        
        try {
            if ($this->member_model->save_user_detail($user_mb_no)===TRUE){
                $this->app_lib->go_back('저장 되었습니다.');
            }
        } catch (Exception $ex) {
            $this->app_lib->go_back('에러 : '.$ex->getMessage());
        }
    }
    
    public function help(){

        # 콘텐츠 상단 서브 헤더 설정
        $this->DATA['admin_sub_header'] = 
                $this->app_lib->admin_sub_header(
                        '고객문의',
                        '1:1문의, 기타문의 조회',
                        array('회원관리',
                              '<a href="/member/help/">고객문의</a>'));
        
        # 페이지 본문 상단 경고, 알림 메세지 박스
        $this->DATA['content_top_message'] .= 
                $this->app_lib->content_top_message('경고 메세지 테스트', 'alert');
        # 뷰 파일 로딩
		$this->load->complete('member_help',  $this->DATA);
    }
}