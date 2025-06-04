<?php
  header('Content-type: text/html; charset=utf-8');
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
  $produto = $_POST['produto'];
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

    <title>DATABIT</title>
  </head>
<script>
    function pergunta1(){ 
    // Deletar Produto
    return confirm('Tem certeza que quer deseja deletar este produto?');
          }
    function pergunta2(){ 
    // 
    return confirm('Tem ceterza que efetivar a conferência?');
          }
</script>
<body>
      <script src="js/jQuery/jquery-3.5.1.min.js" charset="utf-8"></script>
      <script src="js/script.js"></script>
<!-- example 2 - using auto margins -->      
        
     <br>

<!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
<div class="row" style="max-width: 100%; margin-top: 1%; margin-left: 2px;">
  <div>
      <div class="card overflow-auto" style="margin-left: 0%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px; ">
        <div class="card-header" style="background-color: #87CEFA; display: flex;">
        <p class="titulo" >ITENS</p>
        <form method="post" action="http://databitbh.com:51230/coletores/coletorretornoexp/bip1.php" class="form-inicial">
          <?php echo "<input class='inputcod' type='hidden' name='pedido' autofocus='true' value='$pedido'></br>";?>
          <input type="submit" class="voltar" value="VOLTAR">
        </form> 
      </div>
      <div>
             <!-- VALIDA SE A QUANTIDADE DE PRODUTOS ESTA DE ACORDO COM o pedido -->
          <?php

              $sql1 = 
              "
              SELECT 1 Completo
              WHERE
                (SELECT SUM(QTDE) FROM GS_RETORNO LEFT JOIN TB01010 ON TB01010_CODIGO = PRODUTO WHERE CODIGO = '$pedido' AND TB01010_RETORNO = 'S') 
              = (SELECT SUM(TB02022_QTPROD) FROM TB02022 LEFT JOIN TB01010 ON TB01010_CODIGO = TB02022_PRODUTO WHERE TB02022_CODIGO = '$pedido' AND TB01010_RETORNO = 'S')
              ";
              $stmt1 = sqlsrv_query($conn, $sql1);
                
                if($stmt1 === false)
                {
                  die (print_r(sqlsrv_errors(), true));
                }
              
              while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
                $qt = $row1['Completo'];

                if($qt == 1){
                  $QtdeCorreta = 1;
                }
              }
                if($qt != 1){
                  $QtdeCorreta = 0;
                  /* $faltaProd = "FALTAM PRODUTOS!"; */
                }


             /* VALIDA SE O PEDIDO JÁ FOI CONFERIDO */
             $sql0 = "
              SELECT TB02216_STATUS status FROM TB02216
              WHERE TB02216_CODIGO = '$pedido'
            ";
            $stmt0 = sqlsrv_query($conn, $sql0);
         
            while($row0 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){
                 $status = $row0['status'];
            }
          
               if($status == "26" || $QtdeCorreta == 0){
                 $botaoConf .= "<h4 style = 'color: red;'>Existem erros confira os produtos.</h4>
                                <h4 style = 'color: red;'>Ou a conferência já foi realizada.</h4>";
              }else{
                $botaoConf .= "<input onclick='return pergunta2();' type='submit' class='btn-status' id='efetivar' value='EFETIVAR CONF.'>";
               }
            ?>
            
          <?php
              $sql = 
              "
              SELECT
                CODIGO CODIGO,
                PRODUTO Prod,
                SUM(QTDE) Qtde,
                TB01010_NUMSERIE PosSerie,
                TB01010_NOME NomeProd,
                CAST((SELECT SUM(QTDE) FROM GS_RETORNO WHERE SERIE IS NULL AND CODIGO = '$pedido'  AND PRODUTO = TB01010_CODIGO) AS NUMERIC) Inserir,
                ISNULL(CAST((SELECT TB02022_QTPROD FROM TB02022 WHERE TB02022_CODIGO = CODIGO AND TB02022_PRODUTO = PRODUTO) AS INT), 0) Qtdepedido
              FROM 
                GS_RETORNO
                LEFT JOIN TB01010 ON TB01010_CODIGO = PRODUTO
              WHERE
                CODIGO = '$pedido'
              GROUP BY
                CODIGO,
                PRODUTO,
                TB01010_NUMSERIE,
                TB01010_NOME,
                TB01010_CODIGO
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
                      <th scope="col">COD. ORC</th>
                      <th scope="col" style="width:;">PRODUTO</th>
                      <th scope="col" style="width:;">DESCRIÇÃO</th>
                      <th scope="col" style="width:;">QTDE ORC</th>                                   
                      <th scope="col" style="width:;">QTDE CONF.</th>                                   
                      <th scope="col" style="width:;">SÉRIES</th>                                   
                    </tr>
                  </thead>
            <?php
              $tabela = "";
              $cont = 0;
              $cont2 = 1;
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
              {
                $cont++;
                if($row['Qtde'] == $row['Qtdepedido']){
                  $cor = '#90EE90';
                }else{
                  $cor = '#FFA07A';
                }
                $tabela .= "<tr>";
                $tabela .= "<td>".$row['CODIGO']."</td>";
                $tabela .= "<td>".$row['Prod']."</td>";
                $tabela .= "<td>".$row['NomeProd']."</td>";
                $tabela .= "<td style='background: $cor;'>".$row['Qtdepedido']."</td>";
                $tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
          
                $tabela .= "<td>
                            <form id='form-del$cont' name='form-del$cont'>
                              <input type='hidden' id='produto$cont' name='produto$cont' value='$row[Prod]'/>
                              <input type='hidden' id='pedido$cont' name='pedido$cont' value='$row[CODIGO]'>
                              <input  onclick='window.location.reload();' class='btn-deletar' type='submit' name='deletar' id='deletar$cont' value='DELETAR'/>

                              <script>
                                      $('#form-del$cont').submit(function(e){
                                        e.preventDefault();  /*Interronpendo a atualização automatica da pagina*/ 
                                  
                                        var d_produto$cont = $('#produto$cont').val();
                                        var d_pedido$cont = $('#pedido$cont').val();
                                    
                                        let result$cont = document.getElementById('resultados');
                                    
                                        console.log(d_produto$cont, d_pedido$cont);
                                  
                                      $.ajax({
                                          url: 'http://databitbh.com:51230/coletores/coletorretornoexp/deleteProd.php',
                                          method: 'POST',
                                          data: {produto: d_produto$cont, pedido: d_pedido$cont},
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
                    $tabela .= "</table>";
                  print($tabela);
              ?>                                                                           
          </div>
        </br>
    </div>
    </br>
    <form action="http://databitbh.com:51230/coletores/coletorretornoexp/atualStatus.php" method="post">
      <?php echo "<input type='hidden' id='pedido' name='pedido' class='btn-status' value='$pedido'>";?>
      <?php print($botaoConf);?>
    </form>
    <h1 class="mensagem"><?php echo $faltaProd;?></h1>
    <div style="color: white;"  id="resultados"></div>
</body>
</html>
