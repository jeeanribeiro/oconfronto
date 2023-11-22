<?php

$numgoldbars = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", [$player->id, 157]);
if ($numgoldbars->recordcount() > 2) {

    $removelmagicgoldbars = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [157, $player->id, 3]);

    $insert['player_id'] = $player->id;
    $insert['item_id'] = 158;
    $addgoldhelm = $db->autoexecute('items', $insert, 'INSERT');

    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Aten��o</b></legend>\n";
    echo "As tr�s barras de ouro que voc� possuia em seu invent�rio parecem ter se misturado, e formado um novo elmo.<br />";
    echo "<a href=\"inventory.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
