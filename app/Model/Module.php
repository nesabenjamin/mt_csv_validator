<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Module extends Model
{
    protected $gaurd = [];

    public static function getColumns(){
        $column =  Schema::getColumnListing('modules');
        $cleanedColumn =  array_diff($column, ['id', 'created_at', 'updated_at']);
        return array_values(array_filter($cleanedColumn));   
    }


}
