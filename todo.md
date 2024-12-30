## Alterações da Tela de Cotações

O campo nome:
- [x] Deixar livre, mas buscar o nome no cadastro de pessoa e gravar na tabela de cotação 
- [X] Quando já cadastrado, gravar o código da pessoa e mostrar se é um favorecido ou produtor na tela 
- [x] Trocar o label por produtor

Campo Produtor:
- [X] Excluir este campo

Tipo Pessoa:
- [X] Tirar este campo

Email:
- [X] Tirar a obrigatoriedade 

Documento:
- [X] Tirar o campo;

Telefone:
- [X] Não obrigatorio

Produto:
- [X] Colocar um combo
- [X] Colocar a unidade no combo da descricao do produto

Preço Máximo:
- [X] Colocar separador decimal

- [X] Criar tabela de domínio de motivo e tela de manutenção

- [X] Criar campo de observação

- [ ] Verificação de cancelamento no Sankhya
  - [ ] Ver com o Rubens uma fatura que já tenha sido processada no Sankhya e que não pode mais ser cancelada
        para testar a rotina de cancelamento da fatura no SUIF.
        forma_pagamento.php (linha 726)
    Quando da exclusão de um pagamento ao favorecido, verificar se a fatura pode ser excluida no Sankhya
    Caso não possa, não excluir o pagamento no SUIF

