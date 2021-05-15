<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class TurnoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function getListaTurnos(){
		$query = "SELECT * "
				. "FROM turno";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "turno", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
    public function getTurnoPorID($idTurno) {
    	$query = "SELECT * "
    			. "FROM turno "
    			. "WHERE id_turno = ? ";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idTurno, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idTurno);
    	$this->loggerDAO->salvar(null, "SELECT", "turno", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
	
	public function excluirPorID($idTurno){
		$query = "DELETE from turno "
				. "WHERE id_turno = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idTurno, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e){
			return false;
		}
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		
		$parametros = array($idTurno);
		$this->loggerDAO->salvar($idTurno, "DELETE", "turno", $this->diversos->montaQuery($query, $parametros));
		
		return true;
	}
	
	public function update($turno) {
    	$query = "UPDATE turno set turno = ? "
    			. "WHERE id_turno = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $turno->getturno(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $turno->getidturno(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($turno->getturno(), $turno->getidturno());
    	$this->loggerDAO->salvar($turno->getidturno(), "UPDATE", "turno", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function salvar ($turno){
		$query = "INSERT INTO turno (turno) "
						. "VALUES (?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $turno->getturno(), PDO::PARAM_STR);
		$stmt->execute();
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		
		$parametros = array($turno->getturno());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "turno", $this->diversos->montaQuery($query, $parametros));
		
		return $stmt;
	}
	
    function __destruct() {
    	unset($this->conn);
    }
}
?>