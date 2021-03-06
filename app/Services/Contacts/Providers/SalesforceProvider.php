<?php

namespace App\Services\Contacts\Providers;

use App\Exceptions\Contacts\Adapters\Salesforce\InvalidCredentialsException;
use App\Exceptions\SalesforceServerErrorException;
use App\Models\Contact;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SalesforceProvider implements ContactProviderInterface
{
    private ?string $accessToken = null;
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = $this->getAccessToken();
    }

    public function create(Contact $contact): string
    {
        try {
            $salesforceContact = $this->client->post(config('services.salesforce.base_url') . '/contacts/', [
                RequestOptions::HEADERS => $this->getHeaders(),
                RequestOptions::FORM_PARAMS => $contact->only(['first_name', 'last_name', 'email']),
                RequestOptions::DEBUG => true
            ]);

        } catch (ServerException $e) {
            throw new SalesforceServerErrorException();
        }

        $salesforceContact = json_decode($salesforceContact->getBody());

        Log::info('Salesforce contact', $salesforceContact);
        return $salesforceContact->id;
    }

    public function update(Contact $contact): void
    {
        // TODO: Implement update() method.
    }

    public function delete(Contact $contact): void
    {
        // TODO: Implement delete() method.
    }

    public function sync()
    {

    }

    /**
     * Retrieves access token from API and caches it for 30 seconds
     * @return string
     */
    private function getAccessToken(): string
    {
        return Cache::remember('salesforce_access_token', 30, function(){
            $response = $this->client->post(config('services.salesforce.base_url') . '/login/', [
                RequestOptions::FORM_PARAMS => [
                    'email' => config('services.salesforce.email'),
                    'password' => config('services.salesforce.password'),
                ],
            ])->getBody();

            $response = json_decode($response, true);

            if (!isset($response['token'])) {
                throw new InvalidCredentialsException();
            }

            return $response['token'];
        });
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => $this->accessToken,
            'content-type' => 'multipart/form-data'
        ];
    }
}
