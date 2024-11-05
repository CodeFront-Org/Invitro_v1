@extends('layouts.app')

@section('content')
<div class="row mt-1">
           

    <form method="GET" action="/expired">
        <div class="row">

        <div class="row">
            <div class="mb-3 col-md-4">
                <label for="from">From:</label>
                <input type="date" class="form-control" name="from" data-provide="w" placeholder="From: ">
            </div>
            <div class="mb-3 col-md-4">
                <label for="To">To:</label>
                <input type="date" class="form-control" name="to" data-provide="datepicker1" placeholder="To: ">
            </div>
            <div class="mb-3 col-md-3" style="margin-top: 2%">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            
        </div>


    </form>



    <div class="col-12">
    <button  style="color: white" type="button" class="btn btn-secondary right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
            Total: {{$totalExpired}}
        </button><a href="{{route('/expired')}}">
        <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                Expired
            </button></a>
            <a href="{{route('/expired',['type'=>1])}}"><button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                    Due Expiry
                </button></a>
        <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
        <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="salestable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">
                            @if ($from and $to)
                            <tr>
                                <td colspan="6">Expiry dates From: {{$from}}  To: {{$to}} </td>
                            </tr>
                            @endif
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Batch Number</th>
                            <th>Expires In</th>
                        </tr>
                        </thead>


                        <tbody>
                            @foreach ($due_expiry as $item)
                                <tr>
                                    <td>{{$loop->index+1}}. </td>
                                    <td>{{$item['product_name']}}</td>
                                    <td>{{$item['batch_no']}}</td>
                                    <td>{{$item['expires_in']}} days.</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div> <!-- end row -->

    
@endsection


@section('scripts')
    
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



<!-- Table PDF -->

<script src="{{asset('js/pdf/html2pdf.bundle.min.js')}}"></script>

<script type="text/javascript">
document.getElementById("pdfbtn").onclick=function(){
var el=document.getElementById("salestable");
var opt={
margin:0,
filename:'Invitro Products without batches.pdf',
image:{type:'jpeg',quality:0.98},
html2canvas:{scale:2},
jsPDF:{unit:'in',format:'legal',orientation:'landscape'}
};
html2pdf(el,opt);
}

</script>
@endsection