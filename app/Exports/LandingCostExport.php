<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LandingCostExport implements FromQuery, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = \DB::table('batches')
                   ->select(
                'batches.id',
                'products.name',
                'batches.product_id',
                'batch_no',
                'batches.created_at as created_at',
                'stocks.origin as origin',
                \DB::raw('(batches.quantity - batches.sold) as quantity'),
                \DB::raw('batches.cost as landing_cost'),
                \DB::raw('(batches.quantity - batches.sold) * batches.cost as stock_value')
            )
            ->leftJoin('products', 'products.id', '=', 'batches.product_id')
            ->leftJoin('stocks', 'stocks.batch_id', '=', 'batches.id')
            ->whereNull('batches.deleted_at')
            ->where('products.approve', 1)
            ->orderByDesc('batches.product_id');

        if ($this->filters['product_name']) {
            $query->where('products.name', 'LIKE', '%' . $this->filters['product_name'] . '%');
        }

        if ($this->filters['batch_no']) {
            $query->where('batches.batch_no', 'LIKE', '%' . $this->filters['batch_no'] . '%');
        }

        if ($this->filters['product_id']) {
            $query->where('batches.product_id', $this->filters['product_id']);
        }
        if ($this->filters['start_date'] && $this->filters['end_date']) {
            $query->whereBetween('batches.created_at', [$this->filters['start_date'], $this->filters['end_date']]);
        }

        return $query;
    }

    public function headings(): array
    {
       // return ['ID', 'Name', 'Product ID', 'Batch No', 'Quantity', 'Landing Cost', 'Stock Value'];
            return['batches.id',
                'products.name',
                'batches.product_id',
                'batch_no',
                'created_at',
                'origin',
                'quantity',
               'landing_cost',
                'stock_value'];
    }
}
