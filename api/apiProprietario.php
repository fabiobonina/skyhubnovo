<?php

require_once 'config.php';

$table = 'tb_proprietario';


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
            
            $displyName = $_POST['displayName'];
            $name = $_POST['name'];
		$ativo = "0";
		$cadastro = date("Y-m-d H:i:s");

            try{
                  $sql  = "INSERT INTO $table (name, displayName, ativo, cadastro) ";
                  $sql .= "VALUES (:name, :displayName, :ativo, :cadastro)";
                  $stmt = $conn->prepare($sql);
                  $stmt->bindParam(':name', $name);
                  $stmt->bindParam(':displayName', $displayName);
                  $stmt->bindParam(':ativo', $ativo);
                  $stmt->bindParam(':cadastro', $cadastro);
                  return $stmt->execute();
                  
                  if($stmt){
                        $res['message'] = "Suscesso, proprietatio adicionado";
                  } else{
                        $res['error'] = true;
                        $res['message'] = "Error, proprietatio não adicionado";
                  }
            } catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}           

		
	}

      if($action == 'update'){

            $id = $_POST['id'];
            $displyName = $_POST['displayName'];
            $name = $_POST['name'];
		$ativo = $_POST['ativo'];

            $sql  = "UPDATE $table SET displayName = :displayName, name = :name ativo = :ativo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':displayName', $displayName);
            $stmt->bindParam(':ativo', $ativo);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, proprietatio atualizado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, proprietatio não atualizado";
            }
      }


      if($action == 'delete'){

            $id = $_POST['id'];

            $sql  = "DELETE FROM $table WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();

            if($stmt){
		      $res['message'] = "Suscesso, proprietatio deletado";
	      } else{
                  $res['error'] = true;
                  $res['message'] = "Error, proprietatio não deletado";
            }
      }

      header("Content-Type: application/json");
      echo json_encode($res);
	

} catch (Exception $e) {
	echo "Erro: ". $e->getMessage();
};