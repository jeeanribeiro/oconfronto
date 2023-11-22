<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Tesouro do Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");

include(__DIR__ . "/checkguild.php");


//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

if (isset($_POST['amount']) && ($_POST['submit'])) {

    $amount = floor($_POST['amount']);
    if ($player->gold < $amount) {
        include(__DIR__ . "/templates/private_header.php");
        echo "Voc� n�o pode enviar esta quantia de ouro!<br />";
        echo "<a href=\"guild_treasury.php\">Tentar Novamente</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($amount < 1) {
        include(__DIR__ . "/templates/private_header.php");
        echo "Voc� n�o pode enviar esta quantia de ouro!<br />";
        echo "<a href=\"guild_treasury.php\">Tentar Novamente</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (!is_numeric($amount)) {
        include(__DIR__ . "/templates/private_header.php");
        echo "Voc� n�o pode enviar esta quantia de ouro!<br />";
        echo "<a href=\"guild_treasury.php\">Tentar Novamente</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    $quety0 = $db->execute("select `id` from `players` where `username`=?", [$guild['leader']]);
    $query = $db->execute("update `guilds` set `gold`=? where `id`=?", [$guild['gold'] + $amount, $player->guild]);
    $query1 = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $amount, $player->id]);
    include(__DIR__ . "/templates/private_header.php");
    echo "Voc� transferiu <b>$amount de gold</b> para o cl�: " . $guild['name'] . ".<br /><a href=\"guild_home.php\">Voltar</a>.";
    $lider = $quety0->fetchrow();
    $logmsg = "<b>$player->username</b> transferiu <b>$amount de gold</b> para o cl�.";
    addlog($lider['id'], $logmsg, $db);
    $insert['player_id'] = $player->id;
    $insert['name1'] = $player->username;
    $insert['name2'] = $guild['name'];
    $insert['action'] = "doou";
    $insert['value'] = $amount;
    $insert['aditional'] = "gangue";
    $insert['time'] = time();
    $query = $db->autoexecute('log_gold', $insert, 'INSERT');
    if ($guild['vice'] != '' && $guild['vice'] != null) {
        $quety00 = $db->execute("select `id` from `players` where `username`=?", [$guild['vice']]);
        $vice = $quety00->fetchrow();
        $logmsg = "<b>$player->username</b> transferiu <b>$amount de gold</b> para o cl�.";
        addlog($vice['id'], $logmsg, $db);
    }
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

include(__DIR__ . "/templates/private_header.php");

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Tranferir Ouro</b></legend>
<form method="POST" action="guild_treasury.php">
<b>Desejo Enviar:</b> <input type="text" name="amount" value="0" size="15"/>
<input type="submit" name="submit" value="Enviar para o Cl�"><p />
</form>
<b>Seu cl� possui <?=$guild['gold']?> de ouro.</b> O tesouro do seu cl� poder� ser usado pelos lideres para adicionar mais vagas no cl�, pagas as taixas ou at� mesmo para fazer doa��es aos demais membros do cl�.
</fieldset>
<br/>
<a href="guild_home.php">Voltar</a>.

<?php include(__DIR__ . "/templates/private_footer.php");
?>