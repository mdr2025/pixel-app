<?php
namespace PixelApp\Http\Controllers;
 
use PHPMailer\PHPMailer;
use PixelApp\Models\UsersModule\UsersEmailSetting as UsersModuleUsersEmailSetting;

class testPHPMailer extends PixelBaseController
{
    public function index()
    {
        try {
            $email = UsersModuleUsersEmailSetting::where('username','usmanahmedfathy@gmail.com')->get()->first();
            $text             = 'Hello Mail';
            $mail             = new PHPMailer\PHPMailer(true); // create a n
            $mail->SMTPDebug  = 2; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth   = true; // authentication enabled
            $mail->SMTPSecure = $email->encryption; // secure transfer enabled REQUIRED for Gmail
            $mail->Host       = $email->host;
            $mail->Port       = $email->port; // or 587
            $mail->IsHTML(true);
            $mail->Username = $email->username;
            $mail->Password = $email->password;
            $mail->SetFrom($email->email_from, auth()->user()->name);
            $mail->Subject = "Test Subject";
            $mail->Body    = $text;
            $mail->addAddress("osmanarrow90@gmail.com", "Receiver Name");
            if ($mail->Send()) {
                return 'Email Sended Successfully';
            } else {
                return 'Failed to Send Email';
            }
        }catch (\Throwable $e){
            throw $e;
        }
    }
}
