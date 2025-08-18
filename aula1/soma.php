<?php
$V1 = 0;
$v2 = 0;
$resultado=0;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
 echo "<br /> É post";
$V1 = $_POST["a"];
$v2 = $_POST["b"];
$operador = $_POST["select"];
}else{
echo "<br /> É get";
}

    switch ($operador) {
        case "+":
            $resultado = $V1 + $v2;
            break;
        
        case "-":
            $resultado = $V1 - $v2;
            break;
        case "*":
            $resultado = $V1 * $v2;
            break;
        case "/":
            $resultado = $V1 / $v2;
            break;
        
    }
?>

<DOCTYPE  HTML>
    <HTML>
    <head>
        <title> ola mundo </title>
    <head>
    <body>
        <form action="soma.php" method="POST" name="soma">
            a: <input type="text" name="a"> <br>
            b: <input type="text" name="b"> <br>
            operação: 
            <select name="select">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">x</option>
            <option value="/">/</option>
            </select>   
            <input type="submit" value="enviar">
        </form>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo "<h1> resultado: $resultado</h1>";
        }
        ?>
    </body>
    </html>