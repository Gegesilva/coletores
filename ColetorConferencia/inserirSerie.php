<?php
     session_start();
     header('Content-Type: application/json');
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
      echo "<script>location.href='../login.php'</script>";
      
    } 


    $orcamento = $_POST['orcamento'];
    $produto = $_POST['produto'];
    $serie = $_POST['serie'];

    /* VERIFICA SE A SERIE EXISTE NA 2054 */
    $sql1 = 
	"
	SELECT
	    1 existSerie
    WHERE 
    EXISTS (SELECT TB02054_NUMSERIE FROM TB02054 WHERE TB02054_NUMSERIE = '$serie')
	";
	$stmt1 = sqlsrv_query($conn, $sql1);
	
	if($stmt1 === false)
	{
		die (print_r(sqlsrv_errors(), true));
	}
	
	while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$existSerie = $row1['existSerie'];
	}

    if($existSerie == '1'){

        $sql = 
        "
        UPDATE 
            GS_CONFERE 
        SET 
            SERIE = '$serie' 
        WHERE 
           CODIGO = '$orcamento'
           AND PRODUTO = '$produto'
           
           AND ID = (SELECT TOP 1 ID FROM GS_CONFERE
                                        WHERE SERIE IS NULL AND CODIGO = '$orcamento' AND PRODUTO = '$produto')
           AND SERIE NOT IN (SELECT SERIE FROM GS_CONFERE
                                        WHERE SERIE = '$serie' AND CODIGO = '$orcamento')       
        ";
        $stmt = sqlsrv_query($conn, $sql);

    }else{
        echo "SERIE NÃO EXISTENTE! ";
    }


?>

