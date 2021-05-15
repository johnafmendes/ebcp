<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class BoletoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function listarBoletos(){
		$query = "SELECT * "
				. "FROM boleto ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "boleto", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getBoletoPorID($idBoleto) {
		$query = "SELECT * "
			 . "FROM boleto "
			 . "WHERE id_boleto = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idBoleto, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idBoleto);
		$this->loggerDAO->salvar(null, "SELECT", "boleto", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function update($boleto) {
    	$query = "UPDATE boleto set descricao = ?, banco_agencia = ?, conta_numero = ?, conta_dv = ?, cedente_codigo = ?, "
    			. "cedente_codigo_dv = ?, cedente_identificacao = ?, cedente_cnpj = ?, cedente_endereco = ?, "
    			. "cedente_cidade = ?, cedente_estado = ?, "
    			. "cedente_razao_social = ?, nome_arquivo = ?, instrucoes_caixa1 = ?, instrucoes_caixa2 = ?, "
    			. "instrucoes_caixa3 = ?, instrucoes_caixa4 = ? "
    			. "WHERE id_boleto = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $boleto->getdescricao(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $boleto->getbancoagencia(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $boleto->getcontanumero(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $boleto->getcontadv(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $boleto->getcedentecodigo(), PDO::PARAM_STR);
    	$stmt->bindParam(6, $boleto->getcedentecodigodv(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $boleto->getcedenteidentificacao(), PDO::PARAM_STR);
    	$stmt->bindParam(8, $boleto->getcedentecnpj(), PDO::PARAM_STR);
    	$stmt->bindParam(9, $boleto->getcedenteendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(10, $boleto->getcedentecidade(), PDO::PARAM_STR);
    	$stmt->bindParam(11, $boleto->getcedenteestado(), PDO::PARAM_STR);
    	$stmt->bindParam(12, $boleto->getcedenterazaosocial(), PDO::PARAM_STR);
    	$stmt->bindParam(13, $boleto->getnomearquivo(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $boleto->getinstrucoescaixa1(), PDO::PARAM_STR);
    	$stmt->bindParam(15, $boleto->getinstrucoescaixa2(), PDO::PARAM_STR);
    	$stmt->bindParam(16, $boleto->getinstrucoescaixa3(), PDO::PARAM_STR);
    	$stmt->bindParam(17, $boleto->getinstrucoescaixa4(), PDO::PARAM_STR);
    	$stmt->bindParam(18, $boleto->getidboleto(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($boleto->getdescricao(), $boleto->getbancoagencia(), $boleto->getcontanumero(), 
    			$boleto->getcontadv(), $boleto->getcedentecodigo(), $boleto->getcedentecodigodv(), 
    			$boleto->getcedenteidentificacao(), 
    			$boleto->getcedentecnpj(), $boleto->getcedenteendereco(), $boleto->getcedentecidade(), 
    			$boleto->getcedenteestado(), $boleto->getcedenterazaosocial(), $boleto->getnomearquivo(), 
    			$boleto->getinstrucoescaixa1(), $boleto->getinstrucoescaixa2(), $boleto->getinstrucoescaixa3(), 
    			$boleto->getinstrucoescaixa4(), $boleto->getidboleto());
    	$this->loggerDAO->salvar($boleto->getidboleto(), "UPDATE", "boleto", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function salvar ($boleto){
    	$query = "INSERT INTO boleto (descricao, banco_agencia, conta_numero, conta_dv, cedente_codigo, "
    			. "cedente_codigo_dv, "
    			. "cedente_identificacao, cedente_cnpj, cedente_endereco, cedente_cidade, cedente_estado, "
    			. "cedente_razao_social, nome_arquivo, instrucoes_caixa1, instrucoes_caixa2, "
    			. "instrucoes_caixa3, instrucoes_caixa4) "
    			. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $boleto->getdescricao(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $boleto->getbancoagencia(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $boleto->getcontanumero(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $boleto->getcontadv(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $boleto->getcedentecodigo(), PDO::PARAM_STR);
    	$stmt->bindParam(6, $boleto->getcedentecodigodv(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $boleto->getcedenteidentificacao(), PDO::PARAM_STR);
    	$stmt->bindParam(8, $boleto->getcedentecnpj(), PDO::PARAM_STR);
    	$stmt->bindParam(9, $boleto->getcedenteendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(10, $boleto->getcedentecidade(), PDO::PARAM_STR);
    	$stmt->bindParam(11, $boleto->getcedenteestado(), PDO::PARAM_STR);
    	$stmt->bindParam(12, $boleto->getcedenterazaosocial(), PDO::PARAM_STR);
    	$stmt->bindParam(13, $boleto->getnomearquivo(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $boleto->getinstrucoescaixa1(), PDO::PARAM_STR);
    	$stmt->bindParam(15, $boleto->getinstrucoescaixa2(), PDO::PARAM_STR);
    	$stmt->bindParam(16, $boleto->getinstrucoescaixa3(), PDO::PARAM_STR);
    	$stmt->bindParam(17, $boleto->getinstrucoescaixa4(), PDO::PARAM_STR);
    	$stmt->execute();

    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	$parametros = array($boleto->getdescricao(), $boleto->getbancoagencia(), $boleto->getcontanumero(),
    			$boleto->getcontadv(), $boleto->getcedentecodigo(), $boleto->getcedentecodigodv(), 
    			$boleto->getcedenteidentificacao(),
    			$boleto->getcedentecnpj(), $boleto->getcedenteendereco(), $boleto->getcedentecidade(),
    			$boleto->getcedenteestado(), $boleto->getcedenterazaosocial(), $boleto->getnomearquivo(),
    			$boleto->getinstrucoescaixa1(), $boleto->getinstrucoescaixa2(), $boleto->getinstrucoescaixa3(),
    			$boleto->getinstrucoescaixa4());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "boleto", $this->diversos->montaQuery($query, $parametros));
    	return $stmt;
    }
    
    public function excluirPorID($idBoleto){
    	$query = "DELETE from boleto "
    			. "WHERE id_boleto = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idBoleto, PDO::PARAM_INT);
    		$stmt->execute();
    		
    		
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($idBoleto);
    	$this->loggerDAO->salvar(null, "DELETE", "boleto", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>