DELIMITER $

$

CREATE OR REPLACE TRIGGER trg_compras_ins BEFORE INSERT ON compras
FOR EACH ROW 
BEGIN 
	DECLARE existe INT DEFAULT 0;
	DECLARE quantidade_unidade INT;
	DECLARE aux_comprador VARCHAR(30);

	/*
	 * Atualiza o comprador da compra com o comprador apontado no cadastro de pessoa
	 * e caso não tenha comprador atualiza com o usuário da inclusão (usuario_cadastro)
	 */
	SELECT comprador
	  INTO aux_comprador
	  FROM cadastro_pessoa
	 WHERE codigo = NEW.fornecedor;
	
	SET NEW.comprador = IFNULL(aux_comprador,NEW.usuario_cadastro); 

/*
	 * Trata o limite de compras para o produtor/produto, incluir caso não exista ainda.
	 */
	SELECT EXISTS(SELECT 1
					FROM limite_compra a
				   WHERE a.id_produtor = NEW.fornecedor
					 AND a.id_produto  = NEW.cod_produto) 
		   INTO existe;
	  
	IF existe = 0 THEN 
		CALL cria_limite_compra(NEW.fornecedor,NEW.cod_produto);
	END IF;

    /*
     * Obtém a quantidade da unidade de comercialização do produto para trabalhar com Kg
     */
	SELECT quantidade_un
	  INTO quantidade_unidade
	  FROM cadastro_produto
	 WHERE codigo = NEW.cod_produto;
	
	/*
	 * Movimento com pendência de nota fiscal
	*/
	IF NEW.movimentacao IN ('COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO') AND NEW.estado_registro = 'ATIVO' THEN

		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada + (NEW.quantidade * quantidade_unidade),
		       atualizado_em        = now(),
		       atualizado_por       = NEW.usuario_alteracao
		 WHERE id_produtor = NEW.fornecedor
		   AND id_produto  = NEW.cod_produto;
		
	/*
	 * Movimento para baixar nota fiscal
	*/
    ELSEIF NEW.movimentacao IN ('ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO') AND NEW.estado_registro = 'ATIVO' THEN
	
		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada - (NEW.quantidade * quantidade_unidade),
		       atualizado_em        = now(),
		       atualizado_por       = NEW.usuario_alteracao
		 WHERE id_produtor = NEW.fornecedor
		   AND id_produto  = NEW.cod_produto;
    END IF;

END;

$

CREATE OR REPLACE TRIGGER trg_compras_upd BEFORE UPDATE ON compras
FOR EACH ROW 
BEGIN 
	DECLARE existe INT;
	DECLARE quantidade_unidade INT;
	DECLARE excluido BOOLEAN DEFAULT FALSE;
	DECLARE quantidade_alterada BOOLEAN;
	DECLARE compra BOOLEAN DEFAULT FALSE;
	DECLARE entrada BOOLEAN DEFAULT FALSE;

	SET excluido = (NEW.estado_registro = 'EXCLUIDO' AND OLD.estado_registro = 'ATIVO');
	SET quantidade_alterada = (NEW.quantidade <> OLD.quantidade);
	SET compra  = FIND_IN_SET(NEW.movimentacao, 'COMPRA,TRANSFERENCIA_SAIDA,SAIDA,SAIDA_FUTURO');
	SET entrada = FIND_IN_SET(NEW.movimentacao, 'ENTRADA,TRANSFERENCIA_ENTRADA,ENTRADA_FUTURO');

	IF excluido OR quantidade_alterada THEN 
		/*
		 * Trata o limete de compras para o produtor/produto, incluir caso não exista ainda.
		 */
		
		SELECT EXISTS(SELECT 1
						FROM limite_compra a
					   WHERE a.id_produtor = NEW.fornecedor
						 AND a.id_produto  = NEW.cod_produto) 
			   INTO existe;
		  
		IF existe = 0 THEN 
			CALL cria_limite_compra(NEW.fornecedor,NEW.cod_produto);
		END IF;
	
	    /*
	     * Obtém a quantidade da unidade de comercialização do produto para trabalhar com Kg
	     */
		SELECT quantidade_un
		  INTO quantidade_unidade
		  FROM cadastro_produto
		 WHERE codigo = NEW.cod_produto;
			
		/*
		 * Movimento de compra
		*/
		IF compra AND excluido THEN
			UPDATE limite_compra 
			   SET quantidade_utilizada = quantidade_utilizada - (OLD.quantidade * quantidade_unidade),
			       atualizado_em        = now(),
			       atualizado_por       = NEW.usuario_alteracao
			 WHERE id_produtor = NEW.fornecedor
			   AND id_produto  = NEW.cod_produto;
		ELSEIF compra AND quantidade_alterada THEN
			UPDATE limite_compra 
			   SET quantidade_utilizada = (quantidade_utilizada - (OLD.quantidade * quantidade_unidade)) + (NEW.quantidade * quantidade_unidade),
			       atualizado_em        = now(),
			       atualizado_por       = NEW.usuario_alteracao
			 WHERE id_produtor = NEW.fornecedor
			   AND id_produto  = NEW.cod_produto;
		/*
		 * Movimento para baixar nota fiscal
		*/
		ELSEIF entrada AND excluido THEN 
			UPDATE limite_compra 
			   SET quantidade_utilizada = quantidade_utilizada + (OLD.quantidade * quantidade_unidade),
			       atualizado_em        = now(),
			       atualizado_por       = NEW.usuario_alteracao
			 WHERE id_produtor = NEW.fornecedor
			   AND id_produto  = NEW.cod_produto;
		ELSEIF entrada AND quantidade_alterada THEN
			UPDATE limite_compra 
			   SET quantidade_utilizada = (quantidade_utilizada + (OLD.quantidade * quantidade_unidade)) - (NEW.quantidade * quantidade_unidade),
			       atualizado_em        = now(),
			       atualizado_por       = NEW.usuario_alteracao
			 WHERE id_produtor = NEW.fornecedor
			   AND id_produto  = NEW.cod_produto;
		END IF;		
   	END IF;

	
END;


$

DELIMITER ;
