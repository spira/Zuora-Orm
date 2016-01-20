<?php

use OlivierBarbier\Zorm\Zobject\Account;

class VCRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @vcr example
     */
    public function test_find()
    {
        $id = '2c92c0f851ce2d790151cf55799a79eb';

        $api     = new Account();
        $account = $api->find($id);

        $this->assertEquals($id, $account->Id);
        $this->assertEquals('Ceci est un compte de test', $account->Name);
    }

    /**
     * @vcr example
     */
    public function test_where()
    {
        $api      = new Account();
        $accounts = $api->where('Status', '=', 'Active')->get();

        $this->assertEquals(16, $accounts->count());
    }

    /**
     * @vcr example
     */
    public function test_create()
    {
        $api     = new Account();
        $account = $api->create(
            [
                'Name'         => 'John',
                'Currency'     => 'EUR',
                'BillCycleDay' => 1,
                'Status'       => 'Draft',
            ]
        );

        $this->assertEquals('2c92c0f851e800b80151e815710a1192', $account->Id);
        $this->assertEquals('John', $account->Name);
        $this->assertEquals('EUR', $account->Currency);
        $this->assertEquals('1', $account->BillCycleDay);
        $this->assertEquals('Draft', $account->Status);
    }

    /**
     * @vcr example
     */
    public function test_save()
    {
        $api     = new Account();
        $account = $api->find('2c92c0f851e800b80151e815710a1192');

        $account->status = 'Active';

        $save = $account->save(['Status']);

        $this->assertTrue((bool)$save->result->Success);
    }

    /**
     * @expectedException SoapFault
     */
    public function test_override_config()
    {
        $api = new Account(
            new \OlivierBarbier\Zorm\Config(
                [
                    'wsdl'     => __DIR__ . '/../src/config/zuora.wsdl',
                    'endpoint' => 'https://apisandbox.zuora.com/apps/services/a/63.0',
                    'user'     => 'bad',
                    'password' => 'password',
                ]
            )
        );

        $api->all();
    }

    /** Just to be sure config is restored */
    public function test_all()
    {
        $api = new Account();
        $api->all();
    }
}
