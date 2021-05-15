<?php
class LinguaEstrangeira {
	private $idlinguaestrangeira;
	private $idconcurso;
	private $idlingua;
	    
	public function getidlingua() 
	{
	  return $this->idlingua;
	}
	
	public function setidlingua($value) 
	{
	  $this->idlingua = $value;
	}    
	public function getidconcurso() 
	{
	  return $this->idconcurso;
	}
	
	public function setidconcurso($value) 
	{
	  $this->idconcurso = $value;
	}    
	public function getidlinguaestrangeira() 
	{
	  return $this->idlinguaestrangeira;
	}
	
	public function setidlinguaestrangeira($value) 
	{
	  $this->idlinguaestrangeira = $value;
	}
}