<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Tag extends Model implements Sortable
{
    use SortableTrait;
    
    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];
    
    public function videos()
    {
        return $this->morphedByMany(Video::class, 'video_tags');
    }

}
