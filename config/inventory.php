<?php

return [
    'report_email' => array_map(
        'trim',
        explode(',', env('REPORT_EMAIL', ''))
    ),
];
