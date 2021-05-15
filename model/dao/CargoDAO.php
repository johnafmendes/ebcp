<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class CargoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function getCargoPorIdConcurso($idConcurso){
		$query = "SELECT * "
				. "FROM cargo "
				. "WHERE id_concurso = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "cargo", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($cargo) {
		$query = "UPDATE cargo set id_concurso = ?, titulo = ?, valor_inscricao = ?, id_turno = ?, numero_vagas = ? "
			. "WHERE id_cargo = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cargo->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $cargo->gettitulo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $cargo->getvalorinscricao(), PDO::PARAM_INT);
		$stmt->bindParam(4, $cargo->getidturno(), PDO::PARAM_INT);
		$stmt->bindParam(5, $cargo->getnumerovagas(), PDO::PARAM_INT);
		$stmt->bindParam(6, $cargo->getidcargo(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($cargo->getidconcurso(), $cargo->gettitulo(), $cargo->getvalorinscricao(), 
				$cargo->getidturno(), $cargo->getnumerovagas(), $cargo->getidcargo());
		$this->loggerDAO->salvar($cargo->getidcargo(), "UPDATE", "cargo", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($cargo){
		$query = "INSERT INTO cargo (id_concurso, titulo, valor_inscricao, id_turno, numero_vagas) "
			. "VALUES (?, ?, ?, ?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cargo->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $cargo->gettitulo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $cargo->getvalorinscricao(), PDO::PARAM_INT);
		$stmt->bindParam(4, $cargo->getidturno(), PDO::PARAM_INT);
		$stmt->bindParam(5, $cargo->getnumerovagas(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($cargo->getidconcurso(), $cargo->gettitulo(), $cargo->getvalorinscricao(),
				$cargo->getidturno(), $cargo->getnumerovagas());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "cargo", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
		
	public function listarCargosPorConcurso($idConcurso) {
		$query = "SELECT * "
			 . "FROM cargo "
			 . "WHERE id_concurso = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "cargo", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function getCargoPorID($idCargo){
    	$query = "SELECT * "
    			. "FROM cargo "
    			. "WHERE id_cargo = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idCargo, PDO::PARAM_INT);
    	$stmt->execute();
    	
    	$parametros = array($idCargo);
    	$this->loggerDAO->salvar(null, "SELECT", "cargo", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function excluirPorID($idCargo){
    	$query = "DELETE from cargo "
    			. "WHERE id_cargo = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idCargo, PDO::PARAM_INT);
    		$stmt->execute();
    		
    		
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($idCargo);
    	$this->loggerDAO->salvar($idCargo, "DELETE", "cargo", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>