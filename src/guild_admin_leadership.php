<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

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
if ($player->username != $guild['leader']) {
    echo "<p />Voc� n�o pode acessar esta p�gina.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

    if (isset($_POST['username']) && ($_POST['submit'])) {
        $username = $_POST['username'];
        $query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=?", [$username]);

        if ($query->recordcount() == 0) {
            $errmsg .= "Este usu�rio n�o existe!<p />";
            $error = 1;
        } elseif ($username == $guild['leader']) {
            $errmsg .= "Este usu�rio j� � o lider do cl�!<p />";
            $error = 1;
        } else {
            $member = $query->fetchrow();
            if ($member['guild'] != $guild['id']) {
                $errmsg .= "O usu�rio $username n�o faz parte do cl�: " . $member['guild'] ."!<p />";
                $error = 1;
            } else {
                if ($username == $guild['vice']) {
                    $query = $db->execute("update `guilds` set `leader`=?, `vice`='' where `id`=?", [$username, $guild['id']]);
                } else {
                    $query = $db->execute("update `guilds` set `leader`=? where `id`=?", [$username, $guild['id']]);
                }

                $logmsg = "Voc� foi nomeado lider do cl�: ". $guild['name'] .".";
                addlog($member['id'], $logmsg, $db);
                $msg .= "Voc� nomeou $username como lider do cl�. <a href=\"home.php\">Clique aqui</a> para voltar a p�gina inicial.<p />";
            }
        }
    }

    ?>

<fieldset>
<legend><b><?=$guild['name']?> :: Passar Lideran�a</b></legend>
<p><form method="POST" action="guild_admin_leadership.php">
<b>Usu�rio:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", [$guild['id']]);
    echo "<select name=\"username\"><option value=''>Selecione</option>";
    while($result = $query->fetchrow()) {
        echo "<option value=\"$result[username]\">$result[username]</option>";
    }
    echo "</select>"; ?> <input type="submit" name="submit" value="Passar lideran�a"><p />
</form>
<b>ATEN��O:</b> Nomeando um novo lider, voc� perder� todas as fun��es da administra��o!
<p /><?=$msg?><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>

<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>