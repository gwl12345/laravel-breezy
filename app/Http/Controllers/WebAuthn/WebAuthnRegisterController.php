<?php

namespace App\Http\Controllers\WebAuthn;

use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Responsable;
use Laragear\WebAuthn\Models\WebAuthnCredential;
use Laragear\WebAuthn\Http\Requests\AttestedRequest;
use Laragear\WebAuthn\Http\Requests\AttestationRequest;

use function response;

class WebAuthnRegisterController
{
    /**
     * Returns a challenge to be verified by the user device.
     */
    public function options(AttestationRequest $request): Responsable
    {
        return $request
            ->fastRegistration()
            // ->userless()
            // ->allowDuplicates()
            ->toCreate();
    }

    /**
     * Registers a device for further WebAuthn authentication.
     */
    public function register(AttestedRequest $request): Response
    {
        $request->validate(['alias' => 'nullable|string']);
        $request->save($request->only('alias'));

        return response()->noContent();
    }
}
