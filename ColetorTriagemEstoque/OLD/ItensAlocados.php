<?php
  header('Content-type: text/html; charset=utf-8');

  session_start();
  $venda = $_POST['venda'];
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
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/itensAloc.css">

    <title>COPIMAQ</title>
  </head>
<script>
          function pergunta1(){ 
          // Deletar Produto
          return confirm('Tem certeza que quer deseja deletar este produto?');
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
        <form method="get" action="http://localhost:8090/phpprod/dataconfere/coletor/bip1.php" class="form-inicial">

            <b><label class="titulo" >ITENS ALOCADOS</label></b>

            <?php echo "<input class='inputcod' type='hidden' name='venda' autofocus='true' value='$venda'></br>"?>
            <b></a><input type="submit" class="voltar" value="VOLTAR"></b>
      </form> 
              
   </nav>
   <br>


<!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
<div class="row" style="max-width: 100%; margin-top: 1%;">

    <div>

       <div class="card overflow-auto" style="margin-left: 2%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
          <div class="card-header" style="background-color: #87CEFA;">
            <form id="form-status">
              PRODUTOS
              <input type="submit" class="btn-status" value="EFETIVAR CONF.">  
            </form>
        </div>

          <div>

          <?php
              $numSerie = $_POST['numSerie'];
              include_once("conexaoSQL.php");
              $sql = 
              "
              SELECT
                CODVENDA CodVenda,
                PRODUTO Prod,
                COUNT(PRODUTO) Qtde,
                TB01010_NUMSERIE PosSerie,
                TB01010_NOME NomeProd,
                CAST((SELECT COUNT(PRODUTO) FROM GS_CONFERE WHERE SERIE IS NULL AND CODVENDA = '$venda'  AND PRODUTO = TB01010_CODIGO) AS NUMERIC) Inserir,
                CAST((SELECT TB02022_QTPROD FROM TB02022 WHERE TB02022_CODIGO = CODVENDA AND TB02022_PRODUTO = PRODUTO) AS INT) QtdeVenda
              FROM 
                GS_CONFERE
                LEFT JOIN TB01010 ON TB01010_CODIGO = PRODUTO
              WHERE
                CODVENDA = '$venda'
              GROUP BY
                CODVENDA,
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
                          <th scope="col">COD. VENDA</th>
                          <th scope="col" style="width:;">PRODUTO</th>
                          <th scope="col" style="width:;">DESCRIÇÃO</th>
                          <th scope="col" style="width:;">QTDE CONF.</th>                                   
                          <th scope="col" style="width:;">QTDE VENDA</th>                                   
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
              $cont2++;
              if($row['Qtde'] == $row['QtdeVenda']){
                $cor = '#90EE90';
              }else{
                $cor = '#FFA07A';
              }
              $tabela .= "<tr>";
              $tabela .= "<td>".$row['CodVenda']."</td>";
              $tabela .= "<td>".$row['Prod']."</td>";
              $tabela .= "<td>".$row['NomeProd']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
              $tabela .= "<td style='background: $cor;'>".$row['QtdeVenda']."</td>";
              if($row['PosSerie'] == 'S' && $row['Inserir'] > 0){
                $tabela .= "<td>
                              <form action='http://localhost:8090/phpprod/dataconfere/coletor/bip2.php' method='get'>
                                <input type='hidden' id='produto' name='produto' class='btn-inserir' value='$row[Prod]'>
                                <input type='hidden' id='produto' name='venda' class='btn-inserir' value='$venda'>
                                <input type='submit' id='btn' class='btn-inserir' value='Inserir'>
                              </form>
                            </td>";
              }else{
                $tabela .= "<td>  </td>";
              }

              $tabela .= "<td>
                           <form id='form-del$cont' name='form-del$cont'>
                             <input type='hidden' id='produto$cont' name='produto$cont' value='$row[Prod]'/>
                             <input type='hidden' id='venda$cont' name='venda$cont' value='$row[CodVenda]'>
                             <input onclick='return pergunta1();' class='btn-deletar' type='submit' name='deletar' id='deletar$cont' value='DELETAR'/>

                             <script>
                                    $('#form-del$cont').submit(function(e){
                                      e.preventDefault();  /*Interronpendo a atualização automatica da pagina*/ 
                                
                                      var d_produto$cont = $('#produto$cont').val();
                                      var d_venda$cont = $('#venda$cont').val();
                                  
                                      let result$cont = document.getElementById('resultados');
                                  
                                      console.log(d_produto$cont, d_venda$cont);
                                
                                    $.ajax({
                                        url: 'http://localhost:8090/phpprod/dataconfere/coletor/deleteProd.php',
                                        method: 'POST',
                                        data: {produto: d_produto$cont, venda: d_venda$cont},
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
                           <form id='form-conf$cont2' name='form-conf$cont2'>
                             <input type='hidden' id='produto$cont2' name='produto$cont2' value='$row[Prod]'/>
                             <input type='hidden' id='venda$cont2' name='venda$cont2' value='$row[CodVenda]'>
                             <input class='btn-deletar' type='submit' name='conferir' id='conferir$cont2' value='CONF.'/>

                             <script>
                                    $('#form-conf$cont2').submit(function(e){
                                      e.preventDefault(); /*Interronpendo a atualização automatica da pagina*/ 
                                
                                      var d_produto$cont2 = $('#produto$cont2').val();
                                      var d_venda$cont2 = $('#venda$cont2').val();
                                  
                                      let result$cont2 = document.getElementById('resultados');
                                  
                                      console.log(d_produto$cont2, d_venda$cont2);
                                
                                    $.ajax({
                                        url: 'http://localhost:8090/phpprod/dataconfere/coletor/seriesAloc.php',
                                        method: 'post',
                                        data: {produto: d_produto$cont2, venda: d_venda$cont2},
                                        /* dataType: 'json' */
                                    }).done(function(result$cont2){
                                        console.log(result$cont2);
                                        resultados.innerHTML = result$cont2;
                                    });
                                });
                             </script>
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
    <div style="color: white;"  id="resultados"></div>
</body>
</html>
