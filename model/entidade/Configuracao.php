<?php
class Configuracao {
	private $idconfiguracoes;
	private $autenticacaoldaplocal;
	private $ldapbasedn;
	private $ldapdominio;
	private $ldapip;
	private $ldapporta;
	private $ldapgrupo;
	private $ireporturl;
	private $ireportusuario;
	private $ireportpassword;
	    
	public function getireportpassword() 
	{
	  return $this->ireportpassword;
	}
	
	public function setireportpassword($value) 
	{
	  $this->ireportpassword = $value;
	}    
	public function getireportusuario() 
	{
	  return $this->ireportusuario;
	}
	
	public function setireportusuario($value) 
	{
	  $this->ireportusuario = $value;
	}    
	public function getireporturl() 
	{
	  return $this->ireporturl;
	}
	
	public function setireporturl($value) 
	{
	  $this->ireporturl = $value;
	}
	    
	public function getldapgrupo() 
	{
	  return $this->ldapgrupo;
	}
	
	public function setldapgrupo($value) 
	{
	  $this->ldapgrupo = $value;
	}    
	public function getldapporta() 
	{
	  return $this->ldapporta;
	}
	
	public function setldapporta($value) 
	{
	  $this->ldapporta = $value;
	}    
	public function getldapip() 
	{
	  return $this->ldapip;
	}
	
	public function setldapip($value) 
	{
	  $this->ldapip = $value;
	}    
	public function getldapdominio() 
	{
	  return $this->ldapdominio;
	}
	
	public function setldapdominio($value) 
	{
	  $this->ldapdominio = $value;
	}    
	public function getldapbasedn() 
	{
	  return $this->ldapbasedn;
	}
	
	public function setldapbasedn($value) 
	{
	  $this->ldapbasedn = $value;
	}    
	public function getautenticacaoldaplocal() 
	{
	  return $this->autenticacaoldaplocal;
	}
	
	public function setautenticacaoldaplocal($value) 
	{
	  $this->autenticacaoldaplocal = $value;
	}
	    
	public function getidconfiguracoes() 
	{
	  return $this->idconfiguracoes;
	}
	
	public function setidconfiguracoes($value) 
	{
	  $this->idconfiguracoes = $value;
	}	
}