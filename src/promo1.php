<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->level < 240)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


if ($player->promoted != \S && $player->promoted != \P)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Voc� precisa ter um jeweled ring optimizado para fazer esta miss�o!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($player->voc == "knight"){
if ($_GET['act'] == "pay"){
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 2_000_000 < 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2_000_000, $player->id]);
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 12;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
  echo "<i>Muito obrigado, agora vamos continuar.</i><br>\n";
  echo "<a href=\"promo1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
		}
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "Voc� j� me pagou!</i><br/><br/>\n";
 echo "<a href=\"home.php\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
}

	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>� sobre uma guerreiro incr�vel, por�m n�o me lembro muito bem. Acho que 2 milh�es ajudar�o a refrescar minha mem�ria.</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Eu pago</a> | <a href=\"promo1.php?next=3\">Nunca!</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Deseja pagar 2000000 de ouro para ouvir mais sore este guerreiro?</i><br/>\n";
     echo "<a href=\"promo1.php?act=pay\">Sim</a> | <a href=\"promo1.php?next=3\">N�o</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "<i>Ol� " . $player->username . "! Ouvi muito sobre voc� na cidade, e tambem ouvi alguns boatos que voc� vai querer ouvir.</i><br/>\n";
 echo "<a href=\"promo1.php?next=1\">Que Boatos?</a> | <a href=\"promo1.php?next=3\">N�o estou interessado</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
	}

	if ($quest['quest_status'] == 1)
		{
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Seu nome � Friden o destemido guerreiro banhado pelo sangue do drag�o!<br/>Derrote Friden e obtenha sua espada que tamb�m foi banhada pelo sangue do drag�o, e voc� poder� se tornar um grandioso Guerreiro, por�m devo avisar-lhe que n�o � uma tarefa f�cil. Friden tem muitos admiradores e amigos, cuidado para n�o encontralos em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Desejo come�ar minha jornada</a> | <a href=\"promo1.php?next=3\">N�o estou preparado</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php\">Continuar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Tudo bem, treine um pouco mais e mais tarde voc� poder� buscar por Friden.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "<i>Dizem por ai que existe um homem que todos julgam ser imortal.  Arranque sua cabe�a, perfure seus pulm�es, corte-o ao meio nada o matar�... A n�o ser, que perfurem seu cora��o, sua grande franqueza.</i><br/>\n";
 echo "<a href=\"promo1.php?next=1\">Conte-me Mais!</a> | <a href=\"promo1.php?next=3\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
		}

	if ($quest['quest_status'] == 2)
		{
		if ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� conseguiu despistar Alexia, mas dever� enfrent�-la mais tarde.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Miss�o</b></legend>\n";
  echo "<i>Conforme voc� procurava por Friden, encontrou Alexia, uma de seus seguidores.</i><br/>\n";
  echo "<a href=\"bquest.php\">Lutar contra Alexia</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� conseguiu despistar Ramthysts, mas dever� enfrent�-lo mais tarde.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� derrotou Alexia, e continuou sua jornada, at� encontrar Ramthysts, o poderoso guerreiro manipulador de fogo!</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra Ramthysts</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� conseguiu despistar Friden, mas dever� enfrent�-lo mais tarde.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� derrotou Ramthysts, e logo ap�s encontrou Friden, que cabara de sair de uma luta, o sangue do guerreiro derrotado por Friden escorria em sua face.</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra Friden</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}


	if ($quest['quest_status'] == 7)
		{
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", [\P, $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Ao segurar a espada de Friden voc� sente seus m�sculos enrijecerem e seu corpo fortalecido. Voc� acaba de se tornar um Cavaleiro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 90)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Voc� j� terminou esta miss�o!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
}



elseif ($player->voc == "mage"){

if ($_GET['act'] == "pay"){
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 2_000_000 < 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2_000_000, $player->id]);
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 12;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Miss�o</b></legend>\n";
  echo "<i>Voc� se inscreveu no torneio e agora podemos continuar.</i><br>\n";
  echo "<a href=\"promo1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
		}
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Miss�o</b></legend>\n";
 echo "Voc� j� me pagou!</i><br/><br/>\n";
 echo "<a href=\"home.php\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
}
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Voc� segue e encontra um gigantesco coliseu, e ao redor v� v�rios dos mais famosos magos da regi�o. Este torneio n�o ser� nada f�cil. Voc� realmente deseja participar?</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Sim</a> | <a href=\"promo1.php?next=3\">N�o</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Voc� precisar� pagar 2000000 de ouro para participar do torneio. Acredite, valer� a pena!</i><br/>\n";
     echo "<a href=\"promo1.php?act=pay\">Eu pago</a> | <a href=\"promo1.php?next=3\">N�o</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Miss�o</b></legend>\n";
 echo "<i>Voc� sente algo queimar sua pele durante o sono, e ao acordar se repara com uma marca talhada em seu peito em forma de um drag�o. Voc� sabia que a hora chegava e teria de enfrentar seu destino!";
 echo "<br/>Durante toda sua vida voc� se preparava que estas marcas aparecessem. Est� na hora de voc� participar do torneio dos magos, onde voc� dever� provar que voc� domina seus feiti�os e sua mente.</i><br/>\n";
 echo "<a href=\"promo1.php?next=2\">Continuar</a> | <a href=\"promo1.php?next=3\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
	}

	if ($quest['quest_status'] == 1)
		{
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Seu nome � Friden o destemido guerreiro banhado pelo sangue do drag�o!<br/>Derrote Friden e obtenha sua espada que tamb�m foi banhada pelo sangue do drag�o, e voc� poder� se tornar um grandioso Guerreiro, por�m devo avisar-lhe que n�o � uma tarefa f�cil. Friden tem muitos admiradores e amigos, cuidado para n�o encontralos em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Desejo come�ar minha jornada</a> | <a href=\"promo1.php?next=3\">N�o estou preparado</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php\">Continuar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Miss�o</b></legend>\n";
     echo "<i>Tudo bem, treine um pouco mais e volte mais tarde.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Miss�o</b></legend>\n";
 echo "<i>Dizem por ai que existe um homem que todos julgam ser imortal.  Arranque sua cabe�a, perfure seus pulm�es, corte-o ao meio nada o matar�... A n�o ser, que perfurem seu cora��o, sua grande franqueza.</i><br/>\n";
 echo "<a href=\"promo1.php?next=1\">Conte-me Mais!</a> | <a href=\"promo1.php?next=3\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
		}

	if ($quest['quest_status'] == 2)
		{
		if ($_GET['next'] == 1) {
      include(__DIR__ . "/templates/private_header.php");
      echo "<fieldset><legend><b>Miss�o</b></legend>\n";
      echo "<i>Seu primerio oponente � Detros. Este mago � famoso pela sua incr�vel velocidade que tem ao lan�ar feiti�os.</i><br/>\n";
      echo "<a href=\"bquest.php\">Lutar contra Detros</a> | <a href=\"promo1.php?next=3\">N�o estou preparado</a>.";
      echo "</fieldset>";
      include(__DIR__ . "/templates/private_footer.php");
      exit;
  }
  if ($_GET['next'] == 3) {
      include(__DIR__ . "/templates/private_header.php");
      echo "<fieldset><legend><b>Miss�o</b></legend>\n";
      echo "<i>Voc� abandonou o coliseu e acabou deixando uma m� impress�o entre os magos.</i><br/>\n";
      echo "<a href=\"home.php\">Voltar</a>.";
      echo "</fieldset>";
      include(__DIR__ . "/templates/private_footer.php");
      exit;
  }
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Torneio</b></legend>\n";
  echo "<i>O torneio se baseia em tr�s etapas em que voc� ter� de derrotar 3 guerreiros e o �ltimo jovem em p� ser� o que domina seus poderes!</i><br/>\n";
  echo "<a href=\"bquest.php\">Continuar</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� abandonou o coliseu e acabou deixando uma m� impress�o entre os magos.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� derrotou Detros, mas � bom se recuperar logo, pois agora voc� ter� de lutar com Azura, uma poderosa feiticeira.</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra Azura</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� abandonou o coliseu e acabou deixando uma m� impress�o entre os magos.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Incr�vel! Voc� derrotou Azura. Muitos pelo colizeu n�o acreditavam que isso aconteceria!<br/>Voc� avan�ou para a etapa final do torneio, e agora ter� de enfrentar Draconos, um mago com poderes incr�veis.</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra Draconos</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}


	if ($quest['quest_status'] == 7)
		{
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", [\P, $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� derrotou o destemido Draconos! Nunca um forasteiro havia conseguido tamanha fa�anha.<br/><b>Voc� foi promovido para um Arquimago, e agora possui a Draconia Staff!</b></i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 90)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� j� terminou esta miss�o!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
}

elseif ($player->voc == "archer"){
if ($_GET['act'] == "pay"){
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 2_000_000 < 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 2_000_000, $player->id]);
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 12;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
  echo "<i>Muito obrigado, agora vamos continuar.</i><br>\n";
  echo "<a href=\"promo1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
		}
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "Voc� j� me pagou!</i><br/><br/>\n";
 echo "<a href=\"home.php\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
}

	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 12]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0) {
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>� sobre o famoso arqueiro Baltazar, por�m n�o me lembro muito bem. Acho que 2 milh�es ajudar�o a refrescar minha mem�ria.</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Eu pago</a> | <a href=\"promo1.php?next=3\">Nunca!</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Deseja pagar 2000000 de ouro para ouvir mais sore Baltazar?</i><br/>\n";
     echo "<a href=\"promo1.php?act=pay\">Sim</a> | <a href=\"promo1.php?next=3\">N�o</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Tudo bem, talvez mais tarde.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "<i>Ol� " . $player->username . "! Ouvi muito sobre voc� na cidade, e tambem ouvi alguns boatos que voc� vai querer ouvir.</i><br/>\n";
 echo "<a href=\"promo1.php?next=1\">Que Boatos?</a> | <a href=\"promo1.php?next=3\">N�o estou interessado</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
	}

	if ($quest['quest_status'] == 1)
		{
	if ($_GET['next'] == 1) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>O arco de Baltazar foi banhado pelo sangue de dem�nios, � a arma perfeita.<br/>Por�m, os dem�nios est�o sempre pr�ximos a Baltazar, voc� ter� de enfrenta-los em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php?next=2\">Desejo come�ar minha jornada</a> | <a href=\"promo1.php?next=3\">N�o estou preparado</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 2) {
     $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 12]);
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Adimiro sua corragem guerreiro, boa sorte em sua jornada.</i><br/>\n";
     echo "<a href=\"promo1.php\">Continuar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 if ($_GET['next'] == 3) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
     echo "<i>Tudo bem, treine um pouco mais e mais tarde voc� poder� buscar por Baltazar, mas n�o demore demais, ou ele poder� acabar indo embora.</i><br/>\n";
     echo "<a href=\"home.php\">Voltar</a>.";
     echo "</fieldset>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
 echo "<i>Dizem por que Baltazar est� morando em uma caverna ao norte da cidade. E esta provavelmete ser� sua �nica chance de roubar seu poderoso arco.</i><br/>\n";
 echo "<a href=\"promo1.php?next=1\">Conte-me Mais!</a> | <a href=\"promo1.php?next=3\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;
		}

	if ($quest['quest_status'] == 2)
		{
		if ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� fugiu, mas sabe que dever� enfrentar o dem�nio se quiser continuar.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Miss�o</b></legend>\n";
  echo "<i>Voc� seguiu ao norte da cidade como Thomas mencionou, em busca da caverna. Finalmente a encontra, mas avista um dem�nio em sua entrada.</i><br/>\n";
  echo "<a href=\"bquest.php\">Lutar contra o Dem�nio</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
	}

	if ($quest['quest_status'] == 3 || $quest['quest_status'] == 4)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� fugiu, mas sabe que dever� enfrentar o dem�nio se quiser continuar.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� matou o dem�nio, mas com o barulho que voc� fez, v� outro dem�nio se aproxima. Oque voc� far�?</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra o Dem�nio</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}

	if ($quest['quest_status'] == 5 || $quest['quest_status'] == 6)
		{
		if ($_GET['next'] == 1){
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [6, $player->id, 12]);
		header("Location: bquest.php");
		}elseif ($_GET['next'] == 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� conseguiu despistar Baltazar, mas dever� enfrent�-lo mais tarde.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}else{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� derrotou o segundo dem�nio, e ao entrar um pouco mais na caverna v� Baltazar furioso. Oque voc� far�?</i><br/>\n";
		echo "<a href=\"promo1.php?next=1\">Lutar contra Baltazar</a> | <a href=\"promo1.php?next=3\">Fugir</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
	}


	if ($quest['quest_status'] == 7)
		{
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", [\P, $player->id]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 12]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Ao tocar no arco de Baltazar voc� sente seus m�sculos enrijecerem e seu corpo fortalecido. Voc� acaba de se tornar um Arqueiro Royal!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 90)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thomas Shevard</b></legend>\n";
		echo "<i>Voc� j� terminou esta miss�o!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
}



?>