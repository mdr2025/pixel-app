<?php

namespace PixelApp\Models\UsersModule;

use PixelApp\Models\PixelBaseModel;

class PasswordReset extends PixelBaseModel
{
    protected $table = 'password_resets';

    protected $fillable = ['email', 'token',];

    public function getLink(): string
    {
        return urldecode(env("FRONTEND_APP_URL") . '/reset-password?token=' . $this->token);
    }

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function scopeWithResetPasswordToken($query, $token)
    {
        return $query->where('token', $token);
    }
}
