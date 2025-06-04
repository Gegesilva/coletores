<?php
     /* header('Content-Type: application/json'); */
     header('Content-type: text/html; charset=utf-8');
     include_once("conexaoSQL.php");

	/* VALIDA USUARIO */
	session_start();
	$login = $_SESSION["login"];
	$senha = $_SESSION["password"];
	$pedido = $_POST['pedido'];

		$sql="SELECT 
		TB01066_USUARIO Usuario,
		TB01066_SENHA Senha,
        TB01066_USUARIOS permUsu
	   FROM 
		TB01066
	   WHERE 
	   TB01066_USUARIO = '$login'
	   AND TB01066_SENHA = '$senha'";
	$stmt= sqlsrv_query($conn,$sql);
	  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$usuario = $row['Usuario'];
		$senha = $row['Senha'];
		$permUsu = $row['permUsu'];
	  }
	  if($usuario != NULL && $permUsu == '1'){
  
	  }else { 
		echo"<script>window.alert('É necessário fazer login!')</script>";
		echo "<script>location.href='../login.php'</script>"; 
		
	  } 


   /* VALIDA PEDIDO */
   $sql3="
		SELECT TB02021_STATUS status FROM TB02021
		WHERE TB02021_CODIGO = '$pedido'
	";
	$stmt3= sqlsrv_query($conn,$sql3);
	while($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)){
	$status = $row3['status'];
	}
	if($status == 'K1'){
	
	}else{
		echo"<h1 class='resultado'>O pedido <b>$pedido</b> não está em um status válido!</h1>";
		return;
	}

	/* ALTERA O STATUS */
	$sql = "
			UPDATE TB02021 
			SET 
				TB02021_STATUS = 'K1',
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
						'APP COLETOR TRI',
						GETDATE(), 
						GETDATE(), 
						'K1',
						'V',
						TB01024_NOME,
						TB02021_CODCLI,
						TB02021_CODEMP,
						(SELECT TB01021_NOME FROM TB01021 WHERE TB01021_CODIGO = 'U6'),
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
	<div>
		<h1 class='resultado2'>O status do pedido <b><?php echo $pedido; ?></b> foi alterado com sucesso!</h1>                                                                          
	</div>
		<script src="js/jQuery/jquery-3.5.1.min.js"></script>
		<script src="js/script.js"></script>
		</body>
	</html>   