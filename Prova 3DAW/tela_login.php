<?php
// Arquivo simples apenas para redirecionamento
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Modo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 100px auto;
            padding: 0 20px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 40px;
        }
        
        .opcao {
            display: block;
            background: #f2f2f2;
            padding: 30px;
            margin: 20px 0;
            text-decoration: none;
            color: #333;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .opcao:hover {
            background: #e6e6e6;
            border-color: #ccc;
        }
    </style>
</head>
<body>
    <h1>Selecione o Modo de Acesso</h1>
    
    <a href="tela_admin.php" class="opcao">Modo Administrador</a>
    <a href="tela_usuario.php" class="opcao">Modo Usuario</a>
</body>
</html>