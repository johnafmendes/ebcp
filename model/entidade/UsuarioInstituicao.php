<?php
class UsuarioInstituicao {
	private $nome;
	private $email;
	private $login;
	private $senha;
	private $ativo;
	private $idusuarioinstituicao;
	private $idinstituicao;
	    
	public function getidinstituicao() 
	{
	  return $this->idinstituicao;
	}
	
	public function setidinstituicao($value) 
	{
	  $this->idinstituicao = $value;
	}    
	public function getidusuarioinstituicao() 
	{
	  return $this->idusuarioinstituicao;
	}
	
	public function setidusuarioinstituicao($value) 
	{
	  $this->idusuarioinstituicao = $value;
	}
	
	public function getativo() 
	{
	  return $this->ativo;
	}
	
	public function setativo($value) 
	{
	  $this->ativo = $value;
	}    
	public function getsenha() 
	{
	  return $this->senha;
	}
	
	public function setsenha($value) 
	{
	  $this->senha = $value;
	}    
	public function getlogin() 
	{
	  return $this->login;
	}
	
	public function setlogin($value) 
	{
	  $this->login = $value;
	}    
	public function getemail() 
	{
	  return $this->email;
	}
	
	public function setemail($value) 
	{
	  $this->email = $value;
	}    
	public function getnome() 
	{
	  return $this->nome;
	}
	
	public function setnome($value) 
	{
	  $this->nome = $value;
	}    
}