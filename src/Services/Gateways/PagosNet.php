<?php

namespace Ebanx\Benjamin\Services\Gateways;

use Ebanx\Benjamin\Models\Country;
use Ebanx\Benjamin\Models\Currency;
use Ebanx\Benjamin\Models\Payment;
use Ebanx\Benjamin\Services\Adapters\CashPaymentAdapter;
use Ebanx\Benjamin\Services\Traits\Printable;

class PagosNet extends DirectGateway
{
    use Printable;

    const API_TYPE = 'pagosnet';

    protected static function getEnabledCountries()
    {
        return [Country::BOLIVIA];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::BOB,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new CashPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return 'https://%s.ebanxpay.com/print/voucher/execute?hash=%s';
    }
}
