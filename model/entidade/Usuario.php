<?php
class Usuario {
	private $idusuario;
	private $nome;
	private $email;
	private $login;
	private $senha;
	private $ativo;
	private $nivelacesso;
	    
	public function getnivelacesso() 
	{
	  return $this->nivelacesso;
	}
	
	public function setnivelacesso($value) 
	{
	  $this->nivelacesso = $value;
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
	public function getidusuario() 
	{
	  return $this->idusuario;
	}
	
	public function setidusuario($value) 
	{
	  $this->idusuario = $value;
	}	
}