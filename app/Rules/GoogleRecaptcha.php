<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class GoogleRecaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes(string $attribute, $value): bool
    {
        //
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => env('GOOGLE_CAPTCHA_PRIVATE_KEY', false),
                    'remoteip' => request()->getClientIp(),
                    'response' => $value,
                ],
            ]
        );
        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Are you a robot?';
    }
}
