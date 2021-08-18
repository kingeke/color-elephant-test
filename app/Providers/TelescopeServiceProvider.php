<?php

namespace App\Providers;

use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {

            $disabled = ['view', 'cache', 'gate', 'queries'];

            if (in_array($entry->type, $disabled)) {
                return false;
            }

            return true;
        });

        Telescope::tag(function (IncomingEntry $entry) {
            if ($entry->type == 'log') {
                if (isset($entry->content['message']) && explode(' ', $entry->content['message'])[0] == 'HTTP') {
                    $context = $entry->content['context'] ?? false;
                    if ($context) {
                        $uri = $context['request']['uri'] ?? false;
                        if ($uri) {
                            return [explode('?', $uri)[0]];
                        }
                    }
                }
            }

            if ($entry->type == 'request') {
                if (strlen($entry->content['uri']) > 100) {
                    return [$entry->content['controller_action']];
                }

                return [$entry->content['uri'], $entry->content['controller_action']];
            }

            if ($entry->type == 'cache') {
                return [$entry->content['key']];
            }
            
            if ($entry->type == 'command') {
                return [$entry->content['command']];
            }

            return [];
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    
    protected function authorization()
    {
        Telescope::auth(function ($request) {
            return true;
        });
    }
}
