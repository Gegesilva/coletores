<?php
  header('Content-type: text/html; charset=utf-8');

  /* VALIDA USUARIO */
  session_start();
     include "conexaoSQL.php";
     $login = $_SESSION["login"];
     $senha = $_SESSION["password"];
	 $orcamento = $_POST["orcamento"];


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
         echo "<script>location.href='../login.php'</script>"; 
         
       } 
    

   /* VALIDA ORÇAMENTO */
	   $sql3="
			SELECT TB02018_STATUS status FROM TB02018
			WHERE TB02018_CODIGO = '$orcamento'
	   ";
   $stmt3= sqlsrv_query($conn,$sql3);
	 while($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)){
	   $status = $row3['status'];
	 }
	 if($status == 'C7' || $status == '97' || $status == '98' || $status == '96' || $status == 'E3'){
	   
	 }else{
		echo"<script>window.alert('Este orçamento não esta em um status válido!')</script>";
		echo "<script>location.href='inicio.php'</script>"; 
		return;
	 }
	   
	 
  /* PEGA O NOME DO CLIENTE */
  $sql1 = 
	"
		SELECT
			TB01008_NOME NomeCli
		FROM TB02018
		LEFT JOIN TB01008 ON TB01008_CODIGO = TB02018_CODCLI
		WHERE TB02018_CODIGO = '$orcamento'
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
	if($stmt1 === false)
	{
		die (print_r(sqlsrv_errors(), true));
	}
	
	while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$nomeCli = $row1['NomeCli'];
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
			<h1>PRODUTOS<button class="btn-voltar"><a class="btn-voltar" href="inicio.php">VOLTAR</a> </button></h1>
			<form id="form1" class="form1">
				<label for="name" class="label">Cod. Orcamento</label>
    			<?php echo "<input type='text' name='orcamento' class='inputcod' id='orcamento' required  placeholder='Codigo do orcamento' disabled='' value='$orcamento'/>";?>
				<label for="name" class="label">Cod. Orcamento</label>   			
				<?php echo "<input style='width:auto;' type='text' name='nome' class='inputcod' id='nome' required  placeholder='Nome Cliente' disabled='' value='$nomeCli'/>";?>
				<b><label for="name" class="label">Cod. Produto</label></b>
				<input type="text" name="produto" class='inputcod' id="produto" required placeholder="Codigo do Produto" autofocus="true"/>
	 <table class="buttons">
				<tr><input type="submit" form="form1" class="btn-env" value="Enviar"/>
            </form>
			<!-- consultar itens adicionados -->
			<form method="post" action="itensalocados.php">
				<?php echo "<input class='inputcod' type='hidden' name='orcamento' autofocus='true' value='$orcamento'>"?>
							<input class="btn-env" type="submit" value="Produtos"></tr>
	</table>
			</form>
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
						$tabela .= "<td style='background: $cor;'>".$row['Qtde']."</td>";
				
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