<?php
class Curriculo {
	private $nome;
	private $datanascimento;
	private $cidade;
	private $estado;
	private $telefone;
	private $email;
	private $minicurriculo;
	private $cargo;
	    
	public function getnome() 
	{
	  return $this->nome;
	}
	
	public function setnome($value) 
	{
	  $this->nome = $value;
	}
	
	public function getdatanascimento()
	{
		return $this->datanascimento;
	}
	
	public function setdatanascimento($value)
	{
		$this->datanascimento = $value;
	}
	
	public function getcidade()
	{
		return $this->cidade;
	}
	
	public function setcidade($value)
	{
		$this->cidade = $value;
	}
	
	public function getestado()
	{
		return $this->estado;
	}
	
	public function setestado($value)
	{
		$this->estado = $value;
	}
	
	public function gettelefone()
	{
		return $this->telefone;
	}
	
	public function settelefone($value)
	{
		$this->telefone = $value;
	}
	
	public function getemail()
	{
		return $this->email;
	}
	
	public function setemail($value)
	{
		$this->email = $value;
	}
	
	public function getminicurriculo()
	{
		return $this->minicurriculo;
	}
	
	public function setminicurriculo($value)
	{
		$this->minicurriculo = $value;
	}
	
	public function getcargo()
	{
		return $this->cargo;
	}
	
	public function setcargo($value)
	{
		$this->cargo = $value;
	}
}