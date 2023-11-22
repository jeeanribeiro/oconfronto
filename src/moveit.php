<?php

include(__DIR__ . "/lib.php");
$player = check_user($secret_key, $db);
$error = 0;

if ($_GET['itid'] && $_GET['tile']) {
    if ($_GET['itid'] < 1 || !is_numeric($_GET['itid'])) {
        $error = 1;
    } elseif ($_GET['tile'] < 1 || !is_numeric($_GET['tile'])) {
        $error = 1;
    }

    $checkitem = $db->execute("select * from `items` where `id`=? and `player_id`=?", [$_GET['itid'], $player->id]);
    if ($checkitem->recordcount() != 1) {
        $error = 1;
    }

    if ($error == 0) {
        $itstatus = $db->GetOne("select `status` from `items` where `id`=? and `player_id`=?", [$_GET['itid'], $player->id]);
        if ($itstatus == 'equipped') {
            $db->execute("update `items` set `status`='unequipped' where `id`=? and `player_id`=?", [$_GET['itid'], $player->id]);
        }


        $backpackcount = $db->execute("select items.id, items.tile from `items`, `blueprint_items` where items.player_id=? and items.status='unequipped' and items.item_id=blueprint_items.id and blueprint_items.type!='potion' and blueprint_items.type!='stone' and items.mark='f' limit 49", [$player->id]);

        if ($_GET['tile'] <= 1) {
            $limit1 = 0;
            $limit2 = 1;
        } else {
            $limit1 = ($_GET['tile'] - 1);
            $limit2 = ($_GET['tile'] - 1);
        }

        $tileexists = $db->execute("select items.tile from `items`, `blueprint_items` where items.player_id=? and items.status='unequipped' and items.item_id=blueprint_items.id and blueprint_items.type!='potion' and blueprint_items.type!='stone' and items.mark='f' order by items.tile asc limit ?,?", [$player->id, $limit1, $limit2]);
        $tileitd = $db->GetOne("select `tile` from `items` where `id`=? and `player_id`=?", [$_GET['itid'], $player->id]);

        if ($_GET['tile'] > $backpackcount->recordcount()) {
            $biggesttile = $db->GetOne("select `tile` from `items` where `player_id`=? order by `tile` desc", [$player->id]);
            $db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", [$biggesttile + 1, $_GET['itid'], $player->id]);
        } elseif ($tileexists->recordcount() > 0) {
            $tilenumber = $tileexists->fetchrow();

            if ($_GET['tile'] <= 1) {
                $db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", [$tilenumber['tile'] - 1, $_GET['itid'], $player->id]);
            } elseif ($tileitd > $tilenumber['tile']) {
                $db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", [$tilenumber['tile'] - 1, $_GET['itid'], $player->id]);
            } else {
                $db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", [$tilenumber['tile'] + 1, $_GET['itid'], $player->id]);
            }
        }
    }
}

header("Location: inventory.php");
exit;
