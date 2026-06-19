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
                'products.id as product_id',
                'products.name',
                \DB::raw("GROUP_CONCAT(DISTINCT batches.batch_no SEPARATOR ', ') as batch_no"),
                \DB::raw('SUM(batches.quantity - batches.sold) as quantity'),
                \DB::raw('ROUND(SUM((batches.quantity - batches.sold) * batches.cost) / NULLIF(SUM(batches.quantity - batches.sold), 0), 2) as landing_cost'),
                \DB::raw('SUM((batches.quantity - batches.sold) * batches.cost) as stock_value'),
                \DB::raw('MAX(batches.created_at) as created_at')
            )
            ->leftJoin('products', 'products.id', '=', 'batches.product_id')
            ->whereNull('batches.deleted_at')
            ->where('products.approve', 1)
            ->groupBy('products.id', 'products.name')
            ->orderBy('products.name', 'asc');

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
            return [
                'Product ID',
                'Product Name',
                'Batch Numbers',
                'Quantity Available',
                'Landing Cost (Weighted Avg)',
                'Stock Value',
                'Latest Date Created'
            ];
    }
}
