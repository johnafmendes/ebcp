<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class TipoConcursoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function listarTiposConcursos() {
		$query = "SELECT * "
			 . "FROM tipo_concurso ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "tipo_concurso", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function update($tipoConcurso) {
    	$query = "UPDATE tipo_concurso set tipo = ? "
    		. "WHERE id_tipo_concurso = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $tipoConcurso->gettipo(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $tipoConcurso->getidtipoconcurso(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($tipoConcurso->gettipo(), $tipoConcurso->getidtipoconcurso());
    	$this->loggerDAO->salvar($tipoConcurso->getidtipoconcurso(), "UPDATE", "tipo_concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function excluirPorID($idTipoConcurso){
    	$query = "DELETE from tipo_concurso "
    			. "WHERE id_tipo_concurso = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idTipoConcurso, PDO::PARAM_INT);
    		$stmt->execute();
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($idTipoConcurso);
    	$this->loggerDAO->salvar($idTipoConcurso, "DELETE", "tipo_concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    
    public function salvar ($tipoConcurso){
    	$query = "INSERT INTO tipo_concurso (tipo) "
    		. "VALUES (?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $tipoConcurso->gettipo(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	
    	$parametros = array($tipoConcurso->gettipo());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "tipo_concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return $stmt;
    }
    
    
    public function getTipoConcursoPorID($idTipoConcurso) {
    	$query = "SELECT * "
    		. "FROM tipo_concurso "
    		. "WHERE id_tipo_concurso = ? ";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idTipoConcurso, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idTipoConcurso);
    	$this->loggerDAO->salvar(null, "SELECT", "tipo_concurso", $this->diversos->montaQuery($query, $parametros));
    	
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