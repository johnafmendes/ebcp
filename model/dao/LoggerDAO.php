<?php
include_once 'Database.php';

class LoggerDAO extends Database{
	
	private $conn;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();		
	}
	
    public function salvar ($idRegistro, $acao, $tabela, $query_string){
    	if(isset($_SESSION['usuarioAcesso'])){
	    	$query = "INSERT INTO logger_admin (id_registro_tabela, acao, tabela, query, usuario, data_hora) "
	    		. "VALUES (?, ?, ?, ?, ?, ?)";
	    	// 		echo $query;
	    	$stmt = $this->conn->prepare( $query );
	    	$stmt->bindParam(1, $idRegistro, PDO::PARAM_INT);
	    	$stmt->bindParam(2, $acao, PDO::PARAM_STR);
	    	$stmt->bindParam(3, $tabela, PDO::PARAM_STR);
	    	$stmt->bindParam(4, $query_string, PDO::PARAM_STR);
	    	$stmt->bindParam(5, $_SESSION['usuarioAcesso'], PDO::PARAM_STR);
	    	$stmt->bindParam(6, date("Y-m-d H:i:s"), PDO::PARAM_STR);
	    	$stmt->execute();
	    
	    	if ($stmt->rowCount() == 0) {
	    		return null;
	    	}
	    	return $stmt;
    	}
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>