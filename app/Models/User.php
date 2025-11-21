<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['first_name', 'last_name', 'email', 'username', 'dob', 'gender', 'phone', 'country_code', 'address', 'city', 'zip_code', 'description',  'password', 'profile_photo_path', 'provider', 'provider_id', 'device_token', 'device_type', 'active', 'license', 'is_approved', 'busniess_name', 'business_type', 'country_id', 'main_destination', 'no_of_employees', 'is_operate_directly', 'website_link', 'instagram_link', 'about_operation', 'registration_certificate', 'tourism_operating_license', 'insurance', 'business_logo', 'last_seen_at', 'stripe_customer_id', 'stripe_account_no', 'is_completed_stripe_account_verification', 'stripe_account_link', 'why_choose_us','business_years'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'string',
    ];

    protected $appends = [
        'full_name',
        'role_name',
        'profile_photo_url',
        'license_doc_url',
        'registration_pdf_url',
        'license_pdf_url',
        'insurance_pdf_url',
        'operator_logo_full_url'
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getRoleNameAttribute()
    {
        if ($this->roles()->exists())
            return $this->roles()->first()->name;
        else
            return 0;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getDobAttribute($val)
    {
        return $val ? date('m-d-Y', strtotime($val)) : null;
    }

    public function getProfilePhotoUrlAttribute()
    {
        // return ("{$this->profile_photo_path}") ? url()->to('/' . "{$this->profile_photo_path}") : asset('admin_assets/demo.png');

        $path = asset('admin_assets/demo.png');
        if ($this->profile_photo_path) {
            $path = asset($this->profile_photo_path);
        }
        return $path;
    }


    public function getOperatorLogoFullUrlAttribute()
    {
        $path = NULL;
        if ($this->business_logo) {
            $path = asset($this->business_logo);
        }
        return $path;
    }

    public function getLicenseDocUrlAttribute()
    {
        $path = NULL;
        if ($this->license) {
            $path = asset($this->license);
        }
        return $path;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getRegistrationPdfUrlAttribute()
    {
        return $this->registration_certificate ? asset($this->registration_certificate) : '';
    }

    public function getLicensePdfUrlAttribute()
    {
        return $this->tourism_operating_license ? asset($this->tourism_operating_license) : '';
    }
    public function getInsurancePdfUrlAttribute()
    {
        return $this->insurance ? asset($this->insurance) : '';
    }

    public function safaris(): HasMany
    {
        return $this->hasMany(Safari::class, 'user_id');
    }
}
