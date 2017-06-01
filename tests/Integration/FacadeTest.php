<?php
namespace Tests\Integration;

use Tests\TestCase;
use Ebanx\Benjamin\Facade;
use Ebanx\Benjamin\Models\Configs\Config;
use Ebanx\Benjamin\Models\Configs\CreditCardConfig;

class FacadeTest extends TestCase
{
    public function testMainObject()
    {
        $ebanx = EBANX(new Config(), new CreditCardConfig());
        $this->assertNotNull($ebanx);

        return $ebanx;
    }

    /**
     * @param Facade $ebanx
     * @depends testMainObject
     */
    public function testGatewayAccessors($ebanx)
    {
        $gateways = $this->getExpectedGateways();

        foreach ($gateways as $gateway) {
            $class = new \ReflectionClass('Ebanx\Benjamin\Services\Gateways\\'.ucfirst($gateway));

            // skip abstract gateways
            if ($class->isAbstract()) {
                continue;
            }

            $this->assertTrue(
                method_exists($ebanx, $gateway),
                "Facade has no accessor for gateway \"$gateway\"."
            );

            $this->assertNotNull(
                $this->tryBuildGatewayUsingFacadeAccessor($ebanx, $gateway),
                "Accessor failed to build instance of gateway \"$gateway\"."
            );
        }
    }

    private function tryBuildGatewayUsingFacadeAccessor($facade, $accessor)
    {
        return call_user_func(array($facade, $accessor));
    }

    private function getExpectedGateways()
    {
        $result = array();

        $dir = opendir('src/Services/Gateways');
        while (($file = readdir($dir)) !== false) {

            // skip non-php files
            if ($file === basename($file, '.php')) {
                continue;
            }

            $result[] = lcfirst(basename($file, '.php'));
        }
        closedir($dir);

        return $result;
    }
}
