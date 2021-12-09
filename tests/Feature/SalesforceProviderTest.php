<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Services\Contacts\Providers\SalesforceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesforceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_contact()
    {
        /** @var SalesforceProvider $salesforceProvider */
        $salesforceProvider = app()->make(SalesforceProvider::class);

        /** @var Contact $contact */
        $contact = Contact::factory()->create();
        $salesforceProvider->create($contact);
    }
}
