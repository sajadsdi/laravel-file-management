<?php

namespace Sajadsdi\LaravelFileManagement\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasUuids, SoftDeletes;

    protected $table    = "files";
    protected $fillable = ["type", "status", "title", "name", "ext", "path", "disk", "size", "details", 'trashed_at'];
    protected $hidden   = ["path", "disk", "updated_at", "deleted_at"];
    protected $appends  = ["url"];
    protected $casts    = [
        'details' => 'array'
    ];

    const STATUS_INPROGRESS = 1;
    const STATUS_VERIFIED = 2;


    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
