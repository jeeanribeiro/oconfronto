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
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->promoted == \F)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Voc� precisa ter uma voca��o superior para fazer esta miss�o!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($player->level < 100)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{
	case "warrior":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Al�m da for�a, itelig�ncia e coragem, um grande guerreiro precisa de �timos itens. Vejo que voc� tem �timos itens, mas est� faltando uma coisa.</i><br>\n";
		echo "<a href=\"quest1.php?act=what\">Oqu�?</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "what":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� j� ouviu falar no jeweled ring? Ele � capas de aumentar seu ataque, sua defesa e sua resist�ncia.</i><br>\n";
		echo "<i>Eu posso te ajudar a conseguir este precioso anel, irei te dizer tudo que � nesces�rio se voc� me pagar uma pequena quantia de <b>120000 moedas de ouro</b>.</i><br>\n";
		echo "<a href=\"quest1.php?act=pay\">Eu pago!</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "pay":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� aceita pagar <b>120000 moedas de ouro</b> para saber tudo que precisa?</i><br>\n";
		echo "<a href=\"quest1.php?act=confirmpay\">Sim</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "raderon":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� tem certeza disso? Raderon � muito forte!</i><br>\n";
		echo "<a href=\"raderon.php\">Sim</a> | <a href=\"quest1.php\">N�o</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "who":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Minha hist�ria � muito longa, eu j� fui um grande guerreiro e agora ajudo as pessoas que querem seguir meu caminho.</i><br>\n";
		echo "<a href=\"quest1.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "confirmpay":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 120000 < 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 120000, $player->id]);
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 2;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
  echo "<i>Pronto, agora podemos continuar com as miss�es.</i><br>\n";
  echo "<a href=\"quest1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
  exit;
		}
 include(__DIR__ . "/templates/private_header.php");
 echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
 echo "Voc� j� me pagou esta taixa!</i><br/><br/>\n";
 echo "<a href=\"home.php\">Voltar</a>.";
 echo "</fieldset>";
 include(__DIR__ . "/templates/private_footer.php");
 exit;

	case "continue1":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 1){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� n�o possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 2]);
  $query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
  echo "<i>Obrigado, agora podemos passar para a pr�xima miss�o.</i><br>\n";
  echo "<a href=\"quest1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

	case "continue2":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 2){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� n�o possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [3, $player->id, 2]);
  $query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
  echo "<i>Obrigado, agora podemos passar para a pr�xima miss�o.</i><br>\n";
  echo "<a href=\"quest1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

	case "continue3":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 3){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� n�o possui um Jeweled Crystal.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 2]);
  $query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
  echo "<i>Obrigado, agora podemos passar para a pr�xima miss�o.</i><br>\n";
  echo "<a href=\"quest1.php\">Continuar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;


	case "titanium":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 5){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 111]);
		if ($selectfirstitem->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� n�o possui uma Titanium Wheel.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 2]);
  $query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [111, $player->id, 1]);
  $query = $db->execute("update `players` set `promoted`=? where `id`=?", [\R, $player->id]);
  include(__DIR__ . "/templates/private_header.php");
  echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
  echo "<i>Pronto, ai est� seu Jeweled Ring. Ele n�o poder� ser removido.</i><br>\n";
  echo "(O anel ir� aparecer junto com as imagens dos seus itens em alguns instantes)<br>\n";
  echo "<a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

}
?>
<?php
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>A muito tempo ningu�m procura por mim. Oqu� lhe traz aqui?</i><br/>\n";
		echo "<a href=\"quest1.php?act=who\">Quem � voc�?</a> | <a href=\"quest1.php?act=warrior\">Quero me tornar um grande guerreiro</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Para criar o anel, s�o nesces�rios tr�s <b>Jeweled Crystals</b>. Voc� pode obtelos matando Drag�es de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o primeiro jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue1\">J� possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 2)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� j� me entegou um <b>jeweled crystal</b>, preciso de mais dois. Voc� pode obtelos matando Drag�es de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o segundo jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue2\">J� possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 3)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� j� me entegou dois <b>jeweled crystals</b>, preciso de mais um. Voc� pode obtelo matando Drag�es de Pedra ou comprando no mercado.</i><br/>\n";
		echo "<i>Quando conseguir o terceiro jeweled crystal volte aqui.</i><br/>\n";
		echo "<a href=\"quest1.php?act=continue3\">J� possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 4)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Agora que possuo todos os cristais nesces�rios s� preciso de uma pe�a para montar o anel, uma titanium wheel. A �nica maneira de obtela � matando Raderon, um poderoso guerreiro.</i><br/><br/>\n";
		echo "<a href=\"quest1.php?act=raderon\">Quero lutar contra Raderon</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 5)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Nossa! Voc� conseguiu mesmo vencer raderon?!</i><br/>\n";
		echo "<i>Vamos acabar logo com isso, me entregue a titanium wheel e eu criarei o anel.</i><br/>\n";
		echo "<a href=\"quest1.php?act=titanium\">Entregar a titanium wheel</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 90)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Voc� j� fez esta miss�o!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

?>