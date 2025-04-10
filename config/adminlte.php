<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'COMMO',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '',
    'logo_img' => 'imgs/logo120.png',
    'logo_img_class' => '',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'COMMO',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => 'text-center',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-primary elevation-2',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Search menu',
        ],
        [
            'text' => 'Leads',
            'can' => 'leads',
            'route' => 'leads.show',
            'active' => ['leads*'],
            'icon' => 'fas fa-user-circle',
        ],
        [
            'text' => 'Home',
            'url' => 'home/',
            'can' => 'home',
            'active' => ['home*'],
            'icon' => 'fas fa-home',
        ],
        [
            'text' => 'Policies',
            'url' => 'policies/',
            'can' => 'policies',
            'active' => ['policies*'],
            'icon' => 'fas fa-shield-alt',
            'submenu' => [
                [
                    'text' => 'Client Sources',
                    'route' => 'client-sources.show',
                    'can' => 'client-sources',
                    'active' => ['client-sources*'],
                ],
                [
                    'text' => 'Counties',
                    'route' => 'counties.show',
                    'can' => 'counties',
                    'active' => ['counties*'],
                ],
                [
                    'text' => 'Enrollment Methods',
                    'route' => 'enrollment-methods.show',
                    'can' => 'enrollment-methods',
                    'active' => ['enrollment-methods*'],
                ],
                [
                    'text' => 'Policy Agent Number Types',
                    'route' => 'policy-agent-number-types.show',
                    'can' => 'policy-agent-number-types',
                    'active' => ['policy-agent-number-types*'],
                ],
                [
                    'text' => 'Policy Member Types',
                    'route' => 'policy-member-types.show',
                    'can' => 'policy-member-types',
                    'active' => ['policy-member-types*'],
                ],
                [
                    'text' => 'Policy Status',
                    'route' => 'policy-status.show',
                    'can' => 'policy-status',
                    'active' => ['policy-status*'],
                ],
                [
                    'text' => 'Relationships',
                    'route' => 'relationships.show',
                    'can' => 'relationships',
                    'active' => ['relationships*'],
                ]
            ]

        ],
        [
            'text' => 'Customers',
            'url' => 'customers/',
            'can' => 'customers',
            'active' => ['customers*'],
            'icon' => 'fas fa-user-circle',
            'submenu' => [                
                [
                    'text' => 'Customers',
                    'route' => 'customers.show',
                    'can' => 'customers',
                    'active' => ['customers/customers*'],
                ],
                [
                    'text' => 'Genders',
                    'route' => 'genders.show',
                    'can' => 'genders',
                    'active' => ['genders*'],
                ],[
                    'text' => 'Marital Status',
                    'route' => 'marital-status.show',
                    'can' => 'marital-status',
                    'active' => ['marital-status*'],
                ],[
                    'text' => 'Regions',
                    'route' => 'regions.show',
                    'can' => 'regions',
                    'active' => ['regions*'],
                ],[
                    'text' => 'States',
                    'route' => 'states.show',
                    'can' => 'states',
                    'active' => ['states*'],
                ],[
                    'text' => 'Suffixes',
                    'route' => 'suffixes.show',
                    'can' => 'suffixes',
                    'active' => ['suffixes*'],
                ],[
                    'text' => 'Customer Status',
                    'route' => 'customer-status.show',
                    'can' => 'customer-status',
                    'active' => ['customer-status*'],
                ],[
                    'text' => 'Phases',
                    'route' => 'phases.show',
                    'can' => 'phases',
                    'active' => ['phases*'],
                ],[
                    'text' => 'Legal Basis',
                    'route' => 'legal-basis.show',
                    'can' => 'legal-basis',
                    'active' => ['legal-basis*'],
                ],[
                    'text' => 'Registration Sources',
                    'route' => 'registration-sources.show',
                    'can' => 'registration-sources',
                    'active' => ['registration-sources*'],
                ]
            ]

        ],
        [
            'text' => 'Agents',
            'url' => 'agents/',
            'can' => 'agents',
            'active' => ['agents*'],
            'icon' => 'fas fa-headset',
            'submenu' => [
                [
                    'text' => 'Agent',
                    'route' => 'agents.show',
                    'can' => 'agents',
                    'active' => ['agents/agents*','agents/agent-numbers*'],
                ]
                ,[
                    'text' => 'Admin Fees',
                    'route' => 'admin-fees.show',
                    'can' => 'admin-fees',
                    'active' => ['admin-fees*'],
                ],
                [
                    'text' => 'Agencies',
                    'route' => 'agencies.show',
                    'can' => 'agencies',
                    'active' => ['agencies*'],
                ],[
                    'text' => 'Agency Codes',
                    'route' => 'agency-codes.show',
                    'can' => 'agency-codes',
                    'active' => ['agency-codes*'],
                ],[
                    'text' => 'Contract Types',
                    'route' => 'contract-types.show',
                    'can' => 'contract-types',
                    'active' => ['contract-types*'],
                ],[
                    'text' => 'Agent Status',
                    'route' => 'agent-status.show',
                    'can' => 'agent-status',
                    'active' => ['agent-status*'],
                ],[
                    'text' => 'Agent Titles',
                    'route' => 'agent-titles.show',
                    'can' => 'agent-titles',
                    'active' => ['agent-titles*'],
                ],[
                    'text' => 'Sales Regions',
                    'route' => 'sales-regions.show',
                    'can' => 'sales-regions',
                    'active' => ['sales-regions*'],
                ]
            ]

        ],
        [
            'text' => 'Products',
            'url' => 'products/',
            'can' => 'products',
            'active' => ['products*'],
            'icon' => 'fas fa-tags',
            'submenu' => [
                [
                    'text' => 'Business Segments',
                    'route' => 'business-segments.show',
                    'can' => 'business-segments',
                    'active' => ['business-segments*'],
                ],[
                    'text' => 'Business Types',
                    'route' => 'business-types.show',
                    'can' => 'business-types',
                    'active' => ['business-types*'],
                ],[
                    'text' => 'Carriers',
                    'route' => 'carriers.show',
                    'can' => 'carriers',
                    'active' => ['carriers*'],
                ],[
                    'text' => 'Plan Types',
                    'route' => 'plan-types.show',
                    'can' => 'plan-types',
                    'active' => ['plan-types*'],
                ],[
                    'text' => 'Tiers',
                    'route' => 'tiers.show',
                    'can' => 'tiers',
                    'active' => ['tiers*'],
                ],[
                    'text' => 'Types',
                    'route' => 'types.show',
                    'can' => 'types',
                    'active' => ['types*'],
                ]
            ]

        ],[
            'text' => 'Reports',
            'url' => 'reports/',
            'can' => 'reports',
            'active' => ['reports*'],
            'icon' => 'fas fa-file'
        ],[
            'text' => 'Commissions',
            'url' => 'commissions/',
            'can' => 'commissions',
            'active' => ['commissions*'],
            'icon' => 'fas fa-receipt'
        ],[
            'text' => 'Users',
            'url' => 'users/',
            'can' => 'users',
            'active' => ['users*'],
            'icon' => 'fas fa-users'
        ],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.0.7/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.0.7/b-3.0.2/b-html5-3.0.2/b-print-3.0.2/datatables.min.js',
                ],                
            ],
        ],
        'DefaultDatatable' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/js/datatables.js',
                ],
            ]
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/Swal.js',
                ],

            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'Dropzone' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/files/dropzone-upload-files.js',
                ],
            ]
        ],
        'web' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/style.css',
                ],
            ],
        ],
        'Quill' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js'
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css'
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
