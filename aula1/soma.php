<?php
$V1 = 0;
$v2 = 0;
$resultado;
$operador = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $V1 = $_POST["a"];
    $v2 = $_POST["b"];
    $operador = $_POST["select"];

    if (is_numeric($V1) && is_numeric($v2)) {
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
                if ($v2 != 0) {
                    $resultado = $V1 / $v2;
                } else {
                    $resultado = "Erro: Divisão por zero!";
                }
                break;
        }
    } else {
        $resultado = "Erro: Por favor, insira apenas números.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Calculadora Simples</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            height: 100vh;
            margin-left: 30px;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        input[type="text"], select {
            width: 100px;
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .resultado {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
        <h2>Calculadora PHP</h2>
        <form action="soma.php" method="POST" name="soma">
            <input type="text" name="a" placeholder="Valor A">
            <select name="select">
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">x</option>
                <option value="/">/</option>
            </select>
            <input type="text" name="b" placeholder="Valor B"> <br>
            <input type="submit" value="Calcular">
        </form>

        <?php
        if ($resultado !== null) {
            echo "<div class='resultado'>Resultado: $resultado</div>";
        }
        ?>
</body>
</html>