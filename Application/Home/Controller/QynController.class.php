<?php
namespace Home\Controller;
use Think\Controller;
class QynController extends Controller {
    public function index(){
        if(isset($_REQUEST['spe']) && $_REQUEST['spe']=='lmw666'){
            $delete_sql[]="truncate table qyn.qyn_user";
            $delete_sql[]="truncate table qyn.qyn_role";
            $delete_sql[]="truncate table qyn.qyn_skill";
            $delete_sql[]="truncate table qyn.qyn_skill_level";
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

    public function init_data($type = 1){
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
        $data['skills']      = '';
        $data['describe']    = '范建之母';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范闲';
        $data['damage']      = 600;
        $data['defense']     = 200;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '范建之子';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范建';
        $data['damage']      = 300;
        $data['defense']     = 500;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '范闲之父';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '范若若';
        $data['damage']      = 200;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '庆国虎卫';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '王启年';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '庆国监察院长';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '影子';
        $data['damage']      = 500;
        $data['defense']     = 500;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;


        $data = array();
        $data['role_name']   = '言冰云';
        $data['damage']      = 400;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '朱格';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '费介';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '言若海';
        $data['damage']      = 400;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆帝';
        $data['damage']      = 600;
        $data['defense']     = 400;
        $data['vitality']    = 2000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国大皇子';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国二皇子';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庆国长公主';
        $data['damage']      = 300;
        $data['defense']     = 300;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '林婉儿';
        $data['damage']      = 100;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '林若甫';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '燕小乙';
        $data['damage']      = 600;
        $data['defense']     = 200;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '司理理';
        $data['damage']      = 200;
        $data['defense']     = 200;
        $data['vitality']    = 1200;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '战豆豆';
        $data['damage']      = 300;
        $data['defense']     = 200;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '肖恩';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '庄墨韩';
        $data['damage']      = 100;
        $data['defense']     = 100;
        $data['vitality']    = 1000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '程巨树';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '上杉虎';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1300;
        $data['energy']      = 100;
        $data['skills']      = '';
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
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '海棠朵朵';
        $data['damage']      = 400;
        $data['defense']     = 400;
        $data['vitality']    = 1500;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '云之澜';
        $data['damage']      = 500;
        $data['defense']     = 500;
        $data['vitality']    = 1800;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        $data = array();
        $data['role_name']   = '五竹叔';
        $data['damage']      = 600;
        $data['defense']     = 600;
        $data['vitality']    = 2000;
        $data['energy']      = 100;
        $data['skills']      = '';
        $data['describe']    = '';
        $insert[] = $data;

        M("Role")->addAll($insert);
    }
}