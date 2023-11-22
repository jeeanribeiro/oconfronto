<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Grupos de Ca�a");
$player = check_user($secret_key, $db);


if (!$_GET['id']) {
	header("Location: home.php");
} else {
	$query = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['id'], $player->id]);
		if ($query->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
    		echo "Grupo de ca�a n�o encontrado.<br/>";
		echo "<a href=\"home.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $group = $query->fetchrow();
  $countamembros = $db->execute("select * from `groups` where `id`=?", [$_GET['id']]);
  $leaderlevel = $db->GetOne("select `level` from `players` where `id`=?", [$_GET['id']]);
  $leadername = $db->GetOne("select `username` from `players` where `id`=?", [$_GET['id']]);

	include(__DIR__ . "/templates/private_header.php");
	if (($player->level + 30) < $leaderlevel) {
     echo "A diferen�a de n�vel entre voc� e o lider do grupo � maior que 30 n�veis.<br/>";
     echo "<a href=\"home.php\">Voltar</a>.";
 } elseif (($player->level - 30) > $leaderlevel) {
     echo "A diferen�a de n�vel entre voc� e o lider do grupo � maior que 30 n�veis.<br/>";
     echo "<a href=\"home.php\">Voltar</a>.";
 } elseif ($player->level < 30) {
     echo "Seu n�vel � inferior � 30.<br/>";
     echo "<a href=\"home.php\">Voltar</a>.";
 } elseif ($countamembros->recordcount() > 3) {
     echo "Este grupo j� est� cheio.<br/>";
     echo "<a href=\"home.php\">Voltar</a>.";
 } else {
		$insert['id'] = $_GET['id'];
		$insert['player_id'] = $player->id;
		$query = $db->autoexecute('groups', $insert, 'INSERT');

			$query = $db->execute("delete from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['id'], $player->id]);

			$log1 = $db->execute("select `player_id` from `groups` where `id`=? and `player_id`!=?", [$_GET['id'], $player->id]);
			while($p1 = $log1->fetchrow())
			{
    			$logmsg1 = "Agora <a href=\"profile.php?id=". $player->username ."\">" . $player->username . "</a> faz parte do grupo de ca�a de <a href=\"profile.php?id=". $leadername ."\">" . $leadername . "</a>.";
			addlog($p1['player_id'], $logmsg1, $db);
			}

		echo "Voc� acaba de entrar no grupo de ca�a de " . $leadername . ".<br/>";
		echo "<a href=\"friendlist.php\">Voltar</a>.";

	}
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>