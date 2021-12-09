<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Jobs\CreateContact;
use App\Jobs\DeleteContact;
use App\Jobs\SyncContacts;
use App\Jobs\UpdateContact;
use App\Models\Contact;
use App\Services\Contacts\Providers\ContactProviderInterface;
use App\Services\ContactService;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ContactResource::collection(
            Contact::query()->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ContactResource
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        CreateContact::dispatch($contact);

        return new ContactResource($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  Contact $contact
     * @return ContactResource
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return ContactResource
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->fill($request->validated());
        $contact->save();

        UpdateContact::dispatch($contact);

        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        DeleteContact::dispatch($contact);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function syncContacts()
    {
        SyncContacts::dispatch();

        return response()->json();
    }
}
