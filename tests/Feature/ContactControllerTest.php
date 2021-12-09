<?php

namespace Tests\Feature;

use App\Jobs\CreateContact;
use App\Jobs\DeleteContact;
use App\Jobs\SyncContacts;
use App\Jobs\UpdateContact;
use App\Models\Contact;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_can_create_contact()
    {
        Queue::fake();

        $contactData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'lead_source' => $this->faker->company
        ];

        $response = $this->postJson(route('contacts.store'), $contactData);
        $response->assertCreated();

        Queue::assertPushed(CreateContact::class);
    }

    /**
     * @test
     */
    public function it_can_see_paginated_list_of_contacts()
    {
        $response = $this->getJson(route('contacts.index'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_can_update_a_contact()
    {
        Queue::fake();

        $contact = Contact::factory()->create();

        $firstName = $this->faker->firstName;

        $response = $this->putJson(route('contacts.update', compact('contact')), [
            'first_name' => $firstName
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => $firstName
        ]);
        Queue::assertPushed(UpdateContact::class);
    }

    /**
     * @test
     */
    public function it_can_delete_a_contact()
    {
        Queue::fake();

        $contact = Contact::factory()->create();

        $response = $this->deleteJson(route('contacts.destroy', compact('contact')));

        $response->assertNoContent();
        Queue::assertPushed(DeleteContact::class);
    }

    /**
     * @test
     */
    public function it_synchs_contacts_with_providers_data()
    {
        Queue::fake();

        $response = $this->postJson(route('contacts.sync'));

        $response->assertOk();
        Queue::assertPushed(SyncContacts::class);
    }
}
