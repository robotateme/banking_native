<?php
declare(strict_types=1);

namespace Tests\Unit;

use Banking\Entities\Account;
use Banking\Entities\Bank;
use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\CurrencyEntityBalance;
use Banking\Entities\CurrencyEntityRate;
use Banking\Exceptions\Entities\CurrencyBalanceAlreadyExistsException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
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

    /**
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     * @throws CurrencyBalanceAlreadyExistsException
     */
    protected function _before(): void
    {
        $this->bank = new Bank();
        $this->bank->setNewCurrencyRate('USD', 'RUB', 4);
        $this->bank->setNewCurrencyRate('EUR', 'RUB', 2);
        $this->bank->setNewCurrencyRate('EUR', 'USD');

        $this->account = $this->bank->newAccount();
        $this->account->addCurrencyBalance('RUB');
        $this->account->addCurrencyBalance('USD');
        $this->account->addCurrencyBalance('EUR');
    }

    /**
     * @throws WrongCurrencyCodeException
     * @throws CurrencyBalanceAlreadyExistsException
     */
    public function testFailureSetCurrencyBalance()
    {
        $this->expectException(CurrencyBalanceAlreadyExistsException::class);
        $this->account->addCurrencyBalance('EUR');
    }

    public function testDepositBalance()
    {

    }

    public static function dummyBalanceData(): array
    {

    }
}
