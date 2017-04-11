<?php
include('../include/Mysql.class.php');
include('../config.php');

$db=new mysql($db_config, 0);
$action=$_REQUEST['action'];
$rs=array();
switch($action){
    case 'user_login':
        /*$m['username']=$_REQUEST['mobile'];
        $m['password']=$_REQUEST['pwd'];*/
        if(!empty($_REQUEST['mobile']) && !empty($_REQUEST['pwd'])) {
            $rs = $db->row_select_one('member', "mobilephone='" . $_REQUEST['mobile'] . "' and password='" . $_REQUEST['pwd'] . "'");
            if (empty($rs)) {
                $d['code'] = 401;
                $d['msg'] = "登录失败";
                echo json_encode($d,JSON_UNESCAPED_UNICODE);
            } else {
                $d['code'] = 200;
                $d['msg'] = "登录成功";
                $d['data'] = $rs;
                echo json_encode($d);
            }
        }else{
            $d['code'] = 400;
            $d['msg'] = "登录失败";
            echo json_encode($d);
        }
        break;
    case "cars":
        return 2;
    break;
    default:
        return 3;
}