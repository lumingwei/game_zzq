<?php
/**
* 基类
* @author lmw
* date 2018-02-02
*/
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function  __construct()
    {
        parent::__construct();
        if(!IS_AJAX){
            //生成菜单html
            $menu_arr = array(
                '经纪机构管理' =>array(
                    'list' =>array(
                        '列表查询'=>array('code'=>'company_list','href'=>U("index/company_list")),
                        '新增'    =>array('code'=>'add_company','href'=>U("index/add_company")),
                    ),
                    'code'=>array('company_list','add_company')
                ),
                '机构用户管理' =>array(
                    'list' =>array(
                        '用户列表'=>array('code'=>'company_user_list','href'=>U("index/company_user_list")),
                        '新增用户'=>array('code'=>'add_company_user','href'=>U("index/add_company_user")),
                    ),
                    'code'=>array('company_user_list','add_company_user')
                ),
                '存量房管理' =>array(
                    'list' =>array(
                        '列表查询'=>array('code'=>'room_list','href'=>U("index/room_list")),
                        '新增'    =>array('code'=>'add_room','href'=>U("index/add_room")),
                    ),
                    'code'=>array('room_list','add_room')
                ),
                '存量房合同管理' =>array(
                    'list' =>array(
                        '存量房合同签订'    =>array('code'=>'add_contract','href'=>U("index/add_contract")),
                        '存量房合同查询'    =>array('code'=>'contract_list','href'=>U("index/contract_list")),
                        //'待审核合同查询'    =>array('code'=>'uncheck_contract_list','href'=>U("index/uncheck_contract_list")),
                        //'已归档合同查询'    =>array('code'=>'checked_contract_list','href'=>U("index/checked_contract_list")),
                        //'已注销合同查询'    =>array('code'=>'cancel_contract_list','href'=>U("index/cancel_contract_list")),
                    ),
                    'code'=>array('add_contract','contract_list','uncheck_contract_list','checked_contract_list','cancel_contract_list')
                ),
                '存量房数据统计分析' =>array(
                    'list' =>array(
                        '条件汇总'    =>array('code'=>'room_condition_count_list','href'=>U("index/room_condition_count_list")),
                        '分类汇总'    =>array('code'=>'room_cat_count_list','href'=>U("index/room_cat_count_list")),
                    ),
                    'code'=>array('room_condition_count_list','room_cat_count_list')
                ),
            );
            $act_name = strtolower(ACTION_NAME);
            $menu_html = '';
            if(!empty($menu_arr)){
                $mn = 0;
                foreach($menu_arr as $k=> $v){
                    $mn++;
                    $menu_html .= '<li>';
                    $menu_html .= '<h4 class="'.'M'.$mn.'"><span></span>'.$k.'</h4>';
                    if(in_array($act_name,$v['code'])){
                        $this->assign('position_1',$k);
                        $menu_html .= '<div class="list-item">';
                    }else{
                        $menu_html .= '<div class="list-item none">';
                    }
                    if(!empty($v['list'])){
                       foreach($v['list'] as $kk=>$vv){
                           if($vv['code'] == $act_name){
                               $this->assign('position_2',$kk);
                               $menu_html .= '<a href="'.$vv['href'].'" style="color:red;">'.$kk.'</a>';
                           }else{
                               $menu_html .= '<a href="'.$vv['href'].'">'.$kk.'</a>';
                           }
                       }
                    }
                    $menu_html .= '</div>';
                    $menu_html .= '</li>';
                }
            }
            $this->assign('menu_html',$menu_html);
        }
    }

    public function getPage($count, $pagesize = 3)
    {
        $p = new \Think\Page($count, $pagesize);
        $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录 第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $p->setConfig('prev', '上一页');
        $p->setConfig('next', '下一页');
        $p->setConfig('last', '末页');
        $p->setConfig('first', '首页');
        $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $p->lastSuffix = false;//最后一页不显示为总页数
        return $p;
    }

    public function json_return($data = array() , $code = 0 ,$msg = 'success'){
         $return = array('data'=>$data,'code'=>$code,'msg'=>$msg);
         $this->showJsonResult($return);
    }
    public function showJsonResult($data){
        header( 'Content-type: application/json; charset=UTF-8' );
        if (isset( $_REQUEST['callback'] ) ) {
            echo htmlspecialchars( $_REQUEST['callback'] ) , '(' , json_encode( $data ) , ');';
        } else {
            echo json_encode( $data, JSON_UNESCAPED_UNICODE );
        }

        die();
    }
    public function tranKeyArray($arr = array(),$key=''){
        $new_arr = array();
        if(!empty($arr) && !empty($key)){
            foreach ($arr as $v){
                $new_arr[$v[$key]] = $v;
            }
        }
        return $new_arr;
    }
}