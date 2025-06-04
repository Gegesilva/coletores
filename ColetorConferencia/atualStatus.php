<?php
    include_once("conexaoSQL.php");

    /* VALIDA USUARIO */
  session_start();
  $login = $_SESSION["login"];
  $senha = $_SESSION["password"];
      $sql="SELECT 
      TB01066_USUARIO Usuario,
      TB01066_SENHA Senha,
      TB01066_GRAFICOS permGrafOs
     FROM 
      TB01066
     WHERE 
     TB01066_USUARIO = '$login'
     AND TB01066_SENHA = '$senha'";
  $stmt= sqlsrv_query($conn,$sql);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
      $usuario = $row['Usuario'];
      $senha = $row['Senha'];
      $permGrafOs = $row['permGrafOs'];
    }
    if($usuario != NULL && $permGrafOs == '1'){

    }else { 
      echo"<script>window.alert('É necessário fazer login!')</script>";
      echo "<script>location.href='../login.php'</script>"; 
      
    } 

    $orcamento = $_POST['orcamento'];


   $sql0 = "
    SELECT TB02018_STATUS status FROM TB02018
    WHERE TB02018_CODIGO = '$orcamento'
   ";
   $stmt0 = sqlsrv_query($conn, $sql0);

   while($row0 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){
        $status = $row0['status'];
   }
  
   if($status == '97'){
      $novoStatus = 'B0';
   }
      elseif($status == '98'){
        $novoStatus = 'B8';
      }
        elseif($status == '96'){
          $novoStatus = 'C3';
        }
         elseif($status == 'E3'){
            $novoStatus = 'E6';
         }
          elseif($status == 'C7'){
            $novoStatus = 'D0';
            }

    $sql = 
    "
     UPDATE TB02018 SET 
     TB02018_STATUS = '$novoStatus',
     TB02018_OPALT = '$login',
     TB02018_DTALT = GETDATE()
     WHERE 
      TB02018_CODIGO = '$orcamento'
      AND EXISTS (SELECT CODIGO FROM GS_CONFERE WHERE CODIGO = '$orcamento') 
      
      INSERT INTO 
          TB02130 (
        TB02130_CODIGO,
        TB02130_USER,
        TB02130_OBS,
        TB02130_DATA,
        TB02130_DATAEXEC,
        TB02130_STATUS,
        TB02130_TIPO,
        TB02130_NOMETEC,
        TB02130_CODCAD,
        TB02130_CODEMP,
        TB02130_NOME,
        TB02130_CODTEC
        )
        (SELECT 
        '$orcamento',
        '$login',
        'APP COLETOR',
        GETDATE(), 
        GETDATE(), 
        '$novoStatus',
        'V',
        TB01024_NOME,
        TB02018_CODCLI,
        TB02018_CODEMP,
        (SELECT TB01021_NOME FROM TB01021 WHERE TB01021_CODIGO = '$novoStatus'),
        TB02018_CODTEC
        FROM TB02018
        LEFT JOIN TB01024 ON TB01024_CODIGO = TB02018_CODTEC
        WHERE TB02018_CODIGO = '$orcamento'
      )


    ";
    $stmt = sqlsrv_query($conn, $sql);
        
        if($stmt === false)
        {
            $mensagem = 'Não gravado, verifique os campos.';
            /* die (print_r(sqlsrv_errors(), true)); */
        } 
        else{
            $mensagem = 'Dados Gravados.';
        }
?>

<!doctype html>
<html lang="pt-BR">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link rel="stylesheet" href="css/itensAloc.css">
      <script src="js/script.js"></script>

      <title>DATABIT</title>
    </head>
  <script>
      function pergunta1(){ 
      // Deletar Produto
      return confirm('Tem certeza que quer deseja deletar este produto?');
            }
      /* function pergunta2(){ 
      // 
      return confirm('Tem ceterza que efetivar a conferência?');
            } */
  </script>
  <body>
        <script src="js/jQuery/jquery-3.5.1.min.js" charset="utf-8"></script>
        <script src="js/script.js"></script>

        <b><p class="tituloSalvo" ><?php echo $mensagem;?></p></b>
        <table  style="float: right;">
            <tr>
              <td>
                <!-- <form action="http://databitbh.com:51230/coletores/coletorconferencia/itensalocados.php" method="post">
                  <?php /* echo "<input class='inputcod' type='hidden' name='orcamento' autofocus='true' value='$orcamento'></br>" */?>
                  <input type="submit" class="voltarSalvo" value="VOLTAR">
                </Form> -->
              </td>
            </tr>
            <tr>
              <td>
                <form method="get" action="inicio.php" class="form-inicial">
                    <input style="background-color: #90EE90;"  type="submit" class="voltarSalvo" value="INICIO">
                </form>
              </td>
            </tr>
        </table>
  </body>
</html>
