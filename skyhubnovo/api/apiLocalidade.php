<?php

require_once 'config.php';

if (isset($_GET['term'])){
	$return_arr = array();

	try {
	    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $stmt = $conn->prepare('SELECT * FROM tb_localidades WHERE nome LIKE :term');
	    $stmt->execute(array(':term' => '%'.$_GET['term'].'%'));

	    while($row = $stmt->fetch()) {
	        $return_arr[] =  array('id'=>$row['id'],'val'=>$row['nome'] ,'cli'=>$row['cliente'],'lat'=>$row['latitude'],'long'=>$row['longitude']);
	    }

	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}

    echo json_encode($return_arr);
}



?>