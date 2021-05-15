<?php
class Cargo {
	private $idcargo;
	private $idconcurso;
	private $titulo;
	private $valorinscricao;
	private $idturno;
	private $numerovagas;
	    
	public function getnumerovagas() 
	{
	  return $this->numerovagas;
	}
	
	public function setnumerovagas($value) 
	{
	  $this->numerovagas = $value;
	}    
	public function getidturno() 
	{
	  return $this->idturno;
	}
	
	public function setidturno($value) 
	{
	  $this->idturno = $value;
	}    
	public function getvalorinscricao() 
	{
	  return $this->valorinscricao;
	}
	
	public function setvalorinscricao($value) 
	{
	  $this->valorinscricao = $value;
	}    
	public function gettitulo() 
	{
	  return $this->titulo;
	}
	
	public function settitulo($value) 
	{
	  $this->titulo = $value;
	}    
	public function getidconcurso() 
	{
	  return $this->idconcurso;
	}
	
	public function setidconcurso($value) 
	{
	  $this->idconcurso = $value;
	}    
	public function getidcargo() 
	{
	  return $this->idcargo;
	}
	
	public function setidcargo($value) 
	{
	  $this->idcargo = $value;
	}	
}