<?php

namespace admin\users\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct($user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        $emailTemplate = \DB::table('emails')->where('slug', 'register_user')->first(['subject', 'description']);

        $subject = $emailTemplate->subject;
        $content = $emailTemplate->description;

        $content = str_replace('%EMAIL_FOOTER%', config('GET.email_footer_text'), $content);
        $subject = str_replace('%APP_NAME%', env('APP_NAME'), $subject);
        $content = str_replace('%APP_NAME%', env('APP_NAME'), $content);
        $content = str_replace('%USER_NAME%', $this?->user?->full_name, $content);

        $content = str_replace('%EMAIL_ADDRESS%', $this?->user?->email, $content);
        $content = str_replace('%PASSWORD%', $this->plainPassword, $content);
        $result = $this->subject($subject)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->replyTo(env('MAIL_FROM_ADDRESS'))
            ->view('user::admin.email.welcome_mail')
            ->with(['template' => $content]);
    }
}
