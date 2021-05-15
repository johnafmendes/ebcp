<?php
class Recurso {
	private $idrecurso;
	private $idconcurso;
	private $idtiporecurso;
	private $iniciorecurso;
	private $finalrecurso;
	private $horainicio;
	private $horafim;
	    
	public function gethorafim() 
	{
	  return $this->horafim;
	}
	
	public function sethorafim($value) 
	{
	  $this->horafim = $value;
	}    
	public function gethorainicio() 
	{
	  return $this->horainicio;
	}
	
	public function sethorainicio($value) 
	{
	  $this->horainicio = $value;
	}
	    
	public function getfinalrecurso() 
	{
	  return $this->finalrecurso;
	}
	
	public function setfinalrecurso($value) 
	{
	  $this->finalrecurso = $value;
	}
	public function getiniciorecurso() 
	{
	  return $this->iniciorecurso;
	}
	
	public function setiniciorecurso($value) 
	{
	  $this->iniciorecurso = $value;
	}    
	public function getidtiporecurso() 
	{
	  return $this->idtiporecurso;
	}
	
	public function setidtiporecurso($value) 
	{
	  $this->idtiporecurso = $value;
	}    
	public function getidconcurso() 
	{
	  return $this->idconcurso;
	}
	
	public function setidconcurso($value) 
	{
	  $this->idconcurso = $value;
	}    
	public function getidrecurso() 
	{
	  return $this->idrecurso;
	}
	
	public function setidrecurso($value) 
	{
	  $this->idrecurso = $value;
	}
}