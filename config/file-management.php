<?php

use Sajadsdi\LaravelFileManagement\Enums\FileType;
use Sajadsdi\LaravelFileManagement\Jobs\Compress\DeleteCompressJob;
use Sajadsdi\LaravelFileManagement\Jobs\Compress\EditCompressJob;
use Sajadsdi\LaravelFileManagement\Jobs\Compress\TrashCompressJob;
use Sajadsdi\LaravelFileManagement\Jobs\Compress\UploadCompressJob;
use Sajadsdi\LaravelFileManagement\Jobs\Document\DeleteDocumentJob;
use Sajadsdi\LaravelFileManagement\Jobs\Document\EditDocumentJob;
use Sajadsdi\LaravelFileManagement\Jobs\Document\TrashDocumentJob;
use Sajadsdi\LaravelFileManagement\Jobs\Document\UploadDocumentJob;
use Sajadsdi\LaravelFileManagement\Jobs\Image\DeleteImageJob;
use Sajadsdi\LaravelFileManagement\Jobs\Image\EditImageJob;
use Sajadsdi\LaravelFileManagement\Jobs\Image\TrashImageJob;
use Sajadsdi\LaravelFileManagement\Jobs\Image\UploadImageJob;
use Sajadsdi\LaravelFileManagement\Jobs\Music\DeleteMusicJob;
use Sajadsdi\LaravelFileManagement\Jobs\Music\EditMusicJob;
use Sajadsdi\LaravelFileManagement\Jobs\Music\TrashMusicJob;
use Sajadsdi\LaravelFileManagement\Jobs\Music\UploadMusicJob;
use Sajadsdi\LaravelFileManagement\Jobs\Other\DeleteOtherJob;
use Sajadsdi\LaravelFileManagement\Jobs\Other\EditOtherJob;
use Sajadsdi\LaravelFileManagement\Jobs\Other\TrashOtherJob;
use Sajadsdi\LaravelFileManagement\Jobs\Other\UploadOtherJob;
use Sajadsdi\LaravelFileManagement\Jobs\Program\DeleteProgramJob;
use Sajadsdi\LaravelFileManagement\Jobs\Program\EditProgramJob;
use Sajadsdi\LaravelFileManagement\Jobs\Program\TrashProgramJob;
use Sajadsdi\LaravelFileManagement\Jobs\Program\UploadProgramJob;
use Sajadsdi\LaravelFileManagement\Jobs\Video\DeleteVideoJob;
use Sajadsdi\LaravelFileManagement\Jobs\Video\EditVideoJob;
use Sajadsdi\LaravelFileManagement\Jobs\Video\TrashVideoJob;
use Sajadsdi\LaravelFileManagement\Jobs\Video\UploadVideoJob;

return [
    'types'                       => [
        'image'    => [
            'type'                  => FileType::image,
            'allow_mimes'           => [
                'image/png'              => ['png'],
                'image/apng'             => ['png', 'apng'],
                'image/vnd.mozilla.apng' => ['png', 'apng'],
                'image/bmp'              => ['bmp'],
                'image/x-bmp'            => ['bmp', 'dib'],
                'image/gif'              => ['gif'],
                'image/jpeg'             => ['jpg', 'jpeg'],
                'image/pjpeg'            => ['jpg', 'jpeg'],
                'image/svg'              => ['svg'],
                'image/svg+xml'          => ['svg'],
                'image/webp'             => ['webp'],
            ],
            'max_file_size_mb'      => 10,
            'start_path'            => 'images',
            'create_resize_images'  => true,
            'resize_heights'        => [50, 100, 150, 200, 300, 500],
            'duplicate_on_resize'   => true,
            'quality'               => 100,
            'image_process_driver'  => 'gd',
            'save_original_image'   => false,
            'original_image_suffix' => 'org',
            'disk'                  => "public",
            'jobs'                  => [
                'upload' => UploadImageJob::class,
                'edit'   => EditImageJob::class,
                'delete' => DeleteImageJob::class,
                'trash'  => TrashImageJob::class
            ],
            'process_to_queue'      => false,
            'queue'                 => 'file-process-images',
            'create_date_paths'     => true,
            'date_paths_with_day'   => true
        ],
        'video'    => [
            'type'                => FileType::video,
            'allow_mimes'         => [
                'video/mp4'        => ['mp4'],
                'video/x-m4v'      => ['mp4'],
                'video/mp4v-es'    => ['mp4'],
                'video/x-matroska' => ['mkv'],
            ],
            'max_file_size_mb'    => 50,
            'start_path'          => 'videos',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadVideoJob::class,
                'edit'   => EditVideoJob::class,
                'delete' => DeleteVideoJob::class,
                'trash'  => TrashVideoJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-videos',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
        'music'    => [
            'type'                => FileType::music,
            'allow_mimes'         => [
                'audio/mp3'    => ['mp3'],
                'audio/x-mp3'  => ['mp3'],
                'audio/mpeg'   => ['mp3'],
                'audio/x-mpeg' => ['mp3'],
                'audio/x-mpg'  => ['mp3'],
            ],
            'max_file_size_mb'    => 20,
            'start_path'          => 'musics',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadMusicJob::class,
                'edit'   => EditMusicJob::class,
                'delete' => DeleteMusicJob::class,
                'trash'  => TrashMusicJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-musics',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
        'document' => [
            'type'                => FileType::document,
            'application/pdf', 'application/acrobat', 'application/nappdf', 'application/x-pdf', 'image/pdf',
            'allow_mimes'         => [
                'application/pdf'                                                         => ['pdf'],
                'application/acrobat'                                                     => ['pdf'],
                'application/nappdf'                                                      => ['pdf'],
                'application/x-pdf'                                                       => ['pdf'],
                'image/pdf'                                                               => ['pdf'],
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => ['docx'],
            ],
            'max_file_size_mb'    => 5,
            'start_path'          => 'documents',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadDocumentJob::class,
                'edit'   => EditDocumentJob::class,
                'delete' => DeleteDocumentJob::class,
                'trash'  => TrashDocumentJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-documents',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
        'program'  => [
            'type'                => FileType::program,
            'allow_mimes'         => [
                'application/x-dosexec'                   => ['exe'],
                'application/vnd.android.package-archive' => ['apk'],
            ],
            'max_file_size_mb'    => 100,
            'start_path'          => 'programs',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadProgramJob::class,
                'edit'   => EditProgramJob::class,
                'delete' => DeleteProgramJob::class,
                'trash'  => TrashProgramJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-programs',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
        'compress' => [
            'type'                => FileType::compress,
            'allow_mimes'         => [
                'application/zip'              => ['zip'],
                'application/x-zip'            => ['zip'],
                'application/x-zip-compressed' => ['zip'],
                'application/x-gzip'           => ['gz'],
                'application/gzip'             => ['gz'],
                'application/x-tar'            => ['tar'],
                'application/x-gtar'           => ['tar'],
                'application/x-rar-compressed' => ['rar'],
                'application/vnd.rar'          => ['rar'],
                'application/x-rar'            => ['rar'],
            ],
            'max_file_size_mb'    => 50,
            'start_path'          => 'compressed',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadCompressJob::class,
                'edit'   => EditCompressJob::class,
                'delete' => DeleteCompressJob::class,
                'trash'  => TrashCompressJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-compress',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
        'other'    => [
            'type'                => FileType::other,
            'allow_mimes'         => [
                'text/plain'               => ['txt', 'js', 'css'],
                'text/html'                => ['html', 'js'],
                'text/x-php'               => ['php'],
                'application/octet-stream' => ['glb']
            ],
            'max_file_size_mb'    => 2,
            'start_path'          => 'others',
            'disk'                => "public",
            'jobs'                => [
                'upload' => UploadOtherJob::class,
                'edit'   => EditOtherJob::class,
                'delete' => DeleteOtherJob::class,
                'trash'  => TrashOtherJob::class
            ],
            'process_to_queue'    => false,
            'queue'               => 'file-process-others',
            'create_date_paths'   => true,
            'date_paths_with_day' => true
        ],
    ],
    'security'                    => [
        'secure_types' => ['other', 'program'],
        'secure_ext'   => 'secure',
    ],
    'trash'                       => [
        'path' => "trash",
        'disk' => "local"
    ],
    'temp_path'                   => "temp",
    'require_check_ext_for_mimes' => ['application/octet-stream'],
];
