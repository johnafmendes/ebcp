<?php
class Prova {
	private $idprova;
	private $idcargo;
	private $caminhoprova;
	private $datainicio;
	private $datafim;
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
	    
	public function getdatafim() 
	{
	  return $this->datafim;
	}
	
	public function setdatafim($value) 
	{
	  $this->datafim = $value;
	}    
	public function getdatainicio() 
	{
	  return $this->datainicio;
	}
	
	public function setdatainicio($value) 
	{
	  $this->datainicio = $value;
	}    
	public function getcaminhoprova() 
	{
	  return $this->caminhoprova;
	}
	
	public function setcaminhoprova($value) 
	{
	  $this->caminhoprova = $value;
	}    
	public function getidcargo() 
	{
	  return $this->idcargo;
	}
	
	public function setidcargo($value) 
	{
	  $this->idcargo = $value;
	}    
	public function getidprova() 
	{
	  return $this->idprova;
	}
	
	public function setidprova($value) 
	{
	  $this->idprova = $value;
	}
}