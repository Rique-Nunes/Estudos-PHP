<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sigla_para_alterar = $_POST["sigla"]; 
    $novo_nome = $_POST["nome"];
    $nova_carga = $_POST["carga"];

    $arqDisc = fopen("disciplinas.txt", "r") or die("erro ao abrir original");
    $arqTemp = fopen("disciplinas_temp.txt", "w") or die("erro ao abrir temp");

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);

        if(isset($colunaDados[1]) && trim($colunaDados[1]) == $sigla_para_alterar){
            
            $nova_linha = $novo_nome . ";" . $sigla_para_alterar . ";" . $nova_carga . "\n";
            fwrite($arqTemp, $nova_linha);

        } else {

            if($linha != "") {
                fwrite($arqTemp, $linha);
            }
        }
    }


    fclose($arqDisc);
    fclose($arqTemp);
    unlink("disciplinas.txt");
    rename("disciplinas_temp.txt", "disciplinas.txt");
    $msg = "Disciplina '" . $sigla_para_alterar . "' foi alterada com sucesso!;";

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salvando Alterações</title>
</head>
<body>
    <h1><?php echo $msg; ?></h1>
</body>
</html>