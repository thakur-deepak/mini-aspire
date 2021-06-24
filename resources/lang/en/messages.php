<?php

return [
    'success' => [
        'created' => 'Resource created successfully.',
        'updated' => 'Resource updated successfully.',
        'added' => 'Resource added successfully.',
        'listing' => 'Listed successfully.',
    ],
    'validation' => [
        'first_name' => 'First name is required',
        'last_name' => 'Last name is required',
        'password' => 'Password is required',
        'password.password' => 'Please enter valid password',
        'invalid_format' => 'Invalid File Format',
        'password.regex' => 'Password should contain min. 8 characters, at least one capital character and one number',
        'email.unique' => 'This email already exists. Please try to log in or try again with a different email',
        'check_amount' => 'The :attribute must be lower than loan amount'
    ],
    'validation_error' => 'Validation Error',
    'error' => [
        'already_applied' => 'User already applied for loan',
    ]
];
