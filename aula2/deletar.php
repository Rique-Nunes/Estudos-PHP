<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sigla_para_deletar = $_POST["sigla"]; 

    $arqDisc = fopen("disciplinas.txt", "r") or die("erro ao abrir original");
    $arqTemp = fopen("disciplinas_temp.txt", "w") or die("erro ao abrir temp");

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);

        if(isset($colunaDados[1]) && trim($colunaDados[1]) != $sigla_para_deletar){

            if(trim($linha) != "") {
                fwrite($arqTemp, $linha);
            }
        }
    }

    fclose($arqDisc);
    fclose($arqTemp);
    unlink("disciplinas.txt");
    rename("disciplinas_temp.txt", "disciplinas.txt");
    $msg = "Disciplina '" . $sigla_para_deletar . "' foi deletada com sucesso!;";

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deletando...</title>
</head>
<body>
    <form action="deletar.php" method="POST">
        Sigla da disciplina que quer deletar: <br>
        <input type="text" name="sigla"><br>
        <input type="submit" value="Buscar Disciplina">
    </form>
    <h1><?php echo $msg; ?></h1>
</body>
</html>