<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $primaryKey='id';
    public $timestamps = true;
    protected $guarded=[];
}
