<?php
namespace CouchDB\Auth;
use CouchDB\Http\ClientInterface;

/**
 * @author Maxim Gnatenko <mgnatenko@gmail.com>
 */
interface AuthInterface
{

    /**
     * @param ClientInterface $client
     * @return AuthInterface
     */
    public function authorize(ClientInterface $client);

    /**
     * @return array
     */
    public function getHeaders();
}
