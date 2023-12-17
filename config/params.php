<?php

return [
    'adminEmail' => getenv('ADMIN_EMAIL')?? 'admin@test.test',
    'senderEmail' => getenv('SENDER_EMAIL')?? 'noreply@test.test',
    'senderName' => getenv('SENDER_NAME') ?? 'Task System',
];
