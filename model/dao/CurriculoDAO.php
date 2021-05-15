<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class CurriculoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function salvarCurriculo($curriculo) {
		$query = "INSERT INTO curriculos (nome, datanascimento, cidade, estado, telefone, email, minicurriculo, cargo) "
			 . "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $curriculo->getnome(), PDO::PARAM_STR);
		$stmt->bindParam(2, $curriculo->getdatanascimento(), PDO::PARAM_STR);
		$stmt->bindParam(3, $curriculo->getcidade(), PDO::PARAM_STR);
		$stmt->bindParam(4, $curriculo->getestado(), PDO::PARAM_STR);
		$stmt->bindParam(5, $curriculo->gettelefone(), PDO::PARAM_STR);
		$stmt->bindParam(6, $curriculo->getemail(), PDO::PARAM_STR);
		$stmt->bindParam(7, $curriculo->getminicurriculo(), PDO::PARAM_STR);
		$stmt->bindParam(8, $curriculo->getcargo(), PDO::PARAM_STR);
		$stmt->execute();

		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($curriculo->getnome(), $curriculo->getdatanascimento(), $curriculo->getcidade(), 
				$curriculo->getestado(), $curriculo->gettelefone(), $curriculo->getemail(), 
				$curriculo->getminicurriculo(), $curriculo->getcargo());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "curriculos", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
    }	
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>