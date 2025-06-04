<?php
  header('Content-type: text/html; charset=utf-8');

  /* VALIDA USUARIO */
  session_start();
     include "conexaoSQL.php";
     $login = $_SESSION["login"];
     $senha = $_SESSION["password"];

	 $codmoto = $_POST["codmoto"];
	 $nomemoto = $_POST["nome"];

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
         echo "<script>location.href='http://databitbh.com:51230/coletores/login.php'</script>"; 
       } 	   
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>DATABIT</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<section class="content">
		<div class="box_form">
			<h1>Motorista<button class="btn-voltar"><a class="btn-voltar" href="http://databitbh.com:51230/coletores/coletorexpedicao/motorista.php">VOLTAR</a> </button></h1>
			<form id="form1" class="form1">
				<label for="codmoto" class="label">Codigo</label>
    			<?php echo "<input type='text' name='codmoto' class='inputcod' id='codmoto' required  placeholder='Codigo do Motorista' disabled='' value='$codmoto'/>";?>
				<label for="nome" class="label">Nome</label>   			
				<?php echo "<input style='width:auto;' type='text' name='nome' class='inputcod' id='nome' required  placeholder='Nome' disabled='' value='$nomemoto'/>";?>
				<label for="pedido" class="label">Pedido</label>   			
				<?php echo "<input style='width:auto;' type='text' name='pedido' class='inputcod' id='pedido' required  placeholder='Pedido' autofocus/>";?>
	 <table class="buttons">
				<tr><input type="submit" form="form1" class="btn-env" value="Enviar"/>
            </form>
	</table>
		</div>
		</br>
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

	</section>
	<script src="js/jQuery/jquery-3.5.1.min.js"></script>
	<script src="js/script.js"></script>
</body>
</html>