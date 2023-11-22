<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Enviar Imagens");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkwork.php");
include(__DIR__ . "/templates/private_header.php");


if ($setting->allow_upload != \T) {
    echo "O envio de imagens est� desativado no momento. <a href=\"home.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
?>
<fieldset>
<legend><b>Enviar Imagens</b></legend>
<form action="sendfiles.php" method="post" enctype="multipart/form-data">
<input type="file" name="foto" size="30"><input type="submit" name="upload" value="Enviar">
</form>
</fieldset>
<font size=\"1\">Aqui voc� pode enviar imagens para usar como avatar, no f�rum, no perfil, etc.</font><br/><br/>
<?php

if ($_POST['upload']) {
    $erro = $config = [];

    // Prepara a vari�vel do arquivo
    $arquivo = $_FILES["foto"] ?? false;

    // Tamanho m�ximo do arquivo (em bytes)
    $config["tamanho"] = 1_800_000;
    // Largura m�xima (pixels)
    $config["largura"] = 1024;
    // Altura m�xima (pixels)
    $config["altura"] = 1024;

    // Formul�rio postado... executa as a��es
    if($arquivo) {
        // Verifica se o mime-type do arquivo � de imagem
        if(!preg_match('#^image\/(pjpeg|jpeg|gif|bmp)$#mi', (string) $arquivo["type"])) {
            $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em formato inv�lido!</span><br/>- A imagem deve ser jpg, jpeg, bmp ou gif.";
        } else {
            // Verifica tamanho do arquivo
            if($arquivo["size"] > $config["tamanho"]) {
                $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em tamanho muito grande!</span><br>- A imagem deve ser de no m�ximo " . $config["tamanho"] . " bytes.";
            }

            // Para verificar as dimens�es da imagem
            $tamanhos = getimagesize($arquivo["tmp_name"]);

            // Verifica largura
            if($tamanhos[0] > $config["largura"]) {
                $erro[] = "Largura da imagem n�o deve ultrapassar " . $config["largura"] . " pixels";
            }

            // Verifica altura
            if($tamanhos[1] > $config["altura"]) {
                $erro[] = "Altura da imagem n�o deve ultrapassar " . $config["altura"] . " pixels";
            }
        }

        // Imprime as mensagens de erro
        if($erro !== []) {
            foreach($erro as $err) {
                echo " - " . $err . "<BR>";
            }
        }

        // Verifica��o de dados OK, nenhum erro ocorrido, executa ent�o o upload...
        else {
            // Pega extens�o do arquivo
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", (string) $arquivo["name"], $ext);


            // Gera um nome �nico para a imagem
            $imagem_nome = md5(uniqid(time())) . "." . $ext[1];

            // Caminho de onde a imagem ficar�
            $imagem_dir = "imgs/" . $imagem_nome;

            // Faz o upload da imagem
            move_uploaded_file($arquivo["tmp_name"], $imagem_dir);

            echo "<span style=\"color: white; border: solid 1px; background: green;\">Sua imagem foi enviada com sucesso!</span><br/>";
            echo "<b>Endere�o:</b> <font size=\"1\">http://www.oconfronto.kinghost.net/imgs/".$imagem_nome."</font>";
        }
    }
}


    include(__DIR__ . "/templates/private_footer.php");
?>