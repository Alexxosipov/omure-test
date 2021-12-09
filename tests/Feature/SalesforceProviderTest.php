<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Services\Contacts\Providers\SalesforceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SalesforceProviderTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;
    /**
     * @test
     */
    public function it_can_create_contact()
    {
        /** @var SalesforceProvider $salesforceProvider */
        $salesforceProvider = app()->make(SalesforceProvider::class);

        /** @var Contact $contact */
        $contact = Contact::factory()->create();
        $id = $salesforceProvider->create($contact);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'salesforce_id' => $id
        ]);
    }

    /**
     * @test
     */
    public function send_test_request()
    {
        $client = new Client();

        $formParams = [
                'email' => 'tesagdadgadgdasgassssdg@asdasdasd.ru',
                'last_name' => 'Doe',
        ];

        $salesforceContact = $client->post('https://force-bridge-stagining-7lcyopg5cq-ue.a.run.app/contacts/', [
            RequestOptions::HEADERS => [
                'content-type' => 'multipart/form-data',
                'Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXNzd29yZCI6IkFjdVF2SkM3SmY4JEVAYk1hVUsiLCJlbWFpbCI6ImFsZXh4b3NpcG92QHlhbmRleC5ydSJ9.iV9i2AyINWds7CPzvoRFasrE4VesFD1qelA2Ovl0MMk',
                'User-Agent' => 'ttest'
            ],
            RequestOptions::FORM_PARAMS => $formParams,
        ]);

        dd($salesforceContact->getBody());
    }
}
