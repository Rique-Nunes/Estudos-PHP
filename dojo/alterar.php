<?php
$idaluno;
$nome;
$date;
$cpf;
$email;
$turno;

if ( $_SERVER['REQUEST_METHOD'] == "POST") {
    $idaluno = $_POST["idaluno"];
    $nome = $_POST["nome"];
    $date = $_POST["date"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $turno = $_POST["turno"];

    $arqdisc = fopen("alun.txt", "r") or die ("erro de abertura do arquivo");
    $arqSaida = "";



    while(!feof($arqdisc)){
        $line = fgets($arqdisc);
        echo($line);

        $parts = explode(";", $line);

        $mat = $parts[0]; 
        $nomee = $parts[1];
        $datee = $parts[2];
        $cpff = $parts[3];
        $emaill = $parts[4];
        $turnoo = $parts[5]; 


       if ($mat == $idaluno){
            echo "Achei!!";
            echo $nomee;
            echo $datee;
            echo $cpff;
            echo $emaill;
            echo $turnoo;

            $line2 = $idaluno .';' . $nome . ';' . $date . ';' . $cpf. ';' . $email . ';' . $turno;
            $arqSaida = $arqSaida . $line2;
       } else {
            $arqSaida = $arqSaida . $line;
       }
    } 

    echo($arqSaida);
    fclose($arqdisc);

    $arqdisc = fopen("alun.txt", "w") or die ("erro de abertura do arquivo");
    fwrite($arqdisc, $arqSaida);

    fclose($arqdisc);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <ul>
         <li>matricula: <?php echo $idaluno ?></li>
        <li>nome: <?php echo $nome ?></li>
        <li>data de ingresso: <?php echo $date ?></li>
        <li>cpf: <?php echo $cpf ?></li>
        <li>email: <?php echo $email ?></li>
        <li>turno: <?php echo $turno ?></li>
    </ul>
</body>
</html>