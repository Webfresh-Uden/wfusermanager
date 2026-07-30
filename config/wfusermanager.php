<?php

return [
    'allow_shadow_login' => true,
    'allow_shadow_login_opt_out' => true,
    // DO NOT EDIT! These are the basic permissions that will be installed with the package, you can add more if you want to
    'permissions' => [
        'Teams' => [
            'View Teams' => 'web',
            'Create Teams' => 'web',
            'Edit Teams' => 'web',
            'Delete Teams' => 'web',
        ],
        'Users' => [
            'View Users' => 'web',
            'Create Users' => 'web',
            'Edit Users' => 'web',
            'Assign permissions to Users' => 'web',
            'Assign roles to Users' => 'web',
            'Use shadow login' => 'web',
            'Delete Users' => 'web',
        ],
        'Roles' => [
            'View Roles' => 'web',
            'Create Roles' => 'web',
            'Edit Roles' => 'web',
            'Delete Roles' => 'web',
        ],
        'Permissions' => [
            'View Permissions' => 'web',
            'Create Permissions' => 'web',
            'Edit Permissions' => 'web',
            'Delete Permissions' => 'web',
        ],
        'Permission Groups' => [
            'View Permission Groups' => 'web',
            'Create Permission Groups' => 'web',
            'Edit Permission Groups' => 'web',
            'Delete Permission Groups' => 'web',
        ],
        'Permission Matrix' => [
            'View Permission Matrix' => 'web',
            'Edit Permission Matrix' => 'web',
        ],
    ]
];
