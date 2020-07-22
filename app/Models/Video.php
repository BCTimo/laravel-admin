<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Video extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];
}
