<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Editar perfil");
$player = check_user($secret_key, $db);

$error = 0;

include(__DIR__ . "/templates/private_header.php");

if ($player->gm_rank > 4)
{
if ($_POST['submit1']) {
    if (!$_POST['endlotto'] || !$_POST['winid'] && !$_POST['winid2'] || !$_POST['preco']) {
        $errmsg1 .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif ($_POST['endlotto'] < (time() + 1)) {
        $errmsg1 .= "O tempo de premia��o � muito curto";
        $error = 1;
    } elseif ($_POST['winid'] == 0 && $_POST['winid2'] < 5000) {
        $errmsg1 .= "Selecione um pr�mio melhor.";
        $error = 1;
    } elseif ($_POST['preco'] < 1 || $_POST['preco'] > 100000) {
        $errmsg1 .= "O pre�o do ticket � muito caro!";
        $error = 1;
    }

    if ($error == 0) {

	$premiacao = $_POST['winid'] > 0 && $_POST['winid'] < 1000 ? $_POST['winid'] : $_POST['winid2'];

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endlotto'], \END_LOTTO_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$premiacao, \WIN_ID_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco'], \LOTTERY_PRICE_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \LOTTERY_1]);

        $msg1 .= "Voc� come�ou uma loteria com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
    }
}
elseif ($_POST['submit2']) {
    if (!$_POST['endlotto'] || !$_POST['winid'] && !$_POST['winid2'] || !$_POST['preco']) {
        $errmsg1 .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif ($_POST['endlotto'] < (time() + 1)) {
        $errmsg1 .= "O tempo de premia��o � muito curto";
        $error = 1;
    } elseif ($_POST['winid'] == 0 && $_POST['winid2'] < 5000) {
        $errmsg1 .= "Selecione um pr�mio melhor.";
        $error = 1;
    } elseif ($_POST['preco'] < 1 || $_POST['preco'] > 100000) {
        $errmsg1 .= "O pre�o do ticket � muito caro!";
        $error = 1;
    }

    if ($error == 0) {

	$premiacao = $_POST['winid'] > 0 && $_POST['winid'] < 1000 ? $_POST['winid'] : $_POST['winid2'];

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endlotto'], \END_LOTTO_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$premiacao, \WIN_ID_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco'], \LOTTERY_PRICE_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \LOTTERY_2]);

        $msg1 .= "Voc� come�ou uma loteria com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
    }
}
elseif ($_POST['submit3']) {
    if (!$_POST['endtour'] || !$_POST['premo1'] || !$_POST['premo2'] || !$_POST['premo3'] || !$_POST['premo4'] || !$_POST['premo5'] || !$_POST['preco1'] || !$_POST['preco2'] || !$_POST['preco3'] || !$_POST['preco4'] || !$_POST['preco5']) {
        $errmsg2 .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif ($_POST['endtour'] < (time() + 1)) {
        $errmsg2 .= "O tempo para o inicio do torneio � muito curto";
        $error = 1;
    } elseif ($_POST['premo1'] < 1 || $_POST['premo1'] > 99_999_999) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['premo2'] < 1 || $_POST['premo2'] > 99_999_999) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['premo3'] < 1 || $_POST['premo3'] > 99_999_999) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['premo4'] < 1 || $_POST['premo4'] > 99_999_999) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['premo5'] < 1 || $_POST['premo5'] > 99_999_999) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['preco1'] < 1 || $_POST['preco1'] > 100000) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['preco2'] < 1 || $_POST['preco2'] > 100000) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['preco3'] < 1 || $_POST['preco3'] > 100000) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['preco4'] < 1 || $_POST['preco4'] > 100000) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    } elseif ($_POST['preco5'] < 1 || $_POST['preco5'] > 100000) {
        $errmsg2 .= "O pre�o para ingressar no torneio � muito caro!";
        $error = 1;
    }

    if ($error == 0) {

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_1_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_2_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_3_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_4_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_5_1]);

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_1_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_2_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_3_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_4_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['endtour'], \END_TOUR_5_2]);


	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo1'], \TOUR_WIN_1_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo2'], \TOUR_WIN_2_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo3'], \TOUR_WIN_3_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo4'], \TOUR_WIN_4_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo5'], \TOUR_WIN_5_1]);

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo1'], \TOUR_WIN_1_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo2'], \TOUR_WIN_2_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo3'], \TOUR_WIN_3_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo4'], \TOUR_WIN_4_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['premo5'], \TOUR_WIN_5_2]);


	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco1'], \TOUR_PRICE_1_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco2'], \TOUR_PRICE_2_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco3'], \TOUR_PRICE_3_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco4'], \TOUR_PRICE_4_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco5'], \TOUR_PRICE_5_1]);

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco1'], \TOUR_PRICE_1_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco2'], \TOUR_PRICE_2_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco3'], \TOUR_PRICE_3_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco4'], \TOUR_PRICE_4_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [$_POST['preco5'], \TOUR_PRICE_5_2]);


	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_1_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_2_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_3_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_4_1]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_5_1]);

	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_1_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_2_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_3_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_4_2]);
	$query = $db->execute("update `settings` set `value`=? where `name`=?", [\T, \TOURNAMENT_5_2]);

        $msg2 .= "Voc� come�ou um torneio com sucesso.<br/><a href=\"eventos.php\">Voltar</a>.";
    }
}

?>

<fieldset>
<legend><b>Adicionar Loteria - Servidor 1</b></legend>
<?php
if ($setting->lottery_1 != \T){
?>
<table width="100%">
<form method="POST" action="eventos.php">
<tr><td width="30%"><b>Acaba as</b>:</td><td><input type="text" name="endlotto" value="<?=$setting->end_lotto_1?>" size="20"/></td></tr>

<tr><td width="30%"><b>Pr�mio Item</b>:</td><td>
<?php
$itemsid = $db->execute("select `id`, `name` from `blueprint_items`");
echo "<select name=\"winid\"><option value=\"0\">Selecione</option>";
while($result = $itemsid->fetchrow()){
echo "<option value=\"$result[id]\">$result[name]</option>";
}
?>
</td></tr>

<tr><td width="30%"><b>Pr�mio Ouro</b>:</td><td><input type="text" name="winid2" value="<?=$setting->win_id_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o Ticket</b>:</td><td><input type="text" name="preco" value="<?=$setting->lottery_price_1?>" size="20"/></td></tr>

<tr><td colspan="2" align="center"><input type="submit" name="submit1" value="Iniciar Loteria"></td></tr>
</table>
</form>
<?php
}else{
echo "<br/><center><b>A loteria est� acontecendo neste momento.</b></center>";
}
?>
<br/><center><b><font color=green><?=$msg1?></font></b></center>
<br/><center><b><font color=red><?=$errmsg1?></font></b></center>
</fieldset>

<br/><br/>

<fieldset>
<legend><b>Adicionar Loteria - Servidor 2</b></legend>
<?php
if ($setting->lottery_2 != \T){
?>
<table width="100%">
<form method="POST" action="eventos.php">
<tr><td width="30%"><b>Acaba as</b>:</td><td><input type="text" name="endlotto" value="<?=$setting->end_lotto_1?>" size="20"/></td></tr>

<tr><td width="30%"><b>Pr�mio Item</b>:</td><td>
<?php
$itemsid = $db->execute("select `id`, `name` from `blueprint_items`");
echo "<select name=\"winid\"><option value=\"0\">Selecione</option>";
while($result = $itemsid->fetchrow()){
echo "<option value=\"$result[id]\">$result[name]</option>";
}
?>
</td></tr>

<tr><td width="30%"><b>Pr�mio Ouro</b>:</td><td><input type="text" name="winid2" value="<?=$setting->win_id_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o Ticket</b>:</td><td><input type="text" name="preco" value="<?=$setting->lottery_price_1?>" size="20"/></td></tr>

<tr><td colspan="2" align="center"><input type="submit" name="submit2" value="Iniciar Loteria"></td></tr>
</table>
</form>
<?php
}else{
echo "<br/><center><b>A loteria est� acontecendo neste momento.</b></center>";
}
?>
<br/><center><b><font color=green><?=$msg1?></font></b></center>
<br/><center><b><font color=red><?=$errmsg1?></font></b></center>
</fieldset>

<br/><br/>

<fieldset>
<legend><b>Adicionar Torneio</b></legend>
<?php
if ($setting->tournament_1_1 != \T){
?>
<table width="100%">
<form method="POST" action="eventos.php">
<tr><td width="30%"><b>Come�a</b>:</td><td><input type="text" name="endtour" value="<?=$setting->end_tour_1_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pr�mio - Tier 1</b>:</td><td><input type="text" name="premo1" value="<?=$setting->tour_win_1_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pr�mio - Tier 2</b>:</td><td><input type="text" name="premo2" value="<?=$setting->tour_win_2_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pr�mio - Tier 3</b>:</td><td><input type="text" name="premo3" value="<?=$setting->tour_win_3_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pr�mio - Tier 4</b>:</td><td><input type="text" name="premo4" value="<?=$setting->tour_win_4_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pr�mio - Tier 5</b>:</td><td><input type="text" name="premo5" value="<?=$setting->tour_win_5_1?>" size="20"/></td></tr>

<tr><td width="30%"><b>Pre�o - Tier 1</b>:</td><td><input type="text" name="preco1" value="<?=$setting->tour_price_1_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o - Tier 2</b>:</td><td><input type="text" name="preco2" value="<?=$setting->tour_price_2_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o - Tier 3</b>:</td><td><input type="text" name="preco3" value="<?=$setting->tour_price_3_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o - Tier 4</b>:</td><td><input type="text" name="preco4" value="<?=$setting->tour_price_4_1?>" size="20"/></td></tr>
<tr><td width="30%"><b>Pre�o - Tier 5</b>:</td><td><input type="text" name="preco5" value="<?=$setting->tour_price_5_1?>" size="20"/></td></tr>

<tr><td colspan="2" align="center"><input type="submit" name="submit3" value="Iniciar Torneio"></td></tr>
</table>
</form>
<?php
}else{
echo "<br/><center><b>O torneio est� acontecendo neste momento.</b></center>";
}
?>
<br/><center><b><font color=green><?=$msg2?></font></b></center>
<br/><center><b><font color=red><?=$errmsg2?></font></b></center>
</fieldset>

<?php
}else{
echo "Voc� n�o pode acessar esta p�gina.<br/><a href=\"home.php\">Voltar</a>.";
}
include(__DIR__ . "/templates/private_footer.php");
?>
