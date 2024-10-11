<?php
declare(strict_types=1);

namespace Tests\Unit;

use Banking\Entities\Bank;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\CurrencyBalance;
use Banking\Entities\CurrencyRate;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use JetBrains\PhpStorm\NoReturn;
use Tests\Support\UnitTester;

class BankTest extends Unit
{

    protected UnitTester $tester;
    protected BankEntityInterface $bank;

    protected function _before(): void
    {
        $this->bank = new Bank();
    }

    // tests

    /**
     * @throws WrongCurrencyCodeException
     */
    #[NoReturn] public function testWrongCurrencyRateValue(): void
    {
        $this->expectException(WrongCurrencyRateValueException::class);
        $this->bank->setNewCurrencyRate('USD', 'RUB', -0.3);
    }

    /**
     * @throws WrongCurrencyRateValueException
     */
    #[NoReturn] public function testWrongCurrencyCodeValue(): void
    {
        $this->expectException(WrongCurrencyCodeException::class);
        $this->bank->setNewCurrencyRate('JPY', 'RUB', 0.3);
    }

    /**
     * @throws WrongCurrencyRateValueException
     */
    #[NoReturn] public function testWrongRelCurrencyCodeValue(): void
    {
        $this->expectException(WrongCurrencyCodeException::class);
        $this->bank->setNewCurrencyRate('JPY', 'JPY', 0.3);
    }

    /**
     * @throws WrongCurrencyRateValueException
     * @throws WrongCurrencyCodeException
     */
    #[NoReturn] #[DataProvider('dummyData')]
    public function testExchange(CurrencyRate $dummyCurrencyRate, CurrencyBalance $dummyBalanceData, $dummyResult): void
    {
        $this->bank->setNewCurrencyRate(
            $dummyCurrencyRate->getCurrencyCode(),
            $dummyCurrencyRate->getCurrencyRel(),
            $dummyCurrencyRate->getValue()
        );

        $result = $this->bank->exchange(
            $dummyBalanceData->getCurrencyCode(),
            $dummyCurrencyRate->getCurrencyRel(),
            $dummyBalanceData->getAmount()
        );

        $this->assertEquals($dummyResult, $result);
    }

    /**
     * @throws WrongCurrencyRateValueException
     */
    public static function dummyData(): array
    {
        $currencyRate = new CurrencyRate('EUR', 'USD', 1.5);
        $balance = new CurrencyBalance(100, 'EUR');
        $result = 150.0;

        return [
            [
                $currencyRate,
                $balance,
                $result
            ]
        ];
    }
}
