# Teste Prumo Tecnologia

Parte 1 – Teórica

1. Descreva, com suas palavras, como funciona uma requisição HTTP.
2. Qual a diferença de um redirecionamento 301 para um 302?
3. Explique, com suas palavras, o que é o composer e para que ele serve.
4. Dê algum exemplo de um framework em php que você conhece e cite algumas vantagens e desvantagens de utilizá-lo.
5. O que é GIT?
6. Com quais plataformas de repositórios de código fonte você já trabalhou?


Parte 2 – Prática

Monte uma aplicação simples em php, essa aplicação terá apenas uma rota de cadastro de empresas, que receberá um formulário com um campo 

 ````
 <input name="cnpj"> 
 ````

A aplicação precisa validar o CNPJ, fazer o cálculo dos dígitos para garantir que é um CNPJ válido e logo depois fazer uma consulta na api do cnpj.ws (<https://www.cnpj.ws/>)

O cnpj ws vai retornar alguns dados da empresa, exemplo:

![Exemplo de resposta cnpj.ws](https://www.prumotecnologia.com.br/teste/exemplo-cnpj-ws.png)

O serviço pode retornar também que o cnpj não existe.

Caso o CNPJ exista na base deles precisamos que a aplicação salve em um banco MySQL, na tabela "empresas" os seguintes campos, se eles existirem na resposta:

CNPJ, Razão Social, capital social, Porte (utilizar a descrição do porte na resposta da api), Endereço (concatenar os dados do campo estabelecimento, referentes ao endereço).

Além de salvar no banco, esses dados precisam ser enviados para um e-mail, <exemplo@envio.com.br>, com o assunto: nova empresa cadastrada no sistema $nome\_da\_empresa

Após fazer o envio dos dados exibir uma resposta em JSON com o seguinte formato, em caso de sucesso, o campo email\_enviado, precisa checar se o sistema conseguiu fazer o envio para o e-mail descrito acima.

````
{	
  "data":  {
        "cnpj": $numero_cnpj,
        "mensagem": "CNPJ Cadastrado com sucesso",
        "email\_enviado" : true 
    }
}
```` 

No caso de cnpj com formato inválido, cnpj não cadastrado no cnpj.ws a aplicação precisa dar o seguinte retorno: 

````
{ 
    "error" : {
        "cnpj": $numero_cnpj,
        "message": "Mensagem descrevendo o erro"
    }
}
````


Parte 3 – Complementares

Em um servidor Linux, descreva os comandos para instalar:

1. O NGINX

2. O php, php-fpm e cli

3. MySQL

Mostre um exemplo de código que faça o redirecionamento 301 de uma url [www.site.com/pagina1](http://www.site.com/pagina1) para  [www.site.com/pagina2](http://www.site.com/pagina2) utilizando o htacces (servidor com apache2 e modRewrite)

Agora, mostre como o redirecionamento acima ficaria em um servidor com nginx.

````
As entregas deverão ser feitas via github:

Faça um fork desse repositorio, adicione um arquivo txt com as respostas da parte 1 e 3 (se fizer) no root, adicione a aplicação da parte 2 em uma outra pasta, e depois abra um pull request para o repositorio original
````

