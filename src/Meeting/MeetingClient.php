<?php

namespace Meeting;

use Graze\GuzzleHttp\JsonRpc\Client;

/**
 * Class MeetingClient
 * @package Meeting
 */
class MeetingClient {

    /**
     * @var string
     */
    const URL_SCHEME = 'http';

    /**
     * @var string
     */
    const URL_RESOURCE = 'tools.referralsolutionsgroup.com/meetings-api/v1/';

    /**
     * @var Client
     */
    private $client;

    /**
     * MeetingClient constructor.
     * @param Client $client
     */
    public function __construct(Client $client) {

        $this->client = $client;
    }

    /**
     * @return MeetingCollection
     */
    public function search() {

        try {
            $params = [
                [
                    [ 'state_abbr' => 'CA', 'city' => 'San Diego' ],
                ],
            ];
            $request = $this->client->request(1, 'byLocals', $params);

            $response = $this->client->send($request);

            $result = $response->getRpcResult();
            $meetingCollection = new MeetingCollection($result);
            return $meetingCollection;
        } catch (RequestException $e) {
            die($e->getResponse()->getRpcErrorMessage());
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return MeetingClient
     */
    public static function build($username, $password) {

        $url = self::buildUrl($username, $password);
        $client = Client::factory($url);
        return new self($client);
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     */
    public static function buildUrl($username, $password) {

        return  self::URL_SCHEME . '://' . $username . ':' . $password . '@' . self::URL_RESOURCE;
    }
}