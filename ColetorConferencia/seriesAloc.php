<?php
  header('Content-type: text/html; charset=utf-8');
  include "conexaoSQL.php";

  /* VALIDA USUARIO */
  session_start();
     $login = $_SESSION["login"];
     $senha = $_SESSION["password"];
         $sql="SELECT 
         TB01066_USUARIO Usuario,
         TB01066_SENHA Senha
        FROM 
         TB01066
        WHERE 
        TB01066_USUARIO = '$login'
        AND TB01066_SENHA = '$senha'";
     $stmt= sqlsrv_query($conn,$sql);
       while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
         $usuario = $row['Usuario'];
         $senha = $row['Senha'];
       }
       if($usuario != NULL){
   
       }else { 
         echo"<script>window.alert('É necessário fazer login!')</script>";
         echo "<script>location.href='http://databitbh.com:51230/coletores/login.php'</script>"; 
         
       } 


  $orcamento = "'".$_POST['orcamento']."'";
  $produto = "'".$_POST['produto']."'";

  $orcamentoSemAspas = $_POST['orcamento'];
  $produtoSemAspas = $_POST['produto'];


  if($produto == NULL || $produto == "''"){
    $produto = 'SELECT DISTINCT PRODUTO FROM GS_CONFERE';
  }
  else{

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
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/itensAloc.css">

    <title>DATABIT</title>
  </head>
<script>
          function pergunta1(){ 
          // Deletar Produto
          return confirm('Tem certeza que quer deseja deletar esta série?');
                }
          function pergunta2(){ 
          // 
          return confirm('?');
                }
</script>
<body>
      <script src="js/jQuery/jquery-3.5.1.min.js" charset="utf-8"></script>
      <script src="js/script.js"></script>
<!-- example 2 - using auto margins -->
  <nav class="nav">       
        <form method="post" action="http://databitbh.com:51230/coletores/Coletorconferencia/itensalocados.php" class="form-inicial">
            <b><p class="titulo" >SÉRIES</p></b>
            <?php echo "<input class='inputcod' type='hidden' name='orcamento'  id='orcamento' value='$orcamentoSemAspas'>"?>
            <?php echo "<input class='inputcod' type='hidden' name='produto' id='produto' value='$produtoSemAspas'>"?>
            <input type="submit" class="voltar" value="VOLTAR">
        </form> 
   </nav>
   <br>


<!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
<div class="row" style="max-width: 100%; margin-top: 1%;">

    <div>

       <div class="card overflow-auto" style="margin-left: 2%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
          <div class="card-header" style="background-color: #87CEFA;">
            <form id="form-status">
              SÉRIES
              <!-- <input type="submit" class="btn-status" value="EFETIVAR CONF.">  --> 
            </form>
        </div>

          <div>

          <?php
              $numSerie = $_POST['numSerie'];
              include_once("conexaoSQL.php");
              $sql = 
              "
              SELECT
                CODIGO CODIGO,
                PRODUTO Prod,
                TB01010_NOME NomeProd,
                TB02055_NUMSERIE Serieorcamento,
                SERIE Serie
              FROM 
                GS_CONFERE
                LEFT JOIN TB01010 ON TB01010_CODIGO = PRODUTO
                LEFT JOIN TB02055 ON TB02055_CODIGO = CODIGO AND TB02055_PRODUTO = PRODUTO AND TB02055_NUMSERIE = SERIE
              WHERE
                CODIGO IN ($orcamento)
              AND PRODUTO IN ($produto)
              
              ";
            $stmt = sqlsrv_query($conn, $sql);
              
              if($stmt === false)
              {
                die (print_r(sqlsrv_errors(), true));
              }
            ?>            
                  <table class="table table-border table-sm" style="font-size: 11px;">
                      <thead>
                        <tr>
                          <th scope="col">COD. orcamento</th>
                          <th scope="col" style="width:;">PRODUTO</th>
                          <th scope="col" style="width:;">DESCRIÇÃO</th>
                          <th scope="col" style="width:;">SÉRIES CONF.</th>                                   
                          <th scope="col" style="width:;">SÉRIES ORC.</th>                                   
                          <th scope="col" style="width:;"></th>                                   
                          <th scope="col" style="width:;"></th>                                   
                        </tr>
                      </thead>
            <?php
            $tabela = "";
            $cont = 0;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
            {
              $cont++;
              if($row['Serieorcamento'] == $row['SERIE']){
                $cor = '#FFA07A';
                $del = 1;
              }else{
                $cor = '#90EE90';
                $del = 2;
              }
              $tabela .= "<tr>";
              $tabela .= "<td>".$row['CODIGO']."</td>";
              $tabela .= "<td>".$row['Prod']."</td>";
              $tabela .= "<td>".$row['NomeProd']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['Serie']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['Serieorcamento']."</td>";

              if($cor == '#FFA07A'){
            
              $tabela .= "<td>
                          <form id='form-del$cont' name='form-del$cont'>
                            <input type='hidden' id='produto$cont' name='produto$cont' value='$row[Prod]'/>
                            <input type='hidden' id='orcamento$cont' name='orcamento$cont' value='$row[CODIGO]'>
                            <input type='hidden' id='serie$cont' name='serie$cont' value='$row[Serie]'>
                            <input onclick='window.location.reload();' class='btn-deletar' type='submit' name='deletar' id='deletar$cont' value='Deletar'/>

                            <script>
                                  $('#form-del$cont').submit(function(e){
                                      e.preventDefault();   /*Interronpendo a atualização automatica da pagina*/
                              
                                    var d_produto$cont = $('#produto$cont').val();
                                    var d_orcamento$cont = $('#orcamento$cont').val();
                                    var d_serie$cont = $('#serie$cont').val();
                                
                                    let result$cont = document.getElementById('resultados');
                                
                                    console.log(d_produto$cont, d_orcamento$cont, d_serie$cont);
                              
                                  $.ajax({
                                      url: 'http://databitbh.com:51230/coletores/Coletorconferencia/deleteSerie.php',
                                      method: 'POST',
                                      data: {produto: d_produto$cont, orcamento: d_orcamento$cont, serie: d_serie$cont},
                                      dataType: 'json'
                                  }).done(function(result$cont){
                                      console.log(result$cont);
                                      resultados.innerHTML = result$cont;
                                  });
                              });
                            </script>
                          </form>
                          </td>";
                            }
                            else{
                              $tabela .= "<td>  </td>";
                            }
            $tabela .= "</tr>";

            }
              $tabela .= "</table>";
              
            print($tabela);
            ?>                                                                           

          </div>
          </br>
    </div>
    <div style="color: white;"  id="resultados"></div>
</body>
</html>
