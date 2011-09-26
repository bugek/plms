<?
	if(isset($left_menu)){
?>
<div class="menu_path">
	<ul>
<?
		foreach($left_menu as $link){
?>
		<li>
			<a href="<?=$link['link']?>" class="<? if(isset($link['class'])) echo $link['class']; ?> <? if(isset($link['select'])) echo "selected_menu"; ?>"><?=$link['title']?></a>
<?
				if(isset($link['list'])){
?>
<ul class="<? if(isset($link['class'])) echo $link['class']; ?>">
<?
					foreach($link['list'] as $sub_link){
?>
						<li><a href="<?=$sub_link['link']?>" <? if(isset($sub_link['class'])) echo "class='".$sub_link['class']."'";?> <? if(isset($sub_link['onclick'])) echo "onclick=\"".$sub_link['onclick']."\"";?>><?=$sub_link['title']?></a></li>
<?
					}
?>
</ul>
<?
				}
?>
		</li>
<?
		}
?>
	</ul>
</div>
<div class="menu_path_footer">
&nbsp;
</div>
<?
	}
?>
