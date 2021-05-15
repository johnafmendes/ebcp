<?php
class CidadeProva {
	private $idcidadeprova;
	private $idconcurso;
	private $idcidade;
	    
	public function getidcidade() 
	{
	  return $this->idcidade;
	}
	
	public function setidcidade($value) 
	{
	  $this->idcidade = $value;
	}    
	public function getidconcurso() 
	{
	  return $this->idconcurso;
	}
	
	public function setidconcurso($value) 
	{
	  $this->idconcurso = $value;
	}    
	public function getidcidadeprova() 
	{
	  return $this->idcidadeprova;
	}
	
	public function setidcidadeprova($value) 
	{
	  $this->idcidadeprova = $value;
	}
}