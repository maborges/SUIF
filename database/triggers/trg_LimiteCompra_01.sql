-- Verifica saldo limite compra

DELIMITER $

$
DROP TRIGGER IF EXISTS trg_limite_compra_ins;
$
CREATE TRIGGER trg_limite_compra_ins BEFORE UPDATE ON limite_compra 
FOR EACH ROW 
BEGIN 
	DECLARE limite DECIMAL(12,2);

	SELECT b.limite_compra
	  INTO limite
	  FROM cadastro_pessoa         a,
	       categoria_limite_compra b,
	       cadastro_produto        c
	 WHERE a.codigo             = NEW.id_produtor
	   AND b.categoria_produtor = IFNULL(a.categoria,'P') -- Caso o produtor ainda não tenha categoria
	   AND b.produto            = NEW.id_produto
	   AND c.codigo             = b.produto;
	  
	IF NEW.quantidade_utilizada > limite THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compras excedito para este produtor/produto';
	END IF;
END;

$
DROP TRIGGER IF EXISTS trg_limite_compra_upd;
$
CREATE TRIGGER trg_limite_compra_upd AFTER UPDATE ON limite_compra 
FOR EACH ROW 
BEGIN 
	DECLARE limite DECIMAL(12,2);

	SELECT b.limite_compra
	  INTO limite
	  FROM cadastro_pessoa         a,
	       categoria_limite_compra b,
	       cadastro_produto        c
	 WHERE a.codigo             = OLD.id_produtor
	   AND b.categoria_produtor = IFNULL(a.categoria,'P') -- Caso o produtor ainda não tenha categoria
	   AND b.produto            = OLD.id_produto
	   AND c.codigo             = b.produto;
	  
	IF NEW.quantidade_utilizada > limite THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compras excedito para este produtor/produto';
	END IF;
END;

$


DELIMITER ;
