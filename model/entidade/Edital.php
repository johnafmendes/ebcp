<?php
class Edital {
	private $idedital;
	private $idconcurso;
	private $titulo;
	private $data;
	private $caminhoarquivo;
	private $atualizacao;
	    
	public function getatualizacao() 
	{
	  return $this->atualizacao;
	}
	
	public function setatualizacao($value) 
	{
	  $this->atualizacao = $value;
	}    
	public function getcaminhoarquivo() 
	{
	  return $this->caminhoarquivo;
	}
	
	public function setcaminhoarquivo($value) 
	{
	  $this->caminhoarquivo = $value;
	}    
	public function getdata() 
	{
	  return $this->data;
	}
	
	public function setdata($value) 
	{
	  $this->data = $value;
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
	public function getidedital() 
	{
	  return $this->idedital;
	}
	
	public function setidedital($value) 
	{
	  $this->idedital = $value;
	}
}