# ebcp
A subscriber software for a Brazilian public sector.

The software was developed in PHP raw/pure without frameworks.

To see how the application works, run the database sql file: banco_ebcp.sql

Wo access the application you must add a user to login inside of database.

The database is named EBCPC187_EBCP.

To test the system, create a user inside of table USUARIO with password: 123.
INSERT INTO usuario (nome, email, login, senha, ativo, nivelacesso) VALUES ("John", "johnafmendes@gmail.com", "johnafmendes@gmail.com", "202cb962ac59075b964b07152d234b70", 1, 3);

Before access, the system is developed to use LDAP or Local authentication. So, you must insert one line inside CONFIGURACOES table and confirm that you are using a local authentication.

INSERT INTO configuracoes (autenticacao_ldap_local) VALUES (1); //to use a local authentication

To access the system by authentication, access: LOCALHOST/ADMIN

Type in LOGIN: johnafmendes@gmail.com
type in SENHA: 123

You can register yourself in another area for candidates.

Access: LOCALHOST
Click in CADASTRAR to create an account.

Enjoy.

John Mendes
johnafmendes@gmail.com
