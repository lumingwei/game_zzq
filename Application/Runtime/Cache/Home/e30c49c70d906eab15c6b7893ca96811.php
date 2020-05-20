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
            <form name="search_form" action="<?php echo U('index/room_list');?>" method="post">
                &nbsp;企业：
                   <?php echo ($company_html); ?>
                坐落：<input type="text" name="address"   value="<?php echo ($postArr['address']); ?>" size="24"  />
                &nbsp;规划用途：
                <select  name="planning_purposes">
                    <option value="0">请选择</option>
                    <option value="1">住宅</option>
                    <option value="2">商业</option>
                    <option value="3">办公</option>
                </select>
                &nbsp;房屋状态：
                <select  name="status">
                    <option value="0">请选择</option>
                    <option value="1">待出售</option>
                    <option value="2">已出售</option>
                </select>
                建筑面积：<input type="text" name="sarea"   value="<?php echo ($postArr['sarea']); ?>" size="24"  />-<input type="text" name="earea"  value="<?php echo ($postArr['earea']); ?>" size="24"  />
                <button>查询</button>
                <a href="<?php echo U('index/add_room');?>"  title="新增房源"><font color="black">新增房源<font></a>
                <input type="reset" value="重置条件">
            </form>
            <table class="table" width="100%">
                <thead align="center">
                <tr>
                    <th width="10%">经纪机构</th>
                    <th width="10%">房屋坐落</th>
                    <th width="3%">建筑面积</th>
                    <th width="3%">阁楼面积</th>
                    <th width="3%">规划用途</th>
                    <th width="10%">车库坐落</th>
                    <th width="3%">车库面积</th>
                    <th width="10%">车位坐落</th>
                    <th width=3%">车位面积</th>
                    <th width="3%">总价</th>
                    <th width="5%">联系人</th>
                    <th width="8%">联系电话</th>
                    <th width="5%">状态</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody align="center">
                <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
                        <td><?php echo ($vo["company_name"]); ?></td>
                        <td><?php echo ($vo["address"]); ?></td>
                        <td><?php echo ($vo["covered_area"]); ?></td>
                        <td><?php echo ($vo["attic_area"]); ?></td>
                        <td><?php echo ($vo["planning_purposes"]); ?></td>
                        <td><?php echo ($vo["garage_position"]); ?></td>
                        <td><?php echo ($vo["garage_area"]); ?></td>
                        <td><?php echo ($vo["parking_position"]); ?></td>
                        <td><?php echo ($vo["parking_area"]); ?></td>
                        <td><?php echo ($vo["total_price"]); ?></td>
                        <td><?php echo ($vo["linkman"]); ?></td>
                        <td><?php echo ($vo["phone"]); ?></td>
                        <td><?php echo ($vo["status"]); ?></td>
                        <td>
                            <a href="<?php echo U('index/add_room');?>&id=<?php echo ($vo["id"]); ?>"  title="修改"><span>修改</span></a>
                            <a href="<?php echo U('index/del_room');?>&id=<?php echo ($vo["id"]); ?>"  title="删除"><span>删除</span></a>
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