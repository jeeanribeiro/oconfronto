<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$price = 950000;
$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "<p />Voc� n�o pode acessar esta p�gina.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

    if ($_GET['upgrade'] == \MAXPLAYERS) {
        if ($guild['maxmembers'] > 49) {
            $errmsg .= "<center><b>Voc� n�o pode adicionar mais vagas para o cl�.</b></center>";
            $error = 1;
        } elseif ($price > $guild['gold']) {
            $errmsg .= "<center><b>Seu cl� n�o possui ouro suficiente. (Pre�o: " . $price . ")</b></center>";
            $error = 1;
        }
        if ($error == 0) {
            $query = $db->execute("update `guilds` set `maxmembers`=?, `gold`=? where `id`=?", [$guild['maxmembers'] + 10, $guild['gold'] - $price, $guild['id']]);
            $msg .= "<center><b>Agora seu cl� pode possuir " . ($guild['maxmembers'] + 10) . " membros.</b></center>";
        }
    }

    ?>
<?=$msg?><font color=red><?=$errmsg?></font>
<fieldset>
<legend><b><?=$guild['name']?> :: Melhorias</b></legend>
<br/>
<center><input type="button" VALUE="Adicionar mais 10 vagas para o cl�." ONCLICK="window.location.href='guild_admin_upgrade.php?upgrade=maxplayers'"> <b>(Pre�o: <?=$price?>)</b></center>
<br/>
</fieldset>
<a href="guild_admin.php">Voltar</a>.
<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>