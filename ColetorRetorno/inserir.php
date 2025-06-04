<?php
     /* header('Content-Type: application/json'); */
     header('Content-type: text/html; charset=utf-8');
     include_once("conexaoSQL.php");

	/* VALIDA USUARIO */
	session_start();
	$login = $_SESSION["login"];
	$senha = $_SESSION["password"];
		$sql="SELECT 
		TB01066_USUARIO Usuario,
		TB01066_SENHA Senha,
      	TB01066_GRAFICOS2 permGrafReq
	   FROM 
		TB01066
	   WHERE 
	   TB01066_USUARIO = '$login'
	   AND TB01066_SENHA = '$senha'";
	$stmt= sqlsrv_query($conn,$sql);
	  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$usuario = $row['Usuario'];
		$senha = $row['Senha'];
		$permGrafReq = $row['permGrafReq'];
	  }
	  if($usuario != NULL && $permGrafReq == '1'){
  
	  }else { 
		echo"<script>window.alert('É necessário fazer login!')</script>";
		echo "<script>location.href='../login.php'</script>"; 
		
	  } 


	$pedido = $_POST['pedido'];
    $produto = $_POST['produto'];


	
	/* RETORNO SE A QUNTIDADE DE PRODUTOS DO PEDIDO FOI ALCANÇADA */
	$sql1 = 
	"
	SELECT 
		1 qtde
	WHERE
		(SELECT COUNT(PRODUTO) FROM GS_RETORNO WHERE PRODUTO = '$produto' AND CODIGO = '$pedido' AND CODIGO = '$pedido' AND STATUS NOT IN ('21','22','23','24','01')) 
	  < (SELECT CAST(TB02022_QTPROD AS INT) FROM TB02022 WHERE TB02022_PRODUTO = '$produto' AND TB02022_CODIGO = '$pedido')
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
		if($stmt1 === false)
		{
			die (print_r(sqlsrv_errors(), true));
		}
		
		while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
			$Qtde = $row1['qtde'];
		}


    /* RETORNO SE O PRODUTO ESTÁ NO PEDIDO */
	$sql1 = 
	"
	SELECT 
		1 Existe
	WHERE
		EXISTS (SELECT TB02216_PRODUTO FROM TB02216 WHERE TB02216_PRODUTO = '$produto' AND TB02216_CODIGO = '$pedido')
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
		if($stmt1 === false)
		{
			die (print_r(sqlsrv_errors(), true));
		}

		while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
			$existe = $row1['Existe'];
		}


	if($existe == '1' && $Qtde == '1'){
		$sql = 
		"
		INSERT INTO GS_RETORNO (CODIGO,
								PRODUTO,
								QTDE,
								STATUS)
						(SELECT DISTINCT
							'$pedido',
							'$produto',
							1,
							TB02216_STATUS
						FROM TB02216
						WHERE TB02216_CODIGO = '$pedido')
		";
		$stmt = sqlsrv_query($conn, $sql);
	
	?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>DATABIT</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<!-- PAGINA VIA AJAX -->
				<div class="card overflow-auto" style="margin-left: 0%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
					<?php
						include_once("conexaoSQL.php");
						$sql = 
						"
						SELECT 
								TB02022_CODIGO Pedido,
								TB02022_PRODUTO CodProd,
								TB01010_REFERENCIA Ref,
								TB01010_CODBARRAS CodBarras,
								CAST(TB02022_QTPROD AS INT) QtdePedido,
								TB01010_NOME NomeProd,
								(SELECT SUM(QTDE) FROM GS_RETORNO WHERE CODIGO = TB02022_CODIGO AND PRODUTO = TB02022_PRODUTO AND STATUS NOT IN ('21','22','23','24','01')) Qtde,
								TB01010_NUMSERIE PosSerie
							FROM TB02022
							LEFT JOIN TB01010 ON TB01010_CODIGO = TB02022_PRODUTO

							WHERE TB02022_CODIGO = '$pedido'
							AND TB01010_RETORNO = 'S'

							GROUP BY
								TB02022_PRODUTO,
								TB02022_CODIGO,
								TB01010_NOME,
								TB01010_REFERENCIA,
								TB01010_CODBARRAS,
								TB02022_QTPROD,
								TB01010_NUMSERIE
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
									<th scope="col" style="width:;">REF.</th>
									<th scope="col" style="width:;">COD. PROD</th>                                   
									<th scope="col" style="width:;">COD. BARRAS</th>                                   
									<th scope="col" style="width:;">DESCRIÇÃO</th>                                   
									<th scope="col" style="width:;">QTDE PEDIDO</th>                                   
									<th scope="col" style="width:;">QTDE CONF.</th>                                  
									</tr>
								</thead>
						<?php
						$tabela = "";
						$cont = 0;
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
						{
						$cont++;
						if($row['Qtde'] == $row['QtdePedido']){
							$cor = '#90EE90';
						}else{
							$cor = '#FFA07A';
						}
						$tabela .= "<tr>";
						$tabela .= "<td>".$row['Ref']."</td>";
						$tabela .= "<td>".$row['CodProd']."</td>";
						$tabela .= "<td>".$row['CodBarras']."</td>";
						$tabela .= "<td>".$row['NomeProd']."</td>";
						$tabela .= "<td style='background: $cor;'>".$row['QtdePedido']."</td>";
                        $tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
						
						}
							$tabela .= "</table>";
							print($tabela);
						?>                                                                           
					</div>
					<script src="js/jQuery/jquery-3.5.1.min.js"></script>
					<script src="js/script.js"></script>
				 </body>
			  </html>
<?php
   }
	else{
		echo "<h1 style='color: white; margin-top: 1%;'>ESTE PRODUTO NÃO ESTA NESTE PEDIDO! </br> OU A QUANTIDADE JÁ FOI ATINGIDA.</h1>";
	}
	?>