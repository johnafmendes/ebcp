<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class CidadeDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function excluirPorID($idCidade){
		$query = "DELETE from cidade "
				. "WHERE id_cidade = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idCidade, PDO::PARAM_INT);
			$stmt->execute();
			
				
			
		} catch (Exception $e){
			return false;
		}
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idCidade);
		$this->loggerDAO->salvar($idCidade, "DELETE", "cidade", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getCidadePorTitulo($cidade){
		$query = "SELECT * "
				. "FROM cidade "
				. "WHERE cidade like ? "
				. "ORDER BY cidade DESC";
		//     			echo $query;
		$stmt = $this->conn->prepare( $query );
// 		echo $titulocodigo;
		$cidade = "%" . $cidade . "%";
		$stmt->bindParam(1, $cidade, PDO::PARAM_STR);
		$stmt->execute();
		
		$parametros = array($cidade);
		$this->loggerDAO->salvar(null, "SELECT", "cidade", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarCidadesPorIdEstado($idEstado){
		$query = "SELECT c.cidade, c.id_cidade "
				. "FROM cidade c "
				. "INNER JOIN estado e ON e.id_estado = c.id_estado "
				. "WHERE e.id_estado = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idEstado, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idEstado);
		$this->loggerDAO->salvar(null, "SELECT", "cidade", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($cidade) {
    	$query = "UPDATE cidade set cidade = ?, id_estado = ? "
    			. "WHERE id_cidade = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $cidade->getcidade(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $cidade->getidestado(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $cidade->getidcidade(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($cidade->getcidade(), $cidade->getidestado(), $cidade->getidcidade());
    	$this->loggerDAO->salvar($cidade->getidcidade(), "UPDATE", "cidade", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function salvar ($cidade){
		$query = "INSERT INTO cidade (cidade, id_estado) "
			. "VALUES (?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cidade->getcidade(), PDO::PARAM_STR);
		$stmt->bindParam(2, $cidade->getidestado(), PDO::PARAM_INT);
		$stmt->execute();
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($cidade->getcidade(), $cidade->getidestado());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "cidade", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function listarTodosCidades(){
		$query = "SELECT * "
				. "FROM cidade ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "cidade", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarConcursosInscritosPorIdEstado($idEstado){
		$query = "SELECT c.*, e.nome, e.sigla "
				. "FROM cidade c "
				. "INNER JOIN estado e ON e.id_estado = c.id_estado "
				. "WHERE c.id_estado = ? "
				. "ORDER BY c.cidade DESC";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idEstado, PDO::PARAM_INT);
		$stmt->execute();
 		
		$parametros = array($idEstado);
		$this->loggerDAO->salvar(null, "SELECT", "cidade", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
    public function getCidadePorID($idCidade) {
    	$query = "SELECT c.*, e.* "
    			. "FROM cidade c "
    			. "INNER JOIN estado e ON c.id_estado = e.id_estado "
    			. "WHERE c.id_cidade = ? ";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idCidade, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idCidade);
    	$this->loggerDAO->salvar(null, "SELECT", "cidade", $this->diversos->montaQuery($query, $parametros));
    	
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