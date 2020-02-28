<?php
namespace Ebanx\Benjamin\Models\Responses;


class ResponseHandler {
    public static function execute($response){
        if ($response["status"] === "SUCCESS"){
            if ($response["payment"]["payment_type_code"] === 'boleto'){
                echo "<h1>Para confirmar seu pedido efetue o pagamento do boleto </h1> <br/>";
                print "<iframe src=\" " . $response["payment"]["boleto_url"] . " \" height=\"60%\" width=\"60%\" > </iframe>";
            } else {
                echo "<h1>Pagamento efetuado com sucesso</h1>";
            }
        } else {
            ResponseHandler::errorHandler($response["status_code"]);
        }
    }

    public static function errorHandler($error_code){
        echo "<h1> Erro no pagamento: </h1><br>";
        switch ($error_code){
            case "BP-DR-23":
                echo "O CPF informado deve ser válido";
                break;
            case "BP-DR-14":
                echo "O Nome informado não deve exceder 100 caracteres";
                break;
            case "BP-DR-17":
                echo "O email informado não deve exceder 100 caracteres";
                break;
            case "BP-DR-26":
                echo "O número do endereço não foi informado";
                break;
            case "BP-DR-29":
                echo "O estado informado deve ser um estado válido do Brasil";
                break;
            case "BP-DR-35":
                echo "Esse tipo de pagamento não está habilitado, preencha as informções do cartão corretamente";
                break;
            case "BP-DR-33":
                echo "Quantidade de parcelas inválida";
                break;
            default:
                echo "Erro desconhecido";
        }
    }

}