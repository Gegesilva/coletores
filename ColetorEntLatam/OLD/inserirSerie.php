<?php
     session_start();
     header('Content-Type: application/json');
     include_once("conexaoSQL.php");

    $orcamento = $_POST['orcamento'];
    $produto = $_POST['produto'];
    $serie = $_POST['serie'];


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
                                        WHERE SERIE = '$serie')       
        ";
        $stmt = sqlsrv_query($conn, $sql);

    if($existSerie == 0){
        echo "SERIE NÃO EXISTENTE! ";
    }

    if($existEstoque == 0){
        echo "NÃO HÁ ESTOQUE DESTA SÉRIE! ";
    }

?>

