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

      $action = 'read';

      if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

      if($action == 'read'){
            $query = $conn->prepare("SELECT * FROM $table");
            $query->execute();
            $resultado = array();

            while($result = $query->fetch()) {
	            array_push($resultado, $result);
	      }
            $res['users'] = $resultado;
	}

	if($action == 'create'){

            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $user = $_POST['user'];
            $senha = "123";//$_POST['senha'];
            $nivel_usuario = "0";
		$ativo = "0";
		$datacadastro = date("Y-m-d H:i:s");
		$datalogin = date("Y-m-d H:i:s");

		$sql  = "INSERT INTO $table (nome, email, user, senha, nivel, ativo, data_cadastro, data_ultimo_login) ";
		$sql .= "VALUES (:nome, :email, :user, :senha, :nivel, :ativo, :data_cadastro, :data_ultimo_login)";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':nome',$nome);
		$stmt->bindParam(':email',$email);
		$stmt->bindParam(':user',$user);
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
                  $res['message'] = "Error, Usuario não adicionado";
            }
	}

      if($action == 'update'){

            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $user = $_POST['user'];
            $senha = '123';//$_POST['senha'];

            $sql  = "UPDATE $table SET nome = :nome, email = :email, user = :user, senha = :senha WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome',$nome);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':user',$user);
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


      header("Content-Type: application/json");
      echo json_encode($res);
	

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};

//RECUPERAÇÃO DO FORMULÁRIO
//$data = file_get_contents("php://input");
//$objData = json_decode($data);

//$user = $objData->username;
//$senha_informada = $objData->password;

//$user = 'fabio.bonina';
//$senha_informada = '123abc';

//$user = 'fabio.bonina';
//$senha = '123abc';
//$senha_informada = md5($senha);
//$senha_informada = $senha;