<?php if (!defined('THINK_PATH')) exit();?><script src="/Public/js/echarts.min.js"></script>
公式：<input type="text" name="formula"   value="<?php echo ($formula); ?>" size="24" />
x轴：<input type="text" name="x_axis"   value="<?php echo ($x_axis); ?>" size="24" />
<br/>
<div id="chart" style="width:1800px;height:800px;float:left;"></div>

<script>
    var options       = JSON.parse('<?php echo ($myChart); ?>');
    // 初始化图表标签
    var myChart       = echarts.init(document.getElementById('chart'));
    myChart.setOption(options);
</script>