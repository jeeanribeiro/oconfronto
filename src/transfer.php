<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Tranferir Ouro");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

$username = ($_POST['username']);
$password = strtolower((string) $_POST['passcode']);
$amount = ($_POST['amount']);

if (isset($_POST['username']) && ($_POST['amount']) && ($_POST['passcode']) && ($_POST['submit'])) {
    $destinatario = $db->execute("select * from `players` where `username`=?", [$username]);
    $member = $destinatario->fetchrow();
    if ($destinatario->recordcount() == 0) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Este usu�rio n�o existe!";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($player->serv != $member['serv']) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Este usu�rio pertence a outro servidor.";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($player->gold < $amount) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Voc� n�o pode enviar esta quantia de ouro!";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (!is_numeric($amount)) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Voc� n�o pode enviar esta quantia de ouro!";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    } elseif ($amount < 1) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Voc� n�o pode enviar esta quantia de ouro!";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    } elseif ($player->username == $username) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Voc� n�o pode enviar ouro para voc� mesmo!";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    } elseif (strtolower((string) $player->transpass) !== $password) {
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Sua senha de transfer�ncia est� incorreta.";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    } else {

        $query = $db->execute("update `players` set `bank`=`bank`+? where `id`=?", [$amount, $member['id']]);
        $query1 = $db->execute("update `players` set `gold`=`gold`-? where `id`=?", [$amount, $player->id]);

        $insert['player_id'] = $player->id;
        $insert['name1'] = $player->username;
        $insert['name2'] = $member['username'];
        $insert['action'] = "enviou";
        $insert['value'] = $amount;
        $insert['time'] = time();
        $query = $db->autoexecute('log_gold', $insert, 'INSERT');

        $insert['player_id'] = $member['id'];
        $insert['name1'] = $member['username'];
        $insert['name2'] = $player->username;
        $insert['action'] = "recebeu";
        $insert['value'] = $amount;
        $insert['time'] = time();
        $query = $db->autoexecute('log_gold', $insert, 'INSERT');

        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Banco</b></legend>";
        echo "Voc� enviou <b>$amount</b> de ouro para $username.";
        echo "</fieldset>";
        echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
}
include(__DIR__ . "/templates/private_header.php");
echo "<fieldset><legend><b>Banco</b></legend>";
echo "Voc� precisa preencher todos os campos.";
echo "</fieldset>";
echo"<br/><a href=\"bank.php\">Voltar</a>.</br>";
include(__DIR__ . "/templates/private_footer.php");
