<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {

    //后台首页
    public function index(){
        $this->redirect('index/company_list');
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

}