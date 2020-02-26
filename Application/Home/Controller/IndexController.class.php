<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(isset($_REQUEST['spe']) && $_REQUEST['spe']=='lmw666'){
            $delete_sql[]="truncate table sow.sow_action";
            $delete_sql[]="truncate table sow.sow_fruits";
            $delete_sql[]="truncate table sow.sow_goods";
            $delete_sql[]="truncate table sow.sow_message";
            $delete_sql[]="truncate table sow.sow_player_action";
            $delete_sql[]="truncate table sow.sow_player_goods";
            $delete_sql[]="truncate table sow.sow_tree_stage";
            $delete_sql[]="truncate table sow.sow_tree_type";
            $delete_sql[]="truncate table sow.sow_tree_worm";
            $delete_sql[]="truncate table sow.sow_trees";
            $delete_sql[]="truncate table sow.sow_weather";
            foreach($delete_sql as $del){
                @M()->query($del);
            }
        }
        if(isset($_REQUEST['spe']) && $_REQUEST['spe']=='lmw777'){
            for($i=1;$i<6;$i++){
                $this->init_data($i);
            }
        }
        exit('success!');
    }
    public function initArea(){
        if(isset($_REQUEST['spe']) && $_REQUEST['spe']=='lmw666'){
            $delete_sql[]="truncate table war.war_area";
            foreach($delete_sql as $del){
                @M()->query($del);
            }
        }
        //地图大小  10000*10000
        //两个帝都   七个王城   三十个郡城  五十个州城 两百个县城  五百个小镇 两千个小乡村 剩下的荒无人烟
        //1：乡村 2：小镇 3：县城 4：州城 5：郡城 6：王城 7：帝都
        $area_type_arr = array(
            '1'=>'乡村',
            '2'=>'小镇',
            '3'=>'县城',
            '4'=>'州城',
            '5'=>'郡城',
            '6'=>'王城',
            '7'=>'帝都',
        );
        $area_type_num = array(
            '1'=>2000,
            '2'=>500,
            '3'=>200,
            '4'=>50,
            '5'=>30,
            '6'=>7,
            '7'=>2,
        );

    }
    private function getAreaName($type = 0){
        //枫丹白露,苏黎世,洛桑
        //落日城,朝阳城,盘龙城,昭月城,麒麟城,苍龙城,九江城,青龙城
        $type_cn  = M("cn")->where(array('type'=>$type))->getField('type_cn');
        return !empty($type_cn)?explode(',',$type_cn):array();
    }

    public function initGeneral(){
        $now = time();
        set_time_limit(0);
        //初始化武将
        //区服
        $server_id = 1;
        //生成方案npc：男：5000 女 2000
        $sex_num = array(1=>5000,2=>2000);
        foreach($sex_num as $k =>$v){
            $insert      = array();
            $type        = $k==1?8:9;
            $name_arr    = $this->getGeneralName($type,$v);
            for($i=0;$i<$v;$i++){
                $data = array();
                $data['server_id']          = $server_id;
                $data['sex']                 = $k;
                $name                        = array_pop($name_arr);
                $n_arr                       = !empty($name)?explode(',',$name):array();
                if(!empty($n_arr)){
                    $data['name']                = $n_arr[0].$n_arr[1];
                    $data['xing']                = $n_arr[0];
                    $data['ming']                = $n_arr[1];
                }else{
                    $data['name']                = '易小川';
                    $data['xing']                = '易';
                    $data['ming']                = '小川';
                }
                $data['color']               = $this->getColor();
                $data['age']                 = rand(13,60);
                $data['status']              = $data['age']<16?0:1;
                $data['create_time']         = $now;
                $power_data                   = $this->getPowerByColor($data['color']);
                $data                         = array_merge($data,$power_data);
                $insert[] = $data;
            }
            $res = M("General")->addAll($insert);
        }
        echo 'success';
    }
    private function getGeneralName($type,$num){
        $name_arr  = M("Cn")->where(array('type'=>$type))->limit($num)->getField('cn',true);
        return $name_arr;
    }
    private function getColor(){
        //0黑 1赤  2橙 3黄 4绿 5青 6蓝 7紫
        $color = array(
            0=>'black',
            1=>'red',
            2=>'orange',
            3=>'huang',
            4=>'green',
            5=>'cyan',
            6=>'blue',
            7=>'purple',
        );
        $arr[0] = 3;
        $arr[1] = 10;
        $arr[2] = 20;
        $arr[3] = 30;
        $arr[4] = 50;
        $arr[5] = 100;
        $arr[6] = 160;
        $arr[7] = 200;
        $rid         = $this->get_rand($arr); //根据概率获取奖项id
        $col       = !empty($color[$rid])?$color[$rid]:'purple'; //获取中奖项
        return $col;
    }
    private function getPowerByColor($color){
        //tongshuai zhengzhi wuli fangyuli shengmingli meili
        $color_power = array(
            'black' =>600,
            'red'   =>520,
            'orange'=>480,
            'huang'=>420,
            'green'=>360,
            'cyan'=>300,
            'blue'=>280,
            'purple'=>250,
        );
        $power_tppe = array(
            1=>'tongshuai',
            2=>'zhengzhi',
            3=>'wuli',
            4=>'fangyuli',
            5=>'shengmingli',
            6=>'meili'
        );
        $power = array(
            'tongshuai' =>0,
            'zhengzhi'  =>0,
            'wuli'=>0,
            'fangyuli'=>0,
            'shengmingli'=>0,
            'meili'=>0,
        );
        $power_num = !empty($color_power[$color])?$color_power[$color]:250;
        while($power_num>0){
            if($power_num>=100){
                $data_arr  = $this->randomDivInt(100);
                $power_num = $power_num-100;
            }else{
                $data_arr  = $this->randomDivInt($power_num);
                $power_num = 0;
            }
            if(!empty($data_arr)){
                foreach($data_arr as $k=>$v){
                    $power[$power_tppe[$k]] = $power[$power_tppe[$k]]+$v;
                }
            }
        }
       return $power;
    }
    private function randomDivInt($power_num){
        $data  = array();
        if(!empty($power_num)){
            $total_money= $power_num;
            $total_num  = 6;
            $total_money=$total_money - $total_num;
            for($i=$total_num;$i>0;$i--){
                $data[$i]=1;
                $ls_money=0;
                if($total_money>0){
                    if($i==1){
                        $data[$i] +=$total_money;
                    }else{
                        $max_money=floor($total_money/$i);
                        $ls_money=mt_rand(0,$max_money);
                        $data[$i]+=$ls_money;
                    }
                }
                $total_money -= $ls_money;
            }
        }
        return $data;
    }
    public function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr); //计算数组中元素的和
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) { //如果这个随机数小于等于数组中的一个元素，则返回数组的下标
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    public function initCn(){
        $area_type_tail = array(
            '1'=>'村',
            '2'=>'镇',
            '3'=>'县',
            '4'=>'州',
            '5'=>'郡'
        );
        $area_type_page = array(
            '1'=>30,
            '2'=>20,
            '3'=>10,
            '4'=>2,
            '5'=>1
        );
        foreach ($area_type_tail as $k=>$v){
            echo "{$v} start  </br>";
            for($page=1;$page<=$area_type_page[$k];$page++){
                $list = $this->produceCn(2,$v,$page);
                if(!empty($list)){
                    $insert = array();
                    foreach ($list as $cn){
                        $data = array();
                        $data['type']        = $k;
                        $data['cn']          = $cn;
                        $insert[] = $data;
                    }
                    M("Cn")->addAll($insert);
                }
                echo "{$v} page:{$page} </br>";
            }
            echo "{$v} end </br>";
        }
        echo "all success </br>";
    }
    public function initGeneralName(){
        $sex_type = array(
            '8'=>2,
            '9'=>3,
        );
        $area_type_page = array(
            '8'=>30,
            '9'=>30
        );
        $name_num = 2;
        foreach ($sex_type as $k=>$v){
            echo "{$v} start  </br>";
            for($page=1;$page<=$area_type_page[$k];$page++){
                if($name_num == 2){
                    $name_num = 3;
                }else{
                    $name_num = 2;
                }
                $list = $this->produceGeneralName('','',$name_num,$v);
                if(!empty($list)){
                    $insert = array();
                    foreach ($list as $cn){
                        $data = array();
                        $data['type']        = $k;
                        $data['cn']          = $cn;
                        $insert[] = $data;
                    }
                    $res = M("Cn")->addAll($insert);
                }
                echo "{$v} name_num: {$name_num} page:{$page} </br>";
                $sleep_time = rand(1,4);
                sleep($sleep_time);
            }
            $sleep_time = rand(1,7);
            sleep($sleep_time);
            echo "{$v} end </br>";
        }
        echo "all success </br>";
    }
    //$sex 2:男 3：女
    private function  produceGeneralName($xing='',$ming='',$name_num=3,$sex=2){
        $price_url = "https://www.xuanpai.com/tool/names?xing={$xing}&ming={$ming}&name_num={$name_num}&sex={$sex}";
        $arr       = array();
        $cont = file_get_contents($price_url);
        if(!empty($cont)){
            $regex4="/<div id=\"tool_item\".*?>.*?<\/div>/ism";
            if(preg_match_all($regex4, $cont, $matches)){
                if(!empty($matches[0][0])){
                    //$search = '/<li>(.*?)<\/li>/is';
                    $search = '/<font color=red>(.*?)<\/font>/is';
                    preg_match_all($search,$matches[0][0],$xing);
                    $xing_arr = !empty($xing[1])?$xing[1]:array();
                    $search = '/<font color=blue>(.*?)<\/font>/is';
                    preg_match_all($search,$matches[0][0],$ming);
                    $ming_arr = !empty($ming[1])?$ming[1]:array();
                    if(!empty($xing_arr) && !empty($ming_arr)){
                        foreach($xing_arr as $k=>$v){
                            if(!empty($ming_arr[$k])){
                                $arr[] = $v.','.$ming_arr[$k];
                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }
    private function  produceCn($word_num=2,$tail='村',$page=1){
        $price_url = "http://www.xuanpai.com/tool/place/{$page}?word_num={$word_num}&tail={$tail}";
        $arr  = array();
        $cont = file_get_contents($price_url);
        if(!empty($cont)){
            $regex4="/<div id=\"tool_item\".*?>.*?<\/div>/ism";
            if(preg_match_all($regex4, $cont, $matches)){
                if(!empty($matches[0][0])){
                    $search = '/<li>(.*?)<\/li>/is';
                    preg_match_all($search,$matches[0][0],$r);
                    $arr = !empty($r[1])?$r[1]:array();
                }
            }
        }
        return $arr;
    }
    public function init_data($type = 20){
        //果树类型导入
        if($type == 1){
            $data = array();
            $data['tree_name'] = '苹果';
            $data['tree_code'] = 'apple';
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_name'] = '雪梨';
            $data['tree_code'] = 'pear';
            $data['describe']  = '';
            $insert[] = $data;


            $data = array();
            $data['tree_name'] = '西瓜';
            $data['tree_code'] = 'watermelon';
            $data['describe']  = '';
            $insert[] = $data;

            M("TreeType")->addAll($insert);
        }elseif($type == 2){
            //果树阶段导入 （目前只有苹果）
            //1种子   2小树苗   3幼树    4初果      5盛果     6衰老期
            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '种子期';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 0;
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '小树苗';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 0;
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '幼树期';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 1;
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '初果期';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 2;
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '盛果期';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 4;
            $data['describe']  = '';
            $insert[] = $data;

            $data = array();
            $data['tree_type_id'] = 1;
            $data['stage_name'] = '衰老期';
            $data['growth_speed'] = 1;
            $data['fruits_speed'] = 1;
            $data['describe']  = '';
            $insert[] = $data;

            M("TreeStage")->addAll($insert);
        }elseif($type ==3){
            $data  = array();
            $data['weather_name'] = '晴天';
            $data['weather_code'] = 'fine';
            $data['describe']     = '利于生长';
            $insert[]             = $data;

            $data  = array();
            $data['weather_name'] = '阴天';
            $data['weather_code'] = 'cloudy';
            $data['describe']     = '';
            $insert[]             = $data;

            $data  = array();
            $data['weather_name'] = '雨天';
            $data['weather_code'] = 'rain';
            $data['describe']     = '利于播种';
            $insert[]             = $data;

            $data  = array();
            $data['weather_name'] = '闪电';
            $data['weather_code'] = 'bolt';
            $data['describe']     = '影响生长';
            $insert[]             = $data;

            M("Weather")->addAll($insert);
        }elseif($type == 4){
            $data  = array();
            $data['action_name']   = '播种';
            $data['action_code']   = 'sow';
            $insert[]              = $data;

            $data  = array();
            $data['action_name']   = '浇水';
            $data['action_code']   = 'water';
            $insert[]              = $data;

            $data  = array();
            $data['action_name']   = '施肥';
            $data['action_code']   = 'fertilize';
            $insert[]              = $data;

            $data  = array();
            $data['action_name']   = '除虫';
            $data['action_code']   = 'worm';
            $insert[]              = $data;

            $data  = array();
            $data['action_name']   = '修剪';
            $data['action_code']   = 'shave';
            $insert[]              = $data;

            $data  = array();
            $data['action_name']   = '收获';
            $data['action_code']   = 'gain';
            $insert[]              = $data;

            M("Action")->addAll($insert);
        }elseif($type == 5){
            //肥料 3种水果种子  看门狗 剪刀 浇水壶
            //道具信息
            $data  = array();
            $data['goods_name']   = '看门狗';
            $data['goods_code']   = 'dog';
            $data['shop_price']   =  50;
            $data['describe']     = '防盗';
            $data['use_time']     = 10;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '苹果种子';
            $data['goods_code']   = 'apple_seed';
            $data['shop_price']   = 10;
            $data['describe']     = '用于播种';
            $data['use_time']     = 1;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '雪梨种子';
            $data['goods_code']   = 'pear_seed';
            $data['shop_price']   = 10;
            $data['describe']     = '用于播种';
            $data['use_time']     = 1;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '西瓜种子';
            $data['goods_code']   = 'watermelon_seed';
            $data['shop_price']   = 10;
            $data['describe']     = '用于播种';
            $data['use_time']     = 1;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '修剪刀';
            $data['goods_code']   = 'clipper';
            $data['shop_price']   = 15;
            $data['describe']     = '用于修剪果树';
            $data['use_time']     = 100;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '浇水壶';
            $data['goods_code']   = 'water_can';
            $data['shop_price']   = 10;
            $data['describe']     = '用于灌溉果树';
            $data['use_time']     = 200;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '肥料';
            $data['goods_code']   = 'muck';
            $data['shop_price']   = 20;
            $data['describe']     = '用于加速果树生长';
            $data['use_time']     = 10;
            $insert[]             = $data;

            $data  = array();
            $data['goods_name']   = '除虫剂';
            $data['goods_code']   = 'insecticide';
            $data['shop_price']   = 10;
            $data['describe']     = '用于为果树除虫';
            $data['use_time']     = 5;
            $insert[]             = $data;
            M("Goods")->addAll($insert);
        }
        return true;
    }
}