<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parte 2 - Prumo tecnologia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
</head>
<body>

    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        Digite um CNPJ
                    </div>
                    <div class="card-body">
                        <input id="cnpj" name="cnpj"/>
                        <button id="enviar">Consultar</button>
                        <br />
                        <div id="errorinputCnpj"></div> 
                        <div id="success"></div>  
                    </div>
                </div>
            </div>
        </div>

        <div id="info" class="row pt-3">
            <div class="col">
                <div id="divCnpj"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $("#info").hide();

            function getInfo(cnpj){
                var url="https://publica.cnpj.ws/cnpj/"+cnpj;
                var Httpreq = new XMLHttpRequest(); // a new request
                Httpreq.open("GET",url,false);
                Httpreq.send(null);
                return Httpreq.responseText;
            }

            $( "#enviar" ).click(function() {

                var inputCnpj= document.getElementById("cnpj").value;

                //deve ter 14 caracteres
                if(inputCnpj.length < 14 || inputCnpj.length > 14){
                    document.getElementById("errorinputCnpj").innerHTML = '<div class="alert alert-danger">CNPJ inválido. O CNPJ deve conter 14 números.</div>';
                    $("#info").hide();
                    return false;
                }else{
                    document.getElementById("errorinputCnpj").innerHTML = '';
                }

                
                var result= getInfo(inputCnpj);
                var response= JSON.parse(result);

                if(!response.razao_social){
                    document.getElementById("divCnpj").innerHTML = '';
                    document.getElementById("errorinputCnpj").innerHTML = '<br /><b>CNPJ não existe!</b>';
                }else{
                    var text = "<table class='table table-striped'>";
                    text += "<thead><th></th><th></th><th></th><th></th><th></th></thead>";
                    text += "<tbody>";
                        text += "<tr>";
                        text += "<td>" + response.estabelecimento['cnpj'] + "</td>";
                        text += "<td>" + response.razao_social + "</td>";
                        text += "<td>" + response.capital_social + "</td>";
                        text += "<td>" + response.porte['descricao'] + "</td>";

                        var endereco= response.estabelecimento['tipo_logradouro'] + " "+ response.estabelecimento['logradouro'] +" " + response.estabelecimento['numero'] +", "+ response.estabelecimento['bairro'] +"-"+ response.estabelecimento['cep'];

                        text += "<td>" + endereco +"</td>";
                        text += "</tr>";
                    text += "</tbody>";
                    text += "</table>";

                    $("#info").show();
                    document.getElementById("divCnpj").innerHTML = text;

                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "insert.php",
                        data: {
                            cnpj: response.estabelecimento['cnpj'],
                            razao: response.razao_social,
                            capital: response.capital_social,
                            porte: response.porte['descricao'],
                            endereco: endereco
                        },
                        data_type: 'json',
                        success: function (resultado) {
                            resultado = JSON.parse(resultado);
                            if (resultado.ok==true) {
                                valid = false;
                                document.getElementById("success").innerHTML = 'CNPJ Cadastrado com sucesso</div>';
                            }
                        }
                    });

                }

            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>