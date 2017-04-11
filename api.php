<?php

/*if($_REQUEST['jcs_id']){
    session_id($_REQUEST['jcs_id']);
}*/
session_start();
include_once('../include/Mysql.class.php');
include_once('../config.php');
include_once('../include/function.func.php');
include_once('../include/common.func.php');

$db=new mysql($db_config, 0);
$action=$_REQUEST['action'];//接口名称
$page = isset($_GET['page'])?$_GET['page']:1;  //当前页码
$pageSize = isset($_GET['pagesize'])?$_GET['pagesize']:20; //每页显示的数量
$offset = ($page - 1)*$pageSize;
switch($action){
    case 'jcs_login'://登录接口
        if(!empty($_REQUEST['jcs_id']) && !empty($_REQUEST['pwd'])) {
            $jcs_id = intval(trim($_REQUEST['jcs_id']));
            $pwd = trim($_REQUEST['pwd']);
            $sql="select * from cars_jianceshi where jcs_id = '$jcs_id' ";
            $rs=$db->row_query_one($sql);
            if (!empty($rs)) {
                if($rs['pwd']==md5($pwd)){
                    $d['code'] = 1;
                    $d['data'] = $rs;
                    echo json_encode($d);
                    exit;
                }else{
                    $d['code'] = 0;
                    $d['data'] = '密码错误！';
                    echo json_encode($d);
                    exit;
                }
            } else {
                $d['code'] = 0;
                $d['data'] = '检测师工号不存在！';
                echo json_encode($d);
                exit;
            }
        }
        break;

    case "jcs_work"://工作接口
        $jcs_id = intval(trim($_REQUEST['jcs_id']));
        $sql="select * from cars_details where jcs_id = '$jcs_id' order by id desc limit $offset , $pageSize";
        $rs=$db->row_query($sql);
        if (empty($rs)) {
            $d['code'] = 0;
            $d['data'] = '没有工作记录！';
            echo json_encode($d);
            exit;
        } else {
            $d['code'] = 1;
            $d['data'] = $rs;
            echo json_encode($d);
            exit;
        }
        break;

    case "jcs_msg"://修改备注信息
        $id=intval($_REQUEST['id']);
        $where['jcs_msg']=$_REQUEST['jcs_msg'];
        $rs=$db->row_update('details',$where,"id = '".$id."'");
        if (empty($rs)) {
            $d['code'] = 0;
            $d['data'] = '更新备注信息失败！';
            echo json_encode($d);
            exit;
        } else {
            $d['code'] = 1;
            $d['data'] = '更新备注信息成功！';
            echo json_encode($d);
            exit;
        }
        break;

    case "xs"://修改销售状态接口
        $id=intval($_REQUEST['id']);
        $where['xs']=$_REQUEST['xs'];
        $rs=$db->row_update('details',$where,"id = '".$id."'");
        if (empty($rs)) {
            $d['code'] = 0;
            $d['data'] = '更新销售状态失败！';
            echo json_encode($d);
            exit;
        } else {
            $d['code'] = 1;
            $d['data'] = '更新销售状态成功！';
            echo json_encode($d);
            exit;
        }
        break;

    case "czxx":
        $where['cars_num']=$_REQUEST['cars_num'];
        $where['jcs_id']=$_REQUEST['jcs_id'];
        $where['name']=$_REQUEST['name'];
        $where['mobile']=$_REQUEST['mobile'];
        $where['cars_cp']=$_REQUEST['cars_cp'];
        $where['xsz']=upload_img('xsz');
        $where['djz']=upload_img('djz');
        $where['mp']=upload_img('mp');
        $where['cjh']=$_REQUEST['cjh'];
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('info',$where);
            $id=$db->insert_id();
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '车主信息添加失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = array('id'=>$id);
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $id=$_REQUEST['id'];
                $rs=$db->row_update('info',$where,"id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '车主信息修改失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '车主信息修改成功！';
                    echo json_encode($d);
                    exit;
                }
            }

        }
        break;

    case "jcxx":
        $id=$_REQUEST['id'];
        $where['p_brand']=$_REQUEST['p_brand'];
        $where['p_subbrand']=$_REQUEST['p_subbrand'];
        $where['p_subsubbrand']=$_REQUEST['p_subsubbrand'];
        $where['sp_time']=$_REQUEST['sp_time'];
        $where['km']=$_REQUEST['km'];
        $where['aid']=$_REQUEST['aid'];
        $where['cid']=$_REQUEST['cid'];
        $where['s_aid']=$_REQUEST['s_aid'];
        $where['s_cid']=$_REQUEST['s_cid'];
        $where['bsx']=$_REQUEST['bsx'];
        $where['pl']=$_REQUEST['pl'];
        $where['p_emission']=$_REQUEST['p_emission'];
        $where['type']=$_REQUEST['type'];
        $where['gh']=$_REQUEST['gh'];
        $where['nj_overtime']=$_REQUEST['nj_overtime'];
        $where['jqx_overtime']=$_REQUEST['jqx_overtime'];
        $where['syx_overtime']=$_REQUEST['syx_overtime'];
        $where['color']=$_REQUEST['color'];
        $where['jd_pic']=upload_img('jd_pic');
        $where['jqx_pic']=upload_img('jqx_pic');
        $where['hb_pic']=upload_img('hb_pic');
        $rs=$db->row_update('jc',$where,"info_id=".$id);
        if($_REQUEST['method']=="add"){
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '基础信息添加失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '基础信息添加成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '基础信息修改失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '基础信息修改成功！';
                echo json_encode($d);
                exit;
            }
        }


        break;

    case "sgpc":
        $where['info_id']=$_REQUEST['id'];
        $where['zaz']=$_REQUEST['zaz'];
        $where['yaz']=$_REQUEST['yaz'];
        $where['zbz']=$_REQUEST['zbz'];
        $where['ybz']=$_REQUEST['ybz'];
        $where['zcz']=$_REQUEST['zcz'];
        $where['ycz']=$_REQUEST['ycz'];
        $where['zqzl']=$_REQUEST['zqzl'];
        $where['yqzl']=$_REQUEST['yqzl'];
        $where['zhzl']=$_REQUEST['zhzl'];
        $where['yhzl']=$_REQUEST['yhzl'];
        $where['yhzl']=$_REQUEST['yhzl'];
        $where['qfzl']=$_REQUEST['qfzl'];
        $where['hfzl']=$_REQUEST['hfzl'];
        $where['zqjzqxg']=$_REQUEST['zqjzqxg'];
        $where['yqjzqxg']=$_REQUEST['yqjzqxg'];
        $where['zhjzqxg']=$_REQUEST['zhjzqxg'];
        $where['yhjzqxg']=$_REQUEST['yhjzqxg'];
        $where['fdjfhq']=$_REQUEST['fdjfhq'];
        $where['hbxdb']=$_REQUEST['hbxdb'];
        $where['zydc']=$_REQUEST['zydc'];
        $where['fdjxs']=$_REQUEST['fdjxs'];
        $where['hbxbj']=$_REQUEST['hbxbj'];
        $where['zyhgn']=$_REQUEST['zyhgn'];
        $where['hzydb']=$_REQUEST['hzydb'];
        $where['aqddb']=$_REQUEST['aqddb'];
        $where['djdt']=$_REQUEST['djdt'];
        $where['yxlbdb']=$_REQUEST['yxlbdb'];
        $where['yhgdb']=$_REQUEST['yhgdb'];
        $where['clfgj']=$_REQUEST['clfgj'];
        $where['sg_msg']=$_REQUEST['sg_msg'];

        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('sgpc',$where);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '事故排查检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '事故排查检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('sgpc',$where,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '修改事故排查检测失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '修改事故排查检测成功！';
                    echo json_encode($d);
                    exit;
                }
            }

        }

       /* $arr=array(
            '奥迪'=>array(
                '一汽奥迪'=>array(
                    'A3'=>array('2014'=>array('Sportback 35TFSI 豪华型')),
                    'A4'=>array('2008'=>array('2.0TFSI 豪华型'),'2017'=>array('1.8T 手动 个性版'))),
                '进口奥迪'=>array(
                    '进口A3'=>array('2014'=>array('Limousine 40 TFSI S line 豪华型'),'2013'=>array('Sportback 30 TFSI 舒适型')),
                    '进口A4'=>array('2008'=>array('2.0TFSI 豪华型'),'2007'=>array('1.8T 手动 个性版'))
                )));
        print_r($arr);
        echo json_encode($arr);*/
        break;

    case "fdj":
        $where['info_id']=$_REQUEST['id'];
        $where['fdj_cx']=$_REQUEST['fdj_cx'];
        $where['fdj_lh']=$_REQUEST['fdj_lh'];
        $where['cgq_sh']=$_REQUEST['cgq_sh'];
        $where['gx_ps']=$_REQUEST['gx_ps'];
        $where['fdjg_ly']=$_REQUEST['fdjg_ly'];
        $where['sx_ps']=$_REQUEST['sx_ps'];
        $where['jydk']=$_REQUEST['jydk'];
        $where['jyym']=$_REQUEST['jyym'];
        $where['fdym']=$_REQUEST['fdym'];
        $where['zlbym']=$_REQUEST['zlbym'];
        $where['bsxdk']=$_REQUEST['bsxdk'];
        $where['ls']=$_REQUEST['ls'];
        $where['zdy']=$_REQUEST['zdy'];
        $where['ktysj']=$_REQUEST['ktysj'];
        $where['zxzlb']=$_REQUEST['zxzlb'];
        $where['fdj_msg']=$_REQUEST['fdj_msg'];
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('fdj',$where);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '发动机检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '发动机检测检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('fdj',$where,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '修改发动机检测失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '修改发动机检测成功！';
                    echo json_encode($d);
                    exit;
                }
            }

        }
        break;

    case "dp":
        $where['info_id']=$_REQUEST['id'];
        $where['dp']=$_REQUEST['dp'];
        $where['yhjzq']=$_REQUEST['yhjzq'];
        $where['yqjzq']=$_REQUEST['yqjzq'];
        $where['zqjzq']=$_REQUEST['zqjzq'];
        $where['zhjzq']=$_REQUEST['zhjzq'];
        $where['dp_msg']=$_REQUEST['dp_msg'];
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('waiguan',$where);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '发动机检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '发动机检测检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('waiguan',$where,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '修改发动机检测失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '修改发动机检测成功！';
                    echo json_encode($d);
                    exit;
                }
            }

        }
        break;

    case "xtsb":
        $where['info_id']=$_REQUEST['id'];
        $where['bsxd']=$_REQUEST['bsxd'];
        $where['zdd']=$_REQUEST['zdd'];
        $where['absd']=$_REQUEST['absd'];
        $where['aqql']=$_REQUEST['aqql'];
        $where['fdjd']=$_REQUEST['fdjd'];
        $where['qwd']=$_REQUEST['qwd'];
        $where['hwd']=$_REQUEST['hwd'];
        $where['jgd']=$_REQUEST['jgd'];
        $where['ygd']=$_REQUEST['ygd'];
        $where['qzxd']=$_REQUEST['qzxd'];
        $where['hzxd']=$_REQUEST['hzxd'];
        $where['dcd']=$_REQUEST['dcd'];
        $where['scd']=$_REQUEST['scd'];
        $where['sndd']=$_REQUEST['sndd'];
        $where['sb_msg']=$_REQUEST['sb_msg'];
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('xtsb',$where);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '系统设备检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '系统设备检测检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('xtsb',$where,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '修改系统设备检测失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '修改系统设备检测成功！';
                    echo json_encode($d);
                    exit;
                }
            }
        }
        break;

    case "clpz":
        $where['yk_keys']=$_REQUEST['yk_keys'];
        $where['qjd']=$_REQUEST['qjd'];
        $where['tool']=$_REQUEST['tool'];
        $where['sjp']=$_REQUEST['sjp'];
        $where['mhq']=$_REQUEST['mhq'];
        $where['bt']=$_REQUEST['bt'];
        $rs=$db->row_update('info',$where,"info_id=".$_REQUEST['id']);
        if($_REQUEST['method']=="add"){
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '车辆配置检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '车辆配置检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '车辆配置修改失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '车辆配置修改成功！';
                echo json_encode($d);
                exit;
            }
        }
        break;

    case "wgjc":
        $where['zq_bxg']=$_REQUEST['zq_bxg'];$where['zq_bxg_pic']=upload_img('zq_bxg_pic');
        $where['yq_bxg']=$_REQUEST['yq_bxg'];$where['yq_bxg_pic']=upload_img('yq_bxg_pic');
        $where['zh_bxg']=$_REQUEST['zh_bxg'];$where['zh_bxg_pic']=upload_img('zh_bxg_pic');
        $where['yh_bxg']=$_REQUEST['yh_bxg'];$where['yh_bxg_pic']=upload_img('yh_bxg_pic');
        $where['zq_yzb']=$_REQUEST['zq_yzb'];$where['zq_yzb_pic']=upload_img('zq_yzb_pic');
        $where['yq_yzp']=$_REQUEST['yq_yzp'];$where['yq_yzb_pic']=upload_img('yq_yzb_pic');
        $where['zh_yzb']=$_REQUEST['zh_yzb'];$where['zh_yzb_pic']=upload_img('zh_yzb_pic');
        $where['zh_yzb']=$_REQUEST['zh_yzb'];$where['zh_yzb_pic']=upload_img('zh_yzb_pic');
        $where['yh_yzb']=$_REQUEST['yh_yzb'];$where['yh_yzb_pic']=upload_img('yh_yzb_pic');
        $where['zaz']=$_REQUEST['zaz'];$where['zaz_pic']=upload_img('zaz_pic');
        $where['yaz']=$_REQUEST['yaz'];$where['yaz_pic']=upload_img('yaz_pic');
        $where['zbz']=$_REQUEST['zbz'];$where['zbz_pic']=upload_img('zbz_pic');
        $where['ybz']=$_REQUEST['ybz'];$where['ybz_pic']=upload_img('ybz_pic');
        $where['zcz']=$_REQUEST['zcz'];$where['zcz_pic']=upload_img('zcz_pic');
        $where['ycz']=$_REQUEST['ycz'];$where['ycz_pic']=upload_img('ycz_pic');
        $where['zqm']=$_REQUEST['zqm'];$where['zqm_pic']=upload_img('zqm_pic');
        $where['yqm']=$_REQUEST['yqm'];$where['yqm_pic']=upload_img('yqm_pic');
        $where['zhm']=$_REQUEST['zhm'];$where['zhm_pic']=upload_img('zhm_pic');
        $where['yhm']=$_REQUEST['yhm'];$where['yhm_pic']=upload_img('yhm_pic');
        $where['fdj']=$_REQUEST['fdj'];$where['fdj_pic']=upload_img('fdj_pic');
        $where['hbx']=$_REQUEST['hbx'];$where['hbx_pic']=upload_img('hbx_pic');
        $where['zcd']=$_REQUEST['zcd'];$where['zcd_pic']=upload_img('zcd_pic');
        $where['ycd']=$_REQUEST['ycd'];$where['ycd_pic']=upload_img('ycd_pic');
        $where['zcqb']=$_REQUEST['zcqb'];$where['zcqb_pic']=upload_img('zcqb_pic');
        $where['ycqb']=$_REQUEST['ycqb'];$where['ycqb_pic']=upload_img('ycqb_pic');

        $rs=$db->row_update('waiguan',$where,"info_id=".$_REQUEST['id']);
        if($_REQUEST['method']=="add"){
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '外观检测失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '外观检测成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '外观检测修改失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '外观检测修改成功！';
                echo json_encode($d);
                exit;
            }
        }
        break;

    case "cltp":
        $where['info_id']=$_REQUEST['id'];
        $where['zuoqian']=upload_img('zuoqian');
        $where['zhengqian']=upload_img('zhengqian');
        $where['youqian']=upload_img('youqian');
        $where['dadeng']=upload_img('dadeng');
        $where['zhengce']=upload_img('zhengce');
        $where['youhou']=upload_img('youhou');
        $where['zhenghou']=upload_img('zhenghou');
        $where['zuohou']=upload_img('zuohou');
        $where['weideng']=upload_img('weideng');
        $where['luntai']=upload_img('luntai');
        $where['qpzy']=upload_img('qpzy');
        $where['hpzy']=upload_img('hpzy');
        $where['zkt']=upload_img('zkt');
        $where['bsg']=upload_img('bsg');
        $where['tc']=upload_img('tc');
        $where['ybp']=upload_img('ybp');
        $where['tb']=upload_img('tb');
        $where['zzytj']=upload_img('zzytj');
        $where['aqd']=upload_img('aqd');
        $where['zcm']=upload_img('zcm');
        $where['cman']=upload_img('cman');
        $where['cmjt']=upload_img('cmjt');
        $where['fdjc']=upload_img('fdjc');
        $where['hbx']=upload_img('hbx');
        $where['bt']=upload_img('bt');
        $where['ys']=upload_img('ys');
        $where['qmy']=upload_img('qmy');
        $where['zqdp']=upload_img('zqdp');
        $where['cbdp']=upload_img('cbdp');
        $where['zhdp']=upload_img('zhdp');
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('cltp',$where);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '车辆图片添加失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '车辆图片添加成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('cltp',$where,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '车辆图片修改失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '车辆图片修改成功！';
                    echo json_encode($d);
                    exit;
                }
            }
        }
        break;


    case "neishi":
        $neishi['info_id']=$_REQUEST['id'];
        $neishi['fxp']=$_REQUEST['fxp'];
        $neishi['zkt']=$_REQUEST['zkt'];
        $neishi['zjcm']=$_REQUEST['zjcm'];
        $neishi['fjcm']=$_REQUEST['fjcm'];
        $neishi['hzcm']=$_REQUEST['hzcm'];
        $neishi['hycm']=$_REQUEST['hycm'];
        $neishi['zjzy']=$_REQUEST['zjzy'];
        $neishi['fjzy']=$_REQUEST['fjzy'];
        $neishi['hzy']=$_REQUEST['hzy'];
        $neishi['tc']=$_REQUEST['tc'];
        $neishi['blsj']=$_REQUEST['blsj'];

        $where['price']=$_REQUEST['price'];
        $where['jzsb_msg']=$_REQUEST['jzsb_msg'];
        $where['tui']=$_REQUEST['tui'];
        $where['pingjia']=$_REQUEST['pingjia'];

        $update=$db->row_update('info',$where,'id='.$_REQUEST['id']);
        if($_REQUEST['method']=="add"){
            $rs=$db->row_insert('neishi',$neishi);
            if (empty($rs)) {
                $d['code'] = 0;
                $d['data'] = '内饰检测提交失败！';
                echo json_encode($d);
                exit;
            } else {
                $d['code'] = 1;
                $d['data'] = '内饰检测提交成功！';
                echo json_encode($d);
                exit;
            }
        }else{
            if(!empty($_REQUEST['id'])){
                $rs=$db->row_update('neishi',$neishi,"info_id=".$_REQUEST['id']);
                if (empty($rs)) {
                    $d['code'] = 0;
                    $d['data'] = '内饰检测修改失败！';
                    echo json_encode($d);
                    exit;
                } else {
                    $d['code'] = 1;
                    $d['data'] = '内饰检测修改成功！';
                    echo json_encode($d);
                    exit;
                }
            }
        }
        break;


    //获取年份
    case "year":
        $array_year = arr_year();
        echo json_encode($array_year);
        break;

    //获取颜色
    case "color":
        $array_color = arr_color();
        echo json_encode($array_color);
        break;

    //获取变速箱
    case "bsx":
        $array_transmission = arr_transmission();
        echo json_encode($array_transmission);
        break;

    //获取排量
    case "gas":
        $array_gas = arr_gas();
        echo json_encode($array_gas);
        break;

    //获取车辆类型
    case "model":
        $array_model = arr_model();
        echo json_encode($array_model);
        break;

    //获取省市城市
    case "province":
        $province_search = arr_province();
        echo json_encode($province_search);
        break;

    //获取城市
    case "city":
        $province=array();
        $parentid=$_REQUEST['parentid'];
        $list = $db->row_select('area',"parentid='".$parentid."'");
        foreach($list as $key => $value){
            $province[$value['id']]= $value['name'];
        }
        echo json_encode($province);
        break;

    //获取品牌
    case "brand":
        $array_brand = arr_brand('-1');
        echo json_encode($array_brand);
        break;

    case 'subbrand':
       $bid=isset($_REQUEST['bid'])?$_REQUEST['bid']:exit('参数未定义');
        if(($bid>0 && $bid<183) || $bid==66911){
            $info['code'] = 0;
            $info['data'] = '查询失败，请求参数错误';
            echo json_encode($info);
            exit;
        }
        $sql=$db->sql_select('brand',"b_parent=$bid",'b_id,b_name');
        $rs=$db->row_query($sql);

        foreach ($rs as $key => $value) {
            $cars[]=$value['b_id'];
        }
        $cars=implode($cars,',');

        $sql=$db->sql_select('brand',"b_parent in($cars)",'b_id,b_name');
        $result=$db->row_query($sql);

        foreach ($result as $k => $v) {

            $sql=$db->sql_select('brand',"b_parent=$v[b_id]",'b_id,b_name');
            $result1=$db->row_query($sql);
            $configure=array();

            foreach ($result1 as $k1 => $v1) {

                $sql=$db->sql_select('brand',"b_parent=$v1[b_id]",'b_id,b_name');
                $result2=$db->row_query($sql);

                for($i=0;$i<count($result2);$i++){
                    $configure[]=$v1['b_name'].$result2[$i]['b_name'];
                }
            }
            $result[$k]['cars']=$configure;
        }
        if($result){
            $info['code'] = 1;
            $info['data'] = $result;
            echo json_encode($info);
            exit;
        }else{
            $info['code'] = 0;
            $info['data'] = '查询失败，请确认请求地址是否正确';
            echo json_encode($info);
            exit;
        }


    default:
        return 3;
}