<?php
    include_once("conexaoSQL.php");

    /* VALIDA USUARIO */
  session_start();
  $login = $_SESSION["login"];
  $senha = $_SESSION["password"];
      $sql="SELECT 
      TB01066_USUARIO Usuario,
      TB01066_SENHA Senha,
      TB01066_VENDAS vendas
     FROM 
      TB01066
     WHERE 
     TB01066_USUARIO = '$login'
     AND TB01066_SENHA = '$senha'";
  $stmt= sqlsrv_query($conn,$sql);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
      $usuario = $row['Usuario'];
      $senha = $row['Senha'];
      $vendas = $row['vendas'];
    }
    if($usuario != NULL && $vendas == '1'){

    }else { 
      echo"<script>window.alert('É necessário fazer login!')</script>";
      echo "<script>location.href='http://databitbh.com:51230/coletores/login.php'</script>"; 
      
    } 

    $pedido = $_POST['pedido'];


   $sql0 = "
      SELECT TB02216_STATUS status FROM TB02216
      WHERE TB02216_CODIGO = '$pedido'
   ";
   $stmt0 = sqlsrv_query($conn, $sql0);

   while($row0 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){
        $status = $row0['status'];
   }
  
   if($status == '21' || $status == '22' || $status == '23' || $status == '24' || $status == '01'){
      $novoStatus = '26';
   }
      

    $sql = 
    "
    UPDATE TB02216 
    SET TB02216_STATUS = '$novoStatus' 
    WHERE TB02216_CODIGO = '$pedido'

    INSERT TB02217 (TB02217_CODIGO, 
        TB02217_PRODUTO,
        TB02217_ITEM, 
        TB02217_STATUS,
        TB02217_DATA,
        TB02217_OBS, 
        TB02217_USER)  
    (SELECT CODIGO,
            PRODUTO,
            ROW_NUMBER() OVER(PARTITION BY PRODUTO ORDER BY PRODUTO ASC),
            '$novoStatus',
            GETDATE(),
            'APP RETORNO EXP',
            '$login'
            FROM GS_RETORNO
            WHERE CODIGO = '$pedido')

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
                <!-- <form action="http://databitbh.com:51230/coletores/coletorretornoexp/itensalocados.php" method="post">
                  <?php /* echo "<input class='inputcod' type='hidden' name='pedido' autofocus='true' value='$pedido'></br>" */?>
                  <input type="submit" class="voltarSalvo" value="VOLTAR">
                </Form> -->
              </td>
            </tr>
            <tr>
              <td>
                <form method="get" action="http://databitbh.com:51230/coletores/coletorretornoexp/inicio.php" class="form-inicial">
                    <input style="background-color: #90EE90;"  type="submit" class="voltarSalvo" value="INICIO">
                </form>
              </td>
            </tr>
        </table>
  </body>
</html>
