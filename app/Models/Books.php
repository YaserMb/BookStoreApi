<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Books extends Model
{
    use Searchable;
    use HasFactory;


    public function searchableAs()
    {
        return 'books_index';
    }

}
