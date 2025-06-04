<?php
    session_start();
     header('Content-Type: application/json');
     include_once("conexaoSQL.php");

     $produto = $_POST['produto'];
     $pedido = $_POST['pedido'];

    $sql = 
    "
        DELETE FROM GS_RETORNO WHERE PRODUTO = '$produto'
        AND CODIGO = '$pedido'
    ";
    $stmt = sqlsrv_query($conn, $sql);
        
        if($stmt === false)
        {
            echo json_encode('Não gravado, verifique os campos.');
            /* die (print_r(sqlsrv_errors(), true)); */
        } 
        else{
            echo json_encode('DADOS DELETADOS! CLICK EM VOLTAR.');
        }