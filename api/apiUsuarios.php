<?php

require_once 'config.php';

$table = 'usuario';


try {
      
      $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(!$conn){
		echo "Não foi possivel conectar com Banco de Dados!";
	};

      $res = array('error' => false);

      //$action = 'read';

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
            
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $_POST['user'];
            $phone = $_POST['phone'];
            $avatar = $_POST['avatar'];
            $name = $_POST['name'];
            $nivel = "0";
		$ativo = "0";
		$cadastro = date("Y-m-d H:i:s");
            $datalogin = date("Y-m-d H:i:s");

            $sql = "SELECT * FROM $table WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $contar = $stmt->rowCount();
            $stmt->fetch();
            if($contar == '0'){
                  try{
                  $sql  = "INSERT INTO $table (email, password, user, phone, avatar, name, nivel, ativo, cadastro, ultimo_login) ";
                  $sql .= "VALUES (:email, :password, :user, :phone, :avatar, :name, :nivel, :ativo, :cadastro, :ultimo_login)";
                  $stmt = $conn->prepare($sql);
                  $stmt->bindParam(':email', $email);
                  $stmt->bindParam(':password', $password);
                  $stmt->bindParam(':user', $user);
                  $stmt->bindParam(':phone', $phone);
                  $stmt->bindParam(':avatar', $avatar);
                  $stmt->bindParam(':name', $name);
                  $stmt->bindParam(':nivel', $nivel);
                  $stmt->bindParam(':ativo', $ativo);
                  $stmt->bindParam(':cadastro', $cadastro);
                  $stmt->bindParam(':ultimo_login', $datalogin);
                  return $stmt->execute();
                  
                  if($stmt){
                        $res['message'] = "Suscesso, Usuario adicionado";
                  } else{
                        $res['error'] = true;
                        $res['message'] = "Error, Usuario não adicionado";
                  }
            } catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
            }else{
                  $res['error'] = true;
                  $res['message'] = "Error, email cadastrado!";
            }
		
	}

      if($action == 'update'){

            $id = $_POST['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $nickuser = $_POST['user'];
            $phone = $_POST['phone'];
            $avatar = $_POST['avatar'];
            $name = $_POST['name'];

            $sql  = "UPDATE $table SET email = :email, password = :password nickuser = :nickuser, phone = :phone, avatar = :avatar, name = :name WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':nickuser', $nickuser);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':avatar', $avatar);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, Usuario atualizado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, Usuario não atualizado";
            }
      }

      if($action == 'nivel'){
            
            $id = $_POST['id'];
            $proprietario = $_POST['proprietario'];
            $grupoLoja = $_POST['grupoLoja'];
            $loja = $_POST['loja'];
            $nivel = "nivel";
		$ativo = "ativo";

            $sql  = "UPDATE $table SET proprietario = :proprietario, grupoLoja = :grupoLoja loja = :loja, nival = :nival, ativo = :ativo, WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':proprietario', $proprietario);
            $stmt->bindParam(':grupoLoja', $grupoLoja);
            $stmt->bindParam(':loja', $loja);
            $stmt->bindParam(':nival', $nival);
            $stmt->bindParam(':ativo', $ativo);
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
            $sql = "SELECT * FROM $table WHERE email = :email AND password = :password ";
			
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $resultado = array();
            if($stmt){
                  $result = $stmt->fetch();
                  $resultado = $result;
                  if($resultado['ativo'] == 0){
                        $res['auth'] = true; 
                        $res['user'] = $resultado;
                  }else{
                        $res['error'] = true; 
                        $res['message'] = "Error, contate o administrador do sistema";      
                  }
            }else{
                  $res['error'] = true;
                  $res['message'] = "Error, email ou senha invalido. Tente novamente ou cadastra-se";
            }

      }
      header("Content-Type: application/json");
      echo json_encode($res);
	

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};