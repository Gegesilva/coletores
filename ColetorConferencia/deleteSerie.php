<?php
    session_start();
     header('Content-Type: application/json');
     include_once("conexaoSQL.php");

     $produto = $_POST['produto'];
     $orcamento = $_POST['orcamento'];
     $serie = $_POST['serie'];

    $sql = 
    "
        UPDATE GS_CONFERE SET SERIE = NULL
        WHERE CODIGO = '$orcamento' AND PRODUTO = '$produto' AND SERIE = '$serie'
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