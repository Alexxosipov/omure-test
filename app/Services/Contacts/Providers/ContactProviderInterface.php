<?php

namespace App\Services\Contacts\Providers;

use App\Models\Contact;

interface ContactProviderInterface
{
    /**
     * returns id of the created contact
     *
     * @param Contact $contact
     * @return string
     */
    public function create(Contact $contact): string;

    /**
     * @param Contact $contact
     * @return void
     */
    public function update(Contact $contact): void;

    /**
     * @param Contact $contact
     * @return void
     */
    public function delete(Contact $contact): void;
}
