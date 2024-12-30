<?php
/*
Export to PHP Array plugin for PHPMyAdmin
@version 4.9.7
Database `suif_grancafe`
Table `filiais`
SELECT descricao, apelido FROM filiais WHERE estado_registro='ATIVO' ORDER BY codigo
Exportar formato PHP Array
*/

$filiais = array(
	array('descricao' => 'LINHARES','apelido' => 'Linhares'),
	array('descricao' => 'JAGUARE','apelido' => 'Jaguaré'),
	array('descricao' => 'CASTANHAL','apelido' => 'Castanhal'),
	array('descricao' => 'ALTAMIRA','apelido' => 'Altamira'),
	array('descricao' => 'ITAMARAJU','apelido' => 'Itamarajú'),
	array('descricao' => 'ITABELA','apelido' => 'Itabela'),
	array('descricao' => 'SAO_MATEUS','apelido' => 'São Mateus'),
	array('descricao' => 'VARGINHA','apelido' => 'Varginha'),
	array('descricao' => 'LINHARES_POLO','apelido' => 'Linhares/Movelar')
);

?>