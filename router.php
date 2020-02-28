<?php

require_once './vendor/autoload.php';

use Ebanx\Benjamin\Models\Responses\ResponseHandler;
use Ebanx\Benjamin\Models\PaymentHandler;

$response = PaymentHandler::execute($_POST);
ResponseHandler::execute($response);
