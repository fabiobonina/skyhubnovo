<?php

require_once 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$table = 'login';



try {
      $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(!$conn){
		echo "Não foi possivel conectar com Banco de Dados!";
	};

      $res = array('error' => false);

      $action = 'read';

      if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

      if($action == 'read'){
            $query = $conn->prepare("SELECT * FROM $table");
            $query->execute();
            $result = $query->fetch();
            $resultado = array();
            array_push($resultado, $result);
            $res['users'] = $resultado;
            //echo json_encode($resultado);
	}

	if($action == 'create'){

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $nickuser = $_POST['user'];
            $senha = $_POST['senha'];
            $nivel_usuario = "0";
		$ativo = "0";
		$datacadastro = date("Y-m-d H:i:s");
		$datalogin = date("Y-m-d H:i:s");

		$sql  = "INSERT INTO $table (nome, email, nickuser, senha, nivel, ativo, data_cadastro, data_ultimo_login) ";
		$sql .= "VALUES (:nome, :email, :nickuser, :senha, :nivel, :ativo, :data_cadastro, :data_ultimo_login)";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':nome',$nome);
		$stmt->bindParam(':email',$email);
		$stmt->bindParam(':nickuser',$nickuser);
		$stmt->bindParam(':senha',$senha);
		$stmt->bindParam(':nivel',$nivel_usuario);
		$stmt->bindParam(':ativo',$ativo);
		$stmt->bindParam(':data_cadastro',$datacadastro);
		$stmt->bindParam(':data_ultimo_login',$datalogin);
		return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, Usuario adicionado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, Usuario não adicionado ";
            }
		
	}

      if($action == 'update'){

            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $nickuser = $_POST['user'];
            $senha = $_POST['senha'];

            $sql  = "UPDATE $table SET nome = :nome, email = :email, nickuser = :nickuser, senha = :senha, WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome',$nome);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':nickuser',$nickuser);
            $stmt->bindParam(':senha',$senha);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, Usuario atualizado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, Usuario não atualizado";
            }
      }

      if($action == 'delete'){

            $id = $_POST['id'];

            $sql  = "DELETE FROM $table WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, usuario deletado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, usuario não deletado";
            }
      }

      echo json_encode($res);
	

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};

//RECUPERAÇÃO DO FORMULÁRIO
//$data = file_get_contents("php://input");
//$objData = json_decode($data);

//$nickuser = $objData->username;
//$senha_informada = $objData->password;

//$nickuser = 'fabio.bonina';
//$senha_informada = '123abc';

//$nickuser = 'fabio.bonina';
//$senha = '123abc';
//$senha_informada = md5($senha);
//$senha_informada = $senha;