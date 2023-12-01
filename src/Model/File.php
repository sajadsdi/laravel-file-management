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
    protected $fillable = ["type", "title", "name", "ext", "path", "disk"];
    protected $hidden   = ["path", "disk", "updated_at", "deleted_at"];
    protected $appends  = ["url"];


    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
