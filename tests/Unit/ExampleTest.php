<?php


namespace Tests\Unit;

use Banking\Entities\Account;
use Banking\Entities\Bank;
use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\CurrencyBalance;
use Banking\Entities\CurrencyRate;
use Banking\Enums\CurrenciesEnum;
use Banking\ValueObjects\CurrencyCodeValue;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Tests\Support\UnitTester;
use function PHPUnit\Framework\assertEquals;

class ExampleTest extends Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * @throws Exception
     */
    #[NoReturn] #[DataProvider('dummyAccount')] public function testSomeFeature($balances, $currencyRates)
    {
        $bank = $this->make(Bank::class, [
            'currencyRates' => $currencyRates,
        ]);

        $account = $this->make(Account::class, [
            'currencyBalances' => $balances,
            'bank' => $bank,
            'defaultCurrency' => new CurrencyCodeValue(CurrenciesEnum::RUB),
        ]);

        $expectedRub = 150 * 50 + 100 * 50 + 1000;
        $expectedEur = round(50 + 50 + (1000 / 150), 3);
        assertEquals($account->getSummaryBalance(), $expectedRub);
        assertEquals(round($account->getSummaryBalance(CurrenciesEnum::EUR), 3), $expectedEur);
        $account->getSummaryBalance();
        $account->deposit(CurrenciesEnum::RUB, 100);
        $expectedNewRub = 150 * 50 + 100 * 50 + 1100;
        assertEquals($account->getSummaryBalance(), $expectedNewRub);
        $account->removeCurrencyBalance(CurrenciesEnum::USD);
        assertEquals($account->getSummaryBalance(), $expectedNewRub);
        assertEquals($account->getSupportedCurrencies(), [CurrenciesEnum::RUB, CurrenciesEnum::EUR]);
        $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 200);
        $expectedNewEur = round(50 + (6100 / 200), 3);
        assertEquals($account->getSummaryBalance(CurrenciesEnum::EUR), $expectedNewEur);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function dummyAccount(): array
    {
        $rateEur = $this->make(CurrencyRate::class, [
            'currencyCode' => CurrenciesEnum::EUR,
            'currencyRel' => CurrenciesEnum::RUB,
            'value' => 150
        ]);

        $rateUsd = $this->make(CurrencyRate::class, [
            'currencyCode' => CurrenciesEnum::USD,
            'currencyRel' => CurrenciesEnum::RUB,
            'value' => 100
        ]);

        $rateUsdEur = $this->make(CurrencyRate::class, [
            'currencyCode' => CurrenciesEnum::USD,
            'currencyRel' => CurrenciesEnum::EUR,
            'value' => 1
        ]);

        $currencyRates = [
            $rateEur->getKey() => $rateEur,
            $rateUsd->getKey() => $rateUsd,
            $rateUsdEur->getKey() => $rateUsdEur
        ];

        $balances = [
            CurrenciesEnum::RUB => $this->make(CurrencyBalance::class, [
                'currencyCode' => CurrenciesEnum::RUB,
                'amount' => 1000.0,
            ]),
            CurrenciesEnum::EUR => $this->make(CurrencyBalance::class, [
                'currencyCode' => CurrenciesEnum::EUR,
                'amount' => 50.0,
            ]),
            CurrenciesEnum::USD => $this->make(CurrencyBalance::class, [
                'currencyCode' => CurrenciesEnum::USD,
                'amount' => 50.0,
            ])
        ];

        return [
            [
                $balances,
                $currencyRates
            ]
        ];

    }
}
