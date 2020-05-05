<?php
$labels = $values = array();
foreach ($q['parameters'] as $k=>$v) {
	$value = intval(@$q['results']['p'.$k]);
	$labels[] = htmlspecialchars($v . ' (' . $value . ')');
	$values[] = $value;
}
?>

<div class="form-group col-lg-12">
	<canvas class="js_chart" id="chartjs"
	        data-labels="<?=implode(',',$labels)?>"
	        data-values="[<?=implode(',',$values)?>]"
	></canvas>
</div>