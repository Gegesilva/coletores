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
		echo "<script>location.href='http://localhost:8090/phpprod/positiva/Coletorpositiva/login.php'</script>"; 
		
	  } 


	$orcamento = $_POST['orcamento'];
    $produto = $_POST['produto'];


	/* CONFERE SE O PRODUTO POSSUI SÉRIE */
	$sql0 = "
		SELECT TB01010_NUMSERIE PosSerie FROM TB01010
		WHERE TB01010_CODIGO = '$produto'
		";
	$stmt0 = sqlsrv_query($conn, $sql0);

		if($stmt0 === false){
			die(print_r(sqlsrv_errors(), true));
		}
		while($row0 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){
			$posSerie = $row0['PosSerie'];
		}

		if($posSerie == "N"){
			$numserie = "'N'";
		}else{
			$numserie = 'NULL';
		}

	/* CONFERE SE A QUNTIDADE DE PRODUTOS DO PEDIDO FOI ALCANÇADA */
	$sql1 = 
	"
	SELECT 
		1 qtde
	WHERE
		(SELECT COUNT(PRODUTO) FROM GS_CONFERE WHERE PRODUTO = '$produto' AND CODIGO = '$orcamento') 
	  < (SELECT CAST(TB02019_QTPROD AS INT) FROM TB02019 WHERE TB02019_PRODUTO = '$produto' AND TB02019_CODIGO = '$orcamento')
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
		if($stmt1 === false)
		{
			die (print_r(sqlsrv_errors(), true));
		}
		
		while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
			$Qtde = $row1['qtde'];
		}


    /* CONFERE SE O PRODUTO ESTÁ NO PEDIDO */
	$sql1 = 
	"
	SELECT 
		1 Existe
	WHERE
		EXISTS (SELECT TB02019_PRODUTO FROM TB02019 WHERE TB02019_PRODUTO = '$produto' AND TB02019_CODIGO = '$orcamento')
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
		if($stmt1 === false)
		{
			die (print_r(sqlsrv_errors(), true));
		}

		while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
			$existe = $row1['Existe'];
		}


	if($existe == 1 && $Qtde == 1){
		$sql = 
		"
			INSERT INTO GS_CONFERE (CODIGO,
									PRODUTO,
									SERIE,
									QTDE)
						VALUES('$orcamento', '$produto', $numserie, 1)
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
								TB02019_CODIGO orcamento,
								TB02019_PRODUTO CodProd,
								TB01010_REFERENCIA Ref,
								TB01010_CODBARRAS CodBarras,
								CAST(TB02019_QTPROD AS INT) Qtdeorcamento,
								TB01010_NOME NomeProd,
								(SELECT SUM(QTDE) FROM GS_CONFERE WHERE CODIGO = TB02019_CODIGO AND PRODUTO = TB02019_PRODUTO) Qtde,
								TB01010_NUMSERIE PosSerie
							FROM TB02019
							LEFT JOIN TB01010 ON TB01010_CODIGO = TB02019_PRODUTO

							WHERE TB02019_CODIGO = '$orcamento'

							GROUP BY
								TB02019_PRODUTO,
								TB02019_CODIGO,
								TB01010_NOME,
								TB01010_REFERENCIA,
								TB01010_CODBARRAS,
								TB02019_QTPROD,
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
									<th scope="col" style="width:;">QTDE ORC.</th>                                   
									<th scope="col" style="width:;">QTDE CONF.</th>                                  
									</tr>
								</thead>
						<?php
						$tabela = "";
						$cont = 0;
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
						{
						$cont++;
						if($row['Qtde'] == $row['Qtdeorcamento']){
							$cor = '#90EE90';
						}else{
							$cor = '#FFA07A';
						}
						$tabela .= "<tr>";
						$tabela .= "<td>".$row['Ref']."</td>";
						$tabela .= "<td>".$row['CodProd']."</td>";
						$tabela .= "<td>".$row['CodBarras']."</td>";
						$tabela .= "<td>".$row['NomeProd']."</td>";
						$tabela .= "<td style='background: $cor;'>".$row['Qtdeorcamento']."</td>";
                        
						if($row['PosSerie'] == 'N'){
							$tabela .= "<td style='background: $cor;'>
									<form class='formQtde' id='form-qt$cont' name='form-qt$cont'>
										<input type='hidden' id='produto$cont' name='produto$cont' value='$row[CodProd]'>
										<input type='hidden' id='orcamento$cont' name='orcamento$cont' value='$row[orcamento]'>
										<input style='background: $cor;' class='input-qtde' type='number' id='qtde$cont' name='qtde$cont' value='$row[Qtde]'>
										<input onclick='window.location.reload();' class='btn-qtde' type='submit' name='salvar' id='salvar$cont' value='INSERIR'>
									</form>
										<script>
											$('#form-qt$cont').submit(function(e){
												e.preventDefault();  /*Interronpendo a atualização automatica da pagina*/ 
										
												var d_produto$cont = $('#produto$cont').val();
												var d_orcamento$cont = $('#orcamento$cont').val();
												var d_qtde$cont = $('#qtde$cont').val();
											
												let result = document.getElementById('resultados');
											
												console.log(d_produto$cont, d_orcamento$cont, d_qtde$cont);
												
											$.ajax({
												url: 'http://localhost:8090/phpprod/positiva/Coletorpositiva/updateqtdeprod.php',
												method: 'POST',
												data: {produto: d_produto$cont, orcamento: d_orcamento$cont, qtde: d_qtde$cont},
												/* dataType: 'json' */
											}).done(function(result){
												console.log(result);
												resultados.innerHTML = result;
											});
										});
										</script>
								</td>";
						}else{
						     $tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
							}
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
		echo "<h1 style='color: white; margin-top: 1%;'>ESTE PRODUTO NÃO ESTA NESTE ORÇAMENTO! </h1>";
	}
	?>