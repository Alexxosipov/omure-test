<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\Contacts\Providers\ContactProviderInterface;

class ContactService
{
    private ContactProviderInterface $contactProvider;

    public function __construct(ContactProviderInterface $contactProvider)
    {
        $this->contactProvider = $contactProvider;
    }

    public function create(Contact $contact): Contact
    {
        $vendorId = $this->contactProvider->create($contact);
        $contact->fill(['salesforce_id' => $vendorId]);
        $contact->save();

        return $contact;
    }

    public function update(Contact $contact): void
    {
        $this->contactProvider->update($contact);
    }

    public function delete(Contact $contact): void
    {
        $this->contactProvider->delete($contact);
    }

    public function sync()
    {
        //TODO
    }
}
