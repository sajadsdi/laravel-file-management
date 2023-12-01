<?php

namespace Sajadsdi\LaravelFileManagement\Concerns\Relations;

use Sajadsdi\LaravelFileManagement\Model\File;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait FileBelongsToRelation
{
    /**
     * relation to files table
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
