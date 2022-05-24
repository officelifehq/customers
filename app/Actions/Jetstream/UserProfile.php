<?php

namespace App\Actions\Jetstream;

use Illuminate\Http\Request;

class UserProfile
{
    /**
     * Get user profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $data
     * @return array
     */
    public function __invoke(Request $request, array $data): array
    {
        /** @var \Illuminate\Support\Collection $providers */
        $providers = config('auth.login_providers');
        $providersName = [];
        foreach ($providers as $provider) {
            if ($name = config("services.$provider.name")) {
                $providersName[$provider] = $name;
            } else {
                $providersName[$provider] = __("auth.login_provider_{$provider}");
            }
        }

        $data['providers'] = $providers;
        $data['providersName'] = $providersName;
        $data['userTokens'] = $request->user()->usertokens()->get();

        return $data;
    }
}