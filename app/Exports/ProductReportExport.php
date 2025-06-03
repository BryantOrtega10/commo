<?php

namespace App\Exports;


use App\Models\Products\ProductsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $carrier;
    protected $business_type;
    protected $description;
    protected $plan_type;
    protected $product_type;

    public function __construct(
        $carrier,
        $business_type,
        $description,
        $plan_type,
        $product_type,
    ) {
        $this->carrier = $carrier;
        $this->business_type = $business_type;
        $this->description = $description;
        $this->plan_type = $plan_type;
        $this->product_type = $product_type;
    }

    public function collection()
    {
        $products = ProductsModel::select("products.*");

        if($this->carrier !== null && $this->carrier != ""){
            $products->where("products.fk_carrier","=", $this->carrier);
        }
        if($this->business_type !== null && $this->business_type != ""){
            $products->where("products.fk_business_type","=", $this->carrier);
        }
        if($this->description !== null && $this->description != ""){
            $products->where("products.description","like", '%'.$this->description.'%');
        }
        if($this->plan_type !== null && $this->plan_type != ""){
            $products->where("products.fk_plan_type","=", $this->plan_type);
        }
        if($this->product_type !== null && $this->product_type != ""){
            $products->where("products.fk_product_type","=", $this->product_type);
        }
        
        $products->orderBy("description","ASC");

        return $products->get();
    }

    public function map($row): array
    {
        $aliasArr = [];
        $aliasTxt = "";
        foreach($row->product_alias as $alias){
            array_push($aliasArr, $alias->alias);
        }
        $aliasTxt = implode(", \n",$aliasArr);

        return [
            $row->id,
            $row->description,
            $row->product_type?->name,
            $row->plan_type?->name,
            $row->business_type?->name,
            $row->carrier?->name,
            $row->tier?->name,
            $row->business_segment?->name,
            $aliasTxt,
        ];
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Product',
            'Product Type',
            'Plan Type',
            'Business Type',
            'Carrier',
            'Product Tier',
            'Business Segment',
            'Product Alias',
        ];
    }
}
