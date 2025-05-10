<?php

namespace App\Jobs;

use App\Models\Commissions\CommissionUploadRowsModel;
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
        $commissionRow = CommissionUploadRowsModel::find($this->commissionRowId);
        $commissionRow->status = 1;
        $commissionRow->save();

        $commissionRow->commission_upload->processed_rows++;
        $commissionRow->commission_upload->save();
    }
}
