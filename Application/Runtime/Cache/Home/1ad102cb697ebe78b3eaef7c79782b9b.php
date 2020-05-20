<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>存量房备案系统</title>
    <link type="text/css" rel="stylesheet" href="public/css/style.css" />
    <link type="text/css" rel="stylesheet" href="public/css/table.css" />
    <script type="text/javascript" src="public/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="public/js/menu.js"></script>
</head>

<body>
<div class="top"></div>
<div id="header">
    <div class="logo">存量房备案管理系统</div>
    <div class="navigation">
        <ul>
            <li>欢迎您！</li>
            <li><a href="">张山</a></li>
            <li><a href="">修改密码</a></li>
            <li><a href="">设置</a></li>
            <li><a href="">退出</a></li>
        </ul>
    </div>
</div>



<div id="content">
    <div class="left_menu">
        <ul id="nav_dot">
            <?php echo ($menu_html); ?>
        </ul>
    </div>
    <div class="m-right">
        <div class="right-nav">
            <ul>
                <li><img src="public/images/home.png"></li>
                <li style="margin-left:25px;">您当前的位置：</li>
                <li><a href="#"><?php echo ($position_1); ?></a></li>
                <li>></li>
                <li><a href="#"><?php echo ($position_2); ?></a></li>
            </ul>
        </div>
        <div class="main">
            <form name="search_form" action="<?php echo U('index/contract_list');?>" method="post">
                &nbsp;合同状态：
                <select  name="status">
                    <option value="0">请选择</option>
                    <option value="1" <?php if($postArr['status'] == 1): ?>selected<?php endif; ?>>未申请未审核</option>
                    <option value="2" <?php if($postArr['status'] == 2): ?>selected<?php endif; ?>>申请未审核</option>
                    <option value="3" <?php if($postArr['status'] == 3): ?>selected<?php endif; ?>>已审核通过</option>
                    <option value="4" <?php if($postArr['status'] == 4): ?>selected<?php endif; ?>>已审核未通过</option>
                    <option value="5" <?php if($postArr['status'] == 5): ?>selected<?php endif; ?>>已注销</option>
                </select>
                &nbsp;企业：
                   <?php echo ($company_html); ?>
                坐落：<input type="text" name="address"   value="<?php echo ($postArr['address']); ?>" size="24"  />
                &nbsp;规划用途：
                <select  name="planning_purposes">
                    <option value="0">请选择</option>
                    <option value="1" <?php if($postArr['planning_purposes'] == 1): ?>selected<?php endif; ?>>住宅</option>
                    <option value="2" <?php if($postArr['planning_purposes'] == 2): ?>selected<?php endif; ?>>商业</option>
                    <option value="3" <?php if($postArr['planning_purposes'] == 3): ?>selected<?php endif; ?>>办公</option>
                </select>
                合同编号：<input type="text" name="number"   value="<?php echo ($postArr['number']); ?>" size="24"  />
                出卖方：<input type="text" name="seller"   value="<?php echo ($postArr['seller']); ?>" size="24"  />
                买受方 ：<input type="text" name="buyer"   value="<?php echo ($postArr['buyer']); ?>" size="24"  />
                <br/>
                &nbsp;不动产权证 ：<input type="text" name="property_rights_code"   value="<?php echo ($postArr['property_rights_code']); ?>" size="24"  />
                备案时间：<input type="text" name="sdate"   value="<?php echo ($postArr['sdate']); ?>" size="24"  />-<input type="text" name="edate"  value="<?php echo ($postArr['edate']); ?>" size="24"  />
                <button>查询</button>
                <a href="<?php echo U('index/add_contract');?>"  title="新增合同"><font color="black">新增合同<font></a>
                <input type="reset" value="重置条件">
            </form>
            <table class="table" width="100%">
                <thead align="center">
                <tr>
                    <th width="8%">合同编号</th>
                    <th width="8%">合同备案号</th>
                    <th width="3%">出卖方</th>
                    <th width="3%">买受方</th>
                    <th width="8%">合同状态</th>
                    <th width="10%">规划用途</th>
                    <th width="10%">房屋坐落</th>
                    <th width="10%">车库坐落</th>
                    <th width=10%">车位坐落</th>
                    <th width="5%">不动产证号</th>
                    <th width="10%">经纪机构</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody align="center">
                <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
                        <td><?php echo ($vo["number"]); ?></td>
                        <td><?php echo ($vo["record_number"]); ?></td>
                        <td><?php echo ($vo["seller"]); ?></td>
                        <td><?php echo ($vo["buyer"]); ?></td>
                        <td><?php echo ($vo["status_cn"]); ?></td>
                        <td><?php echo ($vo["planning_purposes"]); ?></td>
                        <td><?php echo ($vo["address"]); ?></td>
                        <td><?php echo ($vo["garage_position"]); ?></td>
                        <td><?php echo ($vo["parking_position"]); ?></td>
                        <td><?php echo ($vo["property_rights_code"]); ?></td>
                        <td><?php echo ($vo["company_name"]); ?></td>
                        <td>
                            <?php if($vo['status'] == 1): ?><a href="<?php echo U('index/add_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="修改"><span>修改</span></a>
                                <a href="<?php echo U('index/set_contract_status');?>&id=<?php echo ($vo["id"]); ?>&status=2"  title="提交审核"><span>提交审核</span></a>
                                <a href="<?php echo U('index/del_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="删除"><span>删除</span></a><?php endif; ?>
                            <?php if($vo['status'] == 2): ?><a href="<?php echo U('index/look_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="查看"><span>查看</span></a>
                                <a href="<?php echo U('index/set_contract_status');?>&id=<?php echo ($vo["id"]); ?>&status=3"  title="通过"><span>通过</span></a>
                                <a href="<?php echo U('index/set_contract_status');?>&id=<?php echo ($vo["id"]); ?>&status=4"  title="不通过"><span>不通过</span></a><?php endif; ?>
                            <?php if($vo['status'] == 3): ?><a href="<?php echo U('index/look_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="查看"><span>查看</span></a>
                                <a href="<?php echo U('index/set_contract_status');?>&id=<?php echo ($vo["id"]); ?>&status=5"  title="注销"><span>注销</span></a><?php endif; ?>
                            <?php if($vo['status'] == 4): ?><a href="<?php echo U('index/look_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="查看"><span>查看</span></a><?php endif; ?>
                            <?php if($vo['status'] == 5): ?><a href="<?php echo U('index/look_contract');?>&id=<?php echo ($vo["id"]); ?>"  title="查看"><span>查看</span></a><?php endif; ?>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <div class="pages" style="float:right;">
                <?php echo ($page); ?>
            </div>
        </div>
    </div>
</div>
<div class="bottom"></div>
<div id="footer"><p>存量房备案系统</p></div>
<script>navList(12);</script>
</body>
</html>