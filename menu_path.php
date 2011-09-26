<div class="menu_path">
	<ul>
<?
		foreach($menu_path as $link){
?>
		<li class="<? if(isset($link['class'])) echo $link['class']; ?>">
			<a href="<?=$link['link']?>"><?=$link['title']?></a>
		</li>
<?
		}
?>
	</ul>
</div>

