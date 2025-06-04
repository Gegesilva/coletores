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
         echo "<script>location.href='http://databitbh.com:51230/coletores/login.php'</script>"; 
         
       } 

  
  $orcamento = $_POST['orcamento'];
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
        <form method="post" action="http://databitbh.com:51230/coletores/coletorconferencia/bip1.php" class="form-inicial">

            <b><p class="titulo" >ITENS</p></b>

            <?php echo "<input class='inputcod' type='hidden' name='orcamento' autofocus='true' value='$orcamento'></br>";?>
            <b></a><input type="submit" class="voltar" value="VOLTAR"></b>
      </form> 
     <br>


<!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
<div class="row" style="max-width: 100%; margin-top: 1%; margin-left: 2px;">

    <div>

       <div class="card overflow-auto" style="margin-left: 0%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
          <div class="card-header" style="background-color: #87CEFA;">
          <form action="http://databitbh.com:51230/coletores/coletorconferencia/seriesAloc.php" method='post'>
            <div class="status">
                PRODUTOS  
                <?php echo "<input class='btn-inserir' type='hidden' name='orcamento' value='$orcamento'/>"; ?>          
                <button style="float: right;" class="btn-conftd" type="submit">CONF. TODOS</button>        
            </div>
          </form>
        </div>
        <div>

             <!-- VALIDA SE A QUANTIDADE DE PRODUTOS ESTA DE ACORDO COM o orcamento -->
          <?php

              $sql1 = 
              "
              SELECT 1 Completo
                WHERE
                (SELECT SUM(QTDE) FROM GS_CONFERE WHERE CODIGO = '$orcamento') = (SELECT SUM(TB02019_QTPROD) FROM TB02019 WHERE TB02019_CODIGO = '$orcamento')
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


             
              /* VALIDA SE TODAS SERIES FORAM CONFERIDAS */
              $sql3 = "
              SELECT TOP 1
                     1 FaltaSerie
                   FROM GS_CONFERE
                   WHERE
                     EXISTS (SELECT SERIE FROM GS_CONFERE WHERE SERIE IS NULL AND CODIGO = '$orcamento')
               ";
               $stmt3 = sqlsrv_query($conn, $sql3);
             
               while($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)){
                     $faltaSerie = $row3['FaltaSerie'];
               }



               /* VALIDA SE EXISTEM SERIES DIFERENTES */
              $sql4 = "
                  SELECT TB02055_NUMSERIE SerieDif FROM TB02055 WHERE TB02055_CODIGO = '$orcamento'
                  EXCEPT
                  SELECT SERIE FROM GS_CONFERE WHERE CODIGO = '$orcamento'
               ";
               $stmt4 = sqlsrv_query($conn, $sql4);
             
               while($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)){
                     $SerieDif = $row4['SerieDif'];
               }
            

             /* VALIDA SE O ORÇAMENTO JÁ FOI CONFERIDO */
             $sql0 = "
                SELECT TB02018_STATUS status FROM TB02018
                WHERE TB02018_CODIGO = '$orcamento'
            ";
            $stmt0 = sqlsrv_query($conn, $sql0);
         
            while($row0 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){
                 $status = $row0['status'];
            }
          
               if($status == "E6" || $status == "B0" || $status == "B8" || $status == "C3" || $faltaSerie == 1 || $QtdeCorreta == 0 || $SerieDif != NULL){
                 $botaoConf .= "<h4 style = 'color: red;'>Existem erros confira, as séries e(ou) os produtos.</h4>
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
                CAST((SELECT SUM(QTDE) FROM GS_CONFERE WHERE SERIE IS NULL AND CODIGO = '$orcamento'  AND PRODUTO = TB01010_CODIGO) AS NUMERIC) Inserir,
                ISNULL(CAST((SELECT TB02019_QTPROD FROM TB02019 WHERE TB02019_CODIGO = CODIGO AND TB02019_PRODUTO = PRODUTO) AS INT), 0) Qtdeorcamento
              FROM 
                GS_CONFERE
                LEFT JOIN TB01010 ON TB01010_CODIGO = PRODUTO
              WHERE
                CODIGO = '$orcamento'
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
              if($row['Qtde'] == $row['Qtdeorcamento']){
                $cor = '#90EE90';
              }else{
                $cor = '#FFA07A';
              }
              $tabela .= "<tr>";
              $tabela .= "<td>".$row['CODIGO']."</td>";
              $tabela .= "<td>".$row['Prod']."</td>";
              $tabela .= "<td>".$row['NomeProd']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['Qtdeorcamento']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
              if($row['PosSerie'] == 'S' && $row['Inserir'] > 0){
                $tabela .= "<td>
                              <form action='http://databitbh.com:51230/coletores/coletorconferencia/bip2.php' method='post'>
                                <input type='hidden' id='produto' name='produto' class='btn-inserir' value='$row[Prod]'>
                                <input type='hidden' id='produto' name='orcamento' class='btn-inserir' value='$orcamento'>
                                <input type='hidden' id='desc' name='desc' class='btn-inserir' value='$row[NomeProd]'>
                                <input type='submit' id='btn' class='btn-inserir' value='Inserir'>
                              </form>
                            </td>";
              }else{
                $tabela .= "<td>  </td>";
              }

              $tabela .= "<td>
                           <form id='form-del$cont' name='form-del$cont'>
                             <input type='hidden' id='produto$cont' name='produto$cont' value='$row[Prod]'/>
                             <input type='hidden' id='orcamento$cont' name='orcamento$cont' value='$row[CODIGO]'>
                             <input  onclick='window.location.reload();' class='btn-deletar' type='submit' name='deletar' id='deletar$cont' value='DELETAR'/>

                             <script>
                                    $('#form-del$cont').submit(function(e){
                                      e.preventDefault();  /*Interronpendo a atualização automatica da pagina*/ 
                                
                                      var d_produto$cont = $('#produto$cont').val();
                                      var d_orcamento$cont = $('#orcamento$cont').val();
                                  
                                      let result$cont = document.getElementById('resultados');
                                  
                                      console.log(d_produto$cont, d_orcamento$cont);
                                
                                    $.ajax({
                                        url: 'http://databitbh.com:51230/coletores/coletorconferencia/deleteProd.php',
                                        method: 'POST',
                                        data: {produto: d_produto$cont, orcamento: d_orcamento$cont},
                                        dataType: 'json'
                                    }).done(function(result$cont){
                                        console.log(result$cont);
                                        resultados.innerHTML = result$cont;
                                    });
                                });
                             </script>
                            </form>
                           </td>";

                  /* CONFERIR SERIE */
                  if($row['PosSerie'] == 'S' /* && $row['Inserir'] == 0 */){
                  $tabela .= "<td>
                                <form action='http://databitbh.com:51230/coletores/coletorconferencia/seriesAloc.php' method='post'>
                                  <input type='hidden' id='produto' name='produto' value='$row[Prod]'/>
                                  <input type='hidden' id='orcamento' name='orcamento' value='$row[CODIGO]'>
                                  <input class='btn-deletar' type='submit' name='conferir' id='conferir' value='CONF.'/>
                                </form>
                              </td>";
                  $tabela .= "</tr>";
                          }
                          else{
                            $tabela .= "<td>  </td>";
                          }
                  }
                  $tabela .= "</table>";
                print($tabela);
            ?>                                                                           
          </div>
        </br>
    </div>
    </br>
    <form action="http://databitbh.com:51230/coletores/coletorconferencia/atualStatus.php" method="post">
      <?php echo "<input type='hidden' id='orcamento' name='orcamento' class='btn-status' value='$orcamento'>";?>
      <?php print($botaoConf);?>
    </form>
    <h1 class="mensagem"><?php echo $faltaProd;?></h1>
    <div style="color: white;"  id="resultados"></div>
</body>
</html>
