<?php
if ($i==1) {
	?>
<div id="menu">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="dropdown nav navbar-nav l1">
	<?php
}
?>
			<?php if ($q['_active']==1) {?>
			<li class="active"><span class="a"><?=$q['name']?></span>
			<?php } else {?>
			<li><a href="<?=$q['_url']?>" title="<?=htmlspecialchars($q['name'])?>"><?=$q['name']?></a>
			<?php } ?>

			<?php if ($q['_submenu']) {?>
			<ul class="dropdown-menu">
				<?php foreach ($q['_submenu'] as $k=>$v) {?>
				<li><a href="<?=$v['_url']?>" title="<?=htmlspecialchars($v['name'])?>"><?=$v['name']?></a></li>
				<?php } ?>
			</ul>
			<?php } ?>
			</li>
<?php
if ($i==$num_rows) {
	?>
		</ul>
	</div>
</div>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {
	$('#menu .dropdown-menu').prev('a,.a').append('<span class="caret"></span>').click(function () {
		$(this).next('.dropdown-menu').toggle();
		return false;
	});
	$('#menu .active').parents('li').addClass('active');
})
</script>
	<?php
}
?>