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

<form method="GET" action="{{route('cards.index')}}">
    <div class="row">

    <div class="row">
        <div class="mb-3 col-md-3">
            <label for="from">Product:</label>

            @php
                //se App\Models\Product;
                //use App\Models\Card;
               // $products = Product::all();
                //$cards = Card::select('id', 'remarks')->distinct('remarks')->get();

            @endphp

           <input type='text'  id='search_id3' name='name' class='product-search form-control' value='' placeholder="Search Product ..." >
          <div class='search_id3' id='results-dropdown'></div>

        </div>
        <div class="mb-3 col-md-3">
            <label for="from">Destination:</label>
            <input type="text" list="regnoo123" parsley-trigger="change"  class="form-control"
                id="p1_name" name='destination_filter' autocomplete="off" placeholder="Search Destination ..." aria-label="#"
            />

            {{-- <datalist id="regnoo123">
                @foreach ($cards as $card)
                    <option value="{{ $card->remarks }}">{{ $card->remarks }}</option>
                @endforeach
            </datalist> --}}
        </div>
        <div class="mb-3 col-md-2">
            <label for="from">From:</label>
            <input type="date" class="form-control" name="from" data-provide="w" required placeholder="From: ">
        </div>
        <div class="mb-3 col-md-2">
            <label for="To">To:</label>
            <input type="date" class="form-control" name="to" required data-provide="datepicker1" placeholder="To: ">
        </div>
        <div class="mb-3 col-md-1" style="margin-top: 2%">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
        </div>

    </div>


</form>

        <div class="row mt-1">
            <div class="col-12">
            <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                     New
                </button>
                <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
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
                                    <th>Item</th>
                                    <th>Initial Qty</th>
                                    <th>Out</th>
                                    <th>In</th>
                                    <th>At Hand</th>
                                    <th>Name</th>

                                    <th>Date</th>
                                    <th>Remarks-00</th>

                                </tr>
                                </thead>


                                <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{$page}} </td>
                                        <td>{{$item['item']}}</td>
                                        <td>{{$item['at_hand']}}</td>
                                        <td>{{$item['out']}}</td>
                                        <td>{{$item['in']}}</td>
                                        <td>{{$item['balance']}}</td>
                                        <td>{{$item['user']}}</td>

                                        <td>{{$item['date']}}</td>
                                        <td>{{$item['remarks']}}</td>

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
                                {{ $data1->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- end row -->


            <!-- Add New Stock Modal -->

            <div id="con-close-modal-add-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="addForm" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="type" value="12">
                        <div class="modal-header">
                            <h4 class="modal-title">New Stock Card</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-11w" class="form-label">Product Name</label>

                                <div class="input-group mb-3">
                                     <input type="text" name='name' id='search_id10' class='product-search form-control' placeholder="Search..." value=''  >
                                      <div class='search_id10' id='results-dropdown'></div>

                                        <div class="input-group-append">
                                            <span class="input-group-text "><i class="bi bi-search"></i>search</span>
                                        </div>

                                    </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Size</label>
                                        <input type="number" name="size" class="form-control" id="field-2l" placeholder="size" required>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">At-hand</label>

                                        <input type="number" name="at_hand" class="form-control" id="fieldAtHand" placeholder="at hand" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">Stock In/out</label>
                                          <select class="form-control"  id="stock_card_option" oninput='ChangeCard();' required>
											  <option value="IN">In</option>
											  <option value="OUT">Out</option>

											</select>
                                    </div>
                                </div>

							<div class="col-md-12" id='stock_out'>
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Out</label>
                                        <input type="number" min="1" oninput='updateBalanceOut();' name="out" class="form-control" id="fieldOut" placeholder="out" >
                                    </div>
                                </div>

                                <div class="col-md-12" id='stock_in'>
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">In</label>
                                        <input type="number" oninput='updateBalanceIN();' name="in" class="form-control" id="fieldIN" placeholder="in" >
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2l" class="form-label">Balance</label>
                                        <input type="number" name="balance" class="form-control balanceinput" id="field-2l" placeholder="balance" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="field-2" class="form-label">Remarks</label>
                                        <textarea id="textarea" value="My Comments" name="remarks" class="form-control" maxlength="300" rows="3" placeholder="Your Remarks"></textarea>
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

            {{--
            <!-- Edit  Stock Modal -->
            @foreach ($data as $item)
                <div id="con-close-modal-edit-{{$item['id']}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{route('cards.update',$item['id'])}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="type" value="2">
                            <input type="hidden" name="editorderId" id="editorderId" value="{{$item['id']}}">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Stock Card</h4>
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
                                        id="p_name" name='name' autocomplete="off" value="{{$item['item']}}" placeholder="Search Product ..." aria-label="Recipient's username"
                                    />

                                    <datalist id="regnoo">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">{{ $product->name }}</option>
                                        @endforeach
                                    </datalist>
                                    </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">At-hand</label>
                                            <input type="number" name="at_hand" value="{{$item['at_hand']}}" class="form-control" id="priceInput11" placeholder="at hand" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Out</label>
                                            <input type="number" name="out" value="{{$item['out']}}"  min=0 class="form-control stocksvalue" id="field-2l" placeholder="out" required hidden>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">In</label>
                                            <input type="number" name="in" value="{{$item['in']}}" min=0 class="form-control stocksvalue" id="field-2l" placeholder="in" required hidden>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="field-2l" class="form-label">Balance</label>
                                            <input type="number" name="balance" value="{{$item['balance']}}" min=0 class="form-control" id="field-2l" placeholder="balance" required readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="field-2" class="form-label">Remarks</label>
                                            <textarea id="textarea" value="My Comments" name="remarks" class="form-control" maxlength="300" rows="3" placeholder="Your Remarks">{{$item['remarks']}}</textarea>
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
                </div>
                <!-- /.modal -->

            @endforeach
            --}}

            {{-- <table id="salestable1" style="display: none">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Size</th>
                        <th>At Hand</th>
                        <th>Out</th>
                        <th>In</th>
                        <th>Balance</th>
                        <th>Staff Incharge</th>
                        <th>Date</th>
                        <th>Remarks-11</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data3 as $item)
                        <tr>
                            <td>{{$loop->index+1}} </td>
                            <td>{{$item['item']}}</td>
                            <td>{{$item['size']}}</td>
                            <td>{{$item['at_hand']}}</td>
                            <td>{{$item['out']}}</td>
                            <td>{{$item['in']}}</td>
                            <td>{{$item['balance']}}</td>
                            <td>{{$item['user']}}</td>
                            <td>{{$item['date']}}</td>
                            <td>{{$item['remarks']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}

{{-- 
            @foreach ($product_prices as $item)
                <input type="hidden" id="price11{{ preg_replace('/[^a-zA-Z0-9]/', '', $item['name']) }}" value="{{$item['at_hand']}}"  data-is-at-hand="{{ $item['is_at_hand'] }}">
            @endforeach --}}

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $("#excelbtn").click(function(){
        TableToExcel.convert(document.getElementById("salestable1"), {
            name: "Invitro Stock Cards.xlsx",
            sheet: {
            name: "Sheet1"
            }
        });
        });
});
</script>

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
    url: "/cards",
    data: data,
    success: function (response) { console.log(response)

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
                    toastr["success"]("", "Product Saved Succesfully.")
                }else{
                 Swal.fire("Error!", "An Error occured. Please try again later1", "error");

                }
        btn.show();
        loader.hide();
        location.href='/cards'
    },
    error: function(res){  console.log(res)

        Swal.fire("Error!", "An Error occured. Please try again later", "error");
        btn.show();
        loader.hide();
    }
});
})

// Function to sanitize the string for use in IDs
function sanitizeStringForID(input) {
    // Replace all non-alphanumeric characters with an empty string
    return input.replace(/[^a-zA-Z0-9]/g, ''); // Keeps only letters and numbers
}






//Process input
$('#search_id10').change(function() {
   console.log('Input changed...');
    // Get the selected product name from the datalist input
    const selectedProduct = $(this).val();
    const options = $('#regnoo option');

    //alert(selectedProduct)
    //Fetch product qty live
    $.ajax({
            type: "GET",
            url: "/fetch-qty",
            data:{
                _token:"{{csrf_token()}}",selectedProduct
            },
            success: function (response) {
                if(response.isAtHand==1){
                 $('#fieldAtHand').attr('readonly', true);
                $('#fieldAtHand').val(response.qty);
                    return;
                }else{
                $('#fieldAtHand').removeAttr('readonly');
                $('#fieldAtHand').val('');
                    return
                }

               // Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                //location.href='/cards'})
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



ChangeCard();


})
</script>



<script>

function ChangeCard(){
	/*	*/
	$(".stocksvalue").val("");
	$(".balanceinput").val("");


	if ($("#stock_card_option").val()=='IN'){
		$("#stock_out").hide();
		$("#stock_out").val("");
		$("#stock_in").show();
	}else{
		$("#stock_out").show();
		$("#stock_in").hide();

	}


}

//updateBalance
function updateBalanceOut(){

	$(".balanceinput").val($("#fieldAtHand").val()*1 - $("#fieldOut").val()*1);
}
function updateBalanceIN(){


	$(".balanceinput").val($("#fieldAtHand").val()*1 + $("#fieldIN").val()*1);
}
</script>


<script>

//Deleting Card
function del_product(e){
let id=e.value;
var type=0;//

Swal.fire({
    title: "Confirm deletion",
    text: "Delete the stock card record",
    type: "error",
    showCancelButton: !0,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
}).then((t)=>{
if(t.value){
        $.ajax({
            type: "DELETE",
            url: "cards/"+id,
            data:{
                _token:"{{csrf_token()}}", id,type
            },
            success: function (response) { console.log(response)

                Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                location.href='/cards'})
            },
            error: function(res){console.log(res)
                Swal.fire("Error!", "Try again later...", "error");
            }
        });
    }
    })
}
    </script>

@endsection
