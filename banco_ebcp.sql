SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ebcpc187_ebcp` ;
CREATE SCHEMA IF NOT EXISTS `ebcpc187_ebcp` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `ebcpc187_ebcp` ;

-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`escolaridade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`escolaridade` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`escolaridade` (
  `id_escolaridade` INT NOT NULL AUTO_INCREMENT,
  `grau_instrucao` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_escolaridade`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`estado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`estado` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`estado` (
  `id_estado` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `sigla` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`id_estado`))
ENGINE = InnoDB
AUTO_INCREMENT = 28;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`candidato`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`candidato` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`candidato` (
  `id_candidato` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `rg` VARCHAR(15) NULL,
  `orgao_emissor_rg` VARCHAR(50) NULL,
  `data_emissao_rg` DATE NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `estado_civil` VARCHAR(20) NULL,
  `sexo` VARCHAR(1) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `nome_pai` VARCHAR(50) NULL,
  `nome_mae` VARCHAR(50) NULL,
  `endereco` VARCHAR(100) NOT NULL,
  `numero_endereco` VARCHAR(6) NOT NULL,
  `complemento_endereco` VARCHAR(100) NULL,
  `bairro` VARCHAR(50) NULL,
  `cidade` VARCHAR(50) NOT NULL,
  `id_estado` INT NOT NULL,
  `cep` VARCHAR(8) NOT NULL,
  `telefone` VARCHAR(11) NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(32) NOT NULL,
  `id_escolaridade` INT NOT NULL,
  PRIMARY KEY (`id_candidato`),
  INDEX `fk_candidato_id_escolaridade_idx` (`id_escolaridade` ASC),
  INDEX `fk_candidato_id_estado_idx` (`id_estado` ASC),
  CONSTRAINT `fk_candidato_id_escolaridade`
    FOREIGN KEY (`id_escolaridade`)
    REFERENCES `ebcpc187_ebcp`.`escolaridade` (`id_escolaridade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_candidato_id_estado`
    FOREIGN KEY (`id_estado`)
    REFERENCES `ebcpc187_ebcp`.`estado` (`id_estado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`instituicao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`instituicao` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`instituicao` (
  `id_instituicao` INT NOT NULL AUTO_INCREMENT,
  `instituicao` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) NULL,
  PRIMARY KEY (`id_instituicao`))
ENGINE = InnoDB
COMMENT = '				';


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`tipo_concurso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`tipo_concurso` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`tipo_concurso` (
  `id_tipo_concurso` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_tipo_concurso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`boleto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`boleto` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`boleto` (
  `id_boleto` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(50) NOT NULL,
  `banco_agencia` VARCHAR(10) NOT NULL,
  `conta_numero` VARCHAR(10) NOT NULL,
  `conta_dv` VARCHAR(2) NOT NULL,
  `cedente_codigo` VARCHAR(15) NOT NULL,
  `cedente_identificacao` VARCHAR(255) NOT NULL,
  `cedente_cnpj` VARCHAR(19) NOT NULL,
  `cedente_endereco` VARCHAR(50) NOT NULL,
  `cedente_cidade` VARCHAR(50) NOT NULL,
  `cedente_estado` VARCHAR(2) NOT NULL,
  `cedente_razao_social` VARCHAR(255) NOT NULL,
  `nome_arquivo` VARCHAR(100) NOT NULL,
  `instrucoes_caixa1` VARCHAR(255) NULL,
  `instrucoes_caixa2` VARCHAR(255) NULL,
  `instrucoes_caixa3` VARCHAR(255) NULL,
  `instrucoes_caixa4` VARCHAR(255) NULL,
  PRIMARY KEY (`id_boleto`))
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`concurso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`concurso` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`concurso` (
  `id_concurso` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `subtitulo` VARCHAR(255) NOT NULL,
  `id_instituicao` INT NOT NULL,
  `inicio_inscricao` DATETIME NOT NULL,
  `final_inscricao` DATETIME NOT NULL,
  `vencimento_boleto` DATE NOT NULL,
  `id_boleto` INT NOT NULL,
  `homologado` TINYINT NOT NULL DEFAULT 0,
  `ativo` TINYINT NOT NULL DEFAULT 0,
  `destaque` TINYINT NOT NULL DEFAULT 0,
  `id_tipo_concurso` INT NOT NULL,
  `isencao_doador_sangue` TINYINT NOT NULL DEFAULT 0,
  `doador_sangue_data_inicio` DATETIME NOT NULL,
  `doador_sangue_data_fim` DATETIME NOT NULL,
  `isencao_nis` TINYINT NOT NULL DEFAULT 0,
  `nis_data_inicio` DATETIME NOT NULL,
  `nis_data_fim` DATETIME NOT NULL,
  `cota_racial` TINYINT NOT NULL DEFAULT 0,
  `multiplas_inscricoes` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id_concurso`),
  INDEX `fk_concurso__idx` (`id_instituicao` ASC),
  INDEX `fk_concurso_id_tipo_concurso_idx` (`id_tipo_concurso` ASC),
  INDEX `fk_concurso_id_boleto_idx` (`id_boleto` ASC),
  CONSTRAINT `fk_concurso_id_instituicao`
    FOREIGN KEY (`id_instituicao`)
    REFERENCES `ebcpc187_ebcp`.`instituicao` (`id_instituicao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_concurso_id_tipo_concurso`
    FOREIGN KEY (`id_tipo_concurso`)
    REFERENCES `ebcpc187_ebcp`.`tipo_concurso` (`id_tipo_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_concurso_id_boleto`
    FOREIGN KEY (`id_boleto`)
    REFERENCES `ebcpc187_ebcp`.`boleto` (`id_boleto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`turno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`turno` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`turno` (
  `id_turno` INT NOT NULL AUTO_INCREMENT,
  `turno` VARCHAR(50) NULL,
  PRIMARY KEY (`id_turno`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`cargo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`cargo` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`cargo` (
  `id_cargo` INT NOT NULL AUTO_INCREMENT,
  `id_concurso` INT NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `valor_inscricao` DECIMAL NOT NULL,
  `id_turno` INT NOT NULL,
  `numero_vagas` INT NOT NULL,
  PRIMARY KEY (`id_cargo`),
  INDEX `id_concurso_idx` (`id_concurso` ASC),
  INDEX `fk_cargo_id_turno_idx` (`id_turno` ASC),
  CONSTRAINT `fk_cargo_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cargo_id_turno`
    FOREIGN KEY (`id_turno`)
    REFERENCES `ebcpc187_ebcp`.`turno` (`id_turno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`cidade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`cidade` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`cidade` (
  `id_cidade` INT NOT NULL AUTO_INCREMENT,
  `cidade` VARCHAR(100) NOT NULL,
  `id_estado` INT NOT NULL,
  PRIMARY KEY (`id_cidade`),
  INDEX `fk_cidade_id_estado_idx` (`id_estado` ASC),
  CONSTRAINT `fk_cidade_id_estado`
    FOREIGN KEY (`id_estado`)
    REFERENCES `ebcpc187_ebcp`.`estado` (`id_estado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`cidade_prova`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`cidade_prova` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`cidade_prova` (
  `id_cidade_prova` INT NOT NULL AUTO_INCREMENT,
  `id_concurso` INT NOT NULL,
  `id_cidade` INT NOT NULL,
  PRIMARY KEY (`id_cidade_prova`),
  INDEX `id_concurso_idx` (`id_concurso` ASC),
  INDEX `fk_cidade_prova_id_cidade_idx` (`id_cidade` ASC),
  CONSTRAINT `fk_cidade_prova_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cidade_prova_id_cidade`
    FOREIGN KEY (`id_cidade`)
    REFERENCES `ebcpc187_ebcp`.`cidade` (`id_cidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`edital`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`edital` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`edital` (
  `id_edital` INT NOT NULL AUTO_INCREMENT,
  `id_concurso` INT NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `data` DATE NOT NULL,
  `caminho_arquivo` VARCHAR(255) NOT NULL,
  `atualizacao` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_edital`),
  INDEX `edital_concurso_idx` (`id_concurso` ASC),
  CONSTRAINT `fk_edital_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`lingua`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`lingua` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`lingua` (
  `id_lingua` INT NOT NULL AUTO_INCREMENT,
  `lingua` VARCHAR(50) NULL,
  PRIMARY KEY (`id_lingua`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`lingua_estrangeira`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`lingua_estrangeira` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`lingua_estrangeira` (
  `id_lingua_estrangeira` INT NOT NULL AUTO_INCREMENT,
  `id_lingua` INT NOT NULL,
  `id_concurso` INT NOT NULL,
  PRIMARY KEY (`id_lingua_estrangeira`),
  INDEX `fk_lingua_concurso_lingua_idx` (`id_lingua` ASC),
  INDEX `fk_lingua_concurso_id_concurso_idx` (`id_concurso` ASC),
  CONSTRAINT `fk_lingua_estrangeira_id_lingua`
    FOREIGN KEY (`id_lingua`)
    REFERENCES `ebcpc187_ebcp`.`lingua` (`id_lingua`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lingua_estrangeira_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`pne`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`pne` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`pne` (
  `id_pne` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(50) NULL,
  PRIMARY KEY (`id_pne`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`inscricao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`inscricao` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`inscricao` (
  `id_inscricao` INT NOT NULL AUTO_INCREMENT,
  `id_candidato` INT NOT NULL,
  `id_concurso` INT NOT NULL,
  `id_cargo` INT NOT NULL,
  `id_cidade_prova` INT NOT NULL,
  `id_lingua_estrangeira` INT NULL,
  `data_inscricao` DATE NOT NULL,
  `isencao` TINYINT NOT NULL DEFAULT 0,
  `homologa_isento` TINYINT NOT NULL DEFAULT 0,
  `homologado` TINYINT NOT NULL DEFAULT 0,
  `doador_sangue` TINYINT NOT NULL DEFAULT 0,
  `id_pne` INT NULL,
  `pne_especifico` VARCHAR(255) NULL,
  `nis` TINYINT NULL DEFAULT 0,
  `afrodescendente` TINYINT NOT NULL DEFAULT 0,
  `prova_ampliada` TINYINT NULL DEFAULT 0,
  `sala_facil_acesso` TINYINT NULL DEFAULT 0,
  `auxilio_transcricao` TINYINT NULL DEFAULT 0,
  `outra_solicitacao` VARCHAR(255) NULL,
  PRIMARY KEY (`id_inscricao`),
  INDEX `concurso_inscricao_idx` (`id_concurso` ASC),
  INDEX `candidato_inscricao_idx` (`id_candidato` ASC),
  INDEX `cargo_inscricao_idx` (`id_cargo` ASC),
  INDEX `fk_inscricao_id_cidade_prova_idx` (`id_cidade_prova` ASC),
  INDEX `fk_inscricao_id_lingua_concurso_idx` (`id_lingua_estrangeira` ASC),
  INDEX `fk_inscricao_id_pne_idx` (`id_pne` ASC),
  CONSTRAINT `fk_inscricao_id_candidato`
    FOREIGN KEY (`id_candidato`)
    REFERENCES `ebcpc187_ebcp`.`candidato` (`id_candidato`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_id_cargo`
    FOREIGN KEY (`id_cargo`)
    REFERENCES `ebcpc187_ebcp`.`cargo` (`id_cargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_id_cidade_prova`
    FOREIGN KEY (`id_cidade_prova`)
    REFERENCES `ebcpc187_ebcp`.`cidade_prova` (`id_cidade_prova`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_id_lingua_concurso`
    FOREIGN KEY (`id_lingua_estrangeira`)
    REFERENCES `ebcpc187_ebcp`.`lingua_estrangeira` (`id_lingua_estrangeira`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_id_pne`
    FOREIGN KEY (`id_pne`)
    REFERENCES `ebcpc187_ebcp`.`pne` (`id_pne`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`prova`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`prova` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`prova` (
  `id_prova` INT NOT NULL AUTO_INCREMENT,
  `id_cargo` INT NOT NULL,
  `caminho_prova` VARCHAR(255) NOT NULL,
  `data_inicio` DATETIME NOT NULL,
  `data_fim` DATETIME NOT NULL,
  PRIMARY KEY (`id_prova`),
  INDEX `prova_cargo_idx` (`id_cargo` ASC),
  CONSTRAINT `prova_cargo`
    FOREIGN KEY (`id_cargo`)
    REFERENCES `ebcpc187_ebcp`.`cargo` (`id_cargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`tipos_recursos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`tipos_recursos` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`tipos_recursos` (
  `id_tipos_recursos` INT NOT NULL AUTO_INCREMENT,
  `tipos_recursos` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_tipos_recursos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`recurso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`recurso` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`recurso` (
  `id_recurso` INT NOT NULL AUTO_INCREMENT,
  `id_concurso` INT NOT NULL,
  `id_tipos_recursos` INT NOT NULL,
  `inicio_recurso` DATETIME NOT NULL,
  `final_recurso` DATETIME NOT NULL,
  PRIMARY KEY (`id_recurso`),
  INDEX `fk_recurso_id_concurso_idx` (`id_concurso` ASC),
  INDEX `fk_recurso_id_tipo_recurso_idx` (`id_tipos_recursos` ASC),
  CONSTRAINT `fk_recurso_id_concurso`
    FOREIGN KEY (`id_concurso`)
    REFERENCES `ebcpc187_ebcp`.`concurso` (`id_concurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recurso_id_tipos_recursos`
    FOREIGN KEY (`id_tipos_recursos`)
    REFERENCES `ebcpc187_ebcp`.`tipos_recursos` (`id_tipos_recursos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`recurso_candidato`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`recurso_candidato` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`recurso_candidato` (
  `id_recurso_candidato` INT NOT NULL AUTO_INCREMENT,
  `id_recurso` INT NOT NULL,
  `id_inscricao` INT NOT NULL,
  `data_hora_recurso` DATETIME NOT NULL,
  `texto_recurso` LONGTEXT NOT NULL,
  `arquivo_anexo` VARCHAR(255) NULL,
  `id_edital_resposta` INT NULL,
  PRIMARY KEY (`id_recurso_candidato`),
  INDEX `fk_recurso_candidato_id_edital_idx` (`id_edital_resposta` ASC),
  INDEX `fk_recurso_candidato_id_inscricao_idx` (`id_inscricao` ASC),
  INDEX `fk_recurso_candidato_id_recurso_idx` (`id_recurso` ASC),
  CONSTRAINT `fk_recurso_candidato_id_edital`
    FOREIGN KEY (`id_edital_resposta`)
    REFERENCES `ebcpc187_ebcp`.`edital` (`id_edital`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recurso_candidato_id_inscricao`
    FOREIGN KEY (`id_inscricao`)
    REFERENCES `ebcpc187_ebcp`.`inscricao` (`id_inscricao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recurso_candidato_id_recurso`
    FOREIGN KEY (`id_recurso`)
    REFERENCES `ebcpc187_ebcp`.`recurso` (`id_recurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`usuario` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(32) NOT NULL,
  `ativo` TINYINT NOT NULL DEFAULT 0,
  `nivelacesso` INT NOT NULL,
  PRIMARY KEY (`id_usuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`curriculos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`curriculos` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`curriculos` (
  `id_curriculos` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `datanascimento` DATE NOT NULL,
  `cidade` VARCHAR(50) NOT NULL,
  `estado` VARCHAR(2) NOT NULL,
  `telefone` VARCHAR(11) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `minicurriculo` LONGTEXT NOT NULL,
  `cargo` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_curriculos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`configuracoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`configuracoes` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`configuracoes` (
  `id_configuracoes` INT NOT NULL AUTO_INCREMENT,
  `autenticacao_ldap_local` TINYINT NOT NULL,
  `ldap_base_dn` VARCHAR(255) NULL,
  `ldap_dominio` VARCHAR(100) NULL,
  `ldap_ip` VARCHAR(15) NULL,
  `ldap_porta` VARCHAR(6) NULL,
  `ldap_grupo` VARCHAR(45) NULL,
  `ireport_url` VARCHAR(255) NULL,
  `ireport_usuario` VARCHAR(45) NULL,
  `ireport_password` VARCHAR(45) NULL,
  PRIMARY KEY (`id_configuracoes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`usuario_instituicao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`usuario_instituicao` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`usuario_instituicao` (
  `id_usuario_instituicao` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(32) NOT NULL,
  `ativo` TINYINT NOT NULL DEFAULT 1,
  `id_instituicao` INT NOT NULL,
  PRIMARY KEY (`id_usuario_instituicao`),
  INDEX `fk_usuario_instituicao_id_instituicao_idx` (`id_instituicao` ASC),
  CONSTRAINT `fk_usuario_instituicao_id_instituicao`
    FOREIGN KEY (`id_instituicao`)
    REFERENCES `ebcpc187_ebcp`.`instituicao` (`id_instituicao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ebcpc187_ebcp`.`logger_admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ebcpc187_ebcp`.`logger_admin` ;

CREATE TABLE IF NOT EXISTS `ebcpc187_ebcp`.`logger_admin` (
  `id_logger_admin` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_registro_tabela` INT NULL,
  `acao` VARCHAR(45) NOT NULL,
  `tabela` VARCHAR(45) NOT NULL,
  `query` VARCHAR(1000) NOT NULL,
  `usuario` VARCHAR(255) NOT NULL,
  `data_hora` DATETIME NOT NULL,
  PRIMARY KEY (`id_logger_admin`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
