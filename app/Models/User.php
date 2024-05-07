<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Exception;
use Mail;
use App\Mail\SendMail;

class User extends Authenticatable
{

    protected $guard_name = 'web';

    use HasApiTokens, HasFactory, Notifiable, HasRoles;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'email',
        'password',
        'is_admin',
        'user_type_id',
        'google2fa_secret'
    ];

    public function UserType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    public function UserRoleType()
    {
        return $this->belongsTo(UserRoleType::class, 'user_role_type_id', 'id');
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** 
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateCode()
    {
        $code = rand(1000, 9999);
  
            UserCode::updateOrCreate(
                ['user_id' => auth()->guard('user')->user()->id],
                ['code' => $code]
            );
        
            try {
                $details = [
                    'title' => 'Your Two-Factor Authentication Code',
                    'name' => auth()->guard('user')->user()->name,
                    'body' => 'We hope this email finds you well. As part of our ongoing commitment to ensuring the security of your account, we have implemented Two-Factor Authentication (2FA) to provide an extra layer of protection for your account.',
                    'code' => $code
                ];
                
                Mail::to(auth()->guard('user')->user()->email)->send(new SendMail($details));
                
            } catch (Exception $e) {
    
                info("Error: ". $e->getMessage());
            }
    }
}
