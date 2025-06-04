<?php
  $serverName = '192.168.254.197';
  $connectionInfo = array("Database"=>"POSITIVA", "UID"=>"positivaphp", "PWD"=>"databit@#2023", "CharacterSet"=>"UTF-8");
  $conn = sqlsrv_connect($serverName, $connectionInfo);
  
  if($conn){
    echo "";
  }else{
    echo "falha na conex�o";
    die( print_r(sqlsrv_errors(), true));
  }
  
  
  function getConnection() {
    $serverName = '192.168.254.197';
    $connectionInfo = array("Database"=>"POSITIVA", "UID"=>"positivaphp", "PWD"=>"databit@#2023", "CharacterSet"=>"UTF-8");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    return($conn);
  }


?> 