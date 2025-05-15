<?php

namespace App\Jobs;

use App\Models\Commissions\CommissionUploadRowsModel;
use App\Services\Commissions\CommissionRowProcessor;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class LinkCommissionUploadsJob implements ShouldQueue
{
    use Queueable, SerializesModels, Batchable;

    protected $commissionRowId;
    /**
     * Create a new job instance.
     */
    public function __construct($commissionRowId)
    {   
        $this->commissionRowId = $commissionRowId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $procesor = new CommissionRowProcessor();
        $procesor->startProcess($this->commissionRowId);
    }
}
