<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");


if ($player->level < 40)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Trevus</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{

	case "who":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Eu sou um viajante, e ganho a vida entregando coisas.</i><br><br>\n";
		echo "<a href=\"quest2.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "help":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Preciso de sua ajuda para fazer uma entrega para o Lord Drofus. Estou sem tempo, e preciso que voc� entregue um pacote para ele.<br>Se voc� me ajudar, poderei fazer entregas para voc� quando quiser. O que acha?</i><br><br>\n";
		echo "<a href=\"quest2.php?act=acept\">Aceito</a> | <a href=\"quest2.php?act=decline\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Bom, acho que vou ter que encontrar outra pessoa ent�o.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "abort":
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 3]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 4]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Voc� abordou a miss�o.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "acept":
		$chefsfa8k = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=3 and `quest_status`=1", [$player->id]);
		if ($chefsfa8k->recordcount() != 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  $insert['player_id'] = $player->id;
  $insert['item_id'] = 116;
  $query = $db->autoexecute('items', $insert, 'INSERT');
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 3;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  echo "<fieldset><legend><b>Trevus</b></legend>\n";
  echo "<i>�timo! Pegue este pacote e v� em dire��o � mans�o de Lord Drofus.</i><br>";
  echo "<b>(voc� adiquiriu um pacote)</b><br><br>\n";
  echo "<a href=\"quest2.php?act=go\">Ir � mans�o</a> | <a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "go":
		$csadack = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=3 and `quest_status`>100", [$player->id]);
		if ($csadack->recordcount() != 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [time() + 900, $player->id, 3]);
  echo "<fieldset><legend><b>Miss�o</b></legend>\n";
  echo "<i>Voc� est� a caminho da mans�o de Lord Drofus.</i><br>";
  echo "<i>Faltam 15 minutos para voc� chegar.</i><br><br>\n";
  echo "<a href=\"home.php\">P�gina Principal</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "mansion":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
		echo "<i>Ol� senhor(a), o que deseja?</i><br><br>\n";
		echo "<a href=\"quest2.php?act=box\">Entregar um pacote</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "box":
		$urhsgiosejf = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=3 and `quest_status`=2", [$player->id]);
		if ($urhsgiosejf->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $vesetemobox = $db->execute("select * from `items` where `item_id`=116 and `player_id`=?", [$player->id]);
  if ($vesetemobox->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
		echo "<i>Que pacote? Voc� n�o tem nenhum pacote no seu invent�rio.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a> | <a href=\"quest2.php?act=abort\">Abordar miss�o</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}else{
		include(__DIR__ . "/templates/private_header.php");
		$upxxdateeaaa = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [5, $player->id, 3]);
		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
		echo "<i>Bom, posso entregar esse pagote para o Lord Drofus, por�m voc� vai ter que me pagar 10000 moedas de ouro, caso contr�rio, ele n�o saber� que voc� esteve aqui.</i><br><br>\n";
		echo "<a href=\"quest2.php?act=confirmpay\">Eu pago</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}
		exit;

	case "goback":
		include(__DIR__ . "/templates/private_header.php");
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [time() + 900, $player->id, 4]);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� a caminho da cidade.</i><br>";
		echo "<i>Faltam 15 minutos para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "finish":
		include(__DIR__ . "/templates/private_header.php");
		$dothechec = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=4 and `quest_status`=80", [$player->id]);
		if ($dothechec->recordcount() == 0){
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 4]);
  echo "<fieldset><legend><b>Trevus</b></legend>\n";
  echo "<i>Ol�! Eu recebi uma mensagem de Lord Drofus, ele recebeu o pacote.</i><br>";
  echo "<i>Obrigado por me ajudar. Lembre-se, agora em diante sempre quando precisar enviar algo para algu�m, acesse seu invent�rio.</i><br><br>\n";
  echo "<a href=\"home.php\">P�gina Principal</a>.";
  echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "confirmpay":
		$verifikcheck = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=3 and `quest_status`=5", [$player->id]);
		if ($verifikcheck->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $verificacao = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=4", [$player->id]);
  if ($verificacao->recordcount() == 0)
 		{
 		if ($player->gold - 10000 < 0){
 		include(__DIR__ . "/templates/private_header.php");
 		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
 		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
 		echo "<a href=\"home.php\">Voltar</a>.";
 	        echo "</fieldset>";
 		include(__DIR__ . "/templates/private_footer.php");
 		exit;
 		}
   $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 10000, $player->id]);
   $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 3]);
   $query = $db->execute("delete from `items` where `item_id`=116 and `player_id`=? limit 1", [$player->id]);
   $insert['player_id'] = $player->id;
   $insert['quest_id'] = 4;
   $insert['quest_status'] = 1;
   $query = $db->autoexecute('quests', $insert, 'INSERT');
   include(__DIR__ . "/templates/private_header.php");
   echo "<fieldset><legend><b>Mans�o</b></legend>\n";
   echo "<i>Obrigado. Entregarei o pacote ao Lord Drofus assim que ele chegar.</i><br><br>\n";
   echo "<a href=\"quest2.php?act=goback\">Voltar para a cidade</a>.";
   echo "</fieldset>";
   include(__DIR__ . "/templates/private_footer.php");
   exit;
 		}
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Mans�o</b></legend>\n";
  echo "Voc� j� me pagou!</i><br/><br/>\n";
  echo "<a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;

}
?>
<?php
	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 3]);
	$quest1 = $verificacao1->fetchrow();

	$verificacao2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 4]);
	$quest2 = $verificacao2->fetchrow();

	if ($verificacao1->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Ol� " . $player->username . ". Voc� pode me ajudar?</i><br/><br>\n";
		echo "<a href=\"quest2.php?act=who\">Quem � voc�?</a> | <a href=\"quest2.php?act=help\">Ajudar com o que?</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}


	if ($quest1['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Agora que eu j� lhe entreguei o pacote, v� em dire��o � mans�o de Lord Drofus.</i><br><br>";
		echo "<a href=\"quest2.php?act=go\">Ir � mans�o</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 5)
		{
		$vesetemobox = $db->execute("select * from `items` where `item_id`=116 and `player_id`=?", [$player->id]);
		if ($vesetemobox->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
		echo "<i>Voc� quer entregar um pacote? Estranho, pois n�o existe nenhum pacote no seu invent�rio.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Mans�o</b></legend>\n";
  echo "<i>Bom, posso entregar esse pagote para o Lord Drofus, por�m voc� vai ter que me pagar 10000 moedas de ouro, caso contr�rio, ele n�o saber� que voc� esteve aqui.</i><br><br>\n";
  echo "<a href=\"quest2.php?act=confirmpay\">Eu pago</a> | <a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
		}

	if ($quest1['quest_status'] > 100)
		{
			if ($quest1['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 3]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� chegou na mans�o de Lord Drofus.</i><br><br>";
			echo "<a href=\"quest2.php?act=mansion\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
			}

		include(__DIR__ . "/templates/private_header.php");
		$time = ($quest1['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Trevus</b></legend>\n";
		echo "<i>Voc� est� a caminho da mans�o de Lord Drofus.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� chegou na mans�o de Lord Drofus.</i><br><br>";
		echo "<a href=\"quest2.php?act=mansion\">Continuar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}


	if ($quest2['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Mans�o</b></legend>\n";
		echo "<i>Voc� est� na mans�o de Lord Drofus e j� entregou o pacote para ele.</i><br><br>";
		echo "<a href=\"quest2.php?act=goback\">Voltar para a cidade</a>.";
	     	 echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] > 100)
		{
			if ($quest2['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 4]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� chegou na cidade.</i><br><br>";
			echo "<a href=\"quest2.php?act=finish\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			}

		include(__DIR__ . "/templates/private_header.php");
		$time = ($quest2['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� a caminho da cidade.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] == 80)
		{
		include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� chegou na cidade.</i><br><br>";
			echo "<a href=\"quest2.php?act=finish\">Continuar</a>.";
	     	 	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] == 90)
		{
		include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� j� terminou sua miss�o.</i><br><br>";
			echo "<a href=\"home.php\">Voltar</a>.";
	     	 	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

?>