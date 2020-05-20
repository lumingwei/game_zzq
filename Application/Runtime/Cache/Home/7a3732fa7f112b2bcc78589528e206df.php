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
            <form name="search_form" action="<?php echo U('index/room_cat_count_list');?>" method="post">
                &nbsp;分类条件：
                <select  name="type">
                    <option value="1" <?php if($postArr['type'] == 1): ?>selected<?php endif; ?>>按房屋性质</option>
                    <option value="2" <?php if($postArr['type'] == 2): ?>selected<?php endif; ?>>按用途规划</option>
                    <option value="3" <?php if($postArr['type'] == 3): ?>selected<?php endif; ?>>按经纪机构</option>
                </select>
                户室面积：<input type="text" name="sarea"   value="<?php echo ($postArr['sarea']); ?>" size="24"  />-<input type="text" name="earea"  value="<?php echo ($postArr['earea']); ?>" size="24"  />
                <button>查询</button>
                <input type="reset" value="重置条件">
            </form>
            <table class="table" width="100%">
                <thead align="center">
                <tr>
                    <th width="10%">分类</th>
                    <th width="10%">总套数</th>
                    <th width="10%">总户室面积</th>
                    <th width="10%">总车库面积</th>
                    <th width="10%">总金额</th>
                </tr>
                </thead>
                <tbody align="center">
                <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
                        <td><?php echo ($vo["cat_cn"]); ?></td>
                        <td><?php echo ($vo["number"]); ?></td>
                        <td><?php echo ($vo["covered_area"]); ?></td>
                        <td><?php echo ($vo["garage_area"]); ?></td>
                        <td><?php echo ($vo["total_price"]); ?></td>
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