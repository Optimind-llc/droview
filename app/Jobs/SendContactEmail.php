<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Access\User\User;

class SendContactEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $name;
    protected $email;
    protected $comments;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $email, $comments)
    {
        $this->name = $name;
        $this->email = $email;
        $this->comments = $comments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send(
            'emails.contact',
            [
                'name' => $this->name,
                'email' => $this->email,
                'comments' => $this->comments,
            ],
            function ($message)
            {
                $message->to('horita.works@gmail.com', 'shiichi')->subject('ユーザーからのお問い合わせ');
            }
        );
    }
}
