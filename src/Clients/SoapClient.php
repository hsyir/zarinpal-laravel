<?php

namespace Zarinpal\Clients;

use SoapClient as Client;
use SoapFault;

class SoapClient extends BaseClient
{
    public $baseUrl;

    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://'.$sub.'.zarinpal.com/pg/services/WebGate/wsdl';
    }

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param  string $uri
     * @param  array  $input
     *
     * @return array
     */
    public function sendRequest($uri, $input)
    {
        try {
            $client = new Client($this->baseUrl, ['encoding' => 'UTF-8']);
            $response = $client->{$uri}($input);
            $response = (array) $response;
        } catch (SoapFault $error) {
            $response = ['Status' => -303];
        }

        return $response;
    }
}
