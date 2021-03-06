<?php
namespace CouchDB\Tests\Auth;

use CouchDB\Tests\TestCase;
use CouchDB\Http;
use CouchDB\Auth;


class RealAuthorizationTest extends TestCase
{
    const LOGIN = 'test';
    const PWD = '123';

    protected function setUp()
    {
        $this->createServerAdmin();
    }

    protected function tearDown()
    {
        $this->deleteServerAdmin();
    }

    /**
     * @dataProvider authAdaptersAndHttpClientsProvider
     */
    public function testStreamClientUsesAuthAdapter($clientClassName, $authAdapter)
    {
        $client = new $clientClassName();
        $client->connect();
        $this->assertEquals(401, $client->request('/_config')->getStatusCode());

        $client = new $clientClassName();
        $client->connect($authAdapter);
        $this->assertEquals(200, $client->request('/_config')->getStatusCode());
        $this->assertEquals(200, $client->request('/_config')->getStatusCode());
    }

    public static function authAdaptersAndHttpClientsProvider()
    {
        return array(
            array('CouchDB\Http\StreamClient', new Auth\Cookie(self::LOGIN, self::PWD)),
            array('CouchDB\Http\StreamClient', new Auth\Basic(self::LOGIN, self::PWD)),
/*            array('CouchDB\Http\SocketClient', new Auth\Cookie(self::LOGIN, self::PWD)),
            array('CouchDB\Http\SocketClient', new Auth\Basic(self::LOGIN, self::PWD))*/
        );
    }

    private function createServerAdmin()
    {
        $this->createTestConnection()->getClient()->request(
            '/_config/admins/' . self::LOGIN,
            Http\ClientInterface::METHOD_PUT,
            '"' . self::PWD . '"'
        );
    }

    private function deleteServerAdmin()
    {
        $conn = $this->createTestConnection();
        $conn->getClient()->connect(new Auth\Cookie(self::LOGIN, self::PWD));
        $conn->getClient()->request('/_config/admins/' . self::LOGIN,
            Http\ClientInterface::METHOD_DELETE);
    }

}
