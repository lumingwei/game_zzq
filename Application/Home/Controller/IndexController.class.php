<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {

    //后台首页
    public function index(){
        $this->display();
    }

    public function company_list(){
        $postArr['name'] = I('name','','trim');
        $postArr['code'] = I('code','','trim');
        $postArr['boss'] = I('boss','','trim');
        $where = array();
        if(!empty($postArr['name'])){
            $where['name']  = array('like', "%{$postArr['name']}%");
        }
        if(!empty($postArr['code'])){
            $where['code']  = array('like', "%{$postArr['code']}%");
        }
        if(!empty($postArr['boss'])){
            $where['boss']  = array('like', "%{$postArr['boss']}%");
        }
        $company    = M('company'); // 实例化User对象
        $count      = $company->where($where)->count();// 查询满足要求的总记录数
        $Page       = $this->getPage($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
       //分页跳转的时候保证查询条件
        foreach($postArr as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $company->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    //新增/编辑企业
    public function add_company(){
        $id = I('id',0,'intval');
        if(!empty($id)){
            $info = M('company')->where(array('id'=>$id))->find();
        }
        if(IS_AJAX){
            $company    = M('company');
            $data       = $company->create(); // 把无用的都顾虑掉了
            if($id){
                $ret        = $company->where(array('id'=>$id))->save($data);
            }else{
                $ret        = $company->add($data);
            }
            if($ret){
                $this->success('操作成功', U('index/company_list'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->assign('info',!empty($info)?$info:array());
        }
        $this->display(); // 输出模板
    }

    //删除企业
    public function del_company(){
        $id = I('id',0,'intval');
        if(empty($id)){
            $this->error('非法参数', U('index/company_list'));
        }
        $company    = M('company');
        $ret        = $company->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('操作成功', U('index/company_list'));
        }else{
            $this->error('操作失败', U('index/company_list'));
        }
    }


    //企业用户列表
    public function company_user_list()
    {
        $postArr['name']          = I('name','','trim');
        $postArr['company_name'] = I('company_name','','trim');
        $postArr['code']          = I('code','','trim');
        $where = array();
        if(!empty($postArr['name'])){
            $where['u.name']  = array('like', "%{$postArr['name']}%");
        }
        if(!empty($postArr['company_name'])){
            $where['c.name']  = array('like', "%{$postArr['company_name']}%");
        }
        if(!empty($postArr['code'])){
            $where['c.code']  = array('like', "%{$postArr['code']}%");
        }
        $user       = M('company_user'); // 实例化User对象
        $count      = $user->alias('u')->join('left join __COMPANY__ as c on c.id = u.company_id')->where($where)->count();
        $Page       = $this->getPage($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        //分页跳转的时候保证查询条件
        foreach($postArr as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
       // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $user->alias('u')
            ->field('u.*,c.code,c.name as company_name')
            ->join('left join __COMPANY__ as c on c.id = u.company_id')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }


    //新增/编辑企业用户
    public function add_company_user(){
        $id       = I('id',0,'intval');
        $act_code = I('act_code');
        $act_code = !empty($act_code)?implode(',',$act_code):'';
        if(!empty($id)){
            $info = M('company_user')->where(array('id'=>$id))->find();
        }
        if(IS_AJAX){
            $user               = M('company_user');
            $data               = $user->create(); // 把无用的都顾虑掉了
            $data['act_code']  = $act_code;
            if($id){
                $ret        = $user->where(array('id'=>$id))->save($data);
            }else{
                $ret        = $user->add($data);
            }
            if($ret){
                $this->success('操作成功', U('index/company_user_list'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $act_code_html     = '';
            $user_act_code     = !empty($info['act_code'])?explode(',',$info['act_code']):array();
            $act_code_arr      = array(
                'room_list'=> '存量房房源查询',
                'add_room'=> '存量房房源编辑',
                'contract_list'=> '存量房合同查询',
                'add_contract'=> '存量房合同编辑',
                'room_count'=> '存量房数据统计',
            );
            $act_code_html .= '<ul>';
            foreach ($act_code_arr as $k =>$v){
                if(in_array($k,$user_act_code)){
                    $act_code_html .= '<li><input type="checkbox" name="act_code[]" value="'.$k.'" checked>'.$v.'</li>';
                }else{
                    $act_code_html .= '<li><input type="checkbox" name="act_code[]" value="'.$k.'">'.$v.'</li>';
                }
            }
            $act_code_html .= '</ul>';
            $company_html = '<select name="company_id" class="cs">';
            $company_html .= '<option value="0">请选择</option>';
            $company_list   = M('company')->select();
            if(!empty($company_list)){
               foreach ($company_list as $v){
                   if(!empty($info['company_id']) && $v['id'] == $info['company_id']){
                       $company_html .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                   }else{
                       $company_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                   }
               }
            }
            $company_html .= '</select>';
            $this->assign('company_html',$company_html);
            $this->assign('act_code_html',$act_code_html);
            $this->assign('info',!empty($info)?$info:array());
        }
        $this->display(); // 输出模板
    }

    //删除企业用户
    public function del_company_user(){
        $id = I('id',0,'intval');
        if(empty($id)){
            $this->error('非法参数', U('index/company_user_list'));
        }
        $company    = M('company_user');
        $ret        = $company->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('操作成功', U('index/company_user_list'));
        }else{
            $this->error('操作失败', U('index/company_user_list'));
        }
    }

    //房源信息列表
    public function room_list()
    {
        //经纪机构 坐落 规划用途  房屋状态  建筑面积  （平方米）
        $postArr['company_id']           = I('company_id',0,'intval');
        $postArr['address']              = I('address','','trim');
        $postArr['planning_purposes']   = I('planning_purposes',0,'intval');
        $postArr['nature']               = I('nature',0,'intval');
        $postArr['status']               = I('status',0,'intval');
        $postArr['sarea']                = I('sarea','','trim');
        $postArr['earea']                = I('earea','','trim');
        $where = array();
        if(!empty($postArr['company_id'])){
            $where['company_id']  = $postArr['company_id'];
        }
        if(!empty($postArr['address'])){
            $where['address']  = array('like', "%{$postArr['address']}%");
        }
        if(!empty($postArr['planning_purposes'])){
            $where['planning_purposes']  = $postArr['planning_purposes'];
        }
        if(!empty($postArr['nature'])){
            $where['nature']  = $postArr['nature'];
        }
        if(!empty($postArr['status'])){
            $where['status']  = $postArr['status'];
        }
        if(!empty($postArr['sarea']) && !empty($postArr['earea'])){
            $postArr['sarea'] = intval($postArr['sarea']);
            $postArr['earea'] = intval($postArr['earea']);
            $where['covered_area'] = array('between',$postArr['sarea'].','.$postArr['earea']);
        }
        $room       = M('room'); // 实例化User对象
        $count      = $room->where($where)->count();// 查询满足要求的总记录数
        $Page       = $this->getPage($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        //分页跳转的时候保证查询条件
        foreach($postArr as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list           = $room->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $company_list   = M('company')->select();
        $company_name   = $this->tranKeyArray($company_list,'id');
        if(!empty($list)){
            foreach ($list as $k=>$v){
                $list[$k]['status']             = $this->getRoomStatusCn($v['status']);
                $list[$k]['planning_purposes'] = $this->getRoomPlanningPurposesCn($v['planning_purposes']);
                $list[$k]['nature']             = $this->getRoomNatureCn($v['nature']);
                $list[$k]['company_name']       = !empty($company_name[$v['company_id']]['name'])?$company_name[$v['company_id']]['name']:'';
            }
        }
        $company_html = '<select name="company_id" class="cs">';
        $company_html .= '<option value="0">请选择</option>';
        if(!empty($company_list)){
            foreach ($company_list as $v){
                if(!empty($postArr['company_id']) && $v['id'] == $postArr['company_id']){
                    $company_html .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                }else{
                    $company_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        $company_html .= '</select>';
        $this->assign('company_html',$company_html);
        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    //新增/编辑房源
    public function add_room(){
        $id       = I('id',0,'intval');
        if(!empty($id)){
            $info = M('room')->where(array('id'=>$id))->find();
        }
        if(IS_AJAX){
            $user               = M('room');
            $data               = $user->create(); // 把无用的都顾虑掉了
            if($id){
                $ret        = $user->where(array('id'=>$id))->save($data);
            }else{
                $ret        = $user->add($data);
            }
            if($ret){
                $this->success('操作成功', U('index/room_list'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $company_html = '<select name="company_id" class="cs">';
            $company_html .= '<option value="0">请选择</option>';
            $company_list   = M('company')->select();
            if(!empty($company_list)){
                foreach ($company_list as $v){
                    if(!empty($info['company_id']) && $v['id'] == $info['company_id']){
                        $company_html .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                    }else{
                        $company_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                    }
                }
            }
            $company_html .= '</select>';
            $this->assign('company_html',$company_html);
            $this->assign('info',!empty($info)?$info:array());
        }
        $this->display(); // 输出模板
    }

    //删除企业用户
    public function del_room(){
        $id = I('id',0,'intval');
        if(empty($id)){
            $this->error('非法参数', U('index/company_user_list'));
        }
        $company    = M('company_user');
        $ret        = $company->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('操作成功', U('index/company_user_list'));
        }else{
            $this->error('操作失败', U('index/company_user_list'));
        }
    }

    private function getRoomStatusCn($status){
        $arr = array(
            1=>'待出售',
            2=>'已出售',
            3=>'交易中',
        );
        return !empty($arr[$status])?$arr[$status]:'';
    }
    private function getRoomPlanningPurposesCn($planning_purposes){
        $arr = array(
            1=>'住宅',
            2=>'商业',
            3=>'办公',
        );
        return !empty($arr[$planning_purposes])?$arr[$planning_purposes]:'';
    }
    private function getRoomNatureCn($nature){
        $arr = array(
            1=>'商品房',
            2=>'安置房',
            3=>'房改房',
        );
        return !empty($arr[$nature])?$arr[$nature]:'';
    }

    //合同签订
    public function add_contract(){
        $id       = I('id',0,'intval');
        if(!empty($id)){
            $info = M('contract')->where(array('id'=>$id))->find();
        }
        if(IS_AJAX){
            $contract           = M('contract');
            $data               = $contract->create(); // 把无用的都顾虑掉了
            if(!empty($data['room_id'])){
                $room = M('room')->where(array('id'=>intval($data['room_id'])))->find();
            }
            if(empty($room)){
                $this->error('房源不存在，请重新确认');
            }
            if($id){
                $ret            = $contract->where(array('id'=>$id))->save($data);
            }else{
                $data['add_time'] = time();
                $ret        = $contract->add($data);
            }
            if($ret){
                $this->success('操作成功', U('index/contract_list'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->assign('info',!empty($info)?$info:array());
        }
        $this->display(); // 输出模板
    }

    //合同查看
    public function look_contract(){
        $id       = I('id',0,'intval');
        if(!empty($id)){
            $info = M('contract')->where(array('id'=>$id))->find();
            $info['check_time'] = !empty($info['check_time'])?date('Y-m-d',$info['check_time']):'';
            $info['apply_time'] = !empty($info['apply_time'])?date('Y-m-d',$info['apply_time']):'';
            $info['cancel_time'] = !empty($info['cancel_time'])?date('Y-m-d',$info['cancel_time']):'';
            $info['add_time'] = !empty($info['add_time'])?date('Y-m-d',$info['add_time']):'';
            $info['status']   = $this->getContractStatusCn($info['status']);
        }
        $this->assign('info',!empty($info)?$info:array());
        $this->display(); // 输出模板
    }

    //合同查看
    public function set_contract_status(){
        $now          = time();
        $id           = I('id',0,'intval');
        $status       = I('status',0,'intval');
        if(!empty($id) && !empty($status) && $status>1){
            $contract        = M('contract');
            $info            = $contract->where(array('id'=>$id))->find();
            if(empty($info)){
                $this->error('操作失败');
            }
            $data['status'] = $status;
            if($status == 2){
                $data['apply_time'] = $now;
                M('room')->where(array('id'=>$info['room_id']))->save(array('status'=>3));
            }elseif($status == 3){
                $data['check_time'] = $now;
                M('room')->where(array('id'=>$info['room_id']))->save(array('status'=>2));
            }elseif($status == 4){
                $data['check_time'] = $now;
                M('room')->where(array('id'=>$info['room_id']))->save(array('status'=>1));
            }elseif($status == 5){
                $data['cancel_time'] = $now;
                M('room')->where(array('id'=>$info['room_id']))->save(array('status'=>1));
            }
            $ret            = $contract->where(array('id'=>$id))->save($data);
            if($ret){
                $this->success('操作成功', U('index/contract_list'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('操作失败');
        }
    }

    // 合同信息列表
    public function uncheck_contract_list()
    {

    }
    // 合同信息列表
    public function contract_list()
    {
        //合同状态  坐落  规划用途 经纪机构 合同编号 出卖方 买受方 不动产权证 备案时间（开始 结束）
        $postArr['status']               = I('status',0,'intval');
        $postArr['address']              = I('address','','trim');
        $postArr['planning_purposes']   = I('planning_purposes',0,'intval');
        $postArr['company_id']           = I('company_id',0,'intval');
        $postArr['number']               = I('number','','trim');
        $postArr['seller']               = I('number','','trim');
        $postArr['buyer']                = I('buyer','','trim');
        $postArr['sdate']                = I('sdate','','trim');
        $postArr['edate']                = I('edate','','trim');
        $postArr['property_rights_code']= I('property_rights_code','','trim');
        $where = array();
        if(!empty($postArr['company_id'])){
            $where['r.company_id']  = $postArr['company_id'];
        }
        if(!empty($postArr['address'])){
            $where['r.address']  = array('like', "%{$postArr['address']}%");
        }
        if(!empty($postArr['planning_purposes'])){
            $where['r.planning_purposes']  = $postArr['planning_purposes'];
        }
        if(!empty($postArr['status'])){
            $where['c.status']  = $postArr['status'];
        }
        if(!empty($postArr['number'])){
            $where['c.number']  = $postArr['number'];
        }
        if(!empty($postArr['seller'])){
            $where['c.seller']  = $postArr['seller'];
        }
        if(!empty($postArr['buyer'])){
            $where['c.buyer']  = $postArr['buyer'];
        }
        if(!empty($postArr['sdate']) && !empty($postArr['edate'])){
            $where['add_time'] = array('between',strtotime($postArr['sdate']).','.strtotime($postArr['edate']));
        }
        $contract       = M('contract'); // 实例化User对象
        $count          = $contract->alias('c')->join('left join __ROOM__ as r on c.room_id = r.id')->where($where)->count();
        $Page           = $this->getPage($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        //分页跳转的时候保证查询条件
        foreach($postArr as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $contract->alias('c')
            ->field('c.id,c.number,c.record_number,c.seller,c.buyer,c.status,r.address,r.garage_position,r.parking_position,r.property_rights_code,r.planning_purposes,r.company_id')
            ->join('left join __ROOM__ as r on c.room_id = r.id')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $company_list   = M('company')->select();
        $company_name   = $this->tranKeyArray($company_list,'id');
        if(!empty($list)){
            foreach ($list as $k=>$v){
                $list[$k]['status_cn']             = $this->getContractStatusCn($v['status']);
                $list[$k]['planning_purposes']     = $this->getRoomPlanningPurposesCn($v['planning_purposes']);
                $list[$k]['company_name']          = !empty($company_name[$v['company_id']]['name'])?$company_name[$v['company_id']]['name']:'';
            }
        }
        $company_html = '<select name="company_id" class="cs">';
        $company_html .= '<option value="0">请选择</option>';
        if(!empty($company_list)){
            foreach ($company_list as $v){
                if(!empty($postArr['company_id']) && $v['id'] == $postArr['company_id']){
                    $company_html .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                }else{
                    $company_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        $company_html .= '</select>';
        $this->assign('company_html',$company_html);
        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    //合同状态：1：未申请未审核 2申请未审核  3已审核通过（归档）4：已审核未通过  5注销
    private function getContractStatusCn($status){
        $arr = array(
            1=>'未申请未审核',
            2=>'申请未审核',
            3=>'已审核通过',
            4=>'已审核未通过',
            5=>'已注销',
        );
        return !empty($arr[$status])?$arr[$status]:'';
    }


    // 条件汇总
    public function room_condition_count_list()
    {
        //房屋坐落  规划用途 房屋性质 经纪机构 归档时间（1-2）  户室面积（1-2）
        $postArr['address']              = I('address','','trim');
        $postArr['planning_purposes']   = I('planning_purposes',0,'intval');
        $postArr['nature']               = I('nature',0,'intval');
        $postArr['company_id']           = I('company_id',0,'intval');
        $postArr['sdate']                = I('sdate','','trim');
        $postArr['edate']                = I('edate','','trim');
        $postArr['sarea']                = I('sarea','','trim');
        $postArr['earea']                = I('earea','','trim');
        $where = array();
        if(!empty($postArr['company_id'])){
            $where['r.company_id']  = $postArr['company_id'];
        }
        if(!empty($postArr['address'])){
            $where['r.address']  = array('like', "%{$postArr['address']}%");
        }
        if(!empty($postArr['planning_purposes'])){
            $where['r.planning_purposes']  = $postArr['planning_purposes'];
        }
        if(!empty($postArr['nature'])){
            $where['r.nature']  = $postArr['nature'];
        }
        if(!empty($postArr['sdate']) && !empty($postArr['edate'])){
            $where['c.add_time'] = array('between',strtotime($postArr['sdate']).','.strtotime($postArr['edate']));
        }
        if(!empty($postArr['sarea']) && !empty($postArr['earea'])){
            $postArr['sarea'] = intval($postArr['sarea']);
            $postArr['earea'] = intval($postArr['earea']);
            $where['covered_area'] = array('between',$postArr['sarea'].','.$postArr['earea']);
        }
        $contract       = M('contract'); // 实例化User对象
        //只统计已审核通过的合同
        $where['c.status']  = 3;
        $list = $contract->alias('c')
            ->field('r.covered_area,r.garage_area,c.deal_price')
            ->join('left join __ROOM__ as r on c.room_id = r.id')
            ->join('left join __COMPANY__ as com on r.company_id = com.id')
            ->where($where)
            ->select();
        $company_list   = M('company')->select();
        //成交套数  成交户室面积 成交车库面积 成交金额
        $info['number']        = !empty($list)?count($list):0;
        $info['covered_area'] = 0;
        $info['garage_area']  = 0;
        $info['total_price']  = 0;
        if(!empty($list)){
            foreach ($list as $v){
                $info['covered_area'] += intval($v['covered_area']);
                $info['garage_area'] += intval($v['garage_area']);
                $info['total_price'] += intval($v['deal_price']);
            }
        }
        $company_html = '<select name="company_id" class="cs">';
        $company_html .= '<option value="0">请选择</option>';
        if(!empty($company_list)){
            foreach ($company_list as $v){
                if(!empty($postArr['company_id']) && $v['id'] == $postArr['company_id']){
                    $company_html .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                }else{
                    $company_html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        $company_html .= '</select>';
        $this->assign('company_html',$company_html);
        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('info',$info);
        $this->display(); // 输出模板
    }

    //分类汇总
    public function room_cat_count_list()
    {
        //归档时间（1-2）  户室面积（1-2） 按房屋性质 按用途规划 按经纪机构
        $postArr['type']                 = I('type',1,'intval');
        $postArr['sarea']                = I('sarea','','trim');
        $postArr['earea']                = I('earea','','trim');
        $where = array();
        if(!empty($postArr['sarea']) && !empty($postArr['earea'])){
            $postArr['sarea'] = intval($postArr['sarea']);
            $postArr['earea'] = intval($postArr['earea']);
            $where['covered_area'] = array('between',$postArr['sarea'].','.$postArr['earea']);
        }
        $tmp            = M('room')->where($where)->select();
        $company_list   = M('company')->select();
        $company_name   = $this->tranKeyArray($company_list,'id');
        //总套数  总户室面积 总车库面积 总金额
        $list = array();
        if($postArr['type']  == 1){
            //1 商品房、2安置房、3房改房
            $arr = array(
                1=>'商品房',
                2=>'安置房',
                3=>'房改房',
            );
            foreach ($arr as $k=> $a){
                $list[$k]['cat_cn']       = $a;
                $number      = 0;
                $covered_area = 0;
                $garage_area = 0;
                $total_price = 0;
                foreach ($tmp as $v){
                    if($k == $v['nature']){
                        $number++;
                        $covered_area            += $v['covered_area'];
                        $garage_area             += $v['garage_area'];
                        $total_price             += $v['total_price'];
                    }
                }
                $list[$k]['number']       = $number;
                $list[$k]['covered_area'] = $covered_area;
                $list[$k]['garage_area']  = $garage_area;
                $list[$k]['total_price']  = $total_price;
            }
        }elseif($postArr['type']  == 2){
            //规划用途  1住宅、2商业、3办公
            $arr = array(
                1=>'住宅',
                2=>'商业',
                3=>'办公',
            );
            foreach ($arr as $k=> $a){
                $list[$k]['cat_cn']       = $a;
                $number      = 0;
                $covered_area = 0;
                $garage_area = 0;
                $total_price = 0;
                foreach ($tmp as $v){
                    if($k == $v['planning_purposes']){
                        $number++;
                        $covered_area           += $v['covered_area'];
                        $garage_area             += $v['garage_area'];
                        $total_price             += $v['total_price'];
                    }
                }
                $list[$k]['number']       = $number;
                $list[$k]['covered_area'] = $covered_area;
                $list[$k]['garage_area']  = $garage_area;
                $list[$k]['total_price']  = $total_price;
            }
        }elseif($postArr['type']  == 3){
            $arr = $company_name;
            foreach ($arr as $k=> $a){
                $list[$k]['cat_cn']       = $a['name'];
                $number      = 0;
                $covered_area = 0;
                $garage_area = 0;
                $total_price = 0;
                foreach ($tmp as $v){
                    if($k == $v['company_id']){
                        $number++;
                        $covered_area           += $v['covered_area'];
                        $garage_area             += $v['garage_area'];
                        $total_price             += $v['total_price'];
                    }
                }
                $list[$k]['number']       = $number;
                $list[$k]['covered_area'] = $covered_area;
                $list[$k]['garage_area']  = $garage_area;
                $list[$k]['total_price']  = $total_price;
            }
        }

        $this->assign('postArr',$postArr);// 搜索参数
        $this->assign('list',$list);// 赋值数据集
        $this->display(); // 输出模板
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