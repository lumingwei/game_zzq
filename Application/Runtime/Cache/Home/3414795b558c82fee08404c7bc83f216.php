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
            <form name="search_form" action="<?php echo U('index/company_list');?>" method="post">
                &nbsp;企业名称：  <input type="text" name="name"   value="<?php echo ($postArr['name']); ?>" size="24"  />
                企业代码：  <input type="text" name="code"   value="<?php echo ($postArr['code']); ?>" size="24"  />
                法定代表人：<input type="text" name="boss"   value="<?php echo ($postArr['boss']); ?>" size="24"  />
                <button>查询</button>
                <a href="<?php echo U('index/add_company');?>"  title="新增机构"><font color="black">新增机构<font></a>
                <input type="reset" value="重置条件">
            </form>
            <table class="table" width="100%">
                <thead align="center">
                <tr>
                    <th width="10%">企业代码</th>
                    <th width="10%">企业类型</th>
                    <th width="10%">企业名称</th>
                    <th width="20%">统一社会信用代码/注册号</th>
                    <th width="10%">企业住所</th>
                    <th width="10%">法定代表人</th>
                    <th width="10%">联系人</th>
                    <th width="10%">联系电话</th>
                    <th width="20%">操作</th>
                </tr>
                </thead>
                <tbody align="center">
                <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
                        <td><?php echo ($vo["code"]); ?></td>
                        <td><?php echo ($vo["type"]); ?></td>
                        <td><?php echo ($vo["name"]); ?></td>
                        <td><?php echo ($vo["credit_code"]); ?></td>
                        <td><?php echo ($vo["address"]); ?></td>
                        <td><?php echo ($vo["boss"]); ?></td>
                        <td><?php echo ($vo["linkman"]); ?></td>
                        <td><?php echo ($vo["phone"]); ?></td>
                        <td>
                            <a href="<?php echo U('index/add_company');?>&id=<?php echo ($vo["id"]); ?>"  title="修改"><span>修改</span></a>
                            <a href="<?php echo U('index/del_company');?>&id=<?php echo ($vo["id"]); ?>"  title="删除"><span>删除</span></a>
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