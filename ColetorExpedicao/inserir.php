<?php
     /* header('Content-Type: application/json'); */
     header('Content-type: text/html; charset=utf-8');
     include_once("conexaoSQL.php");

	/* VALIDA USUARIO */
	session_start();
	$login = $_SESSION["login"];
	$senha = $_SESSION["password"];

	$pedido = $_POST['pedido'];
	$codmoto = $_POST['codmoto'];
	$nome = $_POST['nome'];

		$sql="SELECT 
		TB01066_USUARIO Usuario,
		TB01066_SENHA Senha,
        TB01066_CHAT permChat
	   FROM 
		TB01066
	   WHERE 
	   TB01066_USUARIO = '$login'
	   AND TB01066_SENHA = '$senha'";
	$stmt= sqlsrv_query($conn,$sql);
	  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$usuario = $row['Usuario'];
		$senha = $row['Senha'];
		$permChat = $row['permChat'];
	  }
	  if($usuario != NULL && $permChat == '1'){
  
	  }else { 
		echo"<script>window.alert('É necessário fazer login!')</script>";
		echo "<script>location.href='../login.php'</script>"; 
		
	  } 


	/* VALIDA PEDIDO */
		$sql4="SELECT TB02021_STATUS status FROM TB02021
		WHERE TB02021_CODIGO = '$pedido'";

		$stmt4= sqlsrv_query($conn,$sql4);
		while($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)){
		$status = $row4['status'];
		}
		if($status == 'U6'){

		}else{
			echo"<h1 class='resultado'>O pedido <b>$pedido</b> não está em um status válido!</h1>";
			return;
		}

	/* ALTERA O STATUS */
	$sql = "
	INSERT INTO TB02096
			(TB02096_CODIGO,
			TB02096_TIPO,
			TB02096_MOTORISTA,
			TB02096_CODMOTO, 
			TB02096_DTENTREGA)
			VALUES (
					'$pedido',
					'V',
					'$nome',
					'$codmoto',
					GETDATE()
					)


		UPDATE TB02021 
		SET 
			TB02021_STATUS = '92',
			TB02021_DTALT = GETDATE(),
			TB02021_OPALT = '$login'
		WHERE TB02021_CODIGO = '$pedido'


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
					'$pedido',
					'$login',
					'APP COLETOR EXP',
					GETDATE(), 
					GETDATE(), 
					'92',
					'V',
					TB01024_NOME,
					TB02021_CODCLI,
					TB02021_CODEMP,
					(SELECT TB01021_NOME FROM TB01021 WHERE TB01021_CODIGO = '92'),
					TB02021_CODTEC
					FROM TB02021
					LEFT JOIN TB01024 ON TB01024_CODIGO = TB02021_CODTEC
					WHERE TB02021_CODIGO = '$pedido'
				) 
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
	<div style="color: white; margin-top: 1%;" class="resultados" id="resultados">
				<div class="card overflow-auto" style="margin-left: 0%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
					<div class="card-header" style="background-color: #caec4f;"></div>
					<?php
						include_once("conexaoSQL.php");
						$sql = 
						"
							SELECT
								TB02021_CODIGO Pedido,
								TB02021_CODCLI CodCli,
								TB01008_NOME NomeCli,
								TB02021_STATUS Status
							FROM TB02096
							LEFT JOIN TB02021 ON TB02021_CODIGO = TB02096_CODIGO
							LEFT JOIN TB01008 ON TB01008_CODIGO = TB02021_CODCLI
							WHERE TB02096_CODMOTO = '$codmoto'
							AND TB02021_STATUS = '92'
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
										<th scope="col" style="width:;">PEDIDO</th>
										<th scope="col" style="width:;">COD. CLIENTE</th>                                   
										<th scope="col" style="width:;">NOME CLIENTE</th>                                                                   
										<th scope="col" style="width:;">STATUS</th>                                                                   
									</tr>
								</thead>
						<?php
						$tabela = "";
						while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
						{
						$tabela .= "<tr>";
						$tabela .= "<td>".$row['Pedido']."</td>";
						$tabela .= "<td>".$row['CodCli']."</td>";
						$tabela .= "<td>".$row['NomeCli']."</td>";
						$tabela .= "<td>".$row['Status']."</td>";
						$tabela .= "</tr>";
						}
							$tabela .= "</table>";
							print($tabela);
						?>                                                                           
					</div>
		<script src="js/jQuery/jquery-3.5.1.min.js"></script>
		<script src="js/script.js"></script>
		</body>
	</html>   