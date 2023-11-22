<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Banco");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkwork.php");

$lockedgold = 0;

$countlocked = $db->execute("select `prize` from `duels` where `owner`=? and (`active`='w' or `active`='t')", [$player->id]);
while($count = $countlocked->fetchrow())
{
$lockedgold += $lockedgold + $count['prize'];
}

if (isset($_POST['deposit'])) {
    $deposita = floor($_POST['deposit']);
    if ($deposita > $player->gold || $deposita < 1) {
        $msg = "<font color=\"red\">Voc� n�o pode depositar esta quantia de dinheiro!</font>\n";
    } elseif (!is_numeric($_POST['deposit'])) {
        $msg = "<font color=\"red\">Esta quantia de ouro n�o � v�lida!</font>\n";
    } else
   	{
   		$query = $db->execute("update `players` set `bank`=?, `gold`=? where `id`=?", [$player->bank + $deposita, $player->gold - $deposita, $player->id]);
   		$msg = "<font color=\"green\">Voc� depositou seu ouro no banco.</font>\n";
   		$player = check_user($secret_key, $db); //Get new stats so new amount of gold is displayed on left menu
   	}
} elseif (isset($_POST['withdraw'])) {
    $saca = floor($_POST['withdraw']);
    if ($saca > ($player->bank - $lockedgold) || $saca < 1) {
        $msg = "<font color=\"red\">Voc� n�o tem esta quantia de dinheiro na sua conta do banco!</font>\n";
    } elseif (!is_numeric($_POST['withdraw'])) {
        $msg = "<font color=\"red\">Esta quantia de ouro n�o � v�lida!</font>\n";
    } else
   	{
   		$query = $db->execute("update `players` set `bank`=?, `gold`=? where `id`=?", [$player->bank - $saca, $player->gold + $saca, $player->id]);
   		$msg = "<font color=\"green\">Voc� retirou seu dinheiro do banco.</font>\n";
   		$player = check_user($secret_key, $db); //Get new stats so new amount of gold is displayed on left menu
   	}
}

include(__DIR__ . "/templates/private_header.php");

echo "<b>Assistente:</b><br />\n<i>\n";
echo $msg ?? "Bem-vindo ao banco, senhor. O qu� voc� gostaria de fazer?\n";
echo "</i>";
?>
<br /><br />
<table width="100%">
<tr>
<td width="100%">
<fieldset>
<legend><b>Depositar ouro</b></legend>
Voc� tem <b><?=$player->gold?></b> de ouro com voc�.<br />
<form method="post" action="bank.php">
<input type="text" name="deposit" size="15" value="<?=$player->gold?>" />
<input type="submit" name="bank_action" value="Depositar"/>
</form>
</fieldset>
</td>
</tr>
<tr>
<td width="100%">
<fieldset>
<legend><b>Retirar ouro</b></legend>
Voc� tem <b><?php echo ($player->bank - $lockedgold); ?></b> de ouro na sua conta banc�ria.<br />
<form method="post" action="bank.php">
<input type="text" name="withdraw" size="15" value="<?php echo ($player->bank - $lockedgold); ?>" />
<input type="submit" name="bank_action" value="Retirar"/>
</form>
<?php
if ($lockedgold > 0){
echo "<center><font size=\"1\">Voc� tem <b>" . $lockedgold . "</b> de ouro bloqueado na sua conta banc�ria. (ouro para duelos)</font></center>";
}
?>
</fieldset>
<?php
if (($player->bank + $player->gold) > $setting->bank_limit){
echo "<center><font size=1>Sua fortuna j� passou de " . $setting->bank_limit . ", agora voc� n�o receber� mais juros!</font></center>";
}else{
echo "<center><font size=1>Seu ouro depositado se valoriza " . $setting->bank_interest_rate . "% ao dia.</font></center>";
}
?>
</td>
</tr>
</table>
<p />
<fieldset>
<legend><b>Transferir Ouro</b></legend>

if ($player->level < $setting->activate_level) {
    echo "Para poder fazer transfer�ncias banc�rias sua conta precisa estar ativa. Ela ser� ativada automaticamente quando voc� alcan�ar o n�vel " . $setting->activate_level . ".";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}<?php
if ($player->transpass == \F) {
    echo "<form method=\"POST\" action=\"transferpass.php\">";
    echo "<table><tr><td width=\"35%\"><b>Escolha uma senha para enviar ouro e itens:</b></td><td width=\"65%\"><font size=\"1\"><b>Senha:</b></font> <input type=\"password\" name=\"pass\" size=\"15\"/><br/><font size=\"1\"><b>Confirme:</b></font> <input type=\"password\" name=\"pass2\" size=\"15\"/> <input type=\"submit\" name=\"submit\" value=\"Definir Senha\"></td></tr></table><br/><font size=\"1\">Lembre-se desta senha, ela sempre ser� usada para fazer transfer�ncias banc�rias. Se voc� perdela n�o poder� recupera-la.</font>";
    echo "</form></fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
echo "<form method=\"POST\" action=\"transfer.php\">";
echo "<table><tr><td width=\"30%\"><b>Usu�rio:</b></td><td width=\"70%\"><input type=\"text\" name=\"username\" size=\"20\"/></td></tr>";
echo "<tr><td width=\"30%\"><b>Quantia:</b></td><td width=\"70%\"><input type=\"text\" name=\"amount\" size=\"20\"/></td></tr>";
echo "<tr><td width=\"30%\"><b>Senha de transfer�ncia:</b></td><td width=\"70%\"><input type=\"password\" name=\"passcode\" size=\"20\"/> <input type=\"submit\" name=\"submit\" value=\"Enviar\"></td></tr></table>";
echo "</form><font size=\"1\"><a href=\"forgottrans.php\">Esqueceu sua senha de transfer�ncia?</a> - <a href=\"account.php\">Alterar senha de transfer�ncia</a></font>";
echo "</fieldset>";
echo "<center><font size=1><a href=\"#\" onclick=\"javascript:window.open('loggold.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Transfer�ncias realizadas nos �ltimos 14 dias.</a></font></center>";
include(__DIR__ . "/templates/private_footer.php");
exit; 
?>
