<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$dns = "mysql:host=localhost;dbname=system_tec";
$user = "root";
$pass = "";



try {
	$con = new PDO($dns, $user, $pass);

	if(!$con){
		echo "Não foi possivel conectar com Banco de Dados!";
	};

      $res = array('error' => false);

      $action = 'read';

      if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

      if($action == 'read'){            
            $query = $con->prepare("SELECT * FROM login");
            $query->execute();
            $result = $query->fetch();
            $resultado = array();
            array_push($resultado, $result);
            $res['users'] = $resultado;
            //echo json_encode($resultado);
	}

      if($action == 'create'){

            $nome = $_GET['action'];
            $email = $_GET['action'];
            $nickuser = $_GET['action'];
            $senha = $_GET['action'];
            $nivel_usuario;
            $ativo;
            $datacadastro;
            $datalogin;

            $query = $con->prepare("SELECT * FROM login");
            $query->execute();
            $result = $query->fetch();
            $resultado = array();
            array_push($resultado, $result);
            $res['users'] = $resultado;
            //echo json_encode($resultado);
	}

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};

//RECUPERAÇÃO DO FORMULÁRIO
$data = file_get_contents("php://input");
$objData = json_decode($data);

$nickuser = $objData->username;
$senha_informada = $objData->password;

//$nickuser = 'fabio.bonina';
//$senha_informada = '123abc';

//$nickuser = 'fabio.bonina';
//$senha = '123abc';
//$senha_informada = md5($senha);
//$senha_informada = $senha;