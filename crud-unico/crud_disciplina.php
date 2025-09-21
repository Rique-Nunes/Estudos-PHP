<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRUD de Disciplinas</title>
</head>
<body>
  <h2>Cadastro de Disciplinas</h2>

  <?php
  $arquivo = "disciplinas.txt";

  if(isset($_POST['acao']) && $_POST['acao'] == "salvar"){
      $nome  = $_POST["nome"];
      $sigla = $_POST["sigla"];
      $carga = $_POST["carga"];

      if(!file_exists($arquivo)){
          file_put_contents($arquivo, "nome;sigla;carga\n");
      }
      file_put_contents($arquivo, "$nome;$sigla;$carga\n", FILE_APPEND);

      echo "<p style='color:green;'>Disciplina salva!</p>";
  }


  if(isset($_GET['delete'])){
      $linhas = file($arquivo);
      $novaLista = "";
      foreach($linhas as $i => $linha){
          if($i == 0) { $novaLista .= $linha; continue; } 
          if(($i) != $_GET['delete']){ 
              $novaLista .= $linha;
          }
      }
      file_put_contents($arquivo, $novaLista);
      echo "<p style='color:red;'>Disciplina apagada!</p>";
  }

  ?>

  <form method="POST">
    <input type="hidden" name="acao" value="salvar">

    <label for="nome">Nome:</label><br>
    <input type="text" id="nome" name="nome" required><br><br>

    <label for="sigla">Sigla:</label><br>
    <input type="text" id="sigla" name="sigla" required><br><br>

    <label for="carga">Carga horária:</label><br>
    <input type="number" id="carga" name="carga" required min="1"><br><br>

    <input type="submit" value="Salvar">
  </form>

  <hr>
  <h2>Lista de Disciplinas</h2>

  <?php

  if(file_exists($arquivo)){
      $linhas = file($arquivo);
      echo "<table border='1' cellpadding='5'>
              <tr><th>Nome</th><th>Sigla</th><th>Carga</th><th>Ações</th></tr>";

      foreach($linhas as $i => $linha){
          if($i == 0) continue; 
          $dados = explode(";", trim($linha));
          if(count($dados) < 3) continue;
          echo "<tr>
                  <td>{$dados[0]}</td>
                  <td>{$dados[1]}</td>
                  <td>{$dados[2]}</td>
                  <td>
                    <a href='?delete=$i'>Excluir</a>
                  </td>
                </tr>";
      }
      echo "</table>";
  } else {
      echo "<p>Nenhuma disciplina cadastrada.</p>";
  }
  ?>
</body>
</html>
