<?php
    session_start();
    header('Content-type: text/html; charset=utf-8');
     /* header('Content-Type: application/json'); */
     include_once("conexaoSQL.php");

     $orcamento = $_POST['orcamento'];
     $produto = $_POST['produto'];
     $qtde = $_POST['qtde'];

    $sql = 
    "
        UPDATE GS_CONFERE SET QTDE = $qtde
        WHERE CODIGO = '$orcamento' AND PRODUTO = '$produto'
    ";
    $stmt = sqlsrv_query($conn, $sql);

    ?>
     