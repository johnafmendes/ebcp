<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class TipoRecursoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function listarTipoRecursos(){
		$query = "SELECT * "
				. "FROM tipos_recursos ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "tipos_recursos", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getTipoRecursoPorID($idTipo) {
		$query = "SELECT * "
			 . "FROM tipos_recursos "
			 . "WHERE id_tipos_recursos = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idTipo, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idTipo);
		$this->loggerDAO->salvar(null, "SELECT", "tipos_recursos", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function update($tipoRecursos) {
    	$query = "UPDATE tipos_recursos set tipos_recursos = ? "
    			. "WHERE id_tipos_recursos = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $tipoRecursos->gettiporecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $tipoRecursos->getidtiporecurso(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}

    	$parametros = array($tipoRecursos->gettiporecurso(), $tipoRecursos->getidtiporecurso());
    	$this->loggerDAO->salvar($tipoRecursos->getidtiporecurso(), "UPDATE", "tipos_recursos", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function salvar ($tipoRecursos){
    	$query = "INSERT INTO tipos_recursos (tipos_recursos) "
    			. "VALUES (?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $tipoRecursos->gettiporecurso(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	
    	$parametros = array($tipoRecursos->gettiporecurso());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "tipos_recursos", $this->diversos->montaQuery($query, $parametros));
    	
    	return $stmt;
    }
    
    public function excluirPorID($idTipoRecurso){
    	$query = "DELETE from tipos_recursos "
    			. "WHERE id_tipos_recursos = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idTipoRecurso, PDO::PARAM_INT);
    		$stmt->execute();
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($idTipoRecurso);
    	$this->loggerDAO->salvar($idTipoRecurso, "DELETE", "tipos_recursos", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>