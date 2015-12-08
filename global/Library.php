<?php
namespace Noxent\Common;

defined('BASEPATH') OR exit('No direct script access allowed');

class Library {
    
    # 로그인 여부 체크
    public function is_login(){
     
        $CI =& get_instance();

        if ($CI->session->userdata('is_login')===TRUE){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    # 관리자 여부 체크
    public function is_admin(){
        
        $CI =& get_instance();

        if ($CI->session->userdata('am_group')!==null){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    # VIEW 파일에 전달 할 공통 변수
    public function set_common_data(&$DATA){
        
        $CI =& get_instance();
        
        $DATA['GLOBAL_VARS'] = $CI->GLOBAL_VARS;
        $DATA['session'] = $CI->session->userdata();
        $DATA['is_login'] = $this->is_login();
        
        # 로그인 상태면 닉네임, 이름, 아이디 중 하나 가져오기
        if ($DATA['is_login']===TRUE && isset($DATA['session'])){
            $DATA['view_name'] = $this->get_view_name($DATA['session']);
        }
        
        # 디버그 상태면 프로파일링 켜기
        if (IS_DEBUG === TRUE){
            $CI->output->enable_profiler(TRUE);
        }
    }
    # 게시판 등에 보여질 이름 (닉네임, 이름, 아이디 순)
    public function get_view_name($SESSION){
        
        return $SESSION['mb_nick'] 
            ? $SESSION['mb_nick']
            : ($SESSION['mb_name'] ? $SESSION['mb_name'] : $SESSION['mb_id']);
               
    }
    /*
     * 회원 상태에 따른 로그인 가능여부
     * IN : mb_state
     * OUT : 로그인 가능(TRUE), 로그인 불가능(불가능 이유 STRING)
     */
    public function login_possible_check($mb_state){
        
        switch ($mb_state){
            
            case '일반':
                return TRUE;
            case '차단':
                return '로그인이 차단 된 회원입니다. 고객센터로 문의해주십시요.';
            case '휴면':
                return '최근 1년간 로그인 기록이 없는 휴면계정입니다.';
            default:
                # TODO : 오류 로그 처리
                return '회원 상태오류. 고객센터로 문의해주십시요.';
        }
    }
    /*
     * 제한 아이디, 문자 확인
     * IN : 확인 문자, 제한 타입
     * OUT : 제한문자일 경우 또는 타입없을 경우(TRUE), 아닐경우(FALSE)
     */
    public function limit_check($value, $type){
        
        require_once ('Limit.php');
        return Limit::check($value, $type);
    }
    /*
     * 입력 값 검증
     * ---return---
     * 입력값 정상 : 원본 $value 리턴
     * 입력값 오류 또는 타입에러, 기타에러 : 오류메세지 출력 후 뒤로가기
     */
    public function input_validation($value, $type){
        
        $array_type = array(
            'id' => array(
                'title'   => '아이디',
                'regexp'  => '/^[0-9a-zA-Z]+[0-9a-zA-Z\_\-]+$/',
                'min'     => 5,
                'max'     => 24,
                'notNull' => TRUE),
            'pw' => array(
                'title'   => '비밀번호',
                'regexp'  => '',
                'min'     => 8,
                'max'     => 1000,
                'notNull' => TRUE),
            'nick' => array(
                'title'   => '닉네임',
                'regexp'  => '/^[0-9a-zA-Z가-힣]+[0-9a-zA-Z가-힣]+$/',
                'min'     => 2,
                'max'     => 12,
                'notNull' => FALSE),
            'name' => array(
                'title'   => '이름',
                'regexp'  => '/^[0-9a-zA-Z가-힣]+[0-9a-zA-Z가-힣]+$/',
                'min'     => 2,
                'max'     => 10,
                'notNull' => FALSE),
            'hp' => array(
                'title'   => '휴대폰번호',
                'regexp'  => '/^01([0|1|6|7|8|9]{1})([1-9]{1})([0-9]{2,3})([0-9]{4})$/',
                'min'     => 10,
                'max'     => 11,
                'notNull' => FALSE),
            'email' => array(
                'title'   => '이메일주소',
                'regexp'  => '/^[0-9a-zA-Z]([\-.\w]*[0-9a-zA-Z\-_+])*@([0-9a-zA-Z][\-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9}$/',
                'min'     => 5,
                'max'     => 40,
                'notNull' => FALSE)
        );
        $array_error_str = array(
            10 => '입력 값 규칙 오류',
            20 => '길이 제한 오류',
            30 => '빈 값 오류',
            88 => '검증 타입 값 오류',
            99 => '코드 값 오류'
        );        
        # 검증 타입 값 유효성 체크
        if (!array_key_exists($type, $array_type)){

            $error_value = $this->make_return_input_vailidation(88, $array_error_str);
            
        }
        # 빈 값 체크
        if (!isset($error_value) 
            && $array_type[$type]['notNull'] && $value==''){
            
            $error_value = $this->make_return_input_vailidation(30, $array_error_str);
            
        } 
        # 길이 제한 체크
        if (!isset($error_value) 
            && $value != ''
            && (mb_strlen($value, 'utf-8') > $array_type[$type]['max']
            || mb_strlen($value, 'utf-8') < $array_type[$type]['min'])){
            
            $error_value = $this->make_return_input_vailidation(20, $array_error_str);
            
        } 
        # 정규식 체크
        if (!isset($error_value) 
            && $value != ''
            && $array_type[$type]['regexp']!=''
            && !preg_match_all($array_type[$type]['regexp'], $value)){
            
            $error_value = $this->make_return_input_vailidation(10, $array_error_str);
            
        }
        
        # 검증 오류 값이 있을 경우
        if (isset($error_value)){
            
            $this->go_back($array_type[$type]['title'].'가 잘못되었습니다.\n'
                        .'code : '.$error_value['code'].'\n'
                        .'message : '.$error_value['msg']);
        } else {
            # 검증 오류가 없을 경우 원본 값 리턴
            return $value;
        }
    }
    public function make_return_input_vailidation($code, $array_error_str){
        
        if (!array_key_exists($code, $array_error_str)){
            
            $code = 99;
        }
        return array('code'=>$code, 'msg'=>$array_error_str[$code]);
    }
    
    public function get_parse_referer($type=''){
        
        // [scheme] => http, https
        // [host] => example.com
        // [path] => /path/index.php
        // [query] => arg=value
        // [fragment] => # 해쉬태그값
        $type_array = array(
            'SCHEME'   =>PHP_URL_SCHEME,
            'HOST'     =>PHP_URL_HOST,
            'PATH'     =>PHP_URL_PATH,
            'FRAGMENT' =>PHP_URL_FRAGMENT
        );
        
        $CI =& get_instance();
        
        if (!$CI->input->server('HTTP_REFERER')){
            return FALSE;
        }
        if (!$type){
            
            return parse_url($CI->input->server('HTTP_REFERER'));
        }
        if (in_array(strtoupper($type), array('SCHEME','HOST','PATH','FRAGMENT'))){
            
            return parse_url($CI->input->server('HTTP_REFERER'), $type_array[strtoupper($type)])
                ? parse_url($CI->input->server('HTTP_REFERER'), $type_array[strtoupper($type)])
                : FALSE;
        }
        return FALSE;
    }
    
    /*
     * 경고문구 출력 후 뒤로가기
     * 
     * TODO : 뒤로 갈 페이지가 없을 때 이동 할 특정 페이지로 이동시키는 부분
     */
    public function go_back($msg=''){
        
        $return_html = 
            '<meta http-equiv="content-type" content="text/html; charset=UTF-8">'.PHP_EOL;
        $return_html .= '<script type="text/javascript">'.PHP_EOL;
        
        if ($msg!=''){
            $return_html .= 'alert("'.$msg.'");'.PHP_EOL;
        }
        
        $return_html .= 'history.back(-1);'.PHP_EOL;
        $return_html .= '</script>'.PHP_EOL;
        
        echo $return_html;
        exit;
    }
    
    public function go_url($url, $msg=''){
		
        $return_html = 
            '<meta http-equiv="content-type" content="text/html; charset=UTF-8">'.PHP_EOL;
        $return_html .= '<script type="text/javascript">'.PHP_EOL;
        
        if ($msg!=''){
            $return_html .= 'alert("'.$msg.'");'.PHP_EOL;
        }
        $return_html .= 'location.replace("'.$url.'"); </script>'.PHP_EOL;
        
        $return_html .= '</script>'.PHP_EOL;
        
        echo $return_html;
        exit;
    }
    
    # 로그인이 필요한 페이지에서 호출
    public function need_login(){
        
        if ($this->is_login()===FALSE){
            
            $CI =& get_instance();
            
            $this->go_url($CI->GLOBAL_VARS['url']['login'].'?redirect='
                    .urlencode($CI->GLOBAL_VARS['url']['current']), 
                    '로그인이 필요한 페이지 입니다.');
        }
    }
    # 로그인 상태에서 접근하지 못하는 페이지에서 호출
    public function need_not_login(){
        
        if ($this->is_login()===TRUE){
            
            $CI =& get_instance();
            
            $this->go_url($CI->GLOBAL_VARS['url']['main']);
        }
    }
}
