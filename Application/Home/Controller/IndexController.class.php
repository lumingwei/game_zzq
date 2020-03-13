<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //任务管理
    public function task(){
        $now = time();
        $time['date'] = date('Y 年 m 月 d 日',$now);
        $week_array=array("日","一","二","三","四","五","六"); //先定义一个数组
        $time['week'] = "星期".$week_array[date("w",$now)];
        $this->assign('time',$time);
        $this->display();
    }

    //任务管理
    public function addTask(){
        $content = I('content','','trim');
        if(empty($content)){
            $this->json_return(array(),0,'内容为空!');
        }
        $now     = time();
        $ct      = strtotime(date('Y-m-d',$now));
        $ret     = M('Task')->add(array('uid'=>1,'content'=>$content,'create_time'=>$now,'count_time'=>$ct));
        $id      = M()->getLastInsID();
        if($ret){
            $this->json_return($id,1,'操作成功!');
        }else{
            $this->json_return($id,0,'操作失败!');
        }
    }

    //任务管理
    public function getTask(){
        $now     = time();
        $ct      = strtotime(date('Y-m-d',$now));
        $ret     = M('Task')->where(array('uid'=>1,'count_time'=>$ct))->order('update_time asc,id asc')->select();
        if($ret){
            $this->json_return($ret,1,'操作成功!');
        }else{
            $this->json_return(array(),1,'操作失败!');
        }
    }

    //任务管理
    public function updateTask(){
        $id      = I('id','','intval');
        $status  = I('status',0,'intval');
        $now     = time();
        $ct      = strtotime(date('Y-m-d',$now));
        $ret     = M('Task')->where()->where(array('id'=>$id))->save(array('status'=>$status,'count_time'=>$ct,'update_time'=>$now));
        if($ret){
            $this->json_return($ret,1,'操作成功!');
        }else{
            $this->json_return(array(),1,'操作失败!');
        }
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

    //任务管理
    public function clock(){

        $this->display();
    }



    public function echars(){
        $formula       = !empty($_REQUEST['formula'])?$_REQUEST['formula']:'2*x+10';
        $x_axis        = !empty($_REQUEST['x_axis'])?$_REQUEST['x_axis']:'1-10';
        $x_axis_arr    = explode('-',$x_axis);
        $x_axis_arr[0] = intval($x_axis_arr[0]);
        $x_axis_arr[1] = intval($x_axis_arr[1]);
        for($i=$x_axis_arr[0];$i<=$x_axis_arr[1];$i++){
            $myChart['xAxis']['data'][]     = $i;
            $y_val                           = str_replace('x',$i,$formula);
            $y_val                           = eval("return $y_val;");
            $y_val = number_format(log($i)+$i,2);
            $myChart['series'][0]['data'][] = $y_val;
        }

        $myChart['tooltip']['trigger'] = 'axis';
        $myChart['axisPointer']['type'] = 'cross';
        $myChart['axisPointer']['label']['backgroundColor'] = '#6a7985';

        $myChart['xAxis']['type'] = 'category';
        $myChart['xAxis']['boundaryGap'] = false;
        //$myChart['xAxis']['data'] = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $myChart['yAxis']['type'] = 'value';
        //$myChart['series'][0]['data'] = array(820, 932, 100, 934, 1290, 1330, 1320);
        $myChart['series'][0]['name'] = 'y值';
        $myChart['series'][0]['areaStyle'] = (object)array();
        $myChart['series'][0]['type'] = 'line';
        $myChart['series'][0]['smooth'] = true;
        $myChart['series'][0]['label']['normal']['show'] = true;
        $myChart['series'][0]['label']['normal']['position'] = 'top';
        $myChart = json_encode($myChart);
        $this->assign('myChart',$myChart);
        $this->assign('formula',$formula);
        $this->assign('x_axis',$x_axis);
        $this->display();
    }
    //大地图界面
    public function index(){
        $this->redirect('index/task');
        $area_color_arr = array(
            '1'=>'purple',
            '2'=>'blue',
            '3'=>'cyan',
            '4'=>'green',
            '5'=>'yellow',
            '6'=>'orange',
            '7'=>'red',
        );
        $area_list_tmp = M('Area')->where(array('server_id'=>1))->field('area_id,name,position_x,position_y,area_type,title')->select();
        if(!empty($area_list_tmp)){
            foreach ($area_list_tmp as $v){
                $area_list[$v['position_x']][$v['position_y']] = $v;
            }
            unset($area_list_tmp);
        }
        //地图 200*200 40000 四万格子
        $html = '<table border="1" align="center" style="width:20000px;height: 20000px">';
        for ($i=0;$i<200;$i++){
            $html .='<tr align="center">';
            for ($j=0;$j<200;$j++){
                if(!empty($area_list[$i][$j])){
                    $color = $area_color_arr[$area_list[$i][$j]['area_type']];
                    $html .= '<td style="background:'.$color.';width:100px;height: 100px">'.$area_list[$i][$j]['name'];
                }else{
                    $html .= '<td style="width:100px;height: 100px">'.'荒芜人烟';
                }
                $html .= '</td>';
            }
            $html .='</tr>';
        }
        $html .= '</table>';
        $this->assign('html',$html);
        $this->display();
    }

    //城市管理界面
    public function areaView(){
        $area_type_arr = array(
            '1'=>'乡村',
            '2'=>'小镇',
            '3'=>'县城',
            '4'=>'州城',
            '5'=>'郡城',
            '6'=>'王城',
            '7'=>'帝都',
        );
        $land_type_arr = array(
            '1'=>'青青草原',
            '2'=>'蜿蜒丘陵',
            '3'=>'沿海渔港',
            '4'=>'荒原沙漠',
            '5'=>'林海雪原',
            '6'=>'海中小岛',
            '7'=>'沙漠绿洲',
            '8'=>'江南水乡',
            '9'=>'崇山峻岭',
            '10'=>'潇湘湖畔',
        );

        $area_id       = !empty($_REQUEST['area_id'])?intval($_REQUEST['area_id']):2789;
        $area_info     = M("Area")->where(array('area_id'=>$area_id))->find();
        $area_info['create_time'] = !empty($area_info['create_time'])?date('Y-m-d H:i',$area_info['create_time']):'';
        $area_info['area_type'] = $area_type_arr[$area_info['area_type']];
        $area_info['land_type'] = $land_type_arr[$area_info['land_type']];
        $base_cn   = array(
            'area_id'=>'地区id',
            'area_type'=>'地区类型',
            'server_id'=>'区服id',
            'player_id'=>'玩家id',
            'general_id'=>'城主id',
            'level'=>'城市等级',
            'land_type'=>'土地类型',
            'name'=>'地区名称',
            'title'=>'称号',
            'gold'=>'黄金',
            'food'=>'粮食',
            'wood'=>'木材',
            'stone'=>'石头',
            'iron'=>'铁矿',
            'horse'=>'马匹',
            'resource_wood'=>'木材资源',
            'resource_stone'=>'石材资源',
            'resource_iron'=>'铁矿资源',
            'people'=>'人口',
            'people_loyal'=>'民忠',
            'morale'=>'士气',
            'area'=>'面积',
            'useful_area'=>'可用面积',
            'occupied_area'=>'已使用面积',
            'military'=>'军事力量',
            'building'=>'建筑物数量',
            'general'=>'将领数量',
            'position_x'=>'x坐标',
            'position_y'=>'y坐标',
            'create_time'=>'建立时间',
        );
        $cn = array(
            '基础'=>array(
                'area_id'=>'地区id',
                'area_type'=>'地区类型',
                'server_id'=>'区服id',
                'player_id'=>'玩家id',
                'general_id'=>'城主id',
                'level'=>'城市等级',
                'land_type'=>'土地类型',
                'name'=>'地区名称',
                'title'=>'称号',
                'people'=>'人口',
                'people_loyal'=>'民忠',
                'area'=>'面积',
                'useful_area'=>'可用面积',
                'occupied_area'=>'已使用面积',
                'position_x'=>'x坐标',
                'position_y'=>'y坐标',
                'create_time'=>'建立时间',
            ),
            '资源'=>array(
                'gold'=>'黄金',
                'food'=>'粮食',
                'wood'=>'木材',
                'stone'=>'石头',
                'iron'=>'铁矿',
                'horse'=>'马匹',
                'resource_wood'=>'木材资源',
                'resource_stone'=>'石材资源',
                'resource_iron'=>'铁矿资源',
            ),
            '建筑物'=>array(
                'building'=>'建筑物数量',
            ),
            '军事'=>array(
                'morale'=>'士气',
                'military'=>'军事力量',
                'general'=>'将领数量',
            ),
        );
        $html = '';
        foreach($cn as $tn =>$col){
            $html .= '<h1>'.$tn.'</h1>';
            $html .= '<table border="1" align="center">';
            $html .='<tr align="center">';
            foreach($col as $k =>$v){
                $html .= '<th style="width:100px;height: 30px">'.$v;
                $html .= '</th>';
            }
            $html .='</tr>';
            $html .='<tr align="center">';
            foreach($col as $k =>$v){
                $html .= '<td style="width:100px;height: 30px">'.$area_info[$k];
                $html .= '</td>';
            }
            $html .='</tr>';
            $html .= '</table>';
            $html .= '</br>';
        }
        //建筑物明细  (建筑物名称 数量 占地面积 产量)
        $source_cn = array(
            'people'=>'人口',
            'people_loyal'=>'民忠',
            'gold'=>'黄金',
            'food'=>'粮食',
            'wood'=>'木材',
            'stone'=>'石头',
            'iron'=>'铁矿',
            'horse'=>'马匹',
            'general'=>'将领',
            'morale'=>'士气',
            'daodunbing'=>'刀盾兵',
            'qibing'=>'骑兵',
            'gongjianbing'=>'弓箭手',
            'jinjun'=>'禁军',
            'heal'=>'健康',
            'xiake'=>'侠客',
            'toushiche'=>'投石车',
            'xiaoyuchuan'=>'小渔船',
            'zhanchuan'=>'战船',
        );
        //获取建组基础信息
        $building_col     = array('name'=>'名称','b_num'=>'数量','all_capacity'=>'总容量','produce_source'=>'产量信息','occupied_area'=>'占地面积');
        $building_arr     = array();
        $building_arr_tmp = M("Building")->where(1)->select();
        if(!empty($building_arr_tmp)){
            foreach($building_arr_tmp as $v){
                $building_arr[$v['building_id']] = $v;
            }
        }
        $area_building = M("areaBuilding")->field('building_id,sum(capacity) as all_capacity,count(*) as b_num')->where(array('area_id'=>$area_id))->group('building_id')->select();
        if(!empty($area_building)){
            foreach($area_building as $k =>$v){
                $area_building[$k]['name']           = !empty($building_arr[$v['building_id']]['name'])?$building_arr[$v['building_id']]['name']:'';
                $area_building[$k]['occupied_area'] = $v['b_num']*$building_arr[$v['building_id']]['occupied_area'];
                if(!empty($building_arr[$v['building_id']]['produce_source'])){
                    $produce_source_arr                   = array();
                    $produce_source                       = json_decode($building_arr[$v['building_id']]['produce_source'],true);
                    foreach($produce_source as $kk=>$val){
                        if(!empty($source_cn[$kk])){
                            $produce_source_arr[] = $source_cn[$kk].':'.$val.'/day';
                        }
                    }
                    $area_building[$k]['produce_source'] = !empty($produce_source_arr)?implode(',',$produce_source_arr):'';
                }else{
                    $area_building[$k]['produce_source'] = '';
                }
            }
        }
        $html .= '<h1>建筑物明细</h1>';
        $html .= '<table border="1" align="center">';
        $html .='<tr align="center">';
        foreach($building_col as $v){
            $html .= '<th style="width:200px;height: 30px">'.$v;
            $html .= '</th>';
        }
        $html .='</tr>';
        foreach($area_building as $v){
            $html .='<tr align="center">';
            foreach($building_col as $kk =>$vv){
                $html .= '<td style="width:200px;height: 30px">'.$v[$kk];
                $html .= '</td>';
            }
            $html .='</tr>';
        }
        $html .= '</table>';
        $html .= '</br>';
        $this->assign('html',$html);
        $this->display('index/index');
    }
    //为各个地图生成建筑
    public function initAreaBuilding(){
        set_time_limit(0);
        $area_type_arr = array(
            '1'=>'乡村',
            '2'=>'小镇',
            '3'=>'县城',
            '4'=>'州城',
            '5'=>'郡城',
            '6'=>'王城',
            '7'=>'帝都',
        );
        $area_building_arr = array(
            '1'=>array('民居'=>array(2,5),'农场'=>array(0,1)),
            '2'=>array('民居'=>array(10,15),'农场'=>array(2,5)),
            '3'=>array('民居'=>array(15,25),'农场'=>array(10,15),'伐木场'=>array(2,5),'市场'=>array(1,2),'客栈'=>array(0,1),'酒馆'=>array(0,1),'仓库'=>array(1,2),'府邸'=>array(0,1)),
            '4'=>array('民居'=>array(30,45),'农场'=>array(20,30),'伐木场'=>array(10,15),'市场'=>array(5,10),'客栈'=>array(3,5),'酒馆'=>array(2,5),'商铺'=>array(2,5),'仓库'=>array(5,10),'步兵营'=>array(0,1),'弓兵营'=>array(0,1),'府邸'=>array(1,5)),
            '5'=>array('民居'=>array(50,60),'农场'=>array(30,50),'伐木场'=>array(15,20),'市场'=>array(10,20),'客栈'=>array(3,5),'酒馆'=>array(2,5),'商铺'=>array(10,15),'仓库'=>array(10,15),'步兵营'=>array(1,1),'弓兵营'=>array(1,1),'府邸'=>array(5,10)),
            '6'=>array('民居'=>array(70,100),'农场'=>array(45,60),'伐木场'=>array(30,40),'市场'=>array(30,40),'客栈'=>array(6,15),'酒馆'=>array(6,15),'商铺'=>array(20,30),'仓库'=>array(20,25),'步兵营'=>array(1,3),'弓兵营'=>array(1,3),'校武场'=>array(1,1),'学府'=>array(0,1),'军事府'=>array(0,1),'医馆'=>array(2,5),'工坊'=>array(2,5),'箭楼'=>array(2,5),'城墙'=>array(2,5),'府邸'=>array(10,15)),
            '7'=>array('民居'=>array(80,120),'农场'=>array(50,80),'伐木场'=>array(40,50),'市场'=>array(30,40),'客栈'=>array(10,15),'酒馆'=>array(10,15),'商铺'=>array(25,35),'仓库'=>array(25,30),'步兵营'=>array(2,5),'弓兵营'=>array(1,3),'校武场'=>array(1,1),'学府'=>array(1,1),'军事府'=>array(1,1),'医馆'=>array(2,5),'工坊'=>array(2,5),'箭楼'=>array(2,5),'城墙'=>array(2,5),'宫殿'=>array(1,1),'府邸'=>array(15,20)),
        );
        //获取建组基础信息
        $building_arr     = array();
        $building_arr_tmp = M("Building")->where(1)->select();
        if(!empty($building_arr_tmp)){
            foreach($building_arr_tmp as $v){
                $building_arr[$v['name']] = $v;
            }
        }
        //获取城镇列表
        $area_list = M("Area")->where(1)->select();
        if(!empty($area_list)){
            foreach($area_list as $a){
                $area_occupied_area          = 0;
                $area_all_building_num       = 0;
                $insert                      = array();
                $area_building               = !empty($area_building_arr[$a['area_type']])?$area_building_arr[$a['area_type']]:array();
                if(!empty($area_building)){
                     foreach($area_building as $b_name =>$b_num){
                         if(!empty($building_arr[$b_name])){
                             $building_num                = rand($b_num[0],$b_num[1]);
                             $area_all_building_num       += $building_num;
                             for($i=0;$i<$building_num;$i++){
                                 $area_occupied_area         += $building_arr[$b_name]['occupied_area'];
                                 $data = array();
                                 $data['server_id']          = $a['server_id'];
                                 $data['area_id']            = $a['area_id'];
                                 $data['building_id']        = $building_arr[$b_name]['building_id'];
                                 $data['capacity']           = $building_arr[$b_name]['capacity'];
                                 $data['create_time']        = $a['create_time'];
                                 $insert[] = $data;
                             }
                         }
                     }
                 }
                if($insert){
                    $res = M("areaBuilding")->addAll($insert);
                    if($res){
                        $res = M("Area")->where(array('area_id'=>$a['area_id']))->save(array('occupied_area'=>$area_occupied_area,'building'=>$area_all_building_num));
                    }
                }
            }
        }
        echo 'success';
    }
    //城市随机分布
    public function positionArea(){
        set_time_limit(0);
        //200*200
        $exist = array();
        $area_list_tmp = M('Area')->where(array('server_id'=>1,'position_x'=>0,'position_y'=>0))->field('area_id')->select();
        if(!empty($area_list_tmp)){
            foreach ($area_list_tmp as $v){
                $x     = rand(0,199);
                $y     = rand(0,199);
                while(!empty($exist[$x][$y])){
                    $x     = rand(0,199);
                    $y     = rand(0,199);
                    if(empty($exist[$x][$y])){
                        break;
                    }
                }
                $exist[$x][$y] =1;
                $res = M("Area")->where(array('area_id'=>$v['area_id']))->save(array('position_x'=>$x,'position_y'=>$y));
                echo "x:{$x}  y:{$y} </br>";
            }
        }
      echo 'success';
    }
    private function color_code($type=0){
        $color = array(
            0=>'black',
            1=>'red',
            2=>'orange',
            3=>'yellow',
            4=>'green',
            5=>'cyan',
            6=>'blue',
            7=>'purple',
        );
        return $color[$type];
    }
    public function initArea(){
        $now = time();
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
        $server_id = 1;
        foreach ($area_type_arr as $k=>$v){
            $insert      = array();
            $name_arr    = $this->getGeneralName($k,$area_type_num[$k]);
            for($i=0;$i<$area_type_num[$k];$i++){
                $data = array();
                $data['server_id']          = $server_id;
                $data['area_type']          = $k;
                $data['land_type']          = rand(1,10);//土地类型 1：青青草原 2；蜿蜒丘陵 3：沿海渔港 4：荒原沙漠 5：林海雪原 6：海中小岛  7：沙漠绿洲 8：江南水乡 9：崇山峻岭 10：潇湘湖畔
                $data['name']               = array_pop($name_arr);
                $data['title']              = $v;

                $data['gold']               = 20*pow(2,$k);
                $data['food']               = rand(1,$k)*pow(4,$k);
                $data['wood']               = rand(1,$k)*pow(4,$k);
                $data['stone']              = rand(1,$k)*pow(3,$k);
                $data['Iron']               = rand(1,$k)*pow(3,$k);

                if($data['land_type'] == 1){
                    $data['horse']               = rand(1,7)*pow(2,$k);
                }else{
                    $data['horse'] = 0;
                }

                $data['resource_wood']      = 5000*rand(1,$k)*pow(2,$k);
                $data['resource_stone']     = 1000*rand(1,$k)*pow(2,$k);
                $data['resource_Iron']      = 1000*rand(1,$k)*pow(2,$k);

                $data['people']              = 100*rand(1,$k)*pow(2,$k);
                $data['people_loyal']        = rand(30,100);

                $data['morale']              = rand(60,100);

                $data['area']                 = 1000*rand(1,$k)*pow(2,$k);
                $data['useful_area']         = intval($data['area']*10*$k/100);
                $data['occupied_area']       = 0;

                $data['military']              = '';
                $data['building']              = 0;
                $data['general']               = 0;

                $data['position_x']              = 0;
                $data['position_y']              = 0;
                $data['create_time']              = $now;

                $insert[] = $data;
            }
            $res = M("Area")->addAll($insert);
        }
        echo 'success!';
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
            3=>'yellow',
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
            'yellow'=>420,
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