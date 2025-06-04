<?php
header('Content-type: text/html; charset=utf-8');

/* VALIDA USUARIO */
session_start();
include "conexaoSQL.php";
$login = $_SESSION["login"];
$senha = $_SESSION["password"];

$sql = "
            SELECT 
              TB01066_USUARIO Usuario,
              TB01066_SENHA Senha,

              TB01066_GRAFICOS permGrafOs,
              TB01066_GRAFICOS2 permGrafReq,
              TB01066_USUARIOS permUsu,
              TB01066_CHAT permChat,
              TB01066_VENDAS permVendas
            FROM 
              TB01066
            WHERE 
              TB01066_USUARIO = '$login'
              AND TB01066_SENHA = '$senha'
       ";
$stmt = sqlsrv_query($conn, $sql);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $usuario = $row['Usuario'];
  $senha = $row['Senha'];

  $permGrafOs = $row['permGrafOs'];
  $permGrafReq = $row['permGrafReq'];
  $permUsu = $row['permUsu'];
  $permChat = $row['permChat'];
  $permVendas = $row['permVendas'];
}
if ($usuario != NULL) {

} else {
  echo "<script>window.alert('É necessário fazer login!')</script>";
  echo "<script>location.href='login.php'</script>";

}


$conferencia = ($permGrafOs == '1') ? '<form class="form-btn" action="coletorconferencia/inicio.php">
                                          <input type="submit" id="deletar"  class="btn btn-outline-secondary" value="Conferência"></input>
                                         </form> ' : '';
$retornoExp = ($permVendas == '1') ? ' <form class="form-btn" action="coletorretornoexp/inicio.php">
                                          <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Retorno Exp"></input>
                                         </form>  ' : '';
$retorno = ($permGrafReq == '1') ? '   <form class="form-btn" action="coletorretorno/inicio.php">
                                          <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Retorno"></input>
                                         </form>' : '';




$triagemEstoque = ($permUsu == '1') ? '       <form class="form-btn" action="ColetorTriagemEstoque/bip1.php">
                                          <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Triagem"></input>
                                         </form>' : '';
$rotExp = ($permUsu == '1') ? '       <form class="form-btn" action="ColetorRotExp/bip1.php">
                                         <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Triagem"></input>
                                        </form>' : '';
$entCorr = ($permUsu == '1') ? '       <form class="form-btn" action="ColetorEntCorr/bip1.php">
                                        <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Triagem"></input>
                                       </form>' : '';
$entLatam = ($permUsu == '1') ? '       <form class="form-btn" action="ColetorEntLatam/bip1.php">
                                       <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Triagem"></input>
                                      </form>' : '';



$expedicao = ($permChat == '1') ? '    <form class="form-btn" action="coletorexpedicao/inicio.php">
                                          <input type="submit" id="inserir" class="btn btn-outline-secondary" value="Expedição"></input>
                                         </form>' : '';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

  <title>DATABIT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/cover/">
  <!-- Bootstrap core CSS -->
  <link href="dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="cover.css" rel="stylesheet">
  <link rel="stylesheet" href="CSS/styleExibicao.css">
</head>

<body class="text-center">

  <div class="card"
    style="background-color: white; width: auto; margin-left: 2%; margin-right: 2%; margin-top: 2%; border-radius: 30px;">
    <form action="login.php"><button class="btn-voltar">SAIR</button></form>
    <img src="coletorconferencia/media/logo.png" width="100" height="80" style="margin-left: 8px; border-radius:20px;"
      class="d-inline-block align-top" alt="">
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column container-button">
      <header class="masthead mb-auto">
        <div class="inner">
          <h2 class="masthead-brand">COLETORES</h2>
        </div>
      </header>

      <div style="height: 30px; line-height: 10px; font-size: 8px;">&nbsp;</div>
      <main role="main" class="inner cover">
        <div class="divBtn">
          <?php print ($conferencia); ?>

          <?php print ($retornoExp); ?>

          <?php print ($retorno); ?>

          <?php print ($triagemEstoque); ?>

          <?php print ($rotExp); ?>

          <?php print ($entCorr); ?>
          
          <?php print ($entLatam); ?>

          <?php print ($expedicao); ?>
        </div>
      </main>
    </div>
  </div>
</body>

</html>