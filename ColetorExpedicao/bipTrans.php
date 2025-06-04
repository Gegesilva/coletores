<?php
  header('Content-type: text/html; charset=utf-8');

  /* VALIDA USUARIO */
  session_start();
     include "conexaoSQL.php";
     $login = $_SESSION["login"];
     $senha = $_SESSION["password"];
	   $pedido = $_POST["pedido"];


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
			<h1>TRANSPORTADORA<button class="btn-voltar"><a class="btn-voltar" href="inicio.php">VOLTAR</a> </button></h1>
			<form id="form2" class="form2">
				<label for="pedido" class="label">Cod. Pedido</label>
    			<?php echo "<input type='text' name='pedido' class='inputcod' id='pedido' required  placeholder='Codigo do Pedido' value='$pedido'/>";?>
	 <table class="buttons">
				<tr><input type="submit" form="form1" class="btn-env" value="Enviar"/>
      </form>
		</tr>
	 </table>
		</div>
		<div class="resultado">
			<div id="resultados" name="resultados"></div>
		</div>
	</section>
	<script src="js/jQuery/jquery-3.5.1.min.js"></script>
	<script src="js/script.js"></script>
</body>
</html>