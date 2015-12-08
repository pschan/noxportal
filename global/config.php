<?php
defined('ISINDEX') OR exit('No direct script access allowed');
/*
 * 프로젝트 설정파일
 */
$GLOBAL_VARS = array();

/*
 * 배포 환경에서 TRUE 상태가 되지 않도록 주의 하십시요.
 * is_test TRUE : 에러 출력 등
 * is_debug TRUE : 프로파일링, DB쿼리 저장 등
*/
$GLOBAL_VARS['is_test']  = TRUE;
$GLOBAL_VARS['is_debug'] = FALSE;

# 도메인 명
$GLOBAL_VARS['domain']        = 'noxportal.com';
$GLOBAL_VARS['cookie_domain'] = '.noxportal.com';

# 프로젝트 Document root path
$GLOBAL_VARS['document_root'] = 
    filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS);

# 프로토콜 : http, https
$GLOBAL_VARS['is_secure'] = isSecure();
$GLOBAL_VARS['protocol']  = getProtocol($GLOBAL_VARS['is_secure']);

# DATABASE 관련 설정
$GLOBAL_VARS['db']['hostname'] = 'localhost';
$GLOBAL_VARS['db']['username'] = 'root';
$GLOBAL_VARS['db']['password'] = 'xampp';
$GLOBAL_VARS['db']['database'] = 'noxportal';
# DATABASE - TABLE 이름
$GLOBAL_VARS['db']['tables']['prefix'] = 'noxent_';
$GLOBAL_VARS['db']['tables']['members'] = 
    $GLOBAL_VARS['db']['tables']['prefix'].'members';
$GLOBAL_VARS['db']['tables']['admin_members'] = 
    $GLOBAL_VARS['db']['tables']['prefix'].'admin_members';
$GLOBAL_VARS['db']['tables']['board'] = 
        $GLOBAL_VARS['db']['tables']['prefix'].'board';
$GLOBAL_VARS['db']['tables']['board_content'] = 
        $GLOBAL_VARS['db']['tables']['prefix'].'board_content';

# autoload 설정 (콤마로 구분 문자열 배열에 추가)
$GLOBAL_VARS['autoload']['packages']  = array();
$GLOBAL_VARS['autoload']['libraries'] = array('database', 'session', 'app_lib');
$GLOBAL_VARS['autoload']['drivers']   = array();
$GLOBAL_VARS['autoload']['helper']    = array();
$GLOBAL_VARS['autoload']['config']    = array();
$GLOBAL_VARS['autoload']['language']  = array();
$GLOBAL_VARS['autoload']['model']     = array();

# 시간 설정
date_default_timezone_set('Asia/Seoul');

# 메타태그 정보
$GLOBAL_VARS['meta']['title']       = '녹스포탈';
$GLOBAL_VARS['meta']['author']      = '녹스엔터테인먼트';
$GLOBAL_VARS['meta']['keywords']    = '녹스,NOW,녹스엔터테인먼트,게임포탈,게임,웹게임,온라인게임';
$GLOBAL_VARS['meta']['description'] = '게임포탈 녹스엔터테인먼트 입니다.';

# app name 구분
switch (filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_SPECIAL_CHARS)){
    
    # 서브도메인 없이 접근 할 경우
	case $GLOBAL_VARS['domain']:
        # app_name, base_url 정의
		$GLOBAL_VARS['app_name']  = 'app_www';
        $GLOBAL_VARS['base_host'] = $GLOBAL_VARS['domain'];
        $GLOBAL_VARS['base_url']  = 
                $GLOBAL_VARS['protocol'].'://'.$GLOBAL_VARS['domain'];
		break;
    
    # 서브도메인 admin
	case 'admin.'.$GLOBAL_VARS['domain']:
        # app_name, base_url 정의
		$GLOBAL_VARS['app_name']  = 'app_admin';
        $GLOBAL_VARS['base_host'] = 'admin.'.$GLOBAL_VARS['domain'];
        $GLOBAL_VARS['base_url']  = 
                $GLOBAL_VARS['protocol'].'://'.'admin.'.$GLOBAL_VARS['domain'];
		break;

    # 서브도메인 member
	case 'member.'.$GLOBAL_VARS['domain']:
        # app_name, base_url 정의
		$GLOBAL_VARS['app_name']  = 'app_member';
        $GLOBAL_VARS['base_host'] = 'member.'.$GLOBAL_VARS['domain'];
        $GLOBAL_VARS['base_url']  = 
                $GLOBAL_VARS['protocol'].'://'.'member.'.$GLOBAL_VARS['domain'];
        
        # app 별 model autoload
        array_push($GLOBAL_VARS['autoload']['model'], 'Auth_model');
		break;
    
    default:
        header('Location: '.$GLOBAL_VARS['protocol']
            .'://'.$GLOBAL_VARS['domain']
            .filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS));
        exit;
}

# 공통 URL
$GLOBAL_VARS['url']['main']   = $GLOBAL_VARS['protocol'].'://'.$GLOBAL_VARS['domain'];
$GLOBAL_VARS['url']['member'] = 'http://member.'.$GLOBAL_VARS['domain'];
$GLOBAL_VARS['url']['login']  = $GLOBAL_VARS['url']['member'].'/common/login/';
$GLOBAL_VARS['url']['logout'] = $GLOBAL_VARS['url']['member'].'/common/logout/';
$GLOBAL_VARS['url']['join']   = $GLOBAL_VARS['url']['member'].'/join/';
$GLOBAL_VARS['url']['changemyinfo'] = $GLOBAL_VARS['url']['member'].'/manage/changemyinfo/';
$GLOBAL_VARS['url']['changepw']     = $GLOBAL_VARS['url']['member'].'/manage/changepw/';
# 현재 페이지 URL
$GLOBAL_VARS['url']['current'] = $GLOBAL_VARS['base_url']
        .filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);

# 리소스 경로
$GLOBAL_VARS['path_res'] = '/res';
$GLOBAL_VARS['path_common_css'] = $GLOBAL_VARS['path_res'].'/common/css';
$GLOBAL_VARS['path_common_img'] = $GLOBAL_VARS['path_res'].'/common/img';
$GLOBAL_VARS['path_common_js']  = $GLOBAL_VARS['path_res'].'/common/js';
$GLOBAL_VARS['path_common_etc']  = $GLOBAL_VARS['path_res'].'/common/etc';
$GLOBAL_VARS['path_app_css'] = 
        $GLOBAL_VARS['path_res'].'/'.$GLOBAL_VARS['app_name'].'/css';
$GLOBAL_VARS['path_app_img'] = 
        $GLOBAL_VARS['path_res'].'/'.$GLOBAL_VARS['app_name'].'/img';
$GLOBAL_VARS['path_app_js'] = 
        $GLOBAL_VARS['path_res'].'/'.$GLOBAL_VARS['app_name'].'/js';
$GLOBAL_VARS['path_app_etc'] = 
        $GLOBAL_VARS['path_res'].'/'.$GLOBAL_VARS['app_name'].'/etc';


/*
 * 본 파일 내에서만 쓰이는 함수 정의
 */
function isSecure(){

    $HTTPS       = filter_input(INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING);
    $SERVER_PORT = filter_input(INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_NUMBER_INT);
    return
        (!empty($HTTPS) && $HTTPS !== 'off')
        || $SERVER_PORT == 443;
}
function getProtocol($isSecure){

    return $isSecure === TRUE ? 'https' : 'http';
}