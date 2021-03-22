<?php
namespace App\Http\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Administrator extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $table = 'administrators';
    protected $primaryKey='id';
    public $timestamps = true;
    protected $guarded=[];
    protected $guard_name = 'api';
    protected $hidden = ['password'];

    public function toDescription()
    {
        return "$this->given_name $this->family_name";
    }

    public function getActivitiesAttribute()
    {
        return Activity::where('model_type_1', get_class($this))->where('model_id_1', $this->id)->orWhere('model_type_2', get_class($this))->where('model_id_2', $this->id)->orderBy('created_at','desc')->get();
    }
}
