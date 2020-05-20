<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>拖拽</title>
<script type="text/javascript" src="jquery-1.8.3.min.js"></script>
<style type="text/css">
		input{
			transition:all 0.30s ease-in-out;
			-webkit-transition: all 0.30s ease-in-out;
			-moz-transition: all 0.30s ease-in-out;

			border:#35a5e5 1px solid;
			border-radius:3px;
			outline:none;
		}
		input:focus{
			box-shadow:0 0 5px rgba(81, 203, 238, 1);
			-webkit-box-shadow:0 0 5px rgba(81, 203, 238, 1);
			-moz-box-shadow:0 0 5px rgba(81, 203, 238, 1);
		}
		.lmw{
			text-decoration:none;
			background:rgba(81, 203, 238, 1);
			color:white;padding: 6px 25px 6px 25px;
			font:16px '微软雅黑';
			border-radius:3px;

			-webkit-transition:all linear 0.30s;
			-moz-transition:all linear 0.30s;
			transition:all linear 0.30s;
		}
		.lmw:hover{background:rgba(39, 154, 187, 1);}
		.lmw1{
			text-decoration:none;
			background:rgba(238, 44, 44, 1);
			color:white;padding: 6px 25px 6px 25px;
			font:16px '微软雅黑';
			border-radius:3px;

			-webkit-transition:all linear 0.30s;
			-moz-transition:all linear 0.30s;
			transition:all linear 0.30s;
		}
		.lmw1:hover{background:rgba(39, 154, 187, 1);}
	</style>
<style>
	.warp{
		width: 100%;
		height: 100px;
		line-height: 100px;
		text-align: center;
		font-size: 40px;
		font-family: "微软雅黑";
	}
	.warp strong{
		width: 100px;
		display: inline-block;
		text-align: center;
		font-family: georgia;
		color: #C9302C;
	}
</style>
<style>
.container ul{
	width: 350px;
    padding: 15px;
    min-height:300px;
    background-color:#FFFFF0;
    margin:20px;
	display: inline-block;
    border-radius: 5px;
    border: 1px solid #bbb;
}
.container ul li{
    display: block;
    float: left;
    width: 350px;
    height: 35px;
    line-height: 35px;
    border-radius: 4px;
	margin: 0;
	padding: 0;
	list-style: none;
    background-color:#EED2EE;
    margin-bottom:10px;
	-moz-user-select: none;
    user-select: none;
    text-indent: 10px;
    color: #555;
}
</style>
</head>
<body>
	<div style="text-align:center;margin:0 auto;">
		<label style="font-size:30px;"><?php echo ($time["date"]); ?></label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label style="font-size:30px;"><?php echo ($time["week"]); ?></label>
	</div>
	<br/>
	<div style="text-align:center;margin:0 auto;">
		<input type="text" id="task_content" placeholder="描述任务"  style="height:30px;width: 400px;"/>
		<input type="button" class="lmw" value="提交" onclick="addTask()">
	</div>
	<br/>
	<div style="text-align:center;margin:0 auto;">
		<input type="button" class="lmw1" value="开始" onclick="startTime()">
	</div>
	<div class="warp" style="text-align:center;margin:0 auto;">
		<strong class="c"></strong>分 <strong class="a"></strong>秒
	</div>
	<div class="container" style="text-align:center;margin:0 auto;">
		<ul id="ready_task" data-status="0">
			<label style="font-size:22px;">筹划中</label>
		</ul>
		<ul id="doing_task" data-status="1">
			<label style="font-size:22px;">进行中</label>
		</ul>
		<ul id="done_task" data-status="2">
			<label style="font-size:22px;">已完成</label>
		</ul>
	</div>


<script>
	$(document).ready(function() {
		getTask();
		var date_str = "";
		for (var i = 0; i <= 23; i++) {
			var d_;
			i < 10 ? d_ = "0" + i : d_ = i;
			date_str = date_str + "<option>" + d_ + "</option><br/>"
		}
		$("#_minute").append(date_str);
		var time_str = "";
		for (var i = 0; i <= 59; i++) {
			var t_;
			i < 10 ? t_ = "0" + i : t_ = i;
			time_str = time_str + "<option>" + t_ + "</option><br/>"
		}
		$("#_second").append(time_str);
	});
	$(document).ready(function() {
		$("#start_").click(function() {
			var m_ = $("#_minute").val();
			var s_ = $("#_second").val();
			seconds_ = m_ * 60 + s_ * 1;
		});
		$("#reset_").click(function() {
			$("#_minute").val("00");
			$("#_second").val("00");
			seconds_ = 0;
			clearInterval(timer);
		});
	});
</script>
<script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
<script type="text/javascript">
function startTime(){
	if(typeof(countTime) != "undefined"){
		clearInterval(countTime);
	}
	var times = 60 * 25; // 25分钟 1500秒
	countTime = setInterval(function() {
		times = --times < 0 ? 0 : times;

		var min = Math.floor(times / 60).toString();

		if(min.length <= 1) {
			min = "0" + min;
		}

		var ms = Math.floor(times % 60).toString();

		if(ms.length <= 1) {
			ms = "0" + ms;
		}

		if(times == 0) {
			alert("任务结束");
			clearInterval(countTime);
		}
		// 获取分钟、毫秒数
		$(".c").html(min);
		$(".a").html(ms);
	}, 1000);
}

function getTask(){
	$.ajax({
		url: '<?php echo U("Index/getTask");?>',
		type: 'get',
		dataType: 'json',
		timeout: 1000,
		success: function (data, status) {
			if(data.code != 1){
				alert(data.msg);
			}else{
				if(data.data.length>0){
					$.each(data.data,function(index, item){
						var jq_id = '';
                        if(item.status == 0){
							jq_id = 'ready_task';
						}else if(item.status == 1){
							jq_id = 'doing_task';
						}else if(item.status == 2){
							jq_id = 'done_task';
						}else{
							jq_id = 'delay_task';
						}
						$("#"+jq_id).append('<li data-id="'+item.id+'">'+item.content+'</li>');
					})
				}
			}
		},
		fail: function (err, status) {
			alert('操作失败！')
		}
	})
}

function updateTask(id,status){
	$.ajax({
		url: '<?php echo U("Index/updateTask");?>&id='+id+'&status='+status,
		type: 'get',
		dataType: 'json',
		timeout: 1000,
		success: function (data, status) {
			if(data.code != 1){
				alert(data.msg);
			}
		},
		fail: function (err, status) {
			alert('操作失败！')
		}
	})
}

function addTask(){
	var content = $("#task_content").val();
	if(content){
		$.ajax({
			url: '<?php echo U("Index/addTask");?>&content='+content,
			type: 'get',
			dataType: 'json',
			timeout: 1000,
			success: function (data, status) {
				if(data.code != 1){
					alert(data.msg);
				}else{
					var item = '<li data-id="'+data.data+'">'+content+'</li>';
					$("#ready_task").append(item);
				}
			},
			fail: function (err, status) {
				alert('操作失败！')
			}
		})
	}
}


$(function(){

    //出入允许拖拽节点的父容器，一般是ul外层的容器
    drag.init('container');

});


/** 拖拽功能实现原理和说明：

1、说明：拖拽实现一般有两种方式，一种是使用html的新特性dragable，但是由于在火狐浏览器上dragable每拖拽一次会打开一个新的标签，
尝试阻止默认行为和冒泡都无法解决，所以使用第二种方法来实现拖拽。第二种方法是使用js监听鼠标三个事件，配合节点操作来实现。

2、实现原理：
    01-在允许拖拽的节点元素上，使用on来监听mousedown(按下鼠标按钮)事件，鼠标按下后，克隆当前节点
    02-监听mousemove(鼠标移动)事件，修改克隆出来的节点的坐标，实现节点跟随鼠标的效果
    03-监听mouseup(放开鼠标按钮)事件，将原节点克隆到鼠标放下位置的容器里，删除原节点，拖拽完成。

3、优势：
    01-可动态添加允许拖拽的节点(因为使用了on委托事件)
    02-可获取到原节点，跟随节点，目标节点的对象，可根据需要进行操作。
    03-使用js实现，兼容性好。
**/


//拖拽
var drag = {

    class_name : null,  //允许放置的容器
	permitDrag : false,	//是否允许移动标识

	_x : 0,             //节点x坐标
    _y : 0,			    //节点y坐标
    _left : 0,          //光标与节点坐标的距离
    _top : 0,           //光标与节点坐标的距离

    old_elm : null,     //拖拽原节点
    tmp_elm : null,     //跟随光标移动的临时节点
    new_elm : null,     //拖拽完成后添加的新节点

    //初始化
    init : function (className){

        //允许拖拽节点的父容器的classname(可按照需要，修改为id或其他)
        drag.class_name = className;

        //监听鼠标按下事件，动态绑定要拖拽的节点（因为节点可能是动态添加的）
        $('.' + drag.class_name).on('mousedown', 'ul li', function(event){
            //当在允许拖拽的节点上监听到点击事件，将标识设置为可以拖拽
            drag.permitDrag = true;
            //获取到拖拽的原节点对象
            drag.old_elm = $(this);
            //执行开始拖拽的操作
            drag.mousedown(event);
            return false;
        });

        //监听鼠标移动
        $(document).mousemove(function(event){
            //判断拖拽标识是否为允许，否则不进行操作
            if(!drag.permitDrag) return false;
            //执行移动的操作
            drag.mousemove(event);
            return false;
        });

        //监听鼠标放开
        $(document).mouseup(function(event){
            //判断拖拽标识是否为允许，否则不进行操作
            if(!drag.permitDrag) return false;
            //拖拽结束后恢复标识到初始状态
            drag.permitDrag = false;
            //执行拖拽结束后的操作
            drag.mouseup(event);
            return false;
        });

    },

	//按下鼠标 执行的操作
	mousedown : function (event){

		console.log(event);
        //1.克隆临时节点，跟随鼠标进行移动
        drag.tmp_elm = $(drag.old_elm).clone();

        //2.计算 节点 和 光标 的坐标
        drag._x = $(drag.old_elm).offset().left;
        drag._y = $(drag.old_elm).offset().top;

        var e = event || window.event;
        drag._left = e.pageX - drag._x;
        drag._top = e.pageY - drag._y;

        //3.修改克隆节点的坐标，实现跟随鼠标进行移动的效果
        $(drag.tmp_elm).css({
            'position' : 'absolute',
            'background-color' : '#FF8C69',
            'left' : drag._x,
            'top' : drag._y,
        });

        //4.添加临时节点
        tmp = $(drag.old_elm).parent().append(drag.tmp_elm);
        drag.tmp_elm = $(tmp).find(drag.tmp_elm);
        $(drag.tmp_elm).css('cursor', 'move');

	},

	//移动鼠标 执行的操作
	mousemove : function (event){

		console.log('我被mousemove了');

        //2.计算坐标
        var e = event || window.event;
        var x = e.pageX - drag._left;
        var y = e.pageY - drag._top;
        var maxL = $(document).width() - $(drag.old_elm).outerWidth();
        var maxT = $(document).height() - $(drag.old_elm).outerHeight();
        //不允许超出浏览器范围
        x = x < 0 ? 0: x;
        x = x > maxL ? maxL: x;
        y = y < 0 ? 0: y;
        y = y > maxT ? maxT: y;

        //3.修改克隆节点的坐标
        $(drag.tmp_elm).css({
            'left' : x,
            'top' : y,
        });

        //判断当前容器是否允许放置节点
        $.each($('.' + drag.class_name + ' ul'), function(index, value){

            //获取容器的坐标范围 (区域)
            var box_x = $(value).offset().left;     //容器左上角x坐标
            var box_y = $(value).offset().top;      //容器左上角y坐标
            var box_width = $(value).outerWidth();  //容器宽
            var box_height = $(value).outerHeight();//容器高
            
            //给可以放置的容器加背景色
            if(e.pageX > box_x && e.pageX < box_x-0+box_width && e.pageY > box_y && e.pageY < box_y-0+box_height){

                //判断是否不在原来的容器下（使用坐标进行判断：x、y任意一个坐标不等于原坐标，则表示不是原来的容器）
                if($(value).offset().left !== drag.old_elm.parent().offset().left 
                || $(value).offset().top !== drag.old_elm.parent().offset().top){
                    
                    $(value).css('background-color', '#FFEFD5');
                }
            }else{
                //恢复容器原背景色
                $(value).css('background-color', '#FFFFF0');
            }

        });

	},

    //放开鼠标 执行的操作
    mouseup : function (event){

        console.log(event);

		var task_id = drag.tmp_elm[0].dataset.id;
		var status  = -1;

        //移除临时节点
        $(drag.tmp_elm).remove();

        //判断所在区域是否允许放置节点
        var e = event || window.event;

        $.each($('.' + drag.class_name + ' ul'), function(index, value){

            //获取容器的坐标范围 (区域)
            var box_x = $(value).offset().left;     //容器左上角x坐标
            var box_y = $(value).offset().top;      //容器左上角y坐标
            var box_width = $(value).outerWidth();  //容器宽
            var box_height = $(value).outerHeight();//容器高

            //判断放开鼠标位置是否想允许放置的容器范围内
            if(e.pageX > box_x && e.pageX < box_x-0+box_width && e.pageY > box_y && e.pageY < box_y-0+box_height){
                //判断是否不在原来的容器下（使用坐标进行判断：x、y任意一个坐标不等于原坐标，则表示不是原来的容器）
                if($(value).offset().left !== drag.old_elm.parent().offset().left 
                || $(value).offset().top !== drag.old_elm.parent().offset().top){
                    //向目标容器添加节点并删除原节点
                    tmp = $(drag.old_elm).clone();
                    var newObj = $(value).append(tmp);
                    $(drag.old_elm).remove();
                    //获取新添加节点的对象
                    drag.new_elm = $(newObj).find(tmp);
					status = $(value)[0].dataset.status;
                }
            }

			console.log(drag.tmp_elm[0].dataset.id);

			//异步修改任务状态
			if(status != -1){
				updateTask(task_id,status);
			}
            //恢复容器原背景色
            $(value).css('background-color', '#FFFFF0');
        });

    },

};

</script>

</body>
</html>