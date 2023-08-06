<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailReminder extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $shop_name;
    protected $today;
    protected $time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $shop_name, $today, $time)
    {
        $this->user = $user;
        $this->shop_name = $shop_name;
        $this->today = $today;
        $this->time = $time;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('【Rese】ご予約確認')
            ->view('emails.reminder')
            ->with([
                'user' => $this->user,
                'shop_name' => $this->shop_name,
                'today' => $this->today,
                'time' => $this->time,
            ]);
    }
}
