<?php

function auditLogsRepository(): array
{
    return [
        'method' => 'GET',
        'url' => platform_api_url('/organizations/{organizationName}/audit-logs')
    ];
}
