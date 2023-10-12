<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username', 'email', 'password',
        'nombre','ap_paterno','ap_materno',
        'curp','emails','celulares','telefonos',
        'fecha_nacimiento','genero', 'lugar_nacimiento',
        'root','filename','filename_png','filename_thumb',
        'empresa_id','status_user','ip','host','searchtext',
        'logged','logged_at','logout_at', 'user_mig_id','email_verified_at',
        'creadopor_id','created_at','modipor_id','updated_at',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];
    protected $hidden = ['password', 'remember_token','deleted_at'];
    protected $dates = ['fecha_nacimiento' => 'datetime:d-m-Y'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function scopeSearch($query, $search){
        if (!$search || $search == "" || $search == null) return $query;
        return $query->whereRaw("searchtext @@ to_tsquery('spanish', ?)", [$search])
            ->orderByRaw("ts_rank(searchtext, to_tsquery('spanish', ?)) DESC", [$search]);
    }

//    public function scopeFilterBy($query, $filters){
//        return (new UserFilter())->applyTo($query, $filters);
//    }

    public function permisos() {
        return $this->belongsToMany(Permission::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isRole($role): bool{
        return $this->hasRole($role);
    }

    public function IsEmptyPhoto(){
        return $this->filename == '' ? true : false;
    }

    public function IsFemale(){
        return $this->genero == 0 ? true : false;
    }

    public function scopeMyID(){
        return $this->id;
    }

    public function scopeRole(){
        return $this->roles()->first();
    }


//    public function sendPasswordResetNotification($token){
//        $this->notify(new MyResetPassword($token));
//    }
//
//    public function sendEmailVerificationNotification(){
//        $this->notify(new SendEmailAPIVerificationNotification());
//    }





}
