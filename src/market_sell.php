<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Mercado");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

switch($_GET['act'])
{
	case "sell":
		{
			include(__DIR__ . "/templates/private_header.php");

			$gsadasdiiii = $db->execute("select items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_GET['item'], $player->id]);
			$goooosdsfds = $gsadasdiiii->fetchrow();

				if ($gsadasdiiii->recordcount() == 0){
			 	echo "Voc� n�o possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['mark'] == \T){
			 	echo "Este item j� est� � venda!<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['status'] == 'equipped'){
			 	echo "O item que voc� deseja vender est� em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['item_id'] == 111 || $goooosdsfds['item_id'] == 116){
			 	echo "Voc� n�o pode vender este item, caso contr�rio n�o poder� terminar sua miss�o.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}
	
				if ($goooosdsfds['type'] == 'stone'){
			 	echo "Voc� n�o pode vender pedras no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($goooosdsfds['item_bonus'] > 0){
				$bonus1 = " +" . $goooosdsfds['item_bonus'] . "";
				}
				if ($goooosdsfds['for'] > 0){
				$bonus2 = " <font color=\"gray\">+" . $goooosdsfds['for'] . "F</font>";
				}
				if ($goooosdsfds['vit'] > 0){
				$bonus3 = " <font color=\"green\">+" . $goooosdsfds['vit'] . "V</font>";
				}
				if ($goooosdsfds['agi'] > 0){
				$bonus4 = " <font color=\"blue\">+" . $goooosdsfds['agi'] . "A</font>";
				}
				if ($goooosdsfds['res'] > 0){
				$bonus5 = " <font color=\"red\">+" . $goooosdsfds['res'] . "R</font>";
				}

			?>
			Ent�o voc� quer vender seu iten? �timo! Me diga quando voc� quer por ele. Mas lembre-se, este mercado n�o funciona de gra�a, voc� tem que nos pagar 5% de comiss�o.<br /><br />
			<form method="POST" action="market_sell.php?act=confirm" >
			<table>
			<tr><td><b>Vender:</b></td><td><?php echo "" . $goooosdsfds['name'] . "" . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "";?></td></tr>
			<input type="hidden" name="act" value="confirm">
			<input type="hidden" name="item" value="<?php echo $_GET['item']; ?>">
			<tr><td><b>Pre�o:</b></td><td><input type="text" name="price" size="15"></td></tr>
			<?php
			if ($player->transpass != \F){
				echo "<tr><td><b>Senha de<br/>transfer�ncia:</b></td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
			}
			?>
			</table>
			<input type="submit" value="Adicionar ao mercado">
			</form>
			<?php
			include(__DIR__ . "/templates/private_footer.php");
			//end of sell action
			break;
		}
		case "confirm":
			{

			if ($player->transpass != \F){
			if (!$_POST['passcode']){
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
        		echo "Preencha todos os campos.<br />";
        		echo "<a href=\"market.php\">Voltar</a>.";
			echo "</fieldset>";
        		include(__DIR__ . "/templates/private_footer.php");
			break;
			}

			if (strtolower((string) $_POST['passcode']) !== strtolower((string) $player->transpass)){
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
        		echo "Sua senha de transfer�ncia est� incorreta.<br />";
        		echo "<a href=\"market.php\">Voltar</a>.";
			echo "</fieldset>";
        		include(__DIR__ . "/templates/private_footer.php");
			break;
			}
			}

			if (!$_POST['item']){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_POST['item'], $player->id]);
				if ($verificaall->recordcount() == 0){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Voc� n�o possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

			$ver = $verificaall->fetchrow();

				if ($ver['mark'] == 't'){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Este item j� est� no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($ver['status'] == 'equipped'){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O item que voc� deseja vender est� em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}



			$item=stripslashes((string) $_POST['item']);

			if (!$_POST['price']){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Voc� precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			if (!is_numeric($_POST['price'])) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O valor que voc� inseriu n�o � v�lido.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			if ($_POST['price'] < 100) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O pre�o n�o pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}


			if ($_POST['price'] > 50_000_000) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O pre�o n�o pode ser maior que 50 milh�es.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			$price=stripslashes($_POST['price']);
			$price=ceil($price);

			$fee=ceil($price/100);
			$fee=ceil($fee * 5);
			if($price<=0){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Desculpe, mas n�s n�o permitimos que os usu�rios d�em itens. <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}
				$gsassaaa = $db->execute("select blueprint_items.name, items.item_id from `blueprint_items`, `items` where items.id=? and blueprint_items.id=items.item_id", [$item]);
				$gooooa = $gsassaaa->fetchrow();
				include(__DIR__ . "/templates/private_header.php"); 
			?>
			Voc� tem certeza que quer vender seu/sua <b><?php echo $gooooa['name']; ?> por <?php echo $price; ?> de ouro</b>? Voc� precisar� nos pagar <b><?php echo $fee; ?> de ouro</b>, que � nossa comiss�o.<br/><br/>
			<form method="post" action="market_sell.php?act=list">
			<input type="hidden" name="item" value="<?php echo $item; ?>">
			<input type="hidden" name="price" value="<?php echo $price; ?>">
			<input type="submit" name="list" value="Sim, tenho certeza!">
			</form>
			<?php
			}
			include(__DIR__ . "/templates/private_footer.php");
			break;


		case "list":
			{

			if (!$_POST['item']){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_POST['item'], $player->id]);
				if ($verificaall->recordcount() == 0){
			include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Voc� n�o possui este item.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

			$ver = $verificaall->fetchrow();

				if ($ver['mark'] == 't'){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Este item j� est� no mercado.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

				if ($ver['status'] == 'equipped'){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O item que voc� deseja vender est� em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
			 	include(__DIR__ . "/templates/private_footer.php");
				break; 
				}

			$item=stripslashes((string) $_POST['item']);


			if (!$_POST['price']){
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "Voc� precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			if (!is_numeric($_POST['price'])) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O valor que voc� inseriu n�o � v�lido.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			if ($_POST['price'] < 100) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O pre�o n�o pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}


			if ($_POST['price'] > 50_000_000) 	
			{
				include(__DIR__ . "/templates/private_header.php"); 
			 	echo "O pre�o n�o pode ser maior que 50 milh�es.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break; 
			}

			$price=stripslashes($_POST['price']);
			$price=ceil($price);

			$fee=ceil($price/100);
			$fee=ceil($fee * 5);

			if($player->gold < $fee){
			include(__DIR__ . "/templates/private_header.php");
			echo "Voc� n�o tem ouro para pagar nossa comiss�o.";	
			include(__DIR__ . "/templates/private_footer.php");	
			}else {
				$insert['market_id'] = $item;
				$insert['ite_id'] = $ver['item_id'];
				$insert['price']= $price;
				$insert['seller'] = $player->username;
				$insert['expira'] = ceil(time() + 1_209_600);
				$insert['serv'] = $player->serv;
				$query2 = $db->autoexecute('market', $insert, 'INSERT');
			//remove fee from player
			$query02 = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $fee, $player->id]);
			$query03 = $db->execute("update `items` set `mark`='t', `status`='unequipped' where `id`=?", [$item]);

			include(__DIR__ . "/templates/private_header.php");
			echo "Agora seu item est� disponivel no mercado! <a href=\"market.php\">Voltar</a>.";	
			include(__DIR__ . "/templates/private_footer.php");

			}
			break;
			}
 	default:
 
//Default Page
include(__DIR__ . "/templates/private_header.php");
?>
<?php
//Pull info from blueprint items and then compare them to items list to get count.
$query = $db->execute("select * from blueprint_items order by blueprint_items.name asc");
if ($query->recordcount() == 0)
{
	echo "<br /><b>Voc� n�o tem itens para vender.</b>";
}
$abaioepa = $db->execute("select `id` from `items` where `player_id`=?", [$player->id]);
if ($abaioepa->recordcount() == 0)
{
	echo "<br /><b>Voc� n�o tem itens para vender.</b>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
echo "<fieldset><legend><b>Quais itens voc� gostaria de vender?</b></legend>";
echo "<table width=\"100%\" border=\"0\">";
echo "<tr>";
echo "<th><b>Item</b></td>";
echo "<th><b>A��o</b></td>";
echo "</tr>";
$gettheitemuniqid = $db->execute("select items.id, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name from `items`, `blueprint_items` where `player_id`=? and mark!='t' and items.item_id=blueprint_items.id order by blueprint_items.name asc", [$player->id]);
while ($gettheitemuniqiditem = $gettheitemuniqid->fetchrow())
			{
				$bonus01 = $gettheitemuniqiditem['item_bonus'] > 0 ? " +" . $gettheitemuniqiditem['item_bonus'] . "" : "";
				$bonus02 = $gettheitemuniqiditem['for'] > 0 ? " <font color=\"gray\">+" . $gettheitemuniqiditem['for'] . "F</font>" : "";
				if ($gettheitemuniqiditem['vit'] > 0){
				$bonus03 = " <font color=\"green\">+" . $gettheitemuniqiditem['vit'] . "V</font>";
				}else{
				$bonus03 = "";
				}
				$bonus04 = $gettheitemuniqiditem['agi'] > 0 ? " <font color=\"blue\">+" . $gettheitemuniqiditem['agi'] . "A</font>" : "";
				$bonus05 = $gettheitemuniqiditem['res'] > 0 ? " <font color=\"red\">+" . $gettheitemuniqiditem['res'] . "R</font>" : "";
				echo "<tr><td>" . $gettheitemuniqiditem['name'] . "" . $bonus01 . "" . $bonus02 . "" . $bonus03 . "" . $bonus04 . "" . $bonus05 . "</td><td><a href=\"market_sell.php?act=sell&item=" . $gettheitemuniqiditem['id'] . "\">Vender</a></td></tr>";
			}
echo "</table></fieldset>";
?>
<?php 
include(__DIR__ . "/templates/private_footer.php");
}
?>