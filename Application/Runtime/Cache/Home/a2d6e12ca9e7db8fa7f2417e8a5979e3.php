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
            <form id='editRealMsgForm' enctype="multipart/form-data" action="#"  method="post">
                <input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
                <p>企业：
                     <?php echo ($company_html); ?>
                </p>
                <p>用户名称：<input type="text" name="name" value="<?php echo ($info['name']); ?>"> </p>
                <p>密码：<input type="text" name="pwd" value="<?php echo ($info['pwd']); ?>"></p>
                <p>联系电话：<input type="text" name="phone" value="<?php echo ($info['phone']); ?>"></p>
                <p>电子邮箱：<input type="text" name="email" value="<?php echo ($info['email']); ?>"></p>
                <p>
                <label>权限设置:</label>
                    <?php echo ($act_code_html); ?>
                </p>
                <p><input type="submit" value="提交"></p>
            </form>
        </div>
    </div>
</div>
<div class="bottom"></div>
<div id="footer"><p>存量房备案系统</p></div>
<script>navList(12);</script>
</body>
</html>
<script>
    $(document).ready(function() {

// 使用 jQuery异步提交表单

        $('#editRealMsgForm').submit(function() {

            jQuery.ajax({

                url:'<?php echo U("index/add_company_user");?>',

                data:$('#editRealMsgForm').serialize(),

                type:"POST",

                beforeSend:function()
                {
                },
                success:function(data)
                {
                    if(data.status == 1){
                        alert(data.info);
                        window.location.href = data.url;
                    }else{
                        alert(data.info);
                    }
                }

            });

            return false;

        });

    });
</script>
<style>
    p {
        display: block;
        -webkit-margin-before: 1em;
        -webkit-margin-after: 1em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
    }
</style>