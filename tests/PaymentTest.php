<?php

namespace Tests;


use Ebanx\Benjamin\Models\Address;
use Ebanx\Benjamin\Models\Card;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;
use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\PaymentHandler;
use Ebanx\Benjamin\Models\Person;

class PaymentTest extends TestCase {

    public function testPaymentRequest(){
        $fake_request = [
            'value' => '22',
            'name' => 'sayuri',
            'document' => '12028707674',
            'cellphone' => '11111111',
            'email' => 'sa@sa.com',
            'country' => 'br',
            'state' => 'pr',
            'city' => 'curitiba',
            'zipcode' => '80090987',
            'street' => 'marechal',
            'address-number' => '22',
            'street-complement' => 'apt 44',
            'payment-type' => 'boleto',
            'creditcard-holder' => 'sayuri',
            'creditcard-number' => '4111111111111111',
            'creditcard-expiration' => '02/20',
            'credicard-instalments' => '1'

        ];

        $paymentObject = PaymentHandler::execute($fake_request);
        $expected_response = self::expectedResponse($fake_request);
        if ($paymentObject === $expected_response){
            print 'receive: ' . $paymentObject;
            print 'expected: ' . $expected_response;
        }
    }

    public static function expectedResponse($request){
        $expected_response = [
            'type' => $request['payment-type'],
            'address' => new Address([
                'address' => $request['street'],
                'city' => $request['city'],
                'country' => Country::BRAZIL,
                'state' => $request['state'],
                'streetComplement' => $request['street-complement'],
                'streetNumber' => $request['address-number'],
                'zipcode' => $request['zipcode'],
            ]),
            'amountTotal' => $request['value'],
            'deviceId' => 'b2017154beac2625eec083a5d45d872f12dc2c57535e25aa149d3bdb57cbdeb9',
            'merchantPaymentCode' => hash('md5', time()),
            'note' => 'Example payment.',
            'person' => new Person([
                'type' => 'personal',
                'document' => $request['document'],
                'email' => $request['email'],
                'ip' => '30.43.84.28',
                'name' => $request['name'],
                'phoneNumber' =>  $request['cellphone'],
            ]),
            'card' => new Card([
                'autoCapture' => true,
                'createToken' => true,
                'cvv' => $request['creditcard-cvv'],
                'dueDate' => new \DateTime($request['creditcard-expiration']),
                'name' => $request['creditcard-holder'],
                'number' => $request['creditcard-number'],

            ]),
            'instalments' => $request['creditcard-installments'],
            'dueDate' => new \DateTime('now')
        ];

        return $expected_response;
    }

}