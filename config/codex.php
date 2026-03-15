<?php

return [
    'bank' => [
        'account_holder' => env('CODEX_BANK_ACCOUNT_HOLDER', 'Masar'),
        'bank_name' => env('CODEX_BANK_NAME', 'Codex Bank'),
        'iban' => env('CODEX_BANK_IBAN', 'TN59040012345678901234'),
    ],
    'payments' => [
        'proof_max_size_kb' => (int) env('CODEX_PAYMENT_PROOF_MAX_KB', 5120),
        'reference_prefix' => env('CODEX_PAYMENT_REFERENCE_PREFIX', 'CDX'),
    ],
];
