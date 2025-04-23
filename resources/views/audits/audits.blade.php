@extends('layouts.app')

@section('content')
@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif


<form method="GET" action="{{route('audits.index')}}">
    <div class="row">

    <div class="row">
        <div class="mb-3 col-md-3">
            <label for="from">Product:</label>

            @php
                use App\Models\Product;
                $products = Product::all();

            @endphp

            <input type="text" list="regnoo" parsley-trigger="change"  class="form-control"
                id="p1_name" name='product_filter' autocomplete="off" placeholder="Search Product ..." aria-label="#"
            />

            <datalist id="regnoo">
                @foreach ($products as $product)
                    <option value="{{ $product->name }}">{{ $product->name }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="mb-3 col-md-3">
            <label for="from">From:</label>
            <input type="date" class="form-control" name="from" data-provide="w" required placeholder="From: ">
        </div>
        <div class="mb-3 col-md-3">
            <label for="To">To:</label>
            <input type="date" class="form-control" name="to" required data-provide="datepicker1" placeholder="To: ">
        </div>
        <div class="mb-3 col-md-3" style="margin-top: 2%">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
        </div>
        
    </div>


</form>



<div class="row mt-1">
    <div class="col-12">
    <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
             New
        </button>
        {{-- <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button> --}}
        <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
            <div class="card-body">
                <div class="table-responsive">
                    <table  style="font-family: 'Times New Roman', Times, serif" class="table table-bordered nowrap text-center" id="salestable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">

                            @php
                                $page=$page_number;
                            @endphp
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Audit Date</th>
                            <th>Audited By</th>
                            <th>Remarks</th>
                        
                        </tr>
                        </thead>


                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{$page}} </td>
                                <td>{{$item['product']}}</td>
                                <td>{{$item['qty']}}</td>
                                @if ($item['status']==1)
                                    <td class="text-success">Balanced</td>
                                @else
                                    <td class="text-danger">Balanced</td>
                                @endif
                                <td>{{$item['date']}}</td>
                                <td>{{$item['staff']}}</td>
                                <td>{{$item['rmks']}}</td>
                            </tr>
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
                        {{ $data2->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> <!-- end row -->


<div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addForm" method="post">
            @csrf
            @method('post')
            <input type="hidden" name="type" value="12">
            <div class="modal-header">
                <h4 class="modal-title">New Audit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="field-11w" class="form-label">Product Name</label>
                        
                            @php
                                $products = Product::all();
                            @endphp
                        
                            <input type="text" list="regnoo" parsley-trigger="change" required class="form-control"
                                    id="p_name2" name='product_name' autocomplete="off" placeholder="Search Product ..." 
                                    aria-label="Recipient's username" />
                        
                            <datalist id="regnoo">
                                @foreach ($products as $product)
                                    <option value="{{ $product->name }}" data-id="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="field-2l" class="form-label">Quantity</label>
                            <input type="number" min="1" name="qty" class="form-control" placeholder="Quanity" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="1">Balanced</option>
                                    <option value="0">Not Balanced</option>
                        
                                </select>
                        </div>
                    </div> 

                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="field-2l" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" placeholder="date" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="field-2" class="form-label">Remarks</label>
                            <textarea id="textarea" value="My Comments" name="rmks" class="form-control" maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
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
                url: "/audits",
                data: data,
                success: function (response) { //console.log(response)  

                    if(response=='ok'){
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
                                toastr["success"]("", "Data Saved Succesfully.")
                            }else{
                            Swal.fire("Error!", "An Error occured. Please try again later", "error");
                                
                            }
                    btn.show();
                    loader.hide();
                    location.href='/audits'
                },
                error: function(res){  //console.log(res) return

                    Swal.fire("Error!", "An Error occured. Please try again later", "error");
                    btn.show();
                    loader.hide();
                }
              })
            });







                        //Process input 
            $('#p_name2').on('input', function() {
                console.log('Input changed:', $(this).val());
                // Get the selected product name from the datalist input
                const selectedProduct = $(this).val();
                const options = $('#regnoo option');

                //alert(selectedProduct)
                //Fetch product qty live
                console.log("seaerch in progress...");
                $.ajax({
                        type: "GET",
                        url: "/fetch-qty",
                        data:{
                            _token:"{{csrf_token()}}",selectedProduct
                        },
                        success: function (response) { 
                            console.log("seaerch success!!");
                            if(response.isAtHand==1){
                                $('#qty').val(response.qty); 
                            $('#fieldAtHand').attr('readonly', true);
                            $('#fieldAtHand').val(response.qty); 
                                return;
                            }else{
                            $('#fieldAtHand').removeAttr('readonly');
                            $('#fieldAtHand').val(''); 
                                return
                            }

                        console.log("seaerch in progress...");
                        },
                        error: function(res){console.log(res)

                            $('#fieldAtHand').removeAttr('readonly');
                            $('#fieldAtHand').val(''); 
                            return
                            //Swal.fire("Error!", "Try again later...", "error");
                        }
                    });
            return
                // Find the corresponding option element with the matching value
                let selectedId = null;
                let n = selectedProduct;
                //let productName = "price11" + n.replace(/\s+/g, '');
                let productName = "price11" + sanitizeStringForID(n);
                const qty = $(`#${productName}`).val(); // Correctly fetching the value of the element
                
                const isAtHand = $(`#${productName}`).data('is-at-hand'); // Correctly fetching the value of the element

                // Assuming isAtHand is already defined
                if (isAtHand == 1) {
                    // Make the input field read-only
                    $('#fieldAtHand').attr('readonly', true);
                } else {
                    // Ensure the input is editable if isAtHand is not 1
                    $('#fieldAtHand').removeAttr('readonly');
                }

                if (qty) {
                    $('#fieldAtHand').val(qty); // Correct way to set the value
                } else {
                    $('#fieldAtHand').val(''); // Setting the value to an empty string if qty is undefined or empty
                }

            });







        });















        


    </script>
@endsection