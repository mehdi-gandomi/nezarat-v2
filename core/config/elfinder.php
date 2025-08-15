<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public).
    |
    */
    'dir' => ['uploads'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [
        'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => [
        'prefix'     => config('backpack.base.route_prefix', 'admin').'/elfinder',
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')], //Set to null to disable middleware filter
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => [
        [
            'driver'        => 'LocalFileSystem',
            'path'          => dirname(base_path()) . '/uploads',
            'URL'           => '/',
            'alias'         => 'uploads',
            'mimeDetect'    => 'mime_content_type',
            'allowMkdir'    => true,
            'allowMkfile'   => true,
            'allowRename'   => true,
            'allowDelete'   => true,
            'allowUpload'   => true,
            'allowDownload' => true,
            'uploadDeny'    => [],
            'uploadAllow'   => ['image', 'text/plain', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'uploadOrder'   => ['deny', 'allow'],
            'uploadMaxSize' => '10M',
            'defaults'      => [
                'read'   => true,
                'write'  => true,
                'locked' => false,
                'hidden' => false,
            ],
            'attributes'    => [
                [
                    'pattern' => '/\.(git|svn|htaccess|htpasswd)$/',
                    'read'    => false,
                    'write'   => false,
                    'locked'  => true,
                    'hidden'  => true,
                ],
                [
                    'pattern' => '/\.(jpg|jpeg|png|gif|bmp|webp)$/',
                    'read'    => true,
                    'write'   => true,
                    'locked'  => false,
                    'hidden'  => false,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [
        'bind' => [
            'upload.presave' => [
                'Plugin.AutoResize.onUpLoadPreSave',
            ],
        ],
        'plugin' => [
            'AutoResize' => [
                'enable'         => true,
                'maxWidth'       => 1920,
                'maxHeight'      => 1920,
                'quality'        => 95,
                'targetType'     => IMG_GIF | IMG_JPG | IMG_PNG | IMG_WEBP,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Root Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with every root by default.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1#root-options
    |
    */
    'root_options' => [
        'acceptedName' => '/^[^\.].*$/',
        'uploadMaxSize' => '10M',
    ],

];
