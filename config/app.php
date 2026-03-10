<?php

declare(strict_types=1);

return [
    'name' => 'Task Manager',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) ?: false,
];
