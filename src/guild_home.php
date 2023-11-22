<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
include(__DIR__ . '/bbcode.php');
$bbcode = new bbcode();
define("PAGENAME", "Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");

$guildonline = 0;

include(__DIR__ . "/checkguild.php");


if ($player->hp <= 0) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset>";
    echo "<legend><b>Voc� est� morto!</b></legend>\n";
    echo "V� ao <a href=\"hospt.php\">hospital</a> ou espere 30 minutos.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

?>

<center><font size=6><b><?=$guild['name']?> [<?=$guild['tag']?>]</b></font></center><p />
<center><font size=4><b><?=$guild['motd']?></b></font></center><p />
<p /><p />
<fieldset>
<legend><b>Informa��es sobre o cl�</b></legend>
<b>Lider:</b> <a href="profile.php?id=<?=$guild['leader']?>"><?=$guild['leader']?></a><br/>
<b>Vice-Lider:</b> <?=($guild['vice'] != '') ? "<a href=\"profile.php?id=" . $guild['vice']. "\">" . $guild['vice']. "</a>" : "Ningu�m";?><br/>
<b>Membros:</b> <?=$guild['members']?><br/>
<b>Tesouro:</b> <?=$guild['gold']?> - <a href="guild_treasury.php">Enviar ouro para o cl�</a><br/>
</fieldset>
<p /><p />
<fieldset>
<legend><b>Descri��o</b></legend>
<?=$bbcode->parse($guild['blurb'])?>
</fieldset>
<p /><p />
<fieldset>
<legend><b>Membros do cl� online</b></legend>
<?php


$checkonne = $db->execute("select `player_id` from `online`");

while($online = $checkonne->fetchrow()) {
    $getname = $db->execute("select `username` from `players` where `id`=? and `guild`=? order by `username` asc", [$online['player_id'], $guild['id']]);
    $member = $getname->fetchrow();

    echo "<a href=\"profile.php?id=" . $member['username'] . "\">";
    echo ($member['username'] == $player->username) ? "<b>" : "";
    echo $member['username'];
    echo ($member['username'] == $player->username) ? "</b>" : "";
    echo "</a> | ";

    $guildonline += 1;
}
echo "<b>Total:</b> " . $guildonline . "";
?>
</fieldset>
<p /><p />
<form>
<center>
<?php if($player->username == $guild['leader'] || $player->username == $guild['vice']) {
    echo "<input type=\"button\" VALUE=\"Administra��o\" ONCLICK=\"window.location.href='guild_admin.php'\">&nbsp;";
}
?>
<input type="button" VALUE="Perfil do Cl�" ONCLICK="window.location.href='guild_profile.php?id=<?=$guild['id'];?>'">&nbsp;<input type="button" VALUE="Tesouro" ONCLICK="window.location.href='guild_treasury.php'">&nbsp;<input type="button" VALUE="Abandonar Cl�" ONCLICK="window.location.href='guild_leave.php'"></center>
</form>
<p /><p />
<fieldset>
<legend><b>Pagamento do Cl�</b></legend>
<?php
        $valortempo = $guild['pagopor'] - time();
if ($valortempo < 60) {
    $valortempo2 = $valortempo;
    $auxiliar2 = "segundo(s)";
} elseif ($valortempo < 3600) {
    $valortempo2 = floor($valortempo / 60);
    $auxiliar2 = "minuto(s)";
} elseif ($valortempo < 86400) {
    $valortempo2 = floor($valortempo / 3600);
    $auxiliar2 = "hora(s)";
} elseif ($valortempo > 86400) {
    $valortempo2 = floor($valortempo / 86400);
    $auxiliar2 = "dia(s)";
}
?>
<center><b>Cl� pago por:</b> <?=$valortempo2;?> <?=$auxiliar2;?>.<br>Este cl� ser� deletado se o tempo acabar e os lideres n�o pagarem mais.</center>
</fieldset>

<?php include(__DIR__ . "/templates/private_footer.php");
?>