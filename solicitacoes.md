# Tarefas do SUIF

## 06/08/2024 - SAULO  OK
-Informações de qualidade dos produtos quando da entrada do romaneio
    Criar estrutura para cadastrar os parâmetros de qualidade 
    Criar tela para informar a qualidade do produto quanto da entrada
        Enviar documento (PDF, imagem) por email para o produtor informando a qualidade apurada

    Produtos:
        Café: Mesma coisa como na tela de compras
            Tipo: pode ser (7, 7/8, 8, 600 DEF, 800 DEF, 1000 DEF)

            Umidade: Informar percentual
            Impureza: Informar percentual
            Brocado: Informar percentual
            Quebrado: Informar percentual
            Fundo Peneira 10: Informar percentual
            Fundo Peneira 12: Informar percentual

        Cacau:
            Umidade: Informar percentual

        Pimenta:
            Tipo: SOL, SECADO, G4G 

            Umidade: Informar percentual
            Gramagem (gr/l)
            Impureza: Informar percentual
            Densidade: Informar percentual
            M.E.: Informar percentual
            Chocha: Informar percentual
            Mofado: Informar percentual


## 16/08/2024
    Ok Gerar as sequencias que estão faltando no conta dos favorecidos identificando as contas repetidas.

## 12/11/2024
    Criar uma nova tela, cópia de compras, onde os registros filtrados serão "estado_registro = 'FAZENDA'"
    Alterar a API como Sankhya e passar o novo campo AD_ESTATOREGISTROSUIF = 'FAZENDA'
    Esta compra não poderá aparecer nas outras funcionalidades do sistema

## 04/02/2024
    Colocar informações no SUIF no relatório:
        https://suif.grancafe.com.br/sis/financeiro/compras/compra_impressao.php

        

# Backoffice
## 05/12/2024 - 06/12/2024
1. Limite de Compras
   1. Cria no Sankhya tabela de porte do cliente (pequeno, médio ou grande).
   2. Para cada porte cadastrado, será informado a quantidade de limite de compras
   3. Trazer esta informação no cadastro do cliente.
   
2. Preço Gerencial
   1. Apresentar todos os produtores, independente se forem pessoa física ou jurídica
   2. Os valores apresentados no grid, as taxas devem ser somadas qdo de um pessoa física.
   3. No caso de pessoa jurídica, deve-se fazer o cálculo por fora das taxas e o valor gerencial; Ou seja, mostrar as taxas imbtidas no total dos produtos.
   4. Apresentar a somattória das colunas

3. Tratamento de frete no preço gerencial
   1. Colocar na entrega ou romaneio se o frete é "Retira" ou "Posto"
   2. Quanto for Posto, que significa que o frete foi por conta do produtor, deve-se dar um desconto de R$ 5,00 (verificar por qual unidade é dado o desconto, peso, saca, quilo...)
   3. O desconto é por produto e por quilo, colocar no cadastro de produtos

4. Certificações
   1. O Rubens vai passar estas informações

5.  Clientes ativos
    1. Todos os clientes ativos 
    2. Colocar em parâmetros o período que se verifica se o produtor esta ativo ou não, hoje é 24 meses 
    3. Colocar na ficha do produtor se o cliente é ativo ou não

6. Carteira de Produtores
   1. Verificar se o controle de carteira vs produtor foi implementado (verikficar definição)
   2. Não bloquear qdo o comprador for diferente
   3. Revalidar conceito de carteira
   4. Relatório de carteira do comprador
   5. Colocar log para ver movimentação dos produtores pode comprador. 

7. No SUIF, na ficha do produtor, colocar o comprador 
   1. Regra do comprador para o produtor é quem fez a última compra 

8.  Gap de Compra X Entrega
    1.  Colocar filtro por comprador, filial
    2.  Colocar a média conforme o filtro
9.  


## 04/04/2024 - Rubens

### Compra - Filial Faturamento
    Alterar tela de compras e demais telas necessários para permitir que o usuário selecione a filial de faturamento do Sankhya.
    conceituação:
    No processo, um usuário pode estar logado em uma filial e fazer o faturamento para outra, desta forma é necessário que se crie um novo campo "filial_faturamento" na tabela compras que deverá ser utilizado para ser enviado ao Sankhya.
    O comprador só poderá selecionar uma das filiais para a qual ele poderá efetuar compras (este cadastro ainda não existe). 
    - Tarefas
      - [X] criar campo filial_faturamento na tabela compras
      - [X] alterar página de inclusão e alteração de compras, assim como os fontes que fazem o envio ao Sankhya
      - [X] no *Backoffice* , criar nova tela para que seja feita a associação de filial e comprador

Após a criação e quando da implantação desta nova funcionalidade, é necessário que seja atualizado o campo conforme comando de update:
    update compras set filial_faturamento = filial where filial_faturamento is null





