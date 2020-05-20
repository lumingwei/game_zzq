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
        <div class="main" style="text-align: center;">
            <input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
            <p>合同编号：<input type="text" name="number" value="<?php echo ($info['number']); ?>" readonly="readonly"> </p>
            <p>合同备案号：<input type="text" name="record_number" value="<?php echo ($info['record_number']); ?>" readonly="readonly"> </p>
            <p>关联房源id：<input type="text" name="room_id"  value="<?php echo ($info['room_id']); ?>" readonly="readonly"></p>
            <p>出卖方：<input type="text" name="seller"  value="<?php echo ($info['seller']); ?>" readonly="readonly"></p>
            <p>买受方：<input type="text" name="buyer"  value="<?php echo ($info['buyer']); ?>" readonly="readonly"></p>
            <p>成交总价：<input type="text" name="deal_price"  value="<?php echo ($info['deal_price']); ?>" readonly="readonly"></p>
            <p>合同状态：<input type="text" name="status"  value="<?php echo ($info['status']); ?>" readonly="readonly"></p>
            <p>申请审核时间：<input type="text" name="apply_time"  value="<?php echo ($info['apply_time']); ?>" readonly="readonly"></p>
            <p>审核时间：<input type="text" name="check_time"  value="<?php echo ($info['check_time']); ?>" readonly="readonly"></p>
            <p>注销时间：<input type="text" name="cancel_time"  value="<?php echo ($info['cancel_time']); ?>" readonly="readonly"></p>
            <p>创建时间：<input type="text" name="add_time"  value="<?php echo ($info['add_time']); ?>" readonly="readonly"></p>
            <p><a href=”#” onclick="javascript :history.go(-1);">返回</a></p>
        </div>
    </div>
</div>
<div class="bottom"></div>
<div id="footer"><p>存量房备案系统</p></div>
<script>navList(12);</script>
</body>
</html>
<style>
    p {
        display: block;
        -webkit-margin-before: 1em;
        -webkit-margin-after: 1em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
    }
</style>