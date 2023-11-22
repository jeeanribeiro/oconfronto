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

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 17]);

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", [$player->id, 15, 90]);
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
		echo "<i>Voc� j� ouviu falar em um oddin orb? Ele � um orb rar�ssimo, que cai em qualquer monstro morto por usu�rios de n�vel 75 ou mais. Um membro da elite imperial deve ter experi�ncia em procurar itens diversos, e por isso quero que me traga <u>dois oddin orbs</u>.</i><br><br>\n";
		echo "<a href=\"quest8.php?act=acept\">Aceitar a Miss�o</a> / <a href=\"quest8.php?act=decline\">Recusar</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;


	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Vai abandonar a elite imperial agora? Sei que voltar� em breve.</i><br><br>\n";
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
  $insert['quest_id'] = 17;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
  echo "<i>�timo, volte aqui quando possuir os dois orbs.</i><br /><br />";
  echo "<a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;
}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>" . $player->username . ", se voc� realmente estiver interessado em fazer parte da elite imperial, precisar� procurar por alguns itens para mim.</i><br/><br>\n";
		echo "<a href=\"quest8.php?act=question\">Quais Itens?</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 1)
		{
			$contaorbs = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 156]);

			if ($contaorbs->recordcount() >= 2)
			{
			$deletaorbs = $db->execute("delete from `items` where `player_id`=? and `item_id`=? LIMIT 2", [$player->id, 156]);
			$updatestatus = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 17]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� entregou os dois orbs para Alexander.</i><br><br>";
			echo "<a href=\"quest8.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
			}
   include(__DIR__ . "/templates/private_header.php");
   echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
   echo "<i>Voc� ainda n�o possui os dois oddin orbs que solicitei.</i><br><br>";
   echo "<a href=\"home.php\">P�gina Principal</a>.";
   echo "</fieldset>";
   include(__DIR__ . "/templates/private_footer.php");
   exit;

		}

	if ($quest1['quest_status'] == 80)
	{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 17]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Fiquei impressionado quando voc� me entregou os orbs. Geralmente os guerreiros demoram muito mais tempo para reuni-los. Isso me prova que voc� � um �timo guerreiro, e acho que j� podemos passar para o teste final.</i><br><br>";
		echo "<a href=\"quest9.php\">Continuar</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest1['quest_status'] == 90)
	{
		header("Location: quest9.php");
	}

?>