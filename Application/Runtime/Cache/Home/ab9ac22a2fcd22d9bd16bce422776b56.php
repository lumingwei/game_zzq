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
            <form name="search_form" action="<?php echo U('index/room_condition_count_list');?>" method="post">
                &nbsp;房屋坐落：<input type="text" name="address"   value="<?php echo ($postArr['address']); ?>" size="24"  />&nbsp;
                经纪机构：
                   <?php echo ($company_html); ?>
                规划用途：
                <select  name="planning_purposes">
                    <option value="0">请选择</option>
                    <option value="1" <?php if($postArr['planning_purposes'] == 1): ?>selected<?php endif; ?>>住宅</option>
                    <option value="2" <?php if($postArr['planning_purposes'] == 2): ?>selected<?php endif; ?>>商业</option>
                    <option value="3" <?php if($postArr['planning_purposes'] == 3): ?>selected<?php endif; ?>>办公</option>
                </select>
                房屋性质：
                <select  name="nature">
                    <option value="0">请选择</option>
                    <option value="1" <?php if($postArr['nature'] == 1): ?>selected<?php endif; ?>>商品房</option>
                    <option value="2" <?php if($postArr['nature'] == 2): ?>selected<?php endif; ?>>安置房</option>
                    <option value="3" <?php if($postArr['nature'] == 3): ?>selected<?php endif; ?>>房改房</option>
                </select>
                <br/>
                &nbsp;归档时间：<input type="text" name="sdate"   value="<?php echo ($postArr['sdate']); ?>" size="24"  />-<input type="text" name="edate"  value="<?php echo ($postArr['edate']); ?>" size="24"  />
                户室面积：<input type="text" name="sarea"   value="<?php echo ($postArr['sarea']); ?>" size="24"  />-<input type="text" name="earea"  value="<?php echo ($postArr['earea']); ?>" size="24"  />
                <button>查询</button>
                <input type="reset" value="重置条件">
            </form>
            <table class="table" width="100%">
                <thead align="center">
                <tr>
                    <th width="10%">成交套数</th>
                    <th width="10%">成交户室面积</th>
                    <th width="10%">成交车库面积</th>
                    <th width="10%">成交金额</th>
                </tr>
                </thead>
                <tbody align="center">
                <tr>
                    <td><?php echo ($info["number"]); ?></td>
                    <td><?php echo ($info["covered_area"]); ?></td>
                    <td><?php echo ($info["garage_area"]); ?></td>
                    <td><?php echo ($info["total_price"]); ?></td>
                </tr>
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