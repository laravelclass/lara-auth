<?php
return [
    'email' => [

        'builtinTemplate' => [
            'web' => true,
            'admins' => true
        ],

        'customTemplateView' => [

            'web' => [
                'verifyEmailTemplate' => '',

                'resetPasswordTemplate' => ''
            ]
        ],

        'verifyEmail' => [
            'queue' => [
                'web' => [
                    'state' => false,
                    'delay' => 0,
                    'connection' => 'database',
                    'queue' => 'default'
                ],

                'admins' => [
                    'state' => false,
                    'delay' => 0,
                    'connection' => 'database',
                    'queue' => 'default'
                ],
            ],
            'from' => [
                'web' => 'laraAuth@gmail.com',
                'admins' => 'laraAuth@gmail.com'
            ],

            'name' => [
                'web' => 'LaraAuth',
                'admins' => 'LaraAuth'

            ],

            'subject' => [
                'web' => 'verify youre email',
                'admins' => 'verify youre email'
            ],

            'actionLink' => [
                'web' => 'verifyEmail',
                'admins' => 'verifyEmail'
            ],

            'actionLinkParams' => [
                'web' => ['id' => 'id' ,'hash' => 'hash2'],
                'admins' => ['id' => 'id' ,'hash' => 'hash2']
            ] ,

            'redirectAfterVerified' => [
                'web' => 'dashboard',
                'admins' => 'dashboard'
            ],

            'linkExpire' => [
                'web' => 60,
                'admins' => 60
            ]

        ],
        'resetPassword' => [

            'queue' => [
                'web' => [
                    'state' => true,
                    'delay' => 5,
                    'connection' => 'database',
                    'queue' => 'default'
                ],

                'admins' => [
                    'state' => false,
                    'delay' => 0,
                    'connection' => 'database',
                    'queue' => 'default'
                ],
            ],

            'from' => [
                'web' => 'laraAuth@gmail.com',
                'admins' => 'laraAuth@gmail.com'
            ],

            'name' => [
                'web' => 'LaraAuth',
                'admins' => 'LaraAuth'
            ],

            'subject' => [
                'web' => 'reset password link',
                'admins' => 'reset password link'
            ],

            'actionLink' => [
                'web' => 'resetPassword',
                'admins' => 'admin.resetPasswordView'
            ],

            'actionLinkParams' => [
                'web' => ['token' => 'token' ,'email' => 'email'],
                'admins' => ['token' => 'token' ,'email' => 'email']
            ],

            'validation' => [
                'web' => ['email' => ['required','email']],
                'admins' => ['email' => ['required','email']]
            ],

            'linkExpire' => [
                'web' => 60,
                'admins' => 60
            ]
        ]
    ],

    'auth' => [

        'validation' => [

            'register' =>  [

                'rules' => [
                    'web' => ['email'=>['required','email']
                        ,'name'=>['required','min:3','max:16']
                        ,'password'=>['required','min:8','max:24','confirmed'],
                        'username' => ['required','min:3','max:10']],
                    'admins' => ['email'=>['required','email']
                        ,'password'=>['required','min:8','max:24','confirmed'],
                    ],
                ],

                'messages' => [
                    'web' => ['email.required' => 'email lazem ast'],
                    'admins' => ['email.required' => 'email lazem ast']
                ],

                'customAttributes' => [
                    'web' => ['email'=>'email2'],
                    'admins' => ['email'=>'email2']
                ],

                'redirectRoute' => [
                    'web' => 'dashboard',
                    'admins' => 'admin.dashboard'
                ],

                'uniqueField' => [
                    'web' => ['email','username'],
                    'admins' => ['email']
                ],

                'errorMessage' => [
                    'web' => ['registerError' => 'registration was not successful'],
                    'admins' => ['registerError' => 'registration was not successful']
                ],
                'successfulMessages' => [
                    'web' => ['registerSuccess' => 'registration was successful'],
                    'admins' => ['registerError' => 'registration was successful']
                ]
            ],

            'login' => [

                'fieldsToAttempt' => [
                    'web' => ['email','password'],
                    'admins' => ['email','password']
                ],

                'rememberMeField' => [
                    'web' => 'rememberMe',
                    'admins' => 'rememberMe'
                ],

                'redirectRoute' => [
                    'web' => [
                        'intended' => true,
                        'route' => 'dashboard'
                    ],
                    'admins' => [
                        'intended' => true,

                        'route' => 'admin.dashboard'
                    ]
                ],

                'existsField' => [
                    'web' => 'email',
                    'admins' => 'email'
                ],

                'successfulMessage' => [
                    'web' => ['success' => 'the registration was successful'],
                    'admins' => ['success' => 'the registration was successful']
                ],

                'errorMessage' => [
                    'web' => ['loginError' => 'User Or Password Was Wrong'],
                    'admins' => ['loginError' => 'User Or Password Was Wrong']
                ]
            ],

            'logout' => [
                'redirectRoute' => [
                    'web' => 'loginView',
                    'admins' => 'adminLoginView'

                ]
            ],
            'verifyEmail' => [
                'successfulMessage' => [
                    'web' => ['success' => 'the reset link has been sent to youre email account'],
                    'admins' => ['success' => 'the reset link has been sent to youre email account']
                ],
                'redirectAfterEmailSend' => [
                    'web' => 'verification.notice',
                    'admins' => 'verification.notice'
                ],
            ],

            'forgotPassword' => [

                'successfulMessage' => [
                    'web' => ['success' => 'the reset link has been sent to youre email account'],
                    'admins' => ['success' => 'the reset link has been sent to youre email account']
                ],

                'errorMessages' => [
                    'web' => ['forgotPasswordError' => 'something went wrong'],
                    'admins' => ['forgotPasswordError' => 'something went wrong'],
                ],

                'rules' => [
                    'web' => ['email' => ['required','email']],
                    'admins' => ['email' => ['required','email']]
                ],

                'existsFiled' => [
                    'web' => 'email',
                    'admins' => 'email'
                ],

                'messages' => [
                    'web' => ['email.required' => 'email lazem ast','email.exists' => 'email is wrong'],
                    'admins' => ['email.required' => 'email lazem ast','email.exists' => 'email is wrong']
                ],

                'customAttributes' => [
                    'web' => ['email'=>'email2'],
                    'admins' => ['email'=>'email2']
                ],

            ],

            'resetPassword' => [

                'rules' => [
                    'web' => ['email'=>['required','email'],'password'=>['required','confirmed'],'token'=>['required']],
                    'admins' => ['email'=>['required','email'],'password'=>['required','confirmed'],'token'=>['required']]
                ],

                'existsFiled' => [
                    'web' => 'email',
                    'admins' => 'email'
                ],

                'messages' => [
                    'web' => ['email.required' => 'email lazem ast','email.exists' => 'email is wrong'],
                    'admins' => ['email.required' => 'email lazem ast','email.exists' => 'email is wrong']
                ],

                'customAttributes' => [
                    'web' => ['email'=>'email2'],
                    'admins' => ['email'=>'email2']
                ],

                'successfulMessage' => [
                    'web' => ['success' => 'reset password was successful'],
                    'admins' => ['success' => 'reset password was successful']
                ],

                'errorMessages' => [
                    'web' => ['resetPasswordError' => 'something went wrong'],
                    'admins' => ['resetPasswordError' => 'something went wrong'],
                ],

                'redirectAfterPasswordReset' => [
                    'web' => 'loginView',
                    'admins' => 'adminLoginView'
                ],
            ]

        ]
    ],
    'middleware' => [
        'verifyEmail' => [
            'web' => [
                'redirectIfCanNotVerifyEmail' => '/dashboard'
            ],
            'admins' => [
                'redirectIfCanNotVerifyEmail' => '/admin/dashboard'
            ]
        ],
        'resetPassword' => [
            'web' => [
                'redirectIfCanNotResetPassword' => 'error'
            ],
            'admins' => [
                'redirectIfCanNotResetPassword' => 'error'
            ]
        ]
    ]
];
