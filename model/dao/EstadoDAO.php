<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class EstadoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function listarEstados() {
		$query = "SELECT * "
			 . "FROM estado ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "estado", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function getEstadoPorID($idEstado) {
    	$query = "SELECT * "
    			. "FROM estado "
    			. "WHERE id_estado = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idEstado, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idEstado);
    	$this->loggerDAO->salvar(null, "SELECT", "estado", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function update($estado) {
    	$query = "UPDATE estado set nome = ?, sigla = ? "
    		. "WHERE id_estado = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $estado->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $estado->getsigla(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $estado->getidestado(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($estado->getnome(), $estado->getsigla(), $estado->getidestado());
    	$this->loggerDAO->salvar($estado->getidestado(), "UPDATE", "estado", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function salvar ($estado){
    	$query = "INSERT INTO estado (nome, sigla) "
    		. "VALUES (?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $estado->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $estado->getsigla(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	$parametros = array($estado->getnome(), $estado->getsigla());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "estado", $this->diversos->montaQuery($query, $parametros));
    	return $stmt;
    }
    
    public function excluirPorID($idEstado){
    	$query = "DELETE from estado "
    			. "WHERE id_estado = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idEstado, PDO::PARAM_INT);
    		$stmt->execute();
    		
    		
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
   		$parametros = array($idEstado);
   		$this->loggerDAO->salvar($idEstado, "DELETE", "estado", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>