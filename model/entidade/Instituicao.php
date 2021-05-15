<?php
class Instituicao {
	private $idinstituicao;
	private $instituicao;
	private $logo;
	    
	public function getlogo() 
	{
	  return $this->logo;
	}
	
	public function setlogo($value) 
	{
	  $this->logo = $value;
	}    
	public function getinstituicao() 
	{
	  return $this->instituicao;
	}
	
	public function setinstituicao($value) 
	{
	  $this->instituicao = $value;
	}    
	public function getidinstituicao() 
	{
	  return $this->idinstituicao;
	}
	
	public function setidinstituicao($value) 
	{
	  $this->idinstituicao = $value;
	}
}