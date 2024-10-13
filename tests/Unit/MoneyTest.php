<?php


namespace Tests\Unit;

use Banking\Entities\Account;
use Banking\Entities\Bank;
use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\MoneyEntityInterface;
use Banking\Entities\Money;
use Banking\Enums\CurrenciesEnum;
use Banking\Exceptions\Entities\CurrencyBalanceAlreadyExistsException;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Tests\Support\UnitTester;

class MoneyTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    /**
     * @var Bank
     */
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

    // tests

    /**
     * @throws CurrencyBalanceAlreadyExistsException
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     * @throws UnsupportedCurrencyCode
     * @throws BalanceInsufficientFundsException
     * @throws WrongBalanceAmountException
     */
    public function testExchange()
    {
        $this->bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 200);
        $this->account->addCurrencyBalance(CurrenciesEnum::RUB);
        $this->account->addCurrencyBalance(CurrenciesEnum::EUR);
        $this->account->deposit(CurrenciesEnum::EUR, 100);
        $money = $this->account->withdraw(CurrenciesEnum::EUR, 50);
        $this->assertInstanceOf(MoneyEntityInterface::class, $money);
        $this->assertEquals(50, $money->getAmount());
        $this->assertEquals(CurrenciesEnum::EUR, $money->getCurrencyCode());
        $money->exchangeTo(CurrenciesEnum::RUB);
        $this->assertEquals(CurrenciesEnum::RUB, $money->getCurrencyCode());
        $this->assertEquals(200 * 50, $money->getAmount());
    }
}
