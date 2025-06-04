<?php
  header('Content-type: text/html; charset=utf-8');

  /* VALIDA USUARIO */
  session_start();
     include "conexaoSQL.php";
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
         echo "<script>location.href='../login.php'</script>"; 
         
       } 


  $orcamento = $_POST["orcamento"];
  $produto = $_POST["produto"];
  $desc = $_POST["desc"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>DATABIT</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<section class="content">
		<div class="box_form">
			<h1>SÉRIES
				<form method="post" action="itensalocados.php" class="form-inicial">
					<?php echo "<input class='inputcod' type='hidden' name='orcamento' autofocus='true' value='$orcamento'></br>";?>
					</a><button type="submit" class="btn-voltar">VOLTAR</buttom>
				</form> 
			</h1>
			<form id="form2">
				<label for="name" class="label">Produto</label>
    			<?php echo "<input type='text' name='produto' class='inputcod' id='produto' required  placeholder='Codigo da orcamento' disabled='' value='$produto'/>";?>
    			<?php echo "<input type='text' name='desc' class='inputcod' id='desc' required  placeholder='Codigo da orcamento' disabled='' value='$desc'/>";?>
				<b><label for="name" class="label">Série</label></b>
				<input type="text" name="serie" class='inputcod' id="serie" required placeholder="Série" autofocus="true"/>
				<!-- <label for="name">Serie</label><br>
				<input type="text" name="serie" class='inputcod' id="serie" required placeholder="Numero de Serie"/><br><br> -->
				<input type='hidden' id='orcamento' name='orcamento' class='btn-inserir' value='<?php echo $orcamento;?>'>

				<input onclick="window.location.reload();" type="submit" form="form2" class="btn-env" value="Enviar"/>
         </form>
		</div>
		<div style="color: white;" class="resultados" id="resultados">		
		<!-- SERIES ALOCADAS -->
		<div class="row" style=" margin-top: 1%;"></div>
			<div class="card overflow-auto" style="margin-left: 0%;  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
				<div class="card-header" style="background-color: #87CEFA;"></div>
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
						CODIGO = '$orcamento'
					AND PRODUTO = '$produto'
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
								<th scope="col" style="width:;">SÉRIES ORC</th>                                   
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
					$tabela .= "</tr>";

					}
					$tabela .= "</table>";
					
					print($tabela);
					?>                                                                           

				</div>
				</br>
		 </div>
		</div>
	</section>
	<!-- <script src="https://code.jquery.com/jquery-3.6.3.js"></script> -->
	<script src="js/jQuery/jquery-3.5.1.min.js"></script>
	<script src="js/script.js"></script>

	<!-- Esta seção faz com que o enter faça o cursor ir para o proximo campo -->
	<script>
		$("input, select", "form2") // busca input e select no form
    	.keypress(function(e){ // evento ao presionar uma tecla válida keypress
       
       var k = e.which || e.keyCode; // pega o código da tecla do mouse ou teclado
       
       if(k == 13){ // se for ENTER (Codigo 13)
          e.preventDefault(); // evita a função padrão do evento, neste caso o submit, para que a tela não seja atualizada
          $(this)
          .next() // seleciona a próxima linha
          .find('input') // busca o primeiro elemento do form no caso o input
          .first() // seleciona o primeiro que encontrar
          .focus(); // foca no elemento
       }
    });
	</script>
</body>
</html>