DELIMITER $

$
 DROP PROCEDURE IF EXISTS cria_limite_compra 
$

CREATE PROCEDURE cria_limite_compra(IN produtor INT, IN produto INT)
BEGIN
    DECLARE total_utilizado DECIMAL(11,3);
    SET total_utilizado = 0;
   
    -- Obtém o saldo do produtor
    -- Observação:
    SELECT SUM(CASE 
           	WHEN movimentacao IN ("ENTRADA", "TRANSFERENCIA_ENTRADA", "ENTRADA_FUTURO") THEN -quantidade * b.quantidade_un 
            ELSE quantidade * b.quantidade_un
            END
           ) AS saldo_pendente
      INTO total_utilizado
      FROM compras a,
           cadastro_produto b
     WHERE a.estado_registro  = 'ATIVO'
      AND a.movimentacao    IN ('ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO', 'COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO')
      AND a.cod_produto      = produto
      AND a.fornecedor       = produtor
      AND b.codigo           = a.cod_produto;


    IF total_utilizado IS NULL THEN 
        SET total_utilizado = 0;
    END IF;

    -- Cria o registro para controle de limite de compras
    -- 
    INSERT INTO limite_compra (id_produtor, id_produto, quantidade_utilizada, criado_em, criado_por ) VALUES (produtor, produto, total_utilizado, now(), user());
END

$   

DELIMITER ; 


/*
    CALL cria_limite_compra(1113,2); -- Chamada da procedure
*/