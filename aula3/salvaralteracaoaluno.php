<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $matricula_para_alterar = $_POST["matricula"]; 
    $novo_nome = $_POST["nome"];
    $novo_data = $_POST["data"];
    $novo_cpf = $_POST["cpf"];
    $novo_email = $_POST["email"];

    $arqDisc = fopen("aluno.txt", "r") or die("erro ao abrir original");
    $arqTemp = fopen("aluno_temp.txt", "w") or die("erro ao abrir temp");

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);

        if(isset($colunaDados[1]) && trim($colunaDados[1]) == $matricula_para_alterar){
            
            $nova_linha =  $novo_nome . ";" . $novo_data . ";" . $novo_cpf . ";" . $matricula_para_alterar . ";" . $novo_email . "\n";
            fwrite($arqTemp, $nova_linha);

        } else {

            if($linha != "") {
                fwrite($arqTemp, $linha);
            }
        }
    }


    fclose($arqDisc);
    fclose($arqTemp);
    unlink("aluno.txt");
    rename("aluno_temp.txt", "aluno.txt");
    $msg = "aluno: '" . $matricula_para_alterar . "' foi alterada com sucesso!;";

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