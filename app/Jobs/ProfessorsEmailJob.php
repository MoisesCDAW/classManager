<?php

namespace App\Jobs;

use App\Mail\ProfessorsMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ProfessorsEmailJob implements ShouldQueue
{
    use Queueable;

    public $user = null;
    public $absence = null;
    public $week = null;
    public $day = null;

    /**
     * Create a new job instance.
     */
    public function __construct($professor, $absence, $week, $day)
    {
        $this->user = $professor;
        $this->absence = $absence;
        $this->week = $week;
        $this->day = $day;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to("admin@gmail.com")->send(new ProfessorsMail($this->user, $this->absence, $this->week, $this->day));
    }
}
