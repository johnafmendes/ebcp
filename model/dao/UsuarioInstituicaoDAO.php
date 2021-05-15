<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class UsuarioInstituicaoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function autentica($login, $senha) {
		$query = "SELECT * "
				. "FROM usuario_instituicao "
				. "WHERE login = ? AND senha = ? and ativo = 1";
		//     	echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $login, PDO::PARAM_STR);
		$stmt->bindParam(2, $senha, PDO::PARAM_STR);
		$stmt->execute();
	
		$parametros = array($login, $senha);
		$this->loggerDAO->salvar(null, "SELECT", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
				
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
	public function listarUsuarios() {
		$query = "SELECT * "
			 . "FROM usuario_instituicao";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function getUsuarioPorID($idUsuario){
    	$query = "SELECT ui.*, i.instituicao "
    			. "FROM usuario_instituicao ui "
    			. "INNER JOIN instituicao i ON i.id_instituicao = ui.id_instituicao "
    			. "WHERE ui.id_usuario_instituicao = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
    	$stmt->execute();
    	
    	$parametros = array($idUsuario);
    	$this->loggerDAO->salvar(null, "SELECT", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
    	 
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function getUsuarioPorNome($nome){
    	$query = "SELECT ui.*, i.instituicao "
    			. "FROM usuario_instituicao ui "
    			. "INNER JOIN instituicao i ON i.id_instituicao = ui.id_instituicao "
    			. "WHERE ui.nome like ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$nome = "%" . $nome . "%";
    	$stmt->bindParam(1, $nome, PDO::PARAM_STR);
    	$stmt->execute();
    	 
    	$parametros = array($nome);
    	$this->loggerDAO->salvar(null, "SELECT", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
    	 
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }

    public function salvar ($usuario){
    	$query = "INSERT INTO usuario_instituicao (nome, email, login, senha, ativo, id_instituicao) "
			. "VALUES (?, ?, ?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $usuario->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $usuario->getemail(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $usuario->getlogin(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $usuario->getsenha(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $usuario->getativo(), PDO::PARAM_INT);
    	$stmt->bindParam(6, $usuario->getidinstituicao(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	
    	$parametros = array($usuario->getnome(), $usuario->getemail(), $usuario->getlogin(), 
    			$usuario->getsenha(), $usuario->getativo(), $usuario->getidinstituicao());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
    	 
    	return $stmt;
    }
    
    public function update($usuario) {
    	$query = "UPDATE usuario_instituicao set nome = ?, email = ?, login = ?, senha = ?, ativo = ?, id_instituicao = ? "
			. "WHERE id_usuario_instituicao = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $usuario->getnome(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $usuario->getemail(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $usuario->getlogin(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $usuario->getsenha(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $usuario->getativo(), PDO::PARAM_INT);
    	$stmt->bindParam(6, $usuario->getidinstituicao(), PDO::PARAM_INT);
       	$stmt->bindParam(7, $usuario->getidusuarioinstituicao(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	
    	$parametros = array($usuario->getnome(), $usuario->getemail(), $usuario->getlogin(),
    			$usuario->getsenha(), $usuario->getativo(), $usuario->getidinstituicao(),
    			$usuario->getidusuarioinstituicao());
    	$this->loggerDAO->salvar($usuario->getidusuarioinstituicao(), "UPDATE", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function excluirPorID($idUsuario){
    	$query = "DELETE from usuario_instituicao "
    			. "WHERE id_usuario_instituicao = ?";
    	// 		echo $query;
    	try{
    		$stmt = $this->conn->prepare( $query );
    		$stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
    		$stmt->execute();
    	} catch (Exception $e){
    		return false;
    	}
    
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    
    	$parametros = array($idUsuario);
    	$this->loggerDAO->salvar($idUsuario, "DELETE", "usuario_instituicao", $this->diversos->montaQuery($query, $parametros));
    
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>