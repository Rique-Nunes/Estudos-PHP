<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Lista Disciplina</h1>
    <table>
        <!--<tr><th>nome</th><th>sigla</th><th>carga</th></tr> -->
    <?php
    $arqDisc = fopen("disciplinas.txt", "r") or die("erro ao abrir");
    
    fgets($arqDisc);

    while(!feof($arqDisc)){
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);
    
    echo "<tr><td>".$colunaDados[0]."</td>".
    "<td>".$colunaDados[1]."</td>".
    "<td>".$colunaDados[2]."</td>". "</tr>";
    }
    fclose($arqDisc);
    $msg = "deu certo";
    ?>
</body>
</html>