<?php
namespace Ebanx\Benjamin\Models;

use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Card;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Models\Person;
use Ebanx\Benjamin\Models\Responses\ResponseHandler;

class PaymentHandler {
    public static function execute($request){
        $config = new Config([
            'sandboxIntegrationKey' => '1231000',
            'isSandbox' => true,
            'baseCurrency' => Currency::BRL,
        ]);

        $creditcard_config = new CreditCardConfig();
        $paymentObject = self::createPayment($request);

        $result = EBANX($config)->withCreditCardConfig($creditcard_config)->create($paymentObject);

        return($result);
    }
    public static function createPayment(array $request){
        $payment_name = $request["name"];
        $payment_value = $request["value"];
        $payment_document = $request["document"];
        $payment_email = $request["email"];
        $payment_zipcode = $request["zipcode"];
        $payment_state = $request["state"];
        $payment_city = $request["city"];
        $payment_street = $request["street"];
        $payment_street_complement = $request["street-complement"];
        $payment_address_number = $request["address-number"];
        $payment_type = $request["payment-type"];
        $payment_creditcard_holder = $request["creditcard-holder"];
        $payment_creditcard_number = $request["creditcard-number"];
        $payment_creditcard_due_date = $request["creditcard-expiration"];
        $payment_creditcard_cvv = $request["creditcard-cvv"];
        $payment_cellphone = $request["cellphone"];
        $payment_instalments = $request["creditcard-instalments"];

        $payment = new Payment([
            'type' => $payment_type,
            'address' => new Address([
                'address' => $payment_street,
                'city' => $payment_city,
                'country' => Country::BRAZIL,
                'state' => $payment_state,
                'streetComplement' => $payment_street_complement,
                'streetNumber' => $payment_address_number,
                'zipcode' => $payment_zipcode,
            ]),
            'amountTotal' => $payment_value,
            'deviceId' => 'b2017154beac2625eec083a5d45d872f12dc2c57535e25aa149d3bdb57cbdeb9',
            'merchantPaymentCode' => hash('md5', time()),
            'note' => 'Example payment.',
            'person' => new Person([
                'type' => 'personal',
                'document' => $payment_document,
                'email' => $payment_email,
                'ip' => '30.43.84.28',
                'name' => $payment_name,
                'phoneNumber' =>  $payment_cellphone,
            ]),
            'card' => new Card([
                'autoCapture' => true,
                'createToken' => true,
                'cvv' => $payment_creditcard_cvv,
                'dueDate' => new \DateTime($payment_creditcard_due_date),
                'name' => $payment_creditcard_holder,
                'number' => $payment_creditcard_number,

            ]),
            'instalments' => $payment_instalments,
            'dueDate' => new \DateTime('now')
        ]);


        return $payment;
    }
}
