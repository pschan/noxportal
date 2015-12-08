<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends CI_Controller {

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
        
        # 모델 LOAD
        $this->load->model('community_model');
        
        # 관리자 메뉴 가져오기
        $this->DATA['admin']['menus'] = 
                $this->app_lib->get_admin_menu_array(get_class($this));
        
        # 뷰 파일에 전달 할 [공통 데이터]
        $this->app_lib->set_common_data($this->DATA);
        $this->app_lib->set_app_data($this->DATA);
    }
    
	public function board(){
        
        # 리스트 출력 시작 숫자
        $num = is_numeric($this->input->get('num', TRUE)) 
                ? $this->input->get('num', TRUE) : 0;
        # 리스트 출력 페이지 제한 숫자
        $this->DATA['list_limit'] = 
                in_array($this->input->get('list_limit', TRUE), array(10,20,30,50,100)) 
                ? $this->input->get('list_limit', TRUE) : 10;
        
        # 검색 쿼리
        $this->community_model->set_search_query($this->DATA['search_query']);
        
        # 검색 필드 배열
        $this->DATA['search_field_array'] = 
                $this->community_model->get_search_field();
        
        # 전체 검색 결과 수
        $this->DATA['total_num'] = $this->community_model->get_total_board_rows();
        # 넘버링 시작 숫자
        $this->DATA['numbering'] = $this->DATA['total_num'] - (int)$num;
        
        # 게시판 리스트 가져오기
        $this->DATA['board_list_query'] = 
                $this->community_model->get_board_list($num, $this->DATA['list_limit']);

        # 페이징
        $this->DATA['pagination'] = 
                $this->app_lib->get_pagination(
                        $this->GLOBAL_VARS['base_url'].'/community/board/notice/',
                        $this->DATA['total_num'],
                        $this->DATA['list_limit']);
        
        # 상세검색 창 상태 유지 설정
        $this->community_model->set_search_detail($this->DATA);
        
        # 콘텐츠 상단 서브 헤더 설정
        $this->DATA['admin_sub_header'] = 
                $this->app_lib->admin_sub_header(
                        '공지사항',
                        '관리자 전용 공지사항',
                        array('커뮤니티',
                              '<a href="/community/board/notice/">공지사항</a>'));
        # 뷰 파일 로딩
        $this->load->complete('community_board', $this->DATA);
	}
    
    public function ajax_content(){

        switch ($this->input->method(TRUE)) {
            
            # 게시글 상세정보 가져오기
            case!strcasecmp($this->input->method(TRUE), 'GET'):
                
                # 리스트 출력 시작 숫자
                $bc_no = $this->input->get('bc_no', TRUE);
                
                try {
                    $RESULT_ARRAY = $this->community_model->get_board_content($bc_no);
                    echo json_encode($RESULT_ARRAY, JSON_PRETTY_PRINT);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                break;
            # 게시글 신규 생성
            case!strcasecmp($this->input->method(TRUE), 'POST'):
                
                try {
                    if ($this->community_model->create_board_content()===TRUE){
                        echo json_encode(array(
                            'result'=> 'ok', 
                            'msg'   => '저장 되었습니다.'));
                    } else {
                        echo json_encode(array(
                            'result'=> 'no', 
                            'msg'   => '실패 : 알수없는오류.'));
                    }
                } catch (Exception $ex) {
                    echo json_encode(array(
                        'result' => 'no',
                        'msg'    => '실패 : '.$ex->getMessage()
                    ));
                }
                break;
            # 게시글 수정
            case!strcasecmp($this->input->method(TRUE), 'PUT'):

                try {
                    if ($this->community_model->save_board_content()===TRUE){
                        echo json_encode(array(
                            'result'=> 'ok', 
                            'msg'   => '저장 되었습니다.'));
                    } else {
                        echo json_encode(array(
                            'result'=> 'no', 
                            'msg'   => '실패 : 알수없는오류.'));
                    }
                } catch (Exception $ex) {
                    echo json_encode(array(
                        'result' => 'no',
                        'msg'    => '실패 : '.$ex->getMessage()
                    ));
                }
                break;
            # 게시글 삭제
            case!strcasecmp($this->input->method(TRUE), 'DELETE'):
                
                try {
                    if ($this->community_model->delete_board_content()===TRUE){
                        echo json_encode(array(
                            'result'=> 'ok', 
                            'msg'   => '삭제 되었습니다.'));
                    } else {
                        echo json_encode(array(
                            'result'=> 'no', 
                            'msg'   => '실패 : 알수없는오류.'));
                    }
                } catch (Exception $ex) {
                    echo json_encode(array(
                        'result' => 'no',
                        'msg'    => '실패 : '.$ex->getMessage()
                    ));
                }
                break;
        }
    }
}