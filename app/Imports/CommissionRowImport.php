<?php

namespace App\Imports;

use App\Models\Commissions\CommissionUploadRowsModel;
use App\Models\Commissions\CommissionUploadsModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;


class CommissionRowImport implements ToCollection, WithChunkReading, WithHeadingRow, ShouldQueue, WithEvents, WithCalculatedFormulas
{
    protected $commissionUploadId;

    public function __construct($commissionUploadId)
    {
        $this->commissionUploadId = $commissionUploadId;
    }
    
    public function registerEvents(): array
    {
        $commissionUploadId = $this->commissionUploadId;
        return [
            AfterImport::class => function(AfterImport $event) use($commissionUploadId) {
                
                Log::info('ID de carga: ' . $commissionUploadId);
                $commissionUpload = CommissionUploadsModel::find($commissionUploadId);
                $commissionUpload->status = 1;
                $commissionUpload->save();
            },
        ];
    }
   
    public function chunkSize(): int
    {
        return 100;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $count = 0;
        foreach ($rows as $row) {
            $arrRow = $row->toArray();
            $onlyNull = true;
            Log::info('Datos de la fila: ', $arrRow);
            foreach($arrRow as $itemRow){
                if($itemRow != null && $itemRow != ""){
                    $onlyNull = false;
                    break;
                }
            }
            if(!$onlyNull){
                $commisionRow = new CommissionUploadRowsModel();
                $commisionRow->data = json_encode($row);
                $commisionRow->fk_commission_upload = $this->commissionUploadId;
                $commisionRow->save();
                $count++;
            }
          
        }
        $commissionUpload = CommissionUploadsModel::find($this->commissionUploadId);
        $commissionUpload->uploaded_rows += $count;
        $commissionUpload->save();
    }
}
