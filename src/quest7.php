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

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 15]);
	$verificacao2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 16]);

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}

	if ($verificacao2->recordcount() > 0){
	$quest2 = $verificacao2->fetchrow();
	}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", [$player->id, 14, 90]);
if ($verificacao3->recordcount() < 1){
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Voc� precisa completar a miss�o do pacote imperial primeiro.</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

	if ($player->level < 400){
	$needlvl = $player->level + 4;
	}elseif ($player->level < 500){
	$needlvl = $player->level + 3;
	}elseif ($player->level < 600){
	$needlvl = $player->level + 2;
	}else{
	$needlvl = $player->level + 1;
	}


switch($_GET['act'])
{

	case "question":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Voc� ter� 24 horas para atingir o n�vel " . $needlvl . ". Aceite meu desafio e se tiver sucesso, passar� para a pr�xima etapa do treinamento.</i><br><br>\n";
		echo "<a href=\"quest7.php?act=acept\">Aceitar</a> / <a href=\"quest7.php?act=decline\">Recusar</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;


	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Precisa se preparar mais? Ok, volte quando estiver pronto.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "acept":
		if ($verificacao1->recordcount() > 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Voc� j� aceitou o desafio.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 15;
  $insert['quest_status'] = time() + 86400;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 16;
  $insert['quest_status'] = $needlvl;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
  echo "<i>�timo, agora seja r�pido e atinja o n�vel " . $needlvl . " o mais r�pido possivel. Volte aqui depois de alcan�ar este n�vel.</i><br /><br />";
  echo "<a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

	case "retry":
		$delety1 = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 15]);
		$delety2 = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 16]);

		header("Location: quest7.php?act=question");

	break;
}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>" . $player->username . ", voc� precisa me provar que � um guerreiro dedicado, e ter� de realizar um desafio.</i><br/><br>\n";
		echo "<a href=\"quest7.php?act=question\">Qual � o Desafio?</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] > 100)
		{
			if ($quest1['quest_status'] < time()) {
       $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 15]);
       include(__DIR__ . "/templates/private_header.php");
       echo "<fieldset><legend><b>Miss�o</b></legend>\n";
       echo "<i>Voc� demorou demais para atingir o n�vel nesces�rio. Voc� falhou no desafio.</i><br><br>";
       echo "<a href=\"quest7.php\">Continuar</a>.";
       echo "</fieldset>";
       include(__DIR__ . "/templates/private_footer.php");
       exit;
   }
   if ($quest1['quest_status'] > time() && $player->level >= $quest2['quest_status']) {
       $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 15]);
       include(__DIR__ . "/templates/private_header.php");
       echo "<fieldset><legend><b>Miss�o</b></legend>\n";
       echo "<i>Parab�ns, voc� atingiu o n�vel nesces�rio a tempo.</i><br><br>";
       echo "<a href=\"quest7.php\">Falar com Alexander</a>.";
       echo "</fieldset>";
       include(__DIR__ . "/templates/private_footer.php");
       exit;
   }

		include(__DIR__ . "/templates/private_header.php");
		$time = ($quest1['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� ainda n�o atingiu o n�vel " . $quest2['quest_status'] . ".</i><br>";
		echo "<i>Voc� ainda tem $time_remaining minuto(s) para alcan�ar este n�vel.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Parece que voc� falhou no seu desafio, mas estarei lhe dando outra chance. Deseja tentar novamente?</i><br><br>";
		echo "<a href=\"quest7.php?act=retry\">Sim</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 80)
	{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 15]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Vejo que voc� � um guerreiro dedicado, e agora est� mais mais pr�ximo de fazer parte da elite imperial.</i><br><br>";
		echo "<a href=\"quest8.php\">Continuar Treinamento</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest1['quest_status'] == 90)
	{
		header("Location: quest8.php");
	}

?>