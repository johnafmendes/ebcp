<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class CandidatoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function getCandidatoPorNome($nome, $inicio, $registros){
		$query = "SELECT * "
			. "FROM candidato "
			. "WHERE nome like ? ";
		$query .= isset($inicio) ? "LIMIT " . $inicio . ", " . $registros : "";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$nome = "%" . $nome . "%";
		$stmt->bindParam(1, $nome, PDO::PARAM_STR);
		$stmt->execute();
	
		$parametros = array($nome);
		$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getTotalCandidatoPorNome($nome){
		$query = "SELECT count(*) as total "
			. "FROM candidato "
			. "WHERE nome like ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$nome = "%" . $nome . "%";
		$stmt->bindParam(1, $nome, PDO::PARAM_STR);
		$stmt->execute();
	
		$parametros = array($nome);
		$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
		
		return $stmt->fetch(PDO::FETCH_OBJ)->total;
	}
	
	public function alterarSenha($cpf, $senhaAntiga, $novaSenha){
		$query = "UPDATE candidato "
				. "SET senha = ? "
				. "WHERE cpf = ? AND senha = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $novaSenha, PDO::PARAM_STR);
		$stmt->bindParam(2, $cpf, PDO::PARAM_STR);
		$stmt->bindParam(3, $senhaAntiga, PDO::PARAM_STR);
		$stmt->execute();
		
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($novaSenha, $cpf, $senhaAntiga);
		$this->loggerDAO->salvar(null, "UPDATE", "candidato", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
		
	public function verificaCPF($cpf) {
		$query = "SELECT cpf "
			 . "FROM candidato "
			 . "WHERE cpf = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cpf, PDO::PARAM_STR);
		$stmt->execute();

		$parametros = array($cpf);
		$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		return true;
    }	
    
    public function autentica($cpf, $senha) {
    	$query = "SELECT * "
    			. "FROM candidato "
    			. "WHERE cpf = ? AND senha = ?";
//     	echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $cpf, PDO::PARAM_STR);
    	$stmt->bindParam(2, $senha, PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array($cpf, $senha);
    	$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function getCandidatoPorID($idCandidato) {
    	$query = "SELECT c.*, e.nome as estado, e.sigla "
    			. "FROM candidato c "
    			. "INNER JOIN estado e ON e.id_estado = c.id_estado "
    			. "WHERE c.id_candidato = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idCandidato, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idCandidato);
    	$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function getCandidatoPorCPF($cpf) {
    	$query = "SELECT c.* "
    			. "FROM candidato c "
    			. "WHERE c.cpf = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $cpf, PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array($cpf);
    	$this->loggerDAO->salvar(null, "SELECT", "candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function salvar($candidato) {
    	$query = "INSERT INTO candidato (nome, rg, orgao_emissor_rg, data_emissao_rg, cpf, estado_civil, sexo, "
    			. "data_nascimento, nome_pai, nome_mae, endereco, numero_endereco, complemento_endereco, bairro, cidade, id_estado, "
    			. "cep, telefone, email, senha, id_escolaridade) "
    			. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $candidato->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $candidato->getrg(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $candidato->getorgaoemissorrg(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $candidato->getdataemissaorg(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $candidato->getcpf(), PDO::PARAM_STR);
    	$stmt->bindParam(6, $candidato->getestadocivil(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $candidato->getsexo(), PDO::PARAM_STR);
    	$stmt->bindParam(8, $candidato->getdatanascimento(), PDO::PARAM_STR);
    	$stmt->bindParam(9, $candidato->getnomepai(), PDO::PARAM_STR);
    	$stmt->bindParam(10, $candidato->getnomemae(), PDO::PARAM_STR);
    	$stmt->bindParam(11, $candidato->getendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(12, $candidato->getnumeroendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(13, $candidato->getcomplementoendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $candidato->getbairro(), PDO::PARAM_STR);
    	$stmt->bindParam(15, $candidato->getcidade(), PDO::PARAM_STR);
    	$stmt->bindParam(16, $candidato->getidestado(), PDO::PARAM_INT);
    	$stmt->bindParam(17, $candidato->getcep(), PDO::PARAM_STR);
    	$stmt->bindParam(18, $candidato->gettelefone(), PDO::PARAM_STR);
    	$stmt->bindParam(19, $candidato->getemail(), PDO::PARAM_STR);
    	$stmt->bindParam(20, $candidato->getsenha(), PDO::PARAM_STR);
    	$stmt->bindParam(21, $candidato->getidescolaridade(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	$parametros = array($candidato->getnome(), $candidato->getrg(), $candidato->getorgaoemissorrg(), 
    			$candidato->getdataemissaorg(), $candidato->getcpf(), $candidato->getestadocivil(), 
    			$candidato->getsexo(), $candidato->getdatanascimento(), $candidato->getnomepai(), 
    			$candidato->getnomemae(), $candidato->getendereco(), $candidato->getnumeroendereco(), 
    			$candidato->getcomplementoendereco(), $candidato->getbairro(), $candidato->getcidade(), 
    			$candidato->getidestado(), $candidato->getcep(), $candidato->gettelefone(), $candidato->getemail(), 
    			$candidato->getsenha(), $candidato->getidescolaridade());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "candidato", $this->diversos->montaQuery($query, $parametros));
    	return $stmt;
    }
    
    public function update($candidato) {
    	$query = "UPDATE candidato set nome = ?, rg = ?, orgao_emissor_rg = ?, data_emissao_rg = ?, cpf = ?, estado_civil = ?, sexo = ?, "
    			. "data_nascimento = ?, nome_pai = ?, nome_mae = ?, endereco = ?, numero_endereco = ?, complemento_endereco = ?, bairro = ?, cidade = ?, id_estado = ?, "
    			. "cep = ?, telefone = ?, email = ?, id_escolaridade = ?, senha = ? "
    			. "WHERE id_candidato = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $candidato->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $candidato->getrg(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $candidato->getorgaoemissorrg(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $candidato->getdataemissaorg(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $candidato->getcpf(), PDO::PARAM_STR);
    	$stmt->bindParam(6, $candidato->getestadocivil(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $candidato->getsexo(), PDO::PARAM_STR);
    	$stmt->bindParam(8, $candidato->getdatanascimento(), PDO::PARAM_STR);
    	$stmt->bindParam(9, $candidato->getnomepai(), PDO::PARAM_STR);
    	$stmt->bindParam(10, $candidato->getnomemae(), PDO::PARAM_STR);
    	$stmt->bindParam(11, $candidato->getendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(12, $candidato->getnumeroendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(13, $candidato->getcomplementoendereco(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $candidato->getbairro(), PDO::PARAM_STR);
    	$stmt->bindParam(15, $candidato->getcidade(), PDO::PARAM_STR);
    	$stmt->bindParam(16, $candidato->getidestado(), PDO::PARAM_INT);
    	$stmt->bindParam(17, $candidato->getcep(), PDO::PARAM_STR);
    	$stmt->bindParam(18, $candidato->gettelefone(), PDO::PARAM_STR);
    	$stmt->bindParam(19, $candidato->getemail(), PDO::PARAM_STR);
    	$stmt->bindParam(20, $candidato->getidescolaridade(), PDO::PARAM_INT);
    	$stmt->bindParam(21, $candidato->getsenha(), PDO::PARAM_STR);
    	$stmt->bindParam(22, $candidato->getidcandidato(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($candidato->getnome(), $candidato->getrg(), $candidato->getorgaoemissorrg(),
    			$candidato->getdataemissaorg(), $candidato->getcpf(), $candidato->getestadocivil(),
    			$candidato->getsexo(), $candidato->getdatanascimento(), $candidato->getnomepai(),
    			$candidato->getnomemae(), $candidato->getendereco(), $candidato->getnumeroendereco(),
    			$candidato->getcomplementoendereco(), $candidato->getbairro(), $candidato->getcidade(),
    			$candidato->getidestado(), $candidato->getcep(), $candidato->gettelefone(), $candidato->getemail(),
    			$candidato->getsenha(), $candidato->getidescolaridade(), $candidato->getidcandidato());
    	$this->loggerDAO->salvar($candidato->getidcandidato(), "UPDATE", "candidato", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function updateSenha($candidato) {
    	$query = "UPDATE candidato set senha = ? "
    			. "WHERE id_candidato = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $candidato->getsenha(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $candidato->getidcandidato(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($candidato->getsenha(), $candidato->getidcandidato());
    	$this->loggerDAO->salvar($candidato->getidcandidato(), "UPDATE", "candidato", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>