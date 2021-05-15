<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class EscolaridadeDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function getEscolaridadePorID($idEscolaridade) {
		$query = "SELECT * "
			 . "FROM escolaridade "
			 . "WHERE id_escolaridade = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idEscolaridade, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idEscolaridade);
		$this->loggerDAO->salvar(null, "SELECT", "escolaridade", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function update($escolaridade) {
    	$query = "UPDATE escolaridade set grau_instrucao = ? "
    			. "WHERE id_escolaridade = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $escolaridade->getgrauinstrucao(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $escolaridade->getidescolaridade(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($escolaridade->getgrauinstrucao(), $escolaridade->getidescolaridade());
    	$this->loggerDAO->salvar($escolaridade->getidescolaridade(), "UPDATE", "escolaridade", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function salvar ($escolaridade){
    	$query = "INSERT INTO escolaridade (grau_instrucao) "
   			. "VALUES (?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $escolaridade->getgrauinstrucao(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	$parametros = array($escolaridade->getgrauinstrucao());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "escolaridade", $this->diversos->montaQuery($query, $parametros));
    	return $stmt;
    }
    
    public function excluirPorID($idEscolaridade){
    	$query = "DELETE from escolaridade "
    			. "WHERE id_escolaridade = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idEscolaridade, PDO::PARAM_INT);
    		$stmt->execute();
    		
    		
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
   		$parametros = array($idEscolaridade);
   		$this->loggerDAO->salvar($idEscolaridade, "DELETE", "escolaridade", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
	
	public function litarEscolaridades() {
		$query = "SELECT * "
			 . "FROM escolaridade ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "escolaridade", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>