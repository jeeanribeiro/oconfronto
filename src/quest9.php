<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");

if ($player->level < 300)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 18]);

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", [$player->id, 17, 90]);
if ($verificacao3->recordcount() < 1){
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Voc� precisa completar outra miss�o primeiro.</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{

	case "question":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Seu �ltimo desafio ser� trazer os olhos de Zanoth, a criatura mais temida que se conhe�e. Ele se localiza no monte das almas, ao sul do imp�rio.</i><br><br>\n";
		echo "<a href=\"quest9.php?act=acept\">Aceitar Desafio</a> / <a href=\"quest9.php?act=decline\">Recusar</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;


	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>N�o fico surpreso por recusar, ele � um monstro muito poderoso. Volte quando achar que est� pronto.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "acept":
		if ($verificacao1->recordcount() > 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Voc� j� aceitou esta miss�o.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 18;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
  echo "<i>Boa sorte em sua jornada guerreiro, espero que tenha sucesso.</i><br /><br />";
  echo "<a href=\"quest9.php\">Procurar por Zanoth</a> / <a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

	case "noready":
		if ($quest1['quest_status'] == 1 || $quest1['quest_status'] == 2){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 18]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� sente que ainda n�o est� pronto e decide se esconder no monte das almas.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}else{
		header("Location: home.php");
		}
	break;

	case "ready":
		if ($quest1['quest_status'] == 1 || $quest1['quest_status'] == 2 || $quest1['quest_status'] == 3){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [3, $player->id, 18]);

		header("Location: monster.php?act=attack&id=" . (49 * $player->id) . "");

		}else{
		header("Location: home.php");
		}
	break;

	case "abort":
		if ($verificacao1->recordcount() > 0){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 18]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� abandonou a miss�o.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}else{
		header("Location: home.php");
		}
	break;

}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Seu �ltimo desafio ser� trazer os olhos de Zanoth, a criatura mais temida que se conhe�e.</i><br/><br>\n";
		echo "<a href=\"quest9.php?act=question\">Quais Itens?</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� chegou ao monte das almas, e ouve sons de uma criatura poderoza. Podem ser os gritos de Zanoth. Est� pronto para enfrenta-lo?</i><br/><br>\n";
		echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">N�o</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� escondido no monte das almas, e continua ouvindo sons que parecem vir de Zanoth. Est� pronto para enfrenta-lo?</i><br/><br>\n";
		echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">N�o</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 3)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� ainda n�o matou Zanoth. Deseja enfrenta-lo?</i><br/><br>\n";
		echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">N�o</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 80)
	{
		$vesetemoeye = $db->execute("select * from `items` where `item_id`=160 and `player_id`=?", [$player->id]);
		if ($vesetemoeye->recordcount() == 0){
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
			echo "<i>Sinto muito, mas n�o consigo encontrar os olhos de Zanoth no seu invent�rio.</i><br><br>\n";
			echo "<a href=\"home.php\">Voltar</a> / <a href=\"quest9.php?act=abort\">Abandonar miss�o</a>";
	       		echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
		}else{

			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 18]);
			$noeyes = $db->execute("delete from `items` where `item_id`=160 and `player_id`=? limit 1", [$player->id]);

			$insert['player_id'] = $player->id;
			$insert['item_id'] = 161;
			$query = $db->autoexecute('items', $insert, 'INSERT');

			$insert['player_id'] = $player->id;
			$insert['item_id'] = 162;
			$query = $db->autoexecute('items', $insert, 'INSERT');

			$insert['player_id'] = $player->id;   	  
			$insert['medalha'] = "Elite Imperial";
			$insert['motivo'] = "" . $player->username . " faz parte da elite imperial.";
			$query = $db->autoexecute('medalhas', $insert, 'INSERT');

			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
			echo "<i>" . $player->username . ", voc� me provou ser um �timo guerreiro, e como passou por meus testes com sucesso. Agora voc� faz parte da elite imperial.<br/>Membros da elite imperial devem usar �timos itens, ent�o tome esta <u>Holy Armor</u> e estas <u>Holy Legs</u>.</i><br><br>";
			echo "<a href=\"home.php\">P�gina Principal</a>.";
	     		echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}

	if ($quest1['quest_status'] == 90)
	{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� j� terminou esta miss�o.</i><br><br>";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

?>