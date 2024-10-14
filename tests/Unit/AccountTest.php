<?php
declare(strict_types=1);

namespace Tests\Unit;

use Banking\Entities\Account;
use Banking\Entities\Bank;
use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\MoneyEntityInterface;
use Banking\Enums\CurrenciesEnum;
use Banking\Exceptions\Entities\CurrencyBalanceAlreadyExistsException;
use Banking\Exceptions\Entities\DefaultCurrencyIsNotSet;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class AccountTest extends Unit
{

    protected UnitTester $tester;
    protected BankEntityInterface $bank;
    /**
     * @var Account
     */
    protected AccountEntityInterface $account;

    protected function _before(): void
    {
        $this->bank = new Bank();
        $this->account = $this->bank->newAccount();
    }

    /**
     * @throws WrongCurrencyCodeException
     * @throws CurrencyBalanceAlreadyExistsException
     */
    public function testFailureSetCurrencyBalance(): void
    {
        $this->expectException(CurrencyBalanceAlreadyExistsException::class);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
    }

    /**
     * @param $firstCurrency
     * @param $secondCurrency
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws WrongCurrencyCodeException
     * @throws UnsupportedCurrencyCode
     */
    #[DataProvider('dummyDefaultCurrency')]
    public function testSetDefaultCurrencyForAccount($firstCurrency, $secondCurrency): void
    {
        $this->account->addCurrencyBalance($firstCurrency);
        $this->account->setDefaultCurrency($firstCurrency);
        $this->assertEquals($this->account->getDefaultCurrency(), $firstCurrency);

        $this->account->addCurrencyBalance($secondCurrency);
        $this->account->setDefaultCurrency($secondCurrency);
        $this->assertEquals($this->account->getDefaultCurrency(), $secondCurrency);
    }

    /**
     * @throws WrongCurrencyCodeException|UnsupportedCurrencyCode
     */
    public function testFailureSetDefaultCurrencyForAccount(): void
    {
        $this->expectException(WrongCurrencyCodeException::class);
        $this->expectException(UnsupportedCurrencyCode::class);
        $this->account->setDefaultCurrency('JPY');
    }

    /**
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     * @throws DefaultCurrencyIsNotSet
     */
    public function testDepositCurrencyBalance(): void
    {
        $this->bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 120);
        $this->account->addCurrencyBalance(CurrenciesEnum::RUB);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->setDefaultCurrency(CurrenciesEnum::EUR);

        $this->account->deposit(CurrenciesEnum::RUB, 1000);
        $this->account->deposit(CurrenciesEnum::EUR, 50);

        $this->assertEquals($this->account->getSummaryBalance(), (1000 / 120) + 50);
        $this->assertEquals($this->account->getSummaryBalance(CurrenciesEnum::RUB), 1000 + 50 * 120);
    }

    /**
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function testWithdrawCurrencyBalance(): void
    {
        $this->bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 120);
        $this->account->addCurrencyBalance(CurrenciesEnum::RUB);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->setDefaultCurrency(CurrenciesEnum::EUR);

        $this->account->deposit(CurrenciesEnum::RUB, 1000);
        $this->account->deposit(CurrenciesEnum::EUR, 50);

        $this->account->withdraw(CurrenciesEnum::EUR, 10);
        $this->account->withdraw(CurrenciesEnum::RUB, 100);

        $this->assertEquals($this->account->getSummaryBalance(), (900 / 120) + 40);
        $this->assertEquals($this->account->getSummaryBalance(CurrenciesEnum::RUB), 900 + 40 * 120);
    }

    /**
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function testFailureWithdrawCurrencyBalance(): void
    {
        $this->expectException(BalanceInsufficientFundsException::class);
        $this->bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 120);
        $this->account->addCurrencyBalance(CurrenciesEnum::RUB);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->setDefaultCurrency(CurrenciesEnum::EUR);

        $this->account->deposit(CurrenciesEnum::RUB, 1000);
        $this->account->deposit(CurrenciesEnum::EUR, 50);

        $money = $this->account->withdraw(CurrenciesEnum::EUR, 600);
        $this->assertInstanceOf(MoneyEntityInterface::class, $money);
    }

    /**
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function testRemoveCurrencyBalance(): void
    {
        $this->bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 120);
        $this->account->addCurrencyBalance(CurrenciesEnum::RUB);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->setDefaultCurrency(CurrenciesEnum::EUR);

        $this->account->deposit(CurrenciesEnum::RUB, 1000);
        $this->account->deposit(CurrenciesEnum::EUR, 50);
        $this->account->setDefaultCurrency(CurrenciesEnum::RUB);

        $this->account->removeCurrencyBalance(CurrenciesEnum::EUR);
        $this->expectException(UnsupportedCurrencyCode::class);
        $this->account->removeCurrencyBalance(CurrenciesEnum::EUR);
        $this->assertEquals($this->account->getSummaryBalance(), (50 * 120) + 1000);
    }

    /**
     * @return array[]
     */
    public static function dummyDefaultCurrency(): array
    {
        return [
            [
                CurrenciesEnum::RUB,
                CurrenciesEnum::EUR,
            ]
        ];
    }
}
