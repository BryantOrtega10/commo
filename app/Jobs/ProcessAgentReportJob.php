<?php

namespace App\Jobs;

use App\Services\Reports\AgentReportProcessor;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class ProcessAgentReportJob implements ShouldQueue
{
    use Queueable, SerializesModels, Batchable;

    protected $reportItemId;
    /**
     * Create a new job instance.
     */
    public function __construct($reportItemId)
    {   
        $this->reportItemId = $reportItemId;
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $procesor = new AgentReportProcessor();
        $procesor->process($this->reportItemId);
    }
}
