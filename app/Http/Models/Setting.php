<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey='id';
    public $timestamps = true;
    protected $guarded=[];

    public function toDescription()
    {
        return "$this->category:$this->scope:$this->field";
    }
}
