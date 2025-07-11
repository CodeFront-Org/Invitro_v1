@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
        <div class="row mt-1">
            <div class="col-12">
                @role('staff')
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                     New
                </button>
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-restock-1">
                    Re-Stock
                </button>
                <button  style="background-color: #008b8bd0;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-ref">
                         <i class="fa fa-plus"></i> Ref No
                    </button>
                @endrole
                <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                                <thead class="table-light">

                                    @php
                                        $page=$page_number;
                                    @endphp
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Batches</th>
                                    @role('admin')
                                    <th colspan="2">Re-Order level</th>
                                    <th>Stock Audit</th>
                                    @endrole
                                    @role('staff')
                                    <th>Re-Order level</th>
                                    @endrole
                                    <th>Transactions</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($data as $item)
                                @if($item['alert']==10)
                                    <tr style="color:red">
                                        <td>{{$page}} </td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['qty_available']}}  ({{$item['qty_not_approved']}})</td>
                                        <td>{{$item['batch']}}</td>
                                        <td>{{$item['order_level']}}</td>
                                         @role('admin')
                                        <td>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-order-level-{{$item['id']}}">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        @endrole
                                        <td style='font-size:10px; text-align: center;'>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-txn-{{$item['id']}}">
                                                <i class='fas fa-newspaper' aria-hidden='true'></i>
                                            </button>
                                                <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
                                                    <i class='fa fa-trash' aria-hidden='true'></i>
                                                </button>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{$page}} </td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['qty_available']}}  ({{$item['qty_not_approved']}})</td>
                                        <td>{{$item['batch']}}</td>
                                        <td>{{$item['order_level']}}</td>
                                         @role('admin')
                                        <td>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-order-level-{{$item['id']}}">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" style="background-color: #006ca79f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-view-audit-{{$item['id']}}">
                                                <i class='fas fa-eye' aria-hidden='true'></i>
                                            </button>
                                            <button type="button" style="background-color: #006ca79f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-audit-{{$item['id']}}">
                                                <i class='fas fa-pen' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        @endrole
                                        <td style='font-size:10px; text-align: center;'>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-txn-{{$item['id']}}">
                                                <i class='fas fa-newspaper' aria-hidden='true'></i>
                                                </button>
                                                @role('admin')
                                                <button type="button" onclick="del_product(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
                                                    <i class='fa fa-trash' aria-hidden='true'></i>
                                                </button>
                                                @endrole
                                        </td>
                                    </tr>

                                @endif
                                @php
                                    $page+=1;
                                @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination links -->
                        <div class="d-flex justify-content-end" style="margin-top: 20px;height:30%;height:1032%"> <!-- Adjust margin-top as needed -->
                            <div style="margin-right: 0; text-align: right; font-size: 14px; color: #555;">
                                {{ $data1->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- end row -->


            <!-- Add New Stock Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="addForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="12">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Product</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label for="field-11w" class="form-label">Product Name:</label> 

                                    <div class="input-group mb-3">
                                     <input type="text" name='name'  class='form-control' placeholder="Search..." value=''  >
                                      


                                    </div>
                                        
                      
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Re-Order Level</label>
                                        <input type="number" name="o_level" class="form-control" id="field-2l" placeholder="re-order level (min 1)" min=1 required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Reference Number</label>
                                        <input type="text" name="ref_no" class="form-control" id="field-2l" placeholder="Product reference number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Expiry Alert</label>
                                        <input type="number" name="e_period" class="form-control" id="field-2l" placeholder="Expiry period in days" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="addbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="addloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->


            {{-- <!--Edit OrderLevel Modal -->
            @foreach ($data as $item)
                <div id="con-close-modal-edit-order-level-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
                        <div class="modal-content">
                            <form class="orderlevelForm" method="post">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="type" value="2">
                            <input type="hidden" name="editorderId" id="editorderId" value="{{$item['id']}}">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Order Level for Product: {{$item['name']}}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="field-2n" class="form-label">Re order Level</label>
                                            <input type="number" name="o_level" value="{{$item['order_level']}}" class="form-control" id="field-2n" placeholder="set new order level" required>
                                        </div>
                                    </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Expiry Alert</label>
                                        <input type="number" name="e_period" value="{{$item['e_period']}}" class="form-control" id="field-2l" placeholder="Expiry period in days" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Product Name</label>
                                        <input type="text" name="p_name" 
                                        value="{{$item['name']}}" class="form-control" id="field-2l" placeholder="Product Name" required>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn rounded-pill p-1" id="editorderbtn{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                        Submit
                                </button>
                                <button class="btn rounded-pill p-1" id="editorderloader{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        Saving Data...
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->
            @endforeach
 --}}



            {{-- <!--View Stock Audit-->
            @foreach ($data as $item1)
                <div id="con-close-modal-view-audit-{{$item1['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="orderlevelForm" method="post">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="type" value="2">
                            <input type="hidden" name="editorderId" id="editorderId" value="{{$item['id']}}">
                            <div class="modal-header">
                                <h4 class="modal-title">View Stock Audit for {{$item1['name']}}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                    <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
                <div class="table-responsive">
                    <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="salestable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                        @foreach ($audits as $item)
                            @if (empty($item)==0)
                                @if ($item1['id']==$item['product_id'])
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$item['status']==1?'Balanced':'Not Balanced'}}</td>
                                        <td>{{$item['comments']}}</td>
                                        <td>{{$item['created_at']}}</td>
                                    </tr>
                            @php
                            $i+=1;
                            @endphp
                                @endif
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data</td>
                                </tr>
                            @php
                            $i+=1;
                            @endphp
                            @endif
                            
                        @endforeach

                        </tbody>
                    </table>
                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->
            @endforeach --}}




            {{-- <!--Edit Audit Modal -->
            @foreach ($data as $item)
                <div id="con-close-modal-edit-audit-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
                        <div class="modal-content">
                            <form class="editaudit1" method="post">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="type" value="3">
                            <input type="hidden" name="editorderId" id="editorderId" value="{{$item['id']}}">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Audit for {{$item['name']}} Product</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                        <label for="field-11w" class="form-label">Status</label>
                                        <select name="status" class="form-control form-select" id="field-11w" required>
                                                    <option value="1">Balanced</option>
                                                    <option value="0">Not Balanced</option>
                                            </select>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="field-2" class="form-label">Comments</label>
                                            <textarea id="textarea" value="My Comments" name="comments" class="form-control" maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn rounded-pill p-1" id="editauditbtn{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                        Submit
                                </button>
                                <button class="btn rounded-pill p-1" id="editauditloader{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        Saving Data...
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->
            @endforeach --}}



            {{-- <!-- Edit Stock Modal -->
            @foreach ($data2 as $item)
                <div id="con-close-modal-edit-stock-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form class="editstockForm" method="post">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="type" value="1">
                            <input type="hidden" name="batch_id" value="{{$item['batch_id']}}">
                            <input type="hidden" name="product_id" value="{{$item['product_id']}}">
                            <input type="hidden" name="stock_id" value="{{$item['id']}}">
                            <input type="hidden" name="editstockId" id="editstockId" value="{{$item['id']}}">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Stock</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Quantity</label>
                                            <input type="text" value="{{$item['quantity']}}" name="quantity" class="form-control" id="field-2l" placeholder="quantity" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Batch/Lot Number</label>
                                            <input type="text" value="{{$item['batch_no']}}" name="batch_no" class="form-control" id="field-2l" placeholder="batch/lot number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                        <label for="field-11w" class="form-label">Quantity Type</label>
                                        <select name="q_type" class="form-control form-select" id="field-11w" required>
                                                    <option {{$item['quantity_type']==0?'selected':''}} value="0">Cartons</option>
                                                    <option {{$item['quantity_type']==1?'selected':''}} value="1">Packets</option>
                                                    <option {{$item['quantity_type']==2?'selected':''}} value="2">Single Items</option>
                                            </select>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2n" class="form-label">Source</label>
                                            <input type="text" value="{{$item['source']}}" name="source" class="form-control" id="field-2n" placeholder="source" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Expiry Date</label>
                                            <input type="date" value="{{$item['expiry']}}" name="e_date" class="form-control" id="field-2l" placeholder="expiry date">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="field-2" class="form-label">Remarks</label>
                                            <textarea id="textarea" value="{{$item['remarks']}}" name="remarks" class="form-control" maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn rounded-pill p-1" id="editstockbtn{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                        Submit
                                </button>
                                <button class="btn rounded-pill p-1" id="editstockloader{{$item['id']}}" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        Saving Data...
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->
            @endforeach
 --}}


            <!-- REstock Stock Modal -->

            <div id="con-close-modal-restock-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="restockForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="1">
                        <div class="modal-header">
                            <h4 class="modal-title">Re-Stock</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label for="field-11w" class="form-label">Product Name:</label>

                            

                                        <div class="input-group mb-3">
                                            <input type="text" name='name' id='search_restock' class='product-search form-control' placeholder="Search..." value=''  >
                                            <div class='search_restock' id='results-dropdown'></div>

                                                <div class="input-group-append">
                                                    <span class="input-group-text "><i class="bi bi-search"></i>search</span>
                                        </div>

                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Quantity</label>
                                        <input type="text" name="quantity" class="form-control" id="field-2l" placeholder="quantity" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Batch/Lot Number</label>
                                        <input type="text" name="batch_no" class="form-control" id="field-2l" placeholder="batch/lot number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Source</label>
                                        <input type="text" name="source" class="form-control" id="field-2n" placeholder="source" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Landing Cost</label>
                                        <input type="number" name="cost" class="form-control" id="field-2n" required placeholder="landing cost" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Invoice Number</label>
                                        <input type="text" name="invoice" class="form-control" id="field-2n" placeholder="invoice number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Delivery Note</label>
                                        <input type="text" name="d_note" class="form-control" id="field-2l" placeholder="Delivery Note">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2n" class="form-label">Does Expire?</label>
                                        <select name="expires" class="form-control form-select" id="field-11" required>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Expiry Date</label>
                                        <input type="date" name="e_date" class="form-control" id="field-2l" placeholder="expiry date">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2" class="form-label">Remarks</label>
                                        <textarea id="textarea" name="remarks" class="form-control" required maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn rounded-pill p-1" id="restockbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                    Submit
                            </button>
                            <button class="btn rounded-pill p-1" id="restockloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Saving Data...
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->



            <!-- View Transaction of Stock Modal -->
@foreach ($data as $item1)
    <div id="con-close-modal-txn-{{$item1['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <form id="" method="post">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="0">
                <div class="modal-header">
                    <h4 class="modal-title">{{$item1['name']}} Stock Transactions</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="table-responsive">
                    <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="datatable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Quantity</th>
                            <th>Batch/Lot No</th>
                            <th>Source</th>
                            <th>Staff</th>
                            <th>Date In</th>
                            <th>Expiry</th>
                            <th>Remarks</th>
                            <th>Approval</th>
                            @role('admin')
                            <th>Actions</th>
                            @endrole
                        </tr>
                        </thead>


                        <tbody>
                            @php
                            $i=1;
                            @endphp
                        @foreach ($data2 as $item)
                        @if ($item1['id']==$item['product_id'])
                                <tr>
                                    <td>{{$i}}. </td>
                                    <td>{{$item['quantity']}}</td>
                                    <td>{{$item['batch_no']}}</td>
                                    <td>{{$item['source']}}</td>
                                    <td>{{$item['staff']}}</td>
                                    <td>{{$item['date_in']}}</td>
                                    <td>{{$item['expiry']}}</td>
                                    <td>{{$item['remarks']}}</td>
                                    <td>
                                        @if ($item['approve'] == 0)
                                            <span style="color:red">Pending</span>
                                        @else
                                            <span style="color:green">Approved</span>
                                        @endif
                                    </td>
                                    @role('admin')
                                    <td style='font-size:10px; text-align: center;'>
                                        <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-xs" data-bs-toggle="modal" data-bs-target="#con-close-modal-edit-stock-{{$item['id']}}">
                                            <i class='fas fa-pen' aria-hidden='true'></i>
                                            </button>
                                        <button type="button" onclick="del(this)" value="{{$item['id']}}" class="btn btn-danger btn-xs">
                                            <i class='fa fa-trash' aria-hidden='true'></i>
                                        </button>

                                    </td>
                                    @endrole
                                </tr>
                            @php
                            $i+=1;
                            @endphp
                        @endif
                        
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn p-1" id="editbtn" style="background-color: #08228a9f;color: white" type="submit">
                            Submit
                    </button>
                    <button class="btn p-1" id="editloader" style="background-color: #08228a9f;color: white;display:none;" type="button">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving Data...
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->
@endforeach

                <!-- Add Reference Number Modal -->

    <div id="con-close-modal-add-ref" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="addRefForm" method="post">
                            @csrf
                            @method('post')
                            <input type="hidden" name="type" value="12">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Product Reference Number</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                        <label for="field-11w" class="form-label">Product Name:</label>
    
                                  
                                  <div class="input-group mb-3">
                                     <input type="text" name='name' id='search_ref_no' class='product-search form-control' placeholder="Search..." value=''  >
                                      <div class='search_ref_no' id='results-dropdown'></div>

                                        <div class="input-group-append">
                                            <span class="input-group-text "><i class="bi bi-search"></i>search</span>
                                        </div>

                                    </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Reference Number</label>
                                            <input type="text" name="ref_no" class="form-control" id="field-2l" placeholder="Product reference number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn rounded-pill p-1" id="addrefbtn" style="width: 100%; background-color: #08228a9f;color: white" type="submit">
                                        Submit
                                </button>
                                <button class="btn rounded-pill p-1" id="addrefloader" style="width: 100%; background-color: #08228a9f;color: white;display:none;" type="button">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        Saving Data...
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->



@endsection

@section('scripts')
    <script>
    $(document).ready(function(){
//Add Stock Form
$("#addForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addbtn");
var loader=$("#addloader")
btn.hide();
loader.show();
let data=$("#addForm").serialize();
$.ajax({
    type: "POST",
    url: "/stock",
    data: data,
    success: function (response) { console.log(response)
 if(response==103){
        swal.fire("Error","Batch or product exist.","error"); btn.show();
        loader.hide();
        return;
    }
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr["success"]("", "Product Saved Succesfully. Waiting Admin Approval")
        btn.show();
        loader.hide();
        location.href='/stock'
    },
    error: function(res){  console.log(res)
    if(res=='101'){

        Swal.fire("Error!", "Product already exists", "error");
    }else if(res==100){
        swal.fire("Error","Product exist.","error");
    }
    else{
        Swal.fire("Error!", "Product exist.", "error");}
        btn.show();
        loader.hide();
    }
});
})


//Add Reference Number Form
$("#addRefForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#addrefbtn");
var loader=$("#addrefloader")
btn.hide();
loader.show();
let data=$("#addRefForm").serialize();
$.ajax({
    type: "POST",
    url: "{{route('store-ref')}}",
    data: data,
    success: function (response) { //console.log(response)
 if(response==500){
        swal.fire("Error","An error occurred","error"); btn.show();
        loader.hide();
        return;
    }
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "3000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr["success"]("", response)
        btn.show();
        loader.hide();
        //location.href='/stock'
    },
    error: function(res){  //console.log(res)
    if(res=='101'){

        Swal.fire("Error!", "Product already exists", "error");
    }else if(res==100){
        swal.fire("Error","Product exist.","error");
    }
    else{
        Swal.fire("Error!", 'An Error Occurred', "error");}
        btn.show();
        loader.hide();
    }
});
})

// Edit OrderLevel Form
$(".orderlevelForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editorderId"]').val();
  var btn = $("#editorderbtn" + itemId);
  var loader = $("#editorderloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}else{
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
}
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})


// Edit Audit Form
$(".editaudit1").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editorderId"]').val();
  var btn = $("#editauditbtn" + itemId);
  var loader = $("#editauditloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) {
console.log(response)
if(response==200){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}else{
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
}
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})



// Edit Stocks Form
$(".editstockForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editstockId"]').val();
  var btn = $("#editstockbtn" + itemId);
  var loader = $("#editstockloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}if(response==501){
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Batch Number should be Unique", "error");
    }
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})


//Add Re-stock Form
$("#restockForm").on('submit',(e)=>{
e.preventDefault();
var btn=$("#restockbtn");
var loader=$("#restockloader")
btn.hide();
loader.show();
let data=$("#restockForm").serialize();
$.ajax({
    type: "POST",
    url: "/stock", 
    data: data,
    success: function (response) { console.log(response)

        if(response==404){
            btn.show();
            loader.hide();
            Swal.fire("Error!", "Product Does not exists", "error");
            return
        }
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr["success"]("", "Stock Saved Succesfully.")
        location.href='/stock'
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})

// Edit settings Form
$(".settiingsEditForm").on('submit', function(e) {
  e.preventDefault();

  const form = $(this);
  var itemId = form.find('input[name="editsettingId"]').val();
  var btn = $("#editbtn" + itemId);
  var loader = $("#editloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/#/' + itemId,
    data: data,
    success: function (response) { console.log(response)

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr["success"]("", "Settings Update Succesfully.")
        location.href='#'
    },
    error: function(res){ console.log(res)
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})


    })



    // Edit Stocks Transaction
$(".stockTrx").on('submit', function(e) {
  e.preventDefault();
alert("Hey");return
  const form = $(this);
  var itemId = form.find('input[name="editstockId"]').val();
  var btn = $("#editstockbtn" + itemId);
  var loader = $("#editstockloader" + itemId);
  btn.hide();
  loader.show();
  let data = form.serialize();
$.ajax({
    type: 'PATCH',
    url: '/stock/' + itemId,
    data: data,
    success: function (response) { console.log(response)
if(response==200){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["success"]("", "Data Updated Succesfully.")
    location.href='/stock'
        btn.show();
        loader.hide();
}if(response==501){
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Batch Number should be Unique", "error");
    }
    },
    error: function(res){ console.log(res)
        btn.show();
        loader.hide();
        Swal.fire("Error!", "Try again later...", "error");
    }
});
})




    </script>

    <script>

        //Deleting Product
        function del_product(e){
        let id=e.value;
        var type=0;//For knowing deletion operation is coming from settings

        Swal.fire({
            title: "Confirm deletion",
            text: "All batches linked to this product will also be deleted. You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "stock/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { //alert(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/stock'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }


        //Deleting Settings
        function del(e){
        let id=e.value;
        var type=0;//For knowing deletion operation is coming from settings

        Swal.fire({
            title: "Confirm deletion",
            text: "You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "settings/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='#'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }
    </script>
    
    
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#excelbtn").click(function(){
        TableToExcel.convert(document.getElementById("salestable"), {
            name: "Invitro Products without batches.xlsx",
            sheet: {
            name: "Sheet1"
            }
        });
        });
});
</script>
@endsection
