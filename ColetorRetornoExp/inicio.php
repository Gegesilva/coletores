<?PHP 
    header('Content-type: text/html; charset=utf-8');

    /* VALIDA USUARIO */
  session_start();
  include "conexaoSQL.php";
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
      echo "<script>location.href='../login.php'</script>"; 
      
    } 

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/StyleColetor.css">

    <title>DATABIT</title>
  </head>
</body>

  <nav class="divcentral">
          <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
              <ul class="navbar-nav mr-auto">
              </ul>
          </div>
          <!-- <img src="media/logoAPNET.png" width="100" height="20" style="margin-left: 1px;"  class="d-inline-block align-top" alt=""> -->
          <div class="mx-auto order-0" >
               <!-- <b class="titulo">PEDIDO DE VEND.</b> -->
                    
                    <div>
                    <form method="post" action="bip1.php">

                         <input class="inputcod" type="text" name="pedido" required autofocus="true" placeholder="Pedido de Venda"></br>
                        <input class="btn-inicio" type="submit">
                        <!-- <button id="btn">Imprimir</button> -->
                      </form>
                      </br>
                      <form action="../gerencial.php">
                        <input class="btn-inicio" type="submit" value="  Sair  "/>
                      </form>
                    </div>
                    <img style="margin-top: 150px;" data-lazyloaded="1" src="https://databit.com.br/wp-content/uploads/2022/05/DatabitAtivo-2.svg" width="auto" height="auto" data-src="https://databit.com.br/wp-content/uploads/2022/05/DatabitAtivo-2.svg"  alt="" data-ll-status="loaded">
          </div>
          <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">

</div>
</body>
</html>
