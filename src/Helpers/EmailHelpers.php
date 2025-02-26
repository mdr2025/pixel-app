<?php

namespace PixelApp\Helpers;

use App\Models\CivilDefensePanel\CompanyModule\Company;
use App\Services\SystemSettings\UsersModule\MailService;


class EmailHelpers
{
    public static function generateVerificationToken($model, string $email)
    {
        $verification_token =  md5(rand(0, 9) . $model->$email . time());
        $model->verification_token = $verification_token;
        $model->save();
        return $verification_token;
    }

    function codeExists($code, string $column)
    {
        return Company::where($column, $code)->exists();
    }

    // public static function generateVerificationCode($model, string $column)
    // {

    //     $code = random_int(100000, 999999);
    //     if (codeExists($column, $code)) {
    //         return generateVerificationCode($column, $code);
    //     }
    //     $model->verification_code = $code;
    //     $model->save();

    //     return $code;
    // }
    public static function sendEmailVerification($model, $subject = null, $msg, $verification = null, string $email)
    {
        $mail = $model[$email];
        $subject = $subject ?? "verify your account";
        $message = $msg ?? "<br>kindly click this link to verify
    your account <br><a href=$verification target=_blank>Click here </a> <br><br>Sincerely <br> ------------ <br> IGS Support Team ";
        $mailService = new MailService;
        return $mailService->sendEmailVerification(
            env('SUPPORT_MAIL'),
            $model,
            $subject,
            "<h2>Dear $model->first_name,</h2> <br> $message",
            $mail
        );
    }
    public static function getVerificationLink($verificationToken, $type = 'company', $tenant = null)
    {
        return urldecode(env("MAIL_LINK") . "/$type-account-verification?token={$verificationToken}&domain=$tenant");
    }

    public static function resetPasswordLink($verificationToken)
    {
        return urldecode(env("MAIL_LINK") . "/reset-password?token=$verificationToken");
    }
}
