<?php
if ($player->ban > time()) {
    $newlast = (time() - 210);
    $query = $db->execute("update `players` set `last_active`=? where `id`=?", [$newlast, $player->id]);
    session_unset();
    session_destroy();
    echo "Voc� foi banido. As vezes usu�rios s�o banidos automaticamente por algum erro em suas contas. Se voc� acha que foi banido injustamente, ou se tiver algum erro para reportar, crie outra conta e entre em contato com o [GOD]. Assim seu banimento poder� ser removido.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}


if ($player->hp <= 0 && $player->deadtime > time()) {
    $query = $db->execute("update `players` set `lutando`=0 where `id`=?", [$player->id]);
    $query = $db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
    unset($_SESSION['ataques']);

    $time = ($player->deadtime - time());
    $time_remaining = ceil($time / 60);
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset>";
    echo "<legend><b>Voc� est� morto!</b></legend>\n";
    echo "<center>Voc� ir� ressucitar em " . $time_remaining . " minuto(s).</center>";
    ?>
<br/><b><div id="COUNTER" align="center"></div></b><br/>
<script type="text/javascript">
//<![CDATA[[
<!--
var OpenTimeCOUNTER = '';
var TargetCOUNTER = document.getElementById('COUNTER');
var SecondsCOUNTER = <?php echo $time; ?>;
var TargetTimeCOUNTER = new Date();
var TimeBeginnCOUNTER = TargetTimeCOUNTER.getTime();
var TimeEndCOUNTER = TimeBeginnCOUNTER + (SecondsCOUNTER*1000);
TargetTimeCOUNTER.setTime(TimeEndCOUNTER);
var DayCOUNTER = TargetTimeCOUNTER.getDate();
var MonthCOUNTER = TargetTimeCOUNTER.getMonth() + 1;
var YearCOUNTER = TargetTimeCOUNTER.getYear();
if(YearCOUNTER < 999) YearCOUNTER += 1900;
var hCOUNTER = TargetTimeCOUNTER.getHours();
var mCOUNTER = TargetTimeCOUNTER.getMinutes();
var sCOUNTER = TargetTimeCOUNTER.getSeconds();
var fdayCOUNTER  = ((DayCOUNTER < 10) ? "0" : "");
var fmonthCOUNTER  = ((MonthCOUNTER < 10) ? ".0" : ".");
var fhCOUNTER  = ((hCOUNTER < 10) ? "0" : "");
var fmCOUNTER  = ((mCOUNTER < 10) ? ":0" : ":");
var fsCOUNTER  = ((sCOUNTER < 10) ? ":0" : ":");
var EndDateCOUNTER = fdayCOUNTER + DayCOUNTER + fmonthCOUNTER + MonthCOUNTER  + "." + YearCOUNTER;
var EndTimeCOUNTER = fhCOUNTER+hCOUNTER+fmCOUNTER+mCOUNTER+fsCOUNTER+sCOUNTER;

var counterthing = window.setTimeout("CountDownCOUNTER()", 1000);

function CountDownCOUNTER()
{
	var CurrentDateCOUNTER = new Date();
	var CurrentTimeCOUNTER = CurrentDateCOUNTER.getTime();
	OpenTimeCOUNTER = Math.floor((TargetTimeCOUNTER-CurrentTimeCOUNTER)/1000);
	var sCOUNTER = OpenTimeCOUNTER % 60;
	var mCOUNTER = ((OpenTimeCOUNTER-sCOUNTER)/60) % 60;
	var hCOUNTER = ((OpenTimeCOUNTER-sCOUNTER-mCOUNTER*60)/(60*60));
	var fhCOUNTER  = ((hCOUNTER < 10) ? "0" : "");
	var fmCOUNTER  = ((mCOUNTER < 10) ? ":0" : ":");
	var fsCOUNTER  = ((sCOUNTER < 10) ? ":0" : ":");
	var TimeCOUNTER = fhCOUNTER+hCOUNTER+fmCOUNTER+mCOUNTER+fsCOUNTER+sCOUNTER;
	var OutputStringCOUNTER=TimeCOUNTER;

	if( OpenTimeCOUNTER <= 0 )
	{
		OutputStringCOUNTER='<a href="javascript:window.location.reload()">Atualizar<\/a>';
		window.clearTimeout(counterthing);
	}
	TargetCOUNTER.innerHTML=OutputStringCOUNTER;
	counterthing = window.setTimeout("CountDownCOUNTER()",1000);
}
//-->
//]]>
</script>

	<?php
    echo "<center>Ou voc� poder� ir ao <a href=\"hospt.php\">hospital</a> ou at� mesmo usar uma po��o de vida.</center>";
    echo "</fieldset>";

    echo "<br><br>";

    $query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", [$player->id]);
    $numerodepocoes = $query->recordcount();

    $query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", [$player->id]);
    $numerodepocoes2 = $query2->recordcount();

    $query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", [$player->id]);
    $numerodepocoes3 = $query3->recordcount();

    $query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", [$player->id]);
    $numerodepocoes4 = $query4->recordcount();

    echo "<fieldset>";
    echo "<legend><b>Po��es</b></legend>";
    echo "<table width=\"100%\"><tr><td><table width=\"80px\"><tr><td><div title=\"header=[Health Potion] body=[Recupera at� 5 mil de vida.]\"><img src=\"images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
    if ($numerodepocoes > 0) {
        $item = $query->fetchrow();
        echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item['id'] . "\">Usar</a>";
    }
    echo "</td></tr></table></td>";
    echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Big Health Potion] body=[Recupera at� 10 mil de vida.]\"><img src=\"images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
    if ($numerodepocoes3 > 0) {
        $item3 = $query3->fetchrow();
        echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item3['id'] . "\">Usar</a>";
    }
    echo "</td></tr></table></td>";
    echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Mana Potion] body=[Recupera toda sua mana.]\"><img src=\"images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
    if ($numerodepocoes4 > 0) {
        $item4 = $query4->fetchrow();
        echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item4['id'] . "\">Usar</a>";
    }
    echo "</td></tr></table></td>";
    echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Energy Potion] body=[Recupera at� 50 de energia.]\"><img src=\"images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
    if ($numerodepocoes2 > 0) {
        $item2 = $query2->fetchrow();
        echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item2['id'] . "\">Usar</a>";
    }
    echo "</td></tr></table></td><td><a href=\"hospt.php?act=sell\">Vender Po��es</a></td></tr></table>";
    echo "</fieldset>";


    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

?>