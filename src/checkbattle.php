<?php
	$checabattalha = $db->execute("select `hp` from `bixos` where `player_id`=?", array($player->id));
	if ($checabattalha->recordcount() > 0) {
	header("Location: monster.php?act=attack");
	break;
	}
?>