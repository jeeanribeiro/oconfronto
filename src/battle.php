<?php

include(__DIR__ . "/lib.php");
define("PAGENAME", "Batalhar");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");
$valor547 = $player->level/1.25;
$diflvl = ceil ($valor547);

switch($_GET['act'])
{
	case "attack":
		if (!$_GET['username']) //No username entered
		{
			header("Location: battle.php");
			break;
		}
		
		//Otherwise, get player data:
		$query = $db->execute("select * from `players` where `username`=?", [$_GET['username']]);
		if ($query->recordcount() == 0) //Player doesn't exist
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio n�o existe! <a href=\"battle.php\"/>Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}
		
		$enemy1 = $query->fetchrow(); //Get player info
		foreach($enemy1 as $key=>$value)
		{
			$enemy->$key = $value;
		}
		
		if ($enemy->serv != $player->serv)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio n�o pertence ao mesmo servidor que voc�! <a href=\"battle.php\"/>Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		//Otherwise, check if player has any health
		if ($enemy->hp <= 0)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio est� morto! <a href=\"battle.php\"/>Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($player->level < 100){
		$mytier = 1;
		} elseif ($player->level > 99 && $player->level < 200){
		$mytier = 2;
		} elseif ($player->level > 199 && $player->level < 300){
		$mytier = 3;
		} elseif ($player->level > 299 && $player->level < 400){
		$mytier = 4;
		} elseif ($player->level > 399 && $player->level < 1000){
		$mytier = 5;
		}

		if ($enemy->level < 100){
		$enytier = 1;
		} elseif ($enemy->level > 99 && $enemy->level < 200){
		$enytier = 2;
		} elseif ($enemy->level > 199 && $enemy->level < 300){
		$enytier = 3;
		} elseif ($enemy->level > 299 && $enemy->level < 400){
		$enytier = 4;
		} elseif ($enemy->level > 399 && $enemy->level < 1000){
		$enytier = 5;
		}

		$enytourstatus = "tournament_" . $enytier . "_" . $player->serv . "";


		//Checa se o usuario jah foi morto demais
		if ($enemy->died >= 3)
		{
			if ($enemy->tour == 'f'){
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio j� morreu demais hoje! <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
			}

			if ($enemy->tour == 't' && ($setting->$enytourstatus != 'y' || ($setting->$enytourstatus == 'y' && $mytier != $enytier || $setting->$enytourstatus == 'y' && $enemy->killed > 0))){
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio j� morreu demais hoje! <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
			}

		}


		//Checa se o usuario tah banido
		if ($enemy->ban > time())
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio est� banido! <a href=\"battle.php\"/>Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		$checkenyrowk = $db->GetOne("select `status` from `work` where `player_id`=? order by `start` DESC", [$enemy->id]);
		if ($checkenyrowk == \T && $enemy->tour == 'f')
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Voc� n�o encontrou o usu�rio " . $enemy->username . "! Provavelmente ele est� trabalhando. <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		$checarevenge = $db->execute("select * from `revenge` where `player_id`=? and `enemy_id`=?", [$player->id, $enemy->id]);

		//checa os niveis
		if ($player->level > $enemy->level*1.25 && $enemy->tour == 't' && ($enemy->killed > 0 || $mytier != $enytier) && $checarevenge->recordcount() < 1)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "A diferen�a de nivel entre os dois usu�rios � muito grande!<br>";
			echo "<font color=\"red\"><b>Voc� pode atacar usu�rios de nivel $diflvl ou mais.</b></font> <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		//checa os niveis
		if ($player->level > $enemy->level*1.25 && $enemy->tour == 'f' && $checarevenge->recordcount() < 1)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "A diferen�a de nivel entre os dois usu�rios � muito grande!<br>";
			echo "<font color=\"red\"><b>Voc� pode atacar usu�rios de nivel $diflvl ou mais.</b></font> <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		if ($setting->$enytourstatus == 'y' && $enemy->tour == 't' && $player->tour == 'f')
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "O usu�rio " . $enemy->username . " est� participando de um torneio agora.<br/>Voc� n�o est� no torneio portanto n�o pode mata-lo.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($setting->$enytourstatus == 'y' && $enemy->tour == 't' && ($player->tour == 't' && $player->killed > 0 || $mytier != $enytier))
		{
			include(__DIR__ . "/templates/private_header.php");
			if ($mytier != $enytier){
			echo "O usu�rio " . $enemy->username . " est� participando de outra categoria do torneio.<br/>Voc� n�o est� na mesma categoria portanto n�o pode mata-lo.";
			}else{
			echo "Voc� n�o pode matar " . $enemy->username . " pois ele est� participando de um torneio agora.<br/>Voc� foi desclasificado do torneio portanto n�o pode mata-lo.<br>";
			}
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}
		
		//Player cannot attack anymore
		if ($player->energy < 10)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset>";
			echo "<legend><b>Voc� est� sem energia!</b></legend>\n";
			echo "Voc� deve descan�ar um pouco. (1 ponto de energia por minuto).";
			$query = $db->execute("select items.id, items.item_id, blueprint_items.type, blueprint_items.name, blueprint_items.description, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='potion' and blueprint_items.id=137 and items.mark='f' order by blueprint_items.name asc", [$player->id]);
			if ($query->recordcount() > 0)
			{
			$energypotionidnew = $db->GetOne("select `id` from `items` where `item_id`=? and `player_id`=? order by rand() limit 1", [137, $player->id]);
			echo "<br/>Voc� tamb�m pode usar uma das suas " . $query->recordcount() . " po��es de energia.";
			echo " <a href=\"hospt.php?act=potion&pid=" . $energypotionidnew . "\">Usar</a>.";
			}
			echo "</fieldset><a href=\"monster.php\">Voltar</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		//Checa se vc jah foi morto demais
		if ($player->died >= 3)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Voc� morreu 3x hoje e ficou imune de ataques dos outros jogadores.<br/>";
			echo "Se voc� quiser atacar algu�m, voc� perder� sua imunidade! <a href=\"nobless.php\"/>Remover imunidade</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		//Player In Same Guild
		if ($enemy->guild == $player->guild && $player->guild != NULL && !$_GET['comfirm'])
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio � membro do mesmo cl� que voc�.<br/>Tem certeza que deseja ataca-lo?<br/><br/><a href=\"battle.php?act=attack&username=" . $enemy->username . "&comfirm=true\">Atacar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"battle.php\">Voltar</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		//Player In Same Guild
		if ($enemy->guild != NULL && $player->guild != NULL && !$_GET['comfirm'])
		{
			$ganguesaliadas = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$player->guild, $enemy->guild]);
			if ($ganguesaliadas->recordcount() > 0){
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio � membro do cl� " . $enemy->guild . ", um cl� aliado do seu cl�.<br/>Tem certeza que deseja ataca-lo?<br/><br/><a href=\"battle.php?act=attack&username=" . $enemy->username . "&comfirm=true\">Atacar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"battle.php\">Voltar</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
			}
		}

		
		if ($enemy->username == $player->username)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Voc� n�o pode atacar voc� mesmo! <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($enemy->gm_rank > 9)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "Este usu�rio � um GM, voc� n�o pode ataca-lo! <a href=\"battle.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}
		
		//Get enemy's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$enemy->id]);
  		$enemy->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$enemy->id]);
		$enemy->defbonus1 = ($query50->recordcount() == 1)?$query50->fetchrow():0;
		$query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$enemy->id]);
		$enemy->defbonus2 = ($query51->recordcount() == 1)?$query51->fetchrow():0;
		$query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$enemy->id]);
		$enemy->defbonus3 = ($query52->recordcount() == 1)?$query52->fetchrow():0;
		$query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", [$enemy->id]);
		$enemy->defbonus5 = ($query54->recordcount() == 1)?$query54->fetchrow():0;
		$query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$enemy->id]);
		$enemy->agibonus6 = ($query55->recordcount() == 1)?$query55->fetchrow():0;
		
		//Get player's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$player->id]);
		$player->atkbonus = ($query->recordcount() == 1)?$query->fetchrow():0;
		$query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$player->id]);
		$player->defbonus1 = ($query50->recordcount() == 1)?$query50->fetchrow():0;
		$query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$player->id]);
		$player->defbonus2 = ($query51->recordcount() == 1)?$query51->fetchrow():0;
		$query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$player->id]);
		$player->defbonus3 = ($query52->recordcount() == 1)?$query52->fetchrow():0;
		$query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", [$player->id]);
		$player->defbonus5 = ($query54->recordcount() == 1)?$query54->fetchrow():0;
		$query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus, items.for, items.agi, items.res from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$player->id]);
		$player->agibonus6 = ($query55->recordcount() == 1)?$query55->fetchrow():0;


	$checamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", [$player->id]);

		if ($player->voc == 'archer') {
      if ($checamagiastatus->recordcount() > 0){
   			$varataque = 0.31;
   			$vardefesa = 0.15;
   			$vardivide = 0.14;
   			}else{
   			$varataque = 0.29;
   			$vardefesa = 0.14;
   			$vardivide = 0.13;
   			}
  } elseif ($player->voc == 'mage') {
      if ($checamagiastatus->recordcount() > 0){
   			$varataque = 0.265;
   			$vardefesa = 0.15;
   			$vardivide = 0.14;
   			}else{
   			$varataque = 0.245;
   			$vardefesa = 0.14;
   			$vardivide = 0.13;
   			}
  } elseif ($player->voc == 'knight') {
      if ($checamagiastatus->recordcount() > 0){
   			$varataque = 0.22;
   			$vardefesa = 0.17;
   			$vardivide = 0.15;
   			}else{
   			$varataque = 0.20;
   			$vardefesa = 0.16;
   			$vardivide = 0.14;
   			}
  }

			if ($player->promoted == 'f') {
			$multipleatk = 1 + ($varataque * 1.6);
			$multipledef = 1 + ($vardefesa * 1.6);
			$divideres = 2.3 - ($vardivide * 1.6);
			}elseif ($player->promoted == 't') {
			$multipleatk = 1 + ($varataque * 2.4);
			$multipledef = 1 + ($vardefesa * 2.4);
			$divideres = 2.3 - ($vardivide * 2.4);
			}elseif ($player->promoted == 'r') {
			$multipleatk = 1 + ($varataque * 3.2);
			$multipledef = 1 + ($vardefesa * 3.2);
			$divideres = 2.3 - ($vardivide * 3.2);
			}elseif ($player->promoted == 's') {
			$multipleatk = 1 + ($varataque * 4);
			$multipledef = 1 + ($vardefesa * 4);
			$divideres = 2.3 - ($vardivide * 4);
			}elseif ($player->promoted == 'p') {
			$multipleatk = 1 + ($varataque * 4.8);
			$multipledef = 1 + ($vardefesa * 4.8);
			$divideres = 2.3 - ($vardivide * 4.8);
			}



	$enychecamagiastatus = $db->execute("select * from `magias` where `magia_id`=5 and `player_id`=?", [$enemy->id]);

		if ($enemy->voc == 'archer') {
      if ($enychecamagiastatus->recordcount() > 0){
   			$varataque = 0.31;
   			$vardefesa = 0.13;
   			$vardivide = 0.13;
   			}else{
   			$varataque = 0.29;
   			$vardefesa = 0.12;
   			$vardivide = 0.12;
   			}
  } elseif ($enemy->voc == 'mage') {
      if ($enychecamagiastatus->recordcount() > 0){
   			$varataque = 0.265;
   			$vardefesa = 0.15;
   			$vardivide = 0.14;
   			}else{
   			$varataque = 0.245;
   			$vardefesa = 0.14;
   			$vardivide = 0.13;
   			}
  } elseif ($enemy->voc == 'knight') {
      if ($enychecamagiastatus->recordcount() > 0){
   			$varataque = 0.22;
   			$vardefesa = 0.17;
   			$vardivide = 0.15;
   			}else{
   			$varataque = 0.20;
   			$vardefesa = 0.16;
   			$vardivide = 0.14;
   			}
  }

			if ($enemy->promoted == 'f') {
			$enymultipleatk = 1 + ($varataque * 1.6);
			$enymultipledef = 1 + ($vardefesa * 1.6);
			$enydivideres = 2.3 - ($vardivide * 1.6);
			}elseif ($enemy->promoted == 't') {
			$enymultipleatk = 1 + ($varataque * 2.4);
			$enymultipledef = 1 + ($vardefesa * 2.4);
			$enydivideres = 2.3 - ($vardivide * 2.4);
			}elseif ($enemy->promoted == 'r') {
			$enymultipleatk = 1 + ($varataque * 3.2);
			$enymultipledef = 1 + ($vardefesa * 3.2);
			$enydivideres = 2.3 - ($vardivide * 3.2);
			}elseif ($enemy->promoted == 's') {
			$enymultipleatk = 1 + ($varataque * 4);
			$enymultipledef = 1 + ($vardefesa * 4);
			$enydivideres = 2.3 - ($vardivide * 4);
			}elseif ($enemy->promoted == 'p') {
			$enymultipleatk = 1 + ($varataque * 4.8);
			$enymultipledef = 1 + ($vardefesa * 4.8);
			$enydivideres = 2.3 - ($vardivide * 4.8);
			}


		//Calculate some variables that will be used
		$forcadoplayer = ceil(($player->strength + $player->atkbonus['effectiveness'] + ($player->atkbonus['item_bonus'] * 2) + ($player->atkbonus['for'] + $player->defbonus1['for'] + $player->defbonus2['for'] + $player->defbonus3['for'] + $player->defbonus5['for'] + $player->agibonus6['for'])) * $multipleatk);
		$agilidadedoplayer = ceil($player->agility + $player->agibonus6['effectiveness'] + ($player->agibonus6['item_bonus'] * 2) + ($player->atkbonus['agi'] + $player->defbonus1['agi'] + $player->defbonus2['agi'] + $player->defbonus3['agi'] + $player->defbonus5['agi'] + $player->agibonus6['agi']));
		$resistenciadoplayer = ceil(($player->resistance + ($player->defbonus1['effectiveness'] + $player->defbonus2['effectiveness'] + $player->defbonus3['effectiveness'] + $player->defbonus5['effectiveness']) + (($player->defbonus1['item_bonus'] * 2) + ($player->defbonus2['item_bonus'] * 2) + ($player->defbonus3['item_bonus'] * 2) + ($player->defbonus5['item_bonus'] * 2)) + ($player->atkbonus['res'] + $player->defbonus1['res'] + $player->defbonus2['res'] + $player->defbonus3['res'] + $player->defbonus5['res'] + $player->agibonus6['res'])) * $multipledef);

		$forcadoenemy = ceil(($enemy->strength + $enemy->atkbonus['effectiveness'] + ($enemy->atkbonus['item_bonus'] * 2) + ($enemy->atkbonus['for'] + $enemy->defbonus1['for'] + $enemy->defbonus2['for'] + $enemy->defbonus3['for'] + $enemy->defbonus5['for'] + $enemy->agibonus6['for'])) * $enymultipleatk);
		$agilidadedoenemy = ceil($enemy->agility + $enemy->agibonus6['effectiveness'] + ($enemy->agibonus6['item_bonus'] * 2) + ($enemy->atkbonus['agi'] + $enemy->defbonus1['agi'] + $enemy->defbonus2['agi'] + $enemy->defbonus3['agi'] + $enemy->defbonus5['agi'] + $enemy->agibonus6['agi']));
		$resistenciadoenemy = ceil(($enemy->resistance + ($enemy->defbonus1['effectiveness'] + $enemy->defbonus2['effectiveness'] + $enemy->defbonus3['effectiveness'] + $enemy->defbonus5['effectiveness']) + (($enemy->defbonus1['item_bonus'] * 2) + ($enemy->defbonus2['item_bonus'] * 2) + ($enemy->defbonus3['item_bonus'] * 2) + ($enemy->defbonus5['item_bonus'] * 2)) + ($enemy->atkbonus['res'] + $enemy->defbonus1['res'] + $enemy->defbonus2['res'] + $enemy->defbonus3['res'] + $enemy->defbonus5['res'] + $enemy->agibonus6['res'])) * $enymultipledef);

		$enemy->strdiff = (($forcadoenemy - $forcadoplayer) > 0)?($forcadoenemy - $forcadoplayer):0;
		$enemy->resdiff = (($resistenciadoenemy - ($resistenciadoplayer * 1.5)) > 0)?($resistenciadoenemy - $resistenciadoplayer):0;
		$enemy->agidiff = (($agilidadedoenemy - $agilidadedoplayer) > 0)?($agilidadedoenemy - $agilidadedoplayer):0;
		$enemy->leveldiff = (($enemy->level - $player->level) > 0)?($enemy->level - $player->level):0;
		$player->strdiff = (($forcadoplayer - $forcadoenemy) > 0)?($forcadoplayer - $forcadoenemy):0;
		$player->resdiff = (($resistenciadoplayer - $resistenciadoenemy) > 0)?($resistenciadoplayer - $resistenciadoenemy):0;
       		$player->agidiff = (($agilidadedoplayer - $agilidadedoenemy) > 0)?($agilidadedoplayer - $agilidadedoenemy):0;
		$player->leveldiff = (($player->level - $enemy->level) > 0)?($player->level - $enemy->level):0;
		$totalstr = $forcadoenemy + $forcadoplayer;
		$totalres = $resistenciadoenemy + $resistenciadoplayer;
		$totalagi = $agilidadedoenemy + $agilidadedoplayer;
		$totallevel = $enemy->level + $player->level;

		
		//Calculate the damage to be dealt by each player (dependent on strength and agility)
		$enemy->maxdmg = ceil($forcadoenemy - ($resistenciadoplayer / $divideres));
		$enemy->maxdmg -= (int) ($enemy->maxdmg * ($player->leveldiff / $totallevel));
		$enemy->maxdmg = ($enemy->maxdmg <= 2)?2:$enemy->maxdmg; //Set 2 as the minimum damage
		$enemy->mindmg = (($enemy->maxdmg - 4) < 1)?1:($enemy->maxdmg - 4); //Set a minimum damage range of maxdmg-4
  		$player->maxdmg = ceil($forcadoplayer - ($resistenciadoenemy / $enydivideres));
		$player->maxdmg -= (int) ($player->maxdmg * ($enemy->leveldiff / $totallevel));
		$player->maxdmg = ($player->maxdmg <= 2)?2:$player->maxdmg; //Set 2 as the minimum damage
		$player->mindmg = (($player->maxdmg - 4) < 1)?1:($player->maxdmg - 4); //Set a minimum damage range of maxdmg-4
		
		//Calculate battle 'combos' - how many times in a row a player can attack (dependent on agility)
		$enemy->combo = ceil($agilidadedoenemy / $agilidadedoplayer);
		$enemy->combo = ($enemy->combo > 3)?3:$enemy->combo;
		$player->combo = ceil($agilidadedoplayer / $agilidadedoenemy);
		$player->combo = ($player->combo > 3)?3:$player->combo;
		
		//Calculate the chance to miss opposing player
		$enemy->miss = (int) (($player->agidiff / $totalagi) * 100);
		$enemy->miss = ($enemy->miss > 20)?20:$enemy->miss; //Maximum miss chance of 20% (possible to change in admin panel?)
		$enemy->miss = max(8, $enemy->miss); //Minimum miss chance of 5%
		$player->miss = (int) (($enemy->agidiff / $totalagi) * 100);
		$player->miss = ($player->miss > 20)?20:$player->miss; //Maximum miss chance of 20%
		$player->miss = max(8, $player->miss); //Minimum miss chance of 5%
		
		
		$battlerounds = $setting->pvp_battle_rounds; //Maximum number of rounds/turns in the battle. Changed in admin panel?
		
		$depoput = ""; //Output message
		$output = ""; //Output message

		//While somebody is still alive, battle!
		while ($enemy->hp > 0 && $player->hp > 0 && $battlerounds > 0)
		{
			$attacking = ($especagi >= $especagieny)?$player:$enemy;
			$defending = ($especagi >= $especagieny)?$enemy:$player;
			
			for($i = 0;$i < $attacking->combo;$i++)
			{
				//Chance to miss?
				$misschance = random_int(0, 100);
				if ($misschance <= $attacking->miss)
				{
					$output .= $attacking->username . " tentou atacar " . $defending->username . " mas errou!<br />";
				}
				else
				{
					$damage = random_int($attacking->mindmg, $attacking->maxdmg); //Calculate random damage				
					$defending->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"red\">":"<font color=\"green\">";
					$output .= $attacking->username . " atacou " . $defending->username . " e tirou " . $damage . " de vida! (";
					$output .= ($defending->hp > 0)?$defending->hp . " de vida":"Morto";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($defending->hp <= 0)
					{
						$player = ($especagi >= $especagieny)?$attacking:$defending;
						$enemy = ($especagi >= $especagieny)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			for($i = 0;$i < $defending->combo;$i++)
			{
				//Chance to miss?
				$misschance = random_int(0, 100);
				if ($misschance <= $defending->miss)
				{
					$output .= $defending->username . " tentou atacar " . $attacking->username . " mas errou!<br />";
				}
				else
				{
					$damage = random_int($defending->mindmg, $defending->maxdmg); //Calculate random damage
					$attacking->hp -= $damage;
					$output .= ($player->username == $defending->username)?"<font color=\"green\">":"<font color=\"red\">";
					$output .= $defending->username . " atacou " . $attacking->username . " e tirou " . $damage . " de vida! (";
					$output .= ($attacking->hp > 0)?$attacking->hp . " de Vida":"Morto";
					$output .= ")<br />";
					$output .= "</font>";

					//Check if anybody is dead
					if ($attacking->hp <= 0)
					{
						$player = ($especagi >= $especagieny)?$attacking:$defending;
						$enemy = ($especagi >= $especagieny)?$defending:$attacking;
						break 2; //Break out of the for and while loop, but not the switch structure
					}
				}
				$battlerounds--;
				if ($battlerounds <= 0)
				{
					break 2; //Break out of for and while loop, battle is over!
				}
			}
			
			$player = ($especagi >= $especagieny)?$attacking:$defending;
			$enemy = ($especagi >= $especagieny)?$defending:$attacking;
		}


		if ($player->hp <= 0) {
      //Calculate losses
      $exploss1 = $player->level * 6;
      $exploss2 = (($player->level - $enemy->level) > 0)?($enemy->level - $player->level) * 4:0;
      $exploss = $exploss1 + $exploss2;
      $goldloss = (int) (0.2 * $player->gold);
      $goldloss = random_int(1, $goldloss);
      $depoput .= "<br/><div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><b><u>Voc� foi assassinado por " . $enemy->username . "!</u></b></div>";
      $depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Voc� perdeu <b>" . $exploss . "</b> de EXP e <b>" . $goldloss . "</b> de ouro.</div>";
      $exploss3 = (($player->exp - $exploss) <= 0)?$player->exp:$exploss;
      $goldloss2 = (($player->gold - $goldloss) <= 0)?$player->gold:$goldloss;
      //Update player (the loser)
      if ($setting->$enytourstatus == 'y' && $player->tour == 't' && $enemy->tour == 't' && $player->killed == 0 && $enemy->killed == 0 && $mytier == $enytier){
   			$tourlose = time();
   			$logmsg = "Voc� morreu e foi desclassificado do torneio.";
   			addlog($player->id, $logmsg, $db);
   			}else{
   			$tourlose = $player->killed;
   			}
      $query = $db->execute("update `players` set `energy`=?, `exp`=?, `gold`=?, `deaths`=?, `killed`=?, `died`=?, `hp`=0, `deadtime`=? where `id`=?", [$player->energy - 10, $player->exp - $exploss3, $player->gold - $goldloss2, $player->deaths + 1, $tourlose, $player->died + 1, time() + $setting->dead_time, $player->id]);
      //Update enemy (the winner)
      if ($exploss + $enemy->exp < $enemy->maxexp)
   			{
   				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `kills`=?, `hp`=? where `id`=?", [$enemy->exp + $exploss, $enemy->gold + $goldloss, $enemy->kills + 1, $enemy->hp, $enemy->id]);
   				//Add log message for winner
   
   				$logmsg3 = "Voc� foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> mas venceu!<br />\nVoc� ganhou " . $exploss . " de EXP e " . $goldloss . " de ouro.";
   				$insert['player_id'] = $enemy->id;
   				$insert['msg'] = $logmsg3;
   				$insert['time'] = time();
   				$query = $db->autoexecute('logbat', $insert, 'INSERT');
   			}
   			else //Defender has gained a level! =)
   			{
   
   $elevel = ($enemy->level + 1);
   $edividecinco = ($elevel / 5);
   $edividecinco = floor($edividecinco);
   
   $eganha = 100 + ($edividecinco * 15) + $enemy->extramana;
   
   $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [$eganha, $eganha, $enemy->id]);
   
   				if ($player->level <= 3){
   				$expofnewlvl = $enemy->maxexp + 75;
   				}else{
   				$expofnewlvl = floor(20 * ($enemy->level * $enemy->level * $enemy->level)/$enemy->level);
   				}
   				$query = $db->execute("update `players` set `magic_points`=?, `stat_points`=?, `level`=?, `maxexp`=?, `exp`=?, `gold`=?, `kills`=?, `hp`=?, `maxhp`=? where `id`=?", [$enemy->magic_points + 1, $enemy->stat_points + 3, $enemy->level + 1, $expofnewlvl, ($enemy->exp + $exploss) - $enemy->maxexp, $enemy->gold + $goldloss, $enemy->kills + 1, $enemy->maxhp + 30, $enemy->maxhp + 30, $enemy->id]);
   				//Add log message for winner
   
   				$logmsg4 = "Voc� foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> mas venceu!<br />\nVoc� ganhou um nivel e " . $goldloss . " de ouro.";
   				$insert['player_id'] = $enemy->id;
   				$insert['msg'] = $logmsg4;
   				$insert['time'] = time();
   				$query = $db->autoexecute('logbat', $insert, 'INSERT');
   			}
  } elseif ($enemy->hp <= 0) {
      //Calculate losses
      $expwin1 = $enemy->level * 20;
      $expwin2 = (($player->level - $enemy->level) > 0)?$expwin1 - (($player->level - $enemy->level) * 3):$expwin1 + (($player->level - $enemy->level) * 3);
      $expwin2 = ($expwin2 <= 0)?1:$expwin2;
      $expwin3 = round(0.9 * $expwin2);
      $expwin = ceil(random_int($expwin3, $expwin2));
      $goldwin = ceil(0.35 * $enemy->gold);
      $goldwin = random_int(1, $goldwin);
      $depoput .= "<br/><div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><b><u>Voc� matou " . $enemy->username . "!</u></b></div>";
      $depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Voc� ganhou <b>" . $expwin . "</b> de EXP e <b>" . $goldwin . "</b> de ouro.</div>";
      if ($expwin + $player->exp >= $player->maxexp) //Player gained a level!
   			{
   				//Update player, gained a level
   				$depoput .= "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><u><b>Voc� passou de nivel!</b></u></div>";
   				$newexp = $expwin + $player->exp - $player->maxexp;
   
   $plevel = ($player->level + 1);
   $dividecinco = ($plevel / 5);
   $dividecinco = floor($dividecinco);
   
   $ganha = 100 + ($dividecinco * 15) + $player->extramana;
   
   $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [$ganha, $ganha, $player->id]);
   
   				if ($player->level <= 3){
   				$expofnewlvl = $player->maxexp + 75;
   				}else{
   				$expofnewlvl = floor(20 * ($player->level * $player->level * $player->level)/$player->level);
   				}
   				$query = $db->execute("update `players` set `magic_points`=?, `stat_points`=?, `level`=?, `maxexp`=?, `maxhp`=?, `exp`=?, `gold`=?, `kills`=?, `hp`=?, `energy`=?, `gold`=? where `id`=?", [$player->magic_points + 1, $player->stat_points + 3, $player->level + 1, $expofnewlvl, $player->maxhp + 30, $newexp, $player->gold + $goldwin, $player->kills + 1, $player->maxhp + 30, $player->energy - 10, $player->gold + $goldwin, $player->id]);
   			}
   			else
   			{
   				//Update player
   				$query = $db->execute("update `players` set `exp`=?, `gold`=?, `kills`=?, `hp`=?, `energy`=? where `id`=?", [$player->exp + $expwin, $player->gold + $goldwin, $player->kills + 1, $player->hp, $player->energy - 10, $player->id]);
   			}
      $heal = $player->maxhp - $player->hp;
      if ($heal > 0){
   			if ($player->level < 36) {
          $cost = ceil($heal * 1);
      } elseif ($player->level > 35 && $player->level < 90) {
          $cost = ceil($heal * 1.45);
      } else{
   			$cost = ceil($heal * 1.8);
   			}
   			$depoput .= "<div style=\"background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><a href=\"hospt.php?act=heal\">Clique aqui</a> para recuperar toda sua vida por <b>" . $cost . "</b> de ouro.</div>";
   			}
      //Add log message
      if ($player->level*1.25 < $enemy->level){
    			$insert['player_id'] = $enemy->id;
    			$insert['enemy_id'] = $player->id;
    			$insert['time'] = time();
    			$addrevenge = $db->autoexecute('revenge', $insert, 'INSERT');
    		}
      $insert['player_id'] = $enemy->id;
      $insert['enemy_id'] = $player->id;
      $insert['time'] = time();
      $addrevenge = $db->autoexecute('revenge', $insert, 'INSERT');
      $logmsg5 = "Voc� foi atacado por <a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> e foi derrotado... <a href=\"battle.php?act=attack&username=" . $player->username . "&comfirm=true\">Clique aqui</a> para se vingar.";
      $insert['player_id'] = $enemy->id;
      $insert['msg'] = $logmsg5;
      $insert['time'] = time();
      $query = $db->autoexecute('logbat', $insert, 'INSERT');
      //Update enemy (who was defeated)
      if ($setting->$enytourstatus == 'y' && $player->tour == 't' && $enemy->tour == 't' && $player->killed == 0 && $enemy->killed == 0 && $mytier == $enytier){
   			$tourlose = time();
   			$logmsg = "Voc� morreu e foi desclassificado do torneio.";
   			addlog($enemy->id, $logmsg, $db);
   			}else{
   			$tourlose = $enemy->killed;
   			}
      $query = $db->execute("update `players` set `gold`=?, `hp`=0, `deaths`=?, `killed`=?, `died`=?, `deadtime`=? where `id`=?", [$enemy->gold + 1 - $goldwin, $enemy->deaths + 1, $tourlose, $enemy->died + 1, time() + $setting->dead_time, $enemy->id]);
  } else
		{
			$depoput .= "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Os dois est�o can�ados. Ningu�m venceu.</center></b></div>";
		}

		
		if ($checarevenge->recordcount() > 0 && $player->level > $enemy->level*1.25){
			if ($enemy->tour != 't'){
			$deleterevenge = $db->execute("delete from `revenge` where `player_id`=? and `enemy_id`=? limit ?", [$player->id, $enemy->id, 1]);
			}elseif ($enemy->killed != 0){
			$deleterevenge = $db->execute("delete from `revenge` where `player_id`=? and `enemy_id`=? limit ?", [$player->id, $enemy->id, 1]);
			}
		}
		
		$player = check_user($secret_key, $db); //Get new stats
		include(__DIR__ . "/templates/private_header.php");
		echo "<div id=\"logdebatalha\" class=\"scroll\" style=\"background-color:#FFFDE0; overflow: auto; height:270px; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
		echo $output;
		echo "</div>";
		echo $depoput;
		echo "<a href=\"battle.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		break;
	
	case "search":
		//Check in case somebody entered 0
		$_GET['fromlevel'] = ($_GET['fromlevel'] == 0)?"":$_GET['fromlevel'];
		$_GET['tolevel'] = ($_GET['tolevel'] == 0)?"":$_GET['tolevel'];
		
		//Construct query
		$query = "select `id`, `username`, `level`, `voc`, `promoted` from `players` where `id`!= ? and `hp`>0 and `died`<3 and ";
		$query .= ($_GET['fromlevel'] != "")?"`level` >= ? and ":"";
		$query .= ($_GET['tolevel'] != "")?"`level` <= ? and ":"";
		if ($_GET['voc'] == "1"){
		$query .= "`voc` = 'archer' ";
		}elseif ($_GET['voc'] == "2"){
		$query .= "`voc` = 'knight' ";
		}elseif ($_GET['voc'] == "3"){ 
		$query .= "`voc` = 'mage' ";
		}

		if ($player->serv == 1){
		$query .= "and `serv`=1 ";
		}elseif ($player->serv == 2){
		$query .= "and `serv`=2 ";
		}

		$query .= "ORDER BY RAND() LIMIT 20";
		
		//Construct values array for adoDB
		$values = [];
		$values[] = $player->id; //Make sure battle search doesn't show self
		if ($_GET['username'] != "")
		{
			$values[] = "%".trim((string) $_GET['username'])."%"; //Add username value for search
		}
		//Add level range for search
		if ($_GET['fromlevel']) {
      $values[] = (int) $_GET['fromlevel'];
  }
		if ($_GET['tolevel']) {
      $values[] = (int) $_GET['tolevel'];
  }
		
		include(__DIR__ . "/templates/private_header.php");
	
		//Display search form again
		echo "<center><font color=\"red\"><b>Voc� pode atacar usu�rios de nivel $diflvl ou mais.</b></font></center>\n";
		echo "<fieldset>\n";
		echo "<legend><b>Procurar por usu�rios</b></legend>\n";
		echo "<form method=\"get\" action=\"battle.php\">\n<input type=\"hidden\" name=\"act\" value=\"search\" />\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n<td width=\"35%\">Nivel:</td>\n<td width=\"65%\"><input type=\"text\" name=\"fromlevel\" size=\"4\" value=\"" . stripslashes((string) $_GET['fromlevel']) . "\" /> � <input type=\"text\" name=\"tolevel\" size=\"4\" value=\"" . stripslashes((string) $_GET['tolevel']) . "\" /></td>\n</tr>\n";
		echo "<tr>\n<td width=\"35%\">Voca��o:</td>\n<td width=\"65%\"><select name=\"voc\">\n<option value=\"1\"";
		echo ($_GET['voc'] == 1)?" selected=\"selected\"":"";
		echo ">Arqueiro</option>\n<option value=\"2\"";
		echo ($_GET['voc'] == 2)?" selected=\"selected\"":"";
		echo ">Guerreiro</option>\n<option value=\"3\"";
		echo ($_GET['voc'] == 3)?" selected=\"selected\"":"";
		echo ">Mago</option>\n</select></td>\n</tr>\n";
		echo "<tr><td></td><td><br /><input type=\"submit\" value=\"Procurar\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n</fieldset>\n";
		echo "<br /><br />";
		
		echo "<table width=\"100%\">\n";
		echo "<tr><th width=\"35%\">Usu�rio</th><th width=\"15%\">Nivel</th><th width=\20%\">Voca��o</th><th width=\"30%\">Batalha</a></th></tr>\n";
		$query = $db->execute($query, $values); //Search!
		if ($query->recordcount() > 0) //Check if any players were found
		{
			$bool = 1;
			while ($result = $query->fetchrow())
			{
				$checkquerywork = $db->GetOne("select `status` from `work` where `player_id`=? order by `start` DESC", [$result['id']]);
				if ($checkquerywork != \T) {
				echo "<tr class=\"row" . $bool . "\">\n";
				echo "<td width=\"35%\"><a href=\"profile.php?id=" . $result['username'] . "\">" . $result['username'] . "</a></td>\n";
				echo "<td width=\"15%\">" . $result['level'] . "</td>\n";
				echo "<td width=\"20%\">";
if ($result['voc'] == 'archer' && $result['promoted'] == 'f') {
    echo "Ca�ador";
} elseif ($result['voc'] == 'knight' && $result['promoted'] == 'f') {
    echo "Espadachim";
} elseif ($result['voc'] == 'mage' && $result['promoted'] == 'f') {
    echo "Bruxo";
} elseif ($result['voc'] == 'archer' && ($result['promoted'] == 't' || $result['promoted'] == 's' || $result['promoted'] == 'r')) {
    echo "Arqueiro";
} elseif ($result['voc'] == 'knight' && ($result['promoted'] == 't' || $result['promoted'] == 's' || $result['promoted'] == 'r')) {
    echo "Guerreiro";
} elseif ($result['voc'] == 'mage' && ($result['promoted'] == 't' || $result['promoted'] == 's' || $result['promoted'] == 'r')) {
    echo "Mago";
} elseif ($result['voc'] == 'archer' && $result['promoted'] == 'p') {
    echo "Besteiro";
} elseif ($result['voc'] == 'knight' && $result['promoted'] == 'p') {
    echo "Cavaleiro";
} elseif ($result['voc'] == 'mage' && $result['promoted'] == 'p') {
    echo "Arquimago";
}
 				echo "</td>\n";
 				echo "<td width=\"30%\"><a href=\"battle.php?act=attack&username=" . $result['username'] . "\">Atacar</a></td>\n";
				echo "</tr>\n";
				$bool = ($bool==1)?2:1;
				}
			}
		}
		else //Display error message
		{
			echo "<tr>\n";
			echo "<td colspan=\"3\">Nenhum usu�rio encontrado.</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		include(__DIR__ . "/templates/private_footer.php");
		break;
	
	default:
		include(__DIR__ . "/templates/private_header.php");

if ($player->stat_points > 0 && $player->level < 15)
{
	echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Antes de batalhar, utilize seus <b>" . $player->stat_points . "</b> pontos de status dispon�veis, assim voc� fica mais forte! <a href=\"stat_points.php\">Clique aqui para utiliza-los!</a></div>";
}

$query = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
if ($query->recordcount() < 2 && $player->level > 4 && $player->level < 20)
{
	echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">J� est� na hora de voc� comprar seus pr�pios itens. <a href=\"shop.php\">Clique aqui e visite o ferreiro</a>.</div>";
}
		
		//The default battle page, giving choice of whether to search for players or to target one
		echo "<center><font color=\"red\"><b>Voc� pode atacar usu�rios de nivel $diflvl ou mais.</b></font></center>\n";
		echo "<fieldset>\n";
		echo "<legend><b>Procurar por usu�rios</b></legend>\n";
		echo "<form method=\"get\" action=\"battle.php\">\n<input type=\"hidden\" name=\"act\" value=\"search\" />\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n<td width=\"35%\">Nivel:</td>\n<td width=\"65%\"><input type=\"text\" name=\"fromlevel\" size=\"4\" /> � <input type=\"text\" name=\"tolevel\" size=\"4\" /></td>\n</tr>\n";
		echo "<tr>\n<td width=\"35%\">Voca��o:</td>\n<td width=\"65%\"><select name=\"voc\">\n<option value=\"1\">Arqueiro</option>\n<option value=\"2\">Cavaleiro</option>\n<option value=\"3\">Mago</option>\n</select></td>\n</tr>\n";
		echo "<tr><td></td><td><br /><input type=\"submit\" value=\"Procurar\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n</fieldset>\n";
		echo "<br /><br />\n";
		echo "<fieldset>\n";
		echo "<legend><b>Atacar usu�rio</b></legend>\n";
		echo "<form method=\"get\" action=\"battle.php?act=attack\">\n<input type=\"hidden\" name=\"act\" value=\"attack\" />\n";
		echo "<table width=\"100%\">\n";
		echo "<tr><td width=\"35%\">Usu�rio:</td><td width=\"65%\"><input type=\"text\" name=\"username\" /><input type=\"submit\" value=\"Atacar\" /></td></tr></table>";
		echo "</form>\n";
		echo "</fieldset>\n";
		
		include(__DIR__ . "/templates/private_footer.php");
		break;
}
?>