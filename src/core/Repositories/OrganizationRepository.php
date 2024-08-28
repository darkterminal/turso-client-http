<?php

namespace Darkterminal\TursoHttp\core\Repositories;

class OrganizationRepository
{
    public static function endpoints($action): array
    {
        $items = [
            'list' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations')
            ],
            'update' => [
                'method' => 'PATCH',
                'url' => platform_api_url('/organizations/{organizationName}')
            ],
            'plans' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/plans')
            ],
            'subscription' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/subscription')
            ],
            'invoices' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/invoices')
            ],
            'current_usage' => [
                'method' => 'GET',
                'url' => platform_api_url('/organizations/{organizationName}/usage')
            ],
        ];

        return $items[$action] ?? [];
    }

}
