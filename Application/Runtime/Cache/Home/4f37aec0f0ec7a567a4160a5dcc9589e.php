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
                <p>企业名称：<input type="text" name="name" value="<?php echo ($info['name']); ?>"> </p>
                <p>企业代码：<input type="text" name="code" value="<?php echo ($info['code']); ?>"></p>
                <p>企业住所：<input type="text" name="address" value="<?php echo ($info['address']); ?>"></p>
                <p>法定代表人：<input type="text" name="boss" value="<?php echo ($info['boss']); ?>"></p>
                <p>注册资本：<input type="number" name="registered_assets" placeholder="单位：万" value="<?php echo ($info['registered_assets']); ?>"></p>
                <p>成立日期：<input type="text" name="open_date" placeholder="格式：yyyy-mm-dd" value="<?php echo ($info['open_date']); ?>"></p>
                <p>营业期限：<input type="number" name="business_term" value="<?php echo ($info['business_term']); ?>" placeholder="单位：年"></p>
                <p>注册号：<input type="text" name="credit_code" value="<?php echo ($info['credit_code']); ?>"></p>
                <p>登记机关：<input type="text" name="registration_authority" value="<?php echo ($info['registration_authority']); ?>"> </p>
                <p>登记日期：<input type="text" name="registration_date" placeholder="格式：yyyy-mm-dd" value="<?php echo ($info['registration_date']); ?>"></p>
                <p>开户银行：<input type="text" name="bank" value="<?php echo ($info['bank']); ?>"></p>
                <p>银行账号：<input type="text" name="bank_account" value="<?php echo ($info['bank_account']); ?>"></p>
                <p>联系人：  <input type="text" name="linkman" value="<?php echo ($info['linkman']); ?>"></p>
                <p>联系电话：<input type="text" name="phone" value="<?php echo ($info['phone']); ?>"></p>
                <p>经营范围：<input type="text" name="business_scope" value="<?php echo ($info['business_scope']); ?>"></p>
                <p>企业类型：
                    <select name="type" class="cs">
                        <option value="0">请选择</option>
                        <option value="1" <?php if($info['type'] == 1): ?>selected<?php endif; ?>>互联网</option>
                        <option value="2" <?php if($info['type'] == 2): ?>selected<?php endif; ?>>传统</option>
                        <option value="3" <?php if($info['type'] == 3): ?>selected<?php endif; ?>>其他</option>
                    </select>
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

                url:'<?php echo U("index/add_company");?>',

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