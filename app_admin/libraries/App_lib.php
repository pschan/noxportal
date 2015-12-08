<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '../global/Library.php';

# App 종속적 라이브러리 구현
class App_lib extends Noxent\Common\Library{
    
    # View에 전달 할 APP 종속적인 변수 셋팅
    public function set_app_data(&$DATA){
        
        $CI =& get_instance();
        $CI->lang->load('common', 'english');
                
        $DATA['admin']['company'] = $CI->lang->line('company');
        # 관리자 상단 서브 헤더 변수 선언
        $DATA['admin_sub_header']    = '';
        # 콘텐츠 상단 경고, 알림 메세지 박스 변수 선언
        $DATA['content_top_message'] = '';
    }
    # 관리자 메뉴 배열
    public function get_admin_menu_array($current_menu=''){
        
        $menu_array = array();

        array_push($menu_array, array(
            'is_tree'    => TRUE,
            'active'     => $current_menu=='Community' ? 'active' : '',
            'name'       => 'Community',
            'title'      => '커뮤니티',
            'icon'       => 'fa fa-commenting-o',
            'tree_menus' => array(
                array(
                    'sub_name' => 'notice',
                    'title'    => '공지사항',
                    'url'      => '/community/board/notice/'
                )
            )
        ));
        array_push($menu_array, array(
            'is_tree'    => TRUE,
            'active'     => $current_menu=='Member' ? 'active' : '',
            'name'       => 'Member',
            'title'      => '회원관리',
            'icon'       => 'fa fa-user',
            'tree_menus' => array(
                array(
                    'sub_name' => 'userlist',
                    'title'    => '회원목록',
                    'url'      => '/member/userlist/'
                ),
                array(
                    'sub_name' => 'help',
                    'title'    => '고객문의',
                    'url'      => '/member/help/'
                )
            )
        ));
        array_push($menu_array, array(
            'is_tree' => FALSE,
            'active'  => $current_menu=='Setting' ? 'active' : '',
            'name'    => 'Setting',
            'title'   => '설정',
            'icon'    => 'fa fa-cog',
            'url'     => '/setting/'
        ));
        
        return $menu_array;
    }
    # 관리자 리스트 페이징
    public function get_pagination($base_url, $total_rows, $per_page){
        
        $CI =& get_instance();
        $CI->load->library('pagination');
        
        $config['base_url']   = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page']   = $per_page;
        
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'num';
        $config['reuse_query_string'] = TRUE;
        
        $config['first_link'] = FALSE;
        $config['last_link']  = FALSE;
        
        $config['full_tag_open']  = '<div class="box-footer clearfix">';
        $config['full_tag_open'] .= '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close']  = '</ul>';
        $config['full_tag_close'] .= '</div>';
        
        $config['prev_link'] = '&laquo;';
        $config['next_link'] = '&raquo;';
        $config['prev_tag_open']  = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open']  = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open']  = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']  = '<li><a href="#" class="curr_pag_bgcolor">';
        $config['cur_tag_close'] = '</a></li>';

        $CI->pagination->initialize($config);
        
        return $CI->pagination->create_links();
    }
    
    # 본문 상단 경고, 알림 메세지 박스
    public function content_top_message($msg, $type){
        
        $real_path = APPPATH.'/views/template/content_top_message.php';
        $path = 'template/content_top_message.php';
        
        if (!file_exists($real_path)){
            return;
        }
        
        $type_array = array(
            'alert'   => array('title'=>'경고', 'class'=>'alert-danger',  'icon'=>'fa-ban'), 
            'info'    => array('title'=>'알림', 'class'=>'alert-info',    'icon'=>'fa-info'), 
            'warning' => array('title'=>'주의', 'class'=>'alert-warning', 'icon'=>'fa-warning'), 
            'success' => array('title'=>'완료', 'class'=>'alert-success', 'icon'=>'fa-check')
        );
        
        if (!array_key_exists($type, $type_array)){
            $type = 'alert';
        }
        
        $CI =& get_instance();
        return $CI->load->view($path, 
                array('ctm_msg'   =>$msg, 
                      'ctm_title' =>$type_array[$type]['title'],
                      'ctm_clsss' =>$type_array[$type]['class'], 
                      'ctm_icon'  =>$type_array[$type]['icon']), TRUE);
    }
    
    # 본문 상단 헤더 셋팅
    public function admin_sub_header($title, $ash_discription, $ash_lists=array()){
        
        $real_path = APPPATH.'/views/template/admin_sub_header.php';
        $path = 'template/admin_sub_header.php';
        
        if (!file_exists($real_path)){
            return;
        }
        
        $CI =& get_instance();
        return $CI->load->view($path, 
                array('ash_title'       =>$title,
                      'ash_discription' =>$ash_discription,
                      'ash_lists'       =>$ash_lists), TRUE);
    }
    
    # 입력 값 체크 정규식
    public function admin_check_exp($type, $value){
        
        $type_array = array(
            'range'=>'/^([0-9]{4}-[0-9]{2}-[0-9]{2})(\s\-\s)([0-9]{4}-[0-9]{2}-[0-9]{2})$/'
        );
        
        if (!array_key_exists($type, $type_array)){
            return FALSE;
        }
        
        return preg_match($type_array[$type], $value);
    }
}