<?php
namespace Home\Controller;
use Think\Controller;
class QynController extends BaseController {
    //登录页面
    public function login(){
        if(IS_POST){
            $name      = !empty($_REQUEST['name'])?$_REQUEST['name']:'';
            $pwd       = !empty($_REQUEST['pwd'])?$_REQUEST['pwd']:'';
            if(empty($name) || empty($pwd)){
                $this->error('param error!','/Qyn/login');
            }
            $info      = M('User')->where("name='%s'",array($name))->find();
            if($info['pwd'] == md5('lmw'.$pwd)){
                if($info['status'] != 1){
                    $this->error('user is ban!','/Qyn/login');
                }
                $_SESSION['uid']         = $info['uid'];
                $_SESSION['name']        = $info['name'];
                $_SESSION['status']      = $info['status'];
                $_SESSION['create_time'] = $info['create_time'];
            }else{
                $this->error('name or pwd error!','/Qyn/login');
            }
            $this->success('登录成功', '/Qyn/index');
        }
    }

    //注册页面
    public function reg(){
        if(IS_POST){
            $name      = !empty($_REQUEST['name'])?$_REQUEST['name']:'';
            $pwd       = !empty($_REQUEST['pwd'])?$_REQUEST['pwd']:'';
            if(empty($name) || empty($pwd)){
                $this->error('param error!','/Qyn/login');
            }
            $res      = M('User')->add(array('name'=>$name,'pwd'=>md5($pwd),'create_time'=>time()));
            if($res){
                $this->success('注册成功', '/Qyn/login');
            }else{
                $this->error('注册失败-用户名已被注册','/Qyn/reg');
            }
        }
    }

    //发起挑战
    public function find_fight(){
        $uid               = !empty($_REQUEST['uid'])?$_REQUEST['uid']:0;
        $find_fight_list   = S('find_fight'.$_SESSION['uid'])?S('find_fight'.$_SESSION['uid']):array();
        $find_fight_list[] = $uid;
        S('find_fight'.$_SESSION['uid'],json_encode($find_fight_list),array('expire'=>60));
        $find_fight_list_2 = S('find_fight'.$uid)?S('find_fight'.$uid):array();
        $find_fight_list_2[] = $_SESSION['uid'];
        S('find_fight'.$uid,json_encode($find_fight_list_2),array('expire'=>60));
        $this->json_return(array(),0,'发起成功');
    }

    //接受挑战
    public function accept_fight(){
        $uid               = !empty($_REQUEST['uid'])?$_REQUEST['uid']:0;
        $find_fight_list   = S('find_fight'.$_SESSION['uid'])?S('find_fight'.$_SESSION['uid']):array();
        if(!in_array($uid,$find_fight_list)){
            $this->json_return(array(),1,'对手已经离开了!');
        }else{
            $insert  = M('FightLog')->add(array('uid1'=>$uid,'uid2'=>$_SESSION['uid'],'create_time'=>time()));
            if(!empty($insert)){
                $this->json_return(array('fight_id'=>$insert),0,'准备进入战斗!');
            }else{
                $this->json_return(array(),2,'数据异常!');
            }
        }
    }

    //拒绝挑战
    public function refuse_fight(){
        $uid               = !empty($_REQUEST['uid'])?$_REQUEST['uid']:0;
        $find_fight_list   = S('find_fight'.$_SESSION['uid'])?S('find_fight'.$_SESSION['uid']):array();
        if(!empty($find_fight_list)){
            foreach ($find_fight_list as $k =>$v){
                if($uid == $v){
                    unset($find_fight_list[$k]);
                }
            }
            $find_fight_list = !empty($find_fight_list)?$find_fight_list:array();
            S('find_fight'.$uid,json_encode($find_fight_list),array('expire'=>60));
        }

        $find_fight_list   = S('find_fight'.$uid)?S('find_fight'.$uid):array();
        if(!empty($find_fight_list)){
            foreach ($find_fight_list as $k =>$v){
                if($_SESSION['uid'] == $v){
                    unset($find_fight_list[$k]);
                }
            }
            $find_fight_list = !empty($find_fight_list)?$find_fight_list:array();
            S('find_fight'.$uid,json_encode($find_fight_list),array('expire'=>60));
        }

        $this->json_return(array(),0,'成功');
    }

    //开战
    public function open_fight(){
        $fight_id     = !empty($_REQUEST['fight_id'])?$_REQUEST['fight_id']:0;
        $info         = M("FightLog")->where(array('id'=>$fight_id))->find();
        
    }

    //战斗中
    public function fighting(){
        $uid     = !empty($_REQUEST['fight_id'])?$_REQUEST['fight_id']:0;
    }

    //初始化数据
    public function create_data(){
        if(isset($_REQUEST['spe']) && $_REQUEST['spe']=='lmw666'){
            $delete_sql[]="truncate table qyn.qyn_user";
            $delete_sql[]="truncate table qyn.qyn_role";
            $delete_sql[]="truncate table qyn.qyn_skill";
            $delete_sql[]="truncate table qyn.qyn_fight_log";
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

    private function init_data($type = 1){
        if($type == 1){
            //角色导入
            $this->init_role();
        }elseif($type == 2){
            //技能导入
            $this->init_skill();
        }
        return true;
    }

    private function init_skill(){
        //skill_type： 1：随机 2：前排 3：中排 4：后排 5：纵列 6：全体 7：自身
        //affect_type： 1：作用于敌方 2：作用于我方 3：作用与敌我双方
        $data = array();
        $data['skill_name']             = '镭射之光';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 1800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '敌方随机单体大伤害';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '君临天下';
        $data['skill_type']             = 6;
        $data['affect_type']            = 1;
        $data['role_num']               = 100;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 15;
        $data['consume_energy']        = 80;
        $data['describe']               = '敌方全体减气势力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '霸道真气';
        $data['skill_type']             = 2;
        $data['affect_type']            = 1;
        $data['role_num']               = 3;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '前排全体受伤';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '皇族气质';
        $data['skill_type']             = 1;
        $data['affect_type']            = 2;
        $data['role_num']               = 2;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 25;
        $data['consume_energy']        = 100;
        $data['describe']               = '提高我方两人气势力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '振奋精神';
        $data['skill_type']             = 7;
        $data['affect_type']            = 2;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '恢复自身生命力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '反间妙计';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 2;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 500;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '敌方任意两人互攻';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '图穷匕见';
        $data['skill_type']             = 4;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 1000;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '后排单体攻击';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '走为上计';
        $data['skill_type']             = 7;
        $data['affect_type']            = 2;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 700;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '免伤一次（恢复生命力）';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '巨树暴击';
        $data['skill_type']             = 2;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '前排单体暴击';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '大将陷阵';
        $data['skill_type']             = 5;
        $data['affect_type']            = 1;
        $data['role_num']               = 5;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 600;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '纵向冲击敌军';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '鬼斧神工';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 2;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '随机攻击敌方两人';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '一剑封喉';
        $data['skill_type']             = 2;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 900;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '前排单体暴击';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '灵丹妙药';
        $data['skill_type']             = 1;
        $data['affect_type']            = 2;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 800;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '恢复我方单体大部生命力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '百步穿杨';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 900;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '随机单体暴击';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '九品巅峰';
        $data['skill_type']             = 1;
        $data['affect_type']            = 2;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 500;
        $data['vitality']               = 0;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '极大提高自身防御力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '柔情似水';
        $data['skill_type']             = 1;
        $data['affect_type']            = 2;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 30;
        $data['consume_energy']        = 100;
        $data['describe']               = '提高我方单体男性气势力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '饿狼出击';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 1100;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '随机单体暴击';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '用毒如神';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 500;
        $data['energy']                 = 20;
        $data['consume_energy']        = 100;
        $data['describe']               = '敌方单体中毒（-生命力 -气势力 ）';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '垂帘听政';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 30;
        $data['consume_energy']        = 100;
        $data['describe']               = '提高皇帝气势力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '黑骑出击';
        $data['skill_type']             = 2;
        $data['affect_type']            = 1;
        $data['role_num']               = 5;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 700;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '提高皇帝气势力';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '虎卫护主';
        $data['skill_type']             = 7;
        $data['affect_type']            = 1;
        $data['role_num']               = 1;
        $data['damage']                 = 0;
        $data['defense']                = 0;
        $data['vitality']               = 1000;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '免伤并且反弹伤害';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '教化春风';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 3;
        $data['damage']                 = 300;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '敌方随机三人禁攻一回合';
        $insert[] = $data;

        $data = array();
        $data['skill_name']             = '母性之光';
        $data['skill_type']             = 1;
        $data['affect_type']            = 1;
        $data['role_num']               = 2;
        $data['damage']                 = 300;
        $data['defense']                = 0;
        $data['vitality']               = 0;
        $data['energy']                 = 0;
        $data['consume_energy']        = 100;
        $data['describe']               = '敌方随机两人禁攻一回合';
        $insert[] = $data;


        M("Skill")->addAll($insert);
    }

    private function init_role(){
        $data = array();
        $data['role_name']   = '范老太太';
        $data['damage']      = 300;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '母性之光';
        $data['describe']    = '范建之母';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范闲';
        $data['damage']      = 600;
        $data['defense']     = 200;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '霸道真气';
        $data['describe']    = '范建之子';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范建';
        $data['damage']      = 300;
        $data['defense']     = 500;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '虎卫护主';
        $data['describe']    = '范闲之父';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范若若';
        $data['damage']      = 200;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '百步穿杨';
        $data['describe']    = '范建之女';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范思辙';
        $data['damage']      = 200;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '范建之子';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '滕梓荆';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '范闲之友';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '高达';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '虎卫护主';
        $data['describe']    = '庆国虎卫';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '王启年';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '走为上计';
        $data['describe']    = '范闲之友';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '叶灵儿';
        $data['damage']      = 500;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '范闲之徒';
        $insert[] = $data;


        $data = array();
        $data['role_name']   = '陈萍萍';
        $data['damage']      = 500;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '反间妙计,黑骑出击';
        $data['describe']    = '庆国监察院长';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '影子';
        $data['damage']      = 500;
        $data['defense']     = 500;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '图穷匕见';
        $data['describe']    = '';
        $insert[] = $data;


        $data = array();
        $data['role_name']   = '言冰云';
        $data['damage']      = 400;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '反间妙计';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '朱格';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '反间妙计';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '费介';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '用毒如神';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '言若海';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '反间妙计';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆帝';
        $data['damage']      = 600;
        $data['defense']     = 400;
        $data['vitality']    = 2000;
        $data['energy']      = 100;
        $data['skills']      = '君临天下,霸道真气';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国大皇子';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '皇族气质';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国二皇子';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '皇族气质';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '靖王世子';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '谢必安';
        $data['damage']      = 500;
        $data['defense']     = 300;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '一剑封喉';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国长公主';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '皇族气质';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '林婉儿';
        $data['damage']      = 100;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '柔情似水';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '林若甫';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '灵丹妙药';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '燕小乙';
        $data['damage']      = 600;
        $data['defense']     = 200;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '百步穿杨';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '郭保坤';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '贺宗纬';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '洪四庠';
        $data['damage']      = 500;
        $data['defense']     = 400;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '九品巅峰';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '林大宝';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '郭攸之';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '何道人';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1400;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '宫典';
        $data['damage']      = 300;
        $data['defense']     = 400;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '北齐太后';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '垂帘听政';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '司理理';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '柔情似水';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '战豆豆';
        $data['damage']      = 300;
        $data['defense']     = 200;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '君临天下';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '肖恩';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '振奋精神';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '沈重';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '沈小姐';
        $data['damage']      = 100;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '柔情似水';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庄墨韩';
        $data['damage']      = 100;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '教化春风';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '程巨树';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '巨树暴击';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '上杉虎';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '大将陷阵';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '谭武';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '狼桃';
        $data['damage']      = 500;
        $data['defense']     = 500;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '饿狼出击';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '海棠朵朵';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '鬼斧神工';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '云之澜';
        $data['damage']      = 500;
        $data['defense']     = 500;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '一剑封喉';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '五竹叔';
        $data['damage']      = 600;
        $data['defense']     = 600;
        $data['vitality']    = 2000;
        $data['energy']      = 100;
        $data['skills']      = '镭射之光,一剑封喉';
        $data['describe']    = '';
        $insert[] = $data;

        M("Role")->addAll($insert);
    }
}