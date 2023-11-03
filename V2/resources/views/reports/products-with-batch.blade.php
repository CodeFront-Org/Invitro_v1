@extends('layouts.app')

@section('content')
<div class="row mt-1">
    <div class="col-12">
    <button  style="color: white" type="button" class="btn btn-secondary right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
            Total: {{$totalWithBatch}}
        </button><a href="{{route('/with-batch')}}">
        <button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                With Batch
            </button></a>
            <a href="{{route('/without-batch')}}"><button  style="background-color: #08228a9f;color: white" type="button" class="btn right" data-bs-toggle="modal" data-bs-target="#con-close-modal-add-1">
                    Without Batch
                </button></a>
        <button id="excelbtn" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
        <div class="card" style="border-radius:0px 15px 15px 15px;box-shadow: 2px 3px 3px 2px rgba(9, 107, 255, 0.179);">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="salestable" class="table table-sm table-bordered dt-responsive nowrap text-center">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Batches</th>
                            <th>Total Qty</th>
                            <th>Expire Days</th>
                            <th>Order Level</th>
                        </tr>
                        </thead>


                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{$loop->index+1}}. </td>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['batches']}}</td>
                                    <td>{{$item['qty']}}</td>
                                    <td>{{$item['expire']}}</td>
                                    <td>{{$item['order_level']}}</td>
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
            name: "Invitro Products with batches.xlsx",
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
filename:'Invitro Products with batches.pdf',
image:{type:'jpeg',quality:0.98},
html2canvas:{scale:2},
jsPDF:{unit:'in',format:'legal',orientation:'landscape'}
};
html2pdf(el,opt);
}

</script>
@endsection