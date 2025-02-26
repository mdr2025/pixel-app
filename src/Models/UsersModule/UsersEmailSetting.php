<?php

namespace PixelApp\Models\UsersModule; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Models\PixelBaseModel ;

class UsersEmailSetting extends PixelBaseModel
{
    use HasFactory;

    protected $table ="users_email_settings";

    protected $fillable =[
        'mailer',
        'port',
        'host',
        'email_from',
        'username',
        'password',
        'encryption'
    ];
}
