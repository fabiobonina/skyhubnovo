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

      $action = 'create';

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
            $senha = '123abc';
            $email = "teste1@teste.com";//$_POST['email'];
            $password = md5($senha);// $_POST['password'];
            $nickuser = "teste";//$_POST['user'];
            $phone = "teste";//$_POST['phone'];
            $avatar = "teste";//$_POST['avatar'];
            $nome = "teste";//$_POST['nome'];
            $user = "teste";//$_POST['user'];
            $nivel = "0";
		$ativo = "0";
		$datacadastro = date("Y-m-d H:i:s");
		$datalogin = date("Y-m-d H:i:s");

		$sql  = "INSERT INTO $table (email, password, user, phone, avatar, nome, nivel, ativo, data_cadastro, data_ultimo_login) ";
		$sql .= "VALUES (:email, :password, :user, :phone, :avatar, :nome, :nivel, :ativo, :data_cadastro, :data_ultimo_login)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':user', $nickuser);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':avatar', $avatar);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':nivel', $nivel);
            $stmt->bindParam(':ativo', $ativo);
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
            $senha = $_POST['senha'];

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
      if($action == 'logar'){
            
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM $table WHERE BINARY email = :email AND BINARY senha = :senha ";
			
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $password);
            $stmt->execute();

            $resultado = array();
            if($stmt){
                  
            $result = $stmt->fetch();
            $resultado = $result;
            
            $res['auth'] = true; 
            $res['user'] = $resultado;                        

            }else{
                  $res['error'] = true;
                  $res['message'] = "Error, email ou senha invalido. Tente novamente ou cadastra-se";
            }

      }

} catch (Exception $e) {
	$res['erro'] = $e->getMessage();
};
header("Content-Type: application/json");
echo json_encode($res);