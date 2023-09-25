![Concordamos](https://concordamos.com.br/wp-content/uploads/2023/07/concordamos_logotipo-completo_preenchido_preto.png)

Descubra o poder de concordar - Plugin WordPress para organizar votações com Voto Quadrático

## UMA FORMA DE VOTAR PELO CONSENSO

Imagine um lugar onde suas opiniões têm peso, não importa o quão diferentes sejam. No Concordamos, acreditamos na participação e no encontro de consensos. Somos diferentes de outros meios de votação.  Valorizamos não apenas a sua escolha, mas também a intensidade do seu apoio. Descubra como estamos promovendo decisões mais inclusivas.
Botão: Conheça mais o projeto

O Concordamos é uma ferramenta flexível e de código aberto, disponível gratuitamente, que pode ser facilmente integrada em qualquer site construído em WordPress.

O que torna o Concordamos único é a sua metodologia, chamada de Votação Quadrática, que capacita os eleitores a expressarem suas preferências de forma mais precisa e flexível. Em vez de se limitar a escolher apenas uma opção, os eleitores podem distribuir um número limitado de créditos de votos entre as suas escolhas favoritas. Isso promove uma abordagem menos centralizada e mais inclusiva para a tomada de decisões. Venha explorar como o Concordamos está revolucionando a forma como as pessoas participam e influenciam processos democráticos.
#

## CONFIGURAÇÃO DO PLUGIN

Sobre o _plugin_:
- Contribuição de: ITSRIO
- Disponível em inglês e português
- Tags: quadratic, voting, vote
- Licença: GPLv2 ou mais atual
- URL da Licença: https://www.gnu.org/licenses/gpl-2.0.html

O _plugin_ permite, com muita flexibilidade:

- Configurar o total de números de créditos que cada eleitor recebe
- É possível votar com ou sem cadastro e login na plataforma
- Os votos podem ser tanto positivos quanto negativos
- Gerar links de voto cujo uso é único para realização de pesquisas, garantindo o anonimato

### PRÉ-REQUISITOS

Para instalar o _plugin_ em uma plataforma _WordPress_ é necessário que atenda pré-requisito(s):
- Versão do _WordPress_: 5.8 ou mais atual
- tag estável: 0.1.0
- Versão do _PHP_: 7.4
- Habilitar _API REST_

Esse _plugin_ foi testado na versão 6.3 do _WordPress_.

### INSTALAÇÃO:

- Faça o upload completo da pasta `concordamos` no diretório de `/wp-content/plugins/`
2. Ative o plugin através da tela dos Plugins instalados, em Plugins, no painel do _WordPress_

### PRIMEIROS PASSOS

Ao instalar o _plugin_ em um tema do _WordPress_ são necessárias configurações para que o _layout_ disponibilizado pelo _plugin_ seja incorporado nas páginas do tema. Para utilização completa do _plugin_ é necessária realização de dois fluxos de configuração, de modo que todas as páginas sejam implementadas na plataforma que o instalou.

O primeiro passo para editar o _plugin_ do **Concordamos** é realizar o _login_ em um cadastro que possui permissão de administrador no painel WordPress. Após o primeiro passo, deve acessar o painel do _WordPress_ do site para aplicar as páginas de votações.

Portanto, o segundo passo é clicar no ícone do _WordPress_ acima do cabeçalho:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/94f89696-6f7a-427d-bce6-f16e8f3d3075)

Ocorrerá o direcionamento para o painel do _WordPress_:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/378c1f29-eef9-4912-9df1-5ddce389e6e3)

### COMO CONFIGURAR AS PÁGINAS DE VOTAÇÕES

Para implementar as páginas de votações, após acessar o painel do WordPress, é necessário acessar **Páginas** e clicar em **adicionar nova** para adicionar uma nova página:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/b7a86ed7-d2e2-40a6-83f7-55378dbcb6c6)

Após clicar **adicionar nova**, deve atribuir um nome e clicar em **publicar**. Iremos chamá-la de **Criar votação**, mas você pode atribuir um nome de sua escolha. A página deve possuir direcionamento em seu tema, para que usuários da sua plataforma consigam acessá-la:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/e7398457-b6f4-4246-90e4-1016a167c169)

Após criar uma página destinada para as páginas das votações, no menu lateral do _WordPress_ deve acessar **Configuração do Concordamos**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/24f6e7c9-1856-4c54-94ca-6133150b18e0)

No campo **Criar votação** deve selecionar a nova página adicionada no passo anterior. Não se esqueça de clicar em Salvar Configurações:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/edf0db6c-61ac-4820-86b3-021c896ce36c)

Com isso, todas as páginas da votação serão implementadas no site. Sendo elas: **criação de votação**, **lista de votações públicas**, **detalhes da votação**, **votação** e **informações da votação**.

### COMO CONFIGURAR AS PÁGINAS DE CADASTRO

Para implementar as páginas de cadastro, após acessar o painel do _WordPress_, é necessário acessar **Páginas** e clicar em **adicionar nova** para adicionar uma nova página:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/d7f6ae2e-8503-44ab-a402-602faa4cf784)

Após clicar **adicionar nova**, deve atribuir um nome, selecionar o modelo e clicar em publicar. Esse mesmo fluxo deve ser feito para todas as páginas de cadastro, sendo elas: login, cadastro de usuário, minha conta e change password (esqueci a senha). E não se esqueça de publicar!

Assim, chamaremos a primeira página de **Login** e em **Modelo** selecionaremos **Login [Concordamos]**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/153ea38f-b514-4047-8146-2628917528ea)

O mesmo deve ser feito para **Cadastro de usuário** selecionando **Criar usuário [Concordamos]** em **Modelo**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/8247d760-88c9-405e-86c0-0f9aeb3d0c3a)

De mesmo modo para **Minha conta [Concordamos]**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/8b829aae-e3c3-4475-85b4-dc2d1a7300ba)

E, para **Esqueci a senha** selecionando **Change password [Concordamos]**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/3d32c3b7-1c69-4f23-9aa1-68233c93795a)

Com isso, concluímos as configurações disponíveis no _plugin_ do **Concordamos**.

## COMO UTILIZAR AS VOTAÇÕES

### CRIAÇÃO DE VOTAÇÕES
O primeiro passo para criar uma votação é **criar uma conta** através da página de criação de conta fornecida pelo plugin. Para criar a conta é necessário informar um e-mail e uma senha.

Após a criação da conta é possível **criar uma votação**. Para criar uma votação é necessário (quando obrigatório) o preenchimento dos seguintes campos:

1. Selecionar se a votação é pública ou privada (obrigatório)

2. Marcar se a votação requer login para quem for votar

3. Informar o nome da votação (obrigatório)

4. Inserir uma descrição da votação (obrigatório)

5. Informar o número máximo de eleitores que poderão votar (obrigatório)

6. Informar a quantidade de créditos que poderão aplicados como votos (obrigatório)

7. Marcar se haverão votos negativos

8. Preencher pelo menos uma tag (obrigatório)

9. Preencher a data e o horário do período de início e término da votação (obrigatório)

10. Marcar se o resultado deve ser exibido somente ao final da votação

11. Adicionar mais de uma opção de voto (obrigatório)

Alguns dos pontos acima necessitam aprofundamento.

**Sobre 1**, ao criar uma votação pública, ela ficará disponível na lista de votações públicas. As votações privadas só aparecem na página da minha conta, elas ficam disponíveis: na aba votações criadas por mim, para quem criou a votação; na aba votações que eu participei, para quem votou.

**Sobre 2**, ao marcar que a votação requer login, somente quem cadastrar uma conta e realizar o login poderá votar. Se a votação não requer login, qualquer usuario poderá votar. Vale notar que somente quem vota com login tem acesso ao resultado da votação.
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/ca400ba9-bc60-4957-8e39-17dbf30e29e8)

**Sobre 7**, ao marcar se haverão votos negativos, quer dizer que o usuário poderá diminuir os votos de uma opção votando nela negativamente. Por exemplo, digamos que um usuário atribuiu 4 votos para uma mesma opção, caso um outro usuário dê 3 votos negativos para essa mesma opção, o total de votos da opção será 1.

**Sobre 8**, ao inserir mais de uma tag, as tags devem ser separadas por vírgula.
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/440d4bba-961f-4f5d-a294-f950f8d8717c)

**Sobre 9**, o preenchimento do período de início e término da votação é a única informação que pode ser alterada após a criação da votação.
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/0c9676fd-e542-4f05-b18b-e128cfcfa814)

**Sobre 11**, cada opção de voto adicionada, tem como preenchimento obrigatório o título e tem como preenchimento opcional a descrição e um link.
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/c016f45e-8cc5-4231-87a1-4f76e58642ef)

Após o preenchimento de pelo menos todos os campos obrigatórios, deve-se clicar em **Criar Votação**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/0c9b7f2e-72fb-4d6c-a7e9-f093455bbfbd)

Se a votação criada for uma **votação pública**, ocorrerá o direcionamento para a **Links da Votação**, na página onde ficam as **Informações da Votação**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/f2dd1f79-4be9-4a57-a3df-493363624d9b)

A **URL da votação** pode ser compartilhada, ela direcionará direto para a aba **Resultados Detalhados**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/18bd42de-095d-4e88-9cc0-7a228a4b0eb4)

A **URL privada de administrador** ao ser acessada por uma conta _que não criou a votação_, direciona para a mesma página acima, com o acesso aos links de votação e a possibilidade de editar as o período de votação:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/91528f52-8dab-41c3-9359-f8b8cd920763)

Se votação a criada for **votação privada**, ocorrerá o direcionamento para a aba links da votação. A URL da votação e a URL privada de administrador possui o mesmo comportamento que na votação pública. _**É importante ressaltar que a votação privada é acessada somente através das URLs individuais de voto**_:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/a0dfbba3-01cd-4570-9c80-34946e8b875f)


### COMO VOTAR
Existem duas formas de acessar a página de **votação pública**: através da lista de votações públicas ou através da própria URL da página de votação.

Na lista de votações públicas, é necessário clicar na votação desejada:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/29840f44-7a78-43e8-a057-ccf7c0421aca)

Ao acessar uma votação aberta, isto é, que está dentro do período de votação, ocorre o direcionamento para a página de **detalhes da votação**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/e770e679-4fd6-4a46-8ec3-76892ffd579d)

Além do **título**, **descrição**, **tags** e **período de votação**, a página de detalhes de votações oferece informações sobre as **opções de votos** disponíveis e a **quantidade de créditos** que poderão ser aplicados nelas como votos.

Para acessar a votação, basta clicar em **Participar da votação**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/abd1c454-6ddf-45b9-a755-86241cd96843)

Ocorrerá o direcionamento para a **página de votação** onde os votos são atribuídos. _**No voto quadrático cada voto possui um custo de créditos**_.

Chama-se voto quadrático porque a quantidade de votos é a raiz quadrada do custo de créditos. Por exemplo, 1 voto custa 1 crédito porque a raiz quadrada de 1 é 1; 2 votos custam 4 créditos porque a raiz quadrada de 4 é 2, e assim sucessivamente.
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/ab60aa40-bbc1-4088-90bd-4791331b5a25)

Conforme os créditos são atribuídos eles são contabilizados visualmente na interface e são distribuídos como votos. Após votar, deve clicar em **Confirmar Voto**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/eaa851b7-dbf4-4c83-96c4-7cf4bbc5bd53)

### RESULTADOS DA VOTAÇÃO
Os resultados da votação ficam na página **Informações da Votação**. Contudo, eles só podem ser visualizados por quem criou a votação e por quem votou com o login habilitado.

O resultado pode ser visto através de um gráfico disponível na aba **Resultados Detalhados**:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/15f8152b-364f-4a53-bc4a-7b8ac542d1cd)
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/f13020fd-dbc6-4eaa-bf0f-fd86ff5bd1cf)

 E, os votos realizados por um usuário podem ser consultados por ele em **Meu Voto**.

Caso durante a criação da votação tenha sido marcado para os resultados aparecerem somente ao fim do período da votação, a aba **Resultados Detalhados** não ficará disponível enquanto a votação não encerrar:
![image](https://github.com/itsrioadmin/Concordamos/assets/75261677/ced39ee9-737a-4778-9f12-3e4f04746a8a)

Assim, conclui-se o fluxo de criação de votação, de votação e de consulta aos resultados da votação.
