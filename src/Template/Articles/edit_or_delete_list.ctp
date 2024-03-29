<?= $this->Html->script('admin/jsForEditOrDeleteList.js') ?>
<style>
	body{
		padding-top: 15vh;
	}
</style>
<!--作品をフェードインさせるためのdiv -->
<h1 class="text-center">Articles</h1>
<div class="row">
<?php foreach ($articles as $article): ?>
	<div class="col-sm-4 mb-1">
		<div class="card mb-3" style="max-width: 25rem;">
		<a href="">
		<img class="card-img-top" src="/img/uploaded/<?=$article['thumbnail']?>">
		</a>
		<div class="card-body text-center">
			<span><?=$article['title']?>
			</span>
		</div>
		<div class="card-footer text-center" style="font-size:11px; background-color:#ffffff">
		<a href="/<?=$secretUrl?>/editArticles/<?=$article['id']?>"><button class="btn btn-outline-dark border">Edit</button></a>
    <div class="btn btn-outline-dark border delete"><input type="hidden" value="<?=$article['id']?>">Delete</div>
    	<span><?=$article['upd_ymd']?></span>
			</div>
		</div>
	</div>
<?php endforeach?>
</div>

<!--csrfトークン生成-->
<?= $this->Form->create(null, [
'url'=>['controller'=>'Article','action'=>'index'],
]) ?>
<?= $this->Form->end() ?>
