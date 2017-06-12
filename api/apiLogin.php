<?php
require_once 'config.php';

$table = 'login';

try {
      $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(!$conn){
		echo "Não foi possivel conectar com Banco de Dados!";
	};

      $res = array('error' => false);
      $res['permissao'] = false;

      $action = 'outros';

      if(isset($_GET['action'])){
		$action = $_GET['action'];
	}
      
      if($action == 'read'){

            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $query = $conn->prepare("SELECT * FROM $table WHERE email = '$email'");
            $query->execute();
            $resultado = array();

            $result = $query->fetch();

            //var_dump ($result);
            if($email == $result['email'] && $senha == $result['senha']){
                  $res['permissao'] = true;
                  $res['user'] = $result;
            } else{
                  $res['error'] = true;
                  $res['message'] = "Error, Usuario ou senha incorretos. Tente outro ou cadastre-se!";
            };

	}

      header("Content-Type: application/json");
      echo json_encode($res);

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};
