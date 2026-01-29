@extends('layouts.app')

@section('content')
    @if (session()->has('message'))
        <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
        </div>
    @endif

    <div class="row mt-1">
        <!-- Filter Section -->
        <div class="col-12 mb-3">
            <div class="card" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="color: #08228a; font-weight: 600;">
                        <i class="mdi mdi-filter-variant me-2"></i>Filter Options
                    </h5>
                    <form method="GET" action="/restocks">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="product_name" class="form-label fw-semibold">Product Name</label>
                                <input type="text" list="product_list" class="form-control" id="product_name"
                                    name="product_name" value="{{ request('product_name') }}"
                                    placeholder="Search product..." autocomplete="off">
                                <datalist id="product_list">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->name }}">{{ $product->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>


                            <div class="col-md-2">
                                <label for="source" class="form-label fw-semibold">Source Type</label>
                                <select class="form-select" id="source" name="source">
                                    <option value="">All Sources</option>
                                    <option value="import" {{ request('source') == 'import' ? 'selected' : '' }}>Import
                                    </option>
                                    <option value="export" {{ request('source') == 'export' ? 'selected' : '' }}>Export
                                    </option>
                                    <option value="local" {{ request('source') == 'local' ? 'selected' : '' }}>Local</option>
                                </select>
                            </div>



                            <div class="col-md-2">
                                <label for="from" class="form-label fw-semibold">From Date</label>
                                <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                            </div>

                            <div class="col-md-2">
                                <label for="to" class="form-label fw-semibold">To Date</label>
                                <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-magnify me-1"></i>Filter
                                    </button>
                                    <a href="/restocks" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="mdi mdi-database me-1"></i>Total Records: {{$totalRestocks}}
                    </span>
                    <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="mdi mdi-cash-multiple me-1"></i>Total Cost: {{ number_format($totalLandingCost, 2) }}
                    </span>
                </div>
                <button id="excelbtn" type="button" class="btn btn-success">
                    <i class="fa fa-file-excel me-1"></i>Export to Excel
                </button>
            </div>

            <div class="card" style="border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"
                            class="table table-hover table-bordered nowrap text-center" id="restockstable">
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                @php
                                    $page = $page_number;
                                @endphp
                                <tr>
                                    <th style="border-color: rgba(255,255,255,0.2);">#</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Product</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Quantity</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Source</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Landing Cost</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Price/Item</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Invoice</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Delivery Note</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Staff</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Date</th>
                                    <th style="border-color: rgba(255,255,255,0.2);">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr style="transition: all 0.3s ease;">
                                        <td>{{$page}}</td>
                                        <td class="fw-semibold" style="color: #08228a;">{{$item['product_name']}}</td>
                                        <td>
                                            <span class="badge bg-info" style="font-size: 0.9rem;">
                                                {{$item['quantity']}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: #667eea; font-size: 0.85rem;">
                                                {{$item['source'] ?? 'N/A'}}
                                            </span>
                                        </td>
                                        <td class="fw-semibold" style="color: #28a745;">
                                            {{ $item['landing_cost'] ? number_format($item['landing_cost'], 2) : 'N/A' }}
                                        </td>
                                        <td class="fw-semibold" style="color: #fd7e14;">
                                            {{ $item['price_per_item'] > 0 ? number_format($item['price_per_item'], 2) : 'N/A' }}
                                        </td>
                                        <td>{{$item['invoice'] ?? '-'}}</td>
                                        <td>{{$item['delivery_note'] ?? '-'}}</td>
                                        <td>
                                            <i class="mdi mdi-account-circle me-1" style="color: #667eea;"></i>
                                            {{$item['staff']}}
                                        </td>
                                        <td style="font-size: 0.85rem;">
                                            <i class="mdi mdi-clock-outline me-1"></i>
                                            {{$item['date']}}
                                        </td>
                                        <td class="text-start" style="min-width: 150px; max-width: 200px; font-size: 0.85rem;">
                                            {{$item['remarks'] ?? '-'}}
                                        </td>
                                    </tr>
                                    @php
                                        $page += 1;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <i class="mdi mdi-information-outline" style="font-size: 2rem; color: #999;"></i>
                                            <p class="mt-2 mb-0" style="color: #999;">No restock records found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        <div style="font-size: 14px;">
                            {{ $restocks->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#excelbtn").click(function () {
                TableToExcel.convert(document.getElementById("restockstable"), {
                    name: "Invitro_Restocks_Report.xlsx",
                    sheet: {
                        name: "Restocks"
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endsection