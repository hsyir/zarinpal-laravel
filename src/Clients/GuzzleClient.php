<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleClient
{
    public $baseUrl;

    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://'.$sub.'.zarinpal.com/pg/rest/WebGate/';
    }

    /**
     * Request new payment.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function request($input, $extra)
    {
        $uri = ($extra) ? 'PaymentRequestWithExtra.json' : 'PaymentRequest.json';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Verify the payment.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function verify($input, $extra)
    {
        $uri = ($extra) ? 'PaymentVerificationWithExtra.json' : 'PaymentVerification.json';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Extends authority token lifetime.
     *
     * @param  array $input
     *
     * @return array
     */
    public function refreshAuthority($input)
    {
        return $this->sendRequest('RefreshAuthority.json', $input);
    }

    /**
     * Get unverified transactions.
     *
     * @param  array $input
     *
     * @return array
     */
    public function unverifiedTransactions($input)
    {
        return $this->sendRequest('UnverifiedTransactions.json', $input);
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
            $client = new Client(['base_uri' => $this->baseUrl]);
            $response = $client->request('POST', $uri, ['json' => $input]);
            $response = $response->getBody()->getContents();
        } catch (RequestException $request) {
            $response = '{"Status":-202}';
            if ($request->hasResponse()) {
                $response = $request->getResponse();
                $response = $response->getBody()->getContents();
            }
        }
        $response = json_decode($response, true);

        return $response;
    }
}
