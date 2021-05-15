<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class RecursoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function getRecursoPorID($idRecurso) {
		$query = "SELECT * "
			 . "FROM recurso "
			 . "WHERE id_recurso = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idRecurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idRecurso);
		$this->loggerDAO->salvar(null, "SELECT", "recurso", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function update($recurso) {
    	$query = "UPDATE recurso set id_concurso = ?, id_tipos_recursos = ?, inicio_recurso = ?, final_recurso = ? "
    			. "WHERE id_recurso = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $recurso->getidconcurso(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $recurso->getidtiporecurso(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $recurso->getiniciorecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $recurso->getfinalrecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $recurso->getidrecurso(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}

    	$parametros = array($recurso->getidconcurso(), $recurso->getidtiporecurso(), $recurso->getiniciorecurso(), 
    			$recurso->getfinalrecurso(), $recurso->getidrecurso());
    	$this->loggerDAO->salvar($recurso->getidrecurso(), "UPDATE", "recurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function salvar ($recurso){
    	$query = "INSERT INTO recurso (id_concurso, id_tipos_recursos, inicio_recurso, final_recurso) "
    			. "VALUES (?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $recurso->getidconcurso(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $recurso->getidtiporecurso(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $recurso->getiniciorecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $recurso->getfinalrecurso(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}

    	$parametros = array($recurso->getidconcurso(), $recurso->getidtiporecurso(), $recurso->getiniciorecurso(),
    			$recurso->getfinalrecurso());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "recurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return $stmt;
    }
    
    public function excluirPorID($idRecurso){
    	$query = "DELETE from recurso "
    			. "WHERE id_recurso = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idRecurso, PDO::PARAM_INT);
    		$stmt->execute();
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($idRecurso);
    	$this->loggerDAO->salvar($idRecurso, "DELETE", "recurso", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
	
	public function listarRecursosPorIdConcurso($idConcurso) {
		$query = "SELECT r.*, tr.tipos_recursos "
			 . "FROM recurso r "
			 . "INNER JOIN tipos_recursos tr ON tr.id_tipos_recursos = r.id_tipos_recursos "
			 . "WHERE id_concurso = ? "
			 . "ORDER BY inicio_recurso DESC";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "recurso", $this->diversos->montaQuery($query, $parametros));
		
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