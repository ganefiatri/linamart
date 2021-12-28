<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberSignup extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Member object
     *
     * @var Member
     */
    public $member;

    /**
     * Create a new message instance.
     *
     * @param Member $member
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[' . config('global.site_name') . '] Selamat Datang')
            ->markdown('emails.member.signup');
    }
}
