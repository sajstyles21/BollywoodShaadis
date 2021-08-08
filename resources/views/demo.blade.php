@extends('employer.layouts.default')
@section('content')
<style>
    .continue-btn:hover{
        color:#283251!important;
    }
    input[type="checkbox"][readonly] {
        pointer-events: none;
    }
    .completed {
        pointer-events: none;
    }
</style>
<div id="content">
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-12 mb-4">
                <div class="jobs-block mb-5">
                    <div class="heading p-3">Invoice</div>
                    <div class="p-4">
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                        @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                        <form id="dates" action="" method="GET" enctype="multipart/form-data">
                            <div class="row drr-block">
                                <div class="col-md-4 brdr-1">
                                    <label>Show Data(from-to)</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="calandar">
                                                <input class="full-width" autocomplete="off" disabled="" id="startdate" value="{{ (@$startdate)?date('d-m-Y',strtotime(@$startdate)):'' }}" type="text" name="startdate" placeholder="Start date">
                                                <img src="{{ asset('images/employer/ic_calender.png')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="calandar">
                                                <input class="full-width" autocomplete="off" disabled="" id="enddate" value="{{ (@$enddate)?date('d-m-Y',strtotime(@$enddate)):'' }}" type="text" name="enddate" placeholder="End date">
                                                <img src="{{ asset('images/employer/ic_calender.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 brdr-1">
                                    <label>Download Invoice</label>
                                    <div class="row">
                                        @if(@$pdf)
                                        <div class="col-md-6">
                                            <a href="{{URL::to(@$pdf->invoice_pdf)}}" download="">Invoice.pdf</a>
                                        </div>
                                        @else
                                        <div class="col-md-6">Not complete</div>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </form>
                        <form id="payroll" action="" method="POST" enctype="multipart/form-data">
                            <input name="csrf-token" value="{{ csrf_token() }}" type="hidden"/>
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="search-bar mt-4 mb-4">
                                        <input type="text" name="" id="search" autocomplete="off" placeholder="Search candidates or employers">
                                        <img src="{{asset('images/admin/ic_search.png')}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table top-candidates candidate">
                                            <thead>
                                                <tr>
                                                    <th>Candidate</th>
                                                    <th>Shift date</th>
                                                    <th>Timesheet No</th>
                                                    <th>Approved hours</th>
                                                    <th>Charge rate</th>
                                                    <th>Fee</th>
                                                    <th>Total charge</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                                @if($jobs)
                                                @php $hours=0; $amount=0; @endphp
                                                @foreach($jobs as $_job)
                                                @if($_job->job->status!=7)
                                                <tr>
                                                    <td><a class="candidate" href="{{route('employer.candidateprofile',['id'=>$_job->user_id,'jobid'=>$_job->job_id])}}">{{$_job->candidate->name}}</a></td>
                                                    <td><a class="candidate" href="{{route('employer.jobdetails',['id'=>$_job->job_id])}}">
                                                            @if($_job->job->shift_type == 1)
                                                            @if($_job->job->jobshifts)
                                                            @foreach($_job->job->jobshifts as $_jobshift)
                                                            {{ date('d/m/Y',strtotime($_jobshift->shift_date)).' - '.date('H:i',strtotime($_jobshift->shift_start_time)).' - '.date('H:i',strtotime($_jobshift->shift_end_time)) }}
                                                            @php $shiftdate = date('d/m/Y',strtotime($_jobshift->shift_date)).' - '.date('H:i',strtotime($_jobshift->shift_start_time)).' - '.date('H:i',strtotime($_jobshift->shift_end_time)) @endphp
                                                            @endforeach
                                                            @endif

                                                            @else
                                                            @if($_job->job->jobshifts)
                                                            @php $i=1; @endphp
                                                            @foreach($_job->job->jobshifts as $k => $_jobshift)
                                                            @if($i==1)
                                                            @php $startdate = date('d/m/Y',strtotime($_job->job->jobshifts[$k]->shift_date)); @endphp
                                                            @endif
                                                            @if($i==$_job->job->jobshifts->count())
                                                            @php $enddate = date('d/m/Y',strtotime($_job->job->jobshifts[$k]->shift_end_date)); @endphp
                                                            @endif
                                                            @php $i++; @endphp
                                                            @endforeach
                                                            @php $shiftdate = $startdate.' - '.$enddate @endphp
                                                            {{$startdate.' - '.$enddate}}
                                                            @endif
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td><a href="{{route('timesheet-detail',['tid'=>$_job->job->timesheets->id,'jid'=>@$_job->job_id])}}">{{$_job->job->timesheets->timesheet_no}}</a></td>
                                                    <td>{{$_job->job->timesheets->hours}} hrs</td>
                                                    <td>￡{{$_job->job->template->charge_rate}}/hr</td>
                                                    <td>￡{{$_job->job->timesheets->fee}}</td>
                                                    <td>￡{{number_format($_job->job->timesheets->amount,2)}}</td>
                                                     @php $hours += $_job->job->timesheets->hours; $amount += $_job->job->timesheets->amount; @endphp
                                                </tr>


                                                @else

                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                @endif



                                                @endforeach
                                                 <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{$hours}} hrs</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>￡{{number_format($amount,2)}}</td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td colspan="10">There are no data.</td>
                                                </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                        <input type="hidden" name="startdate" id="startdate" value="{{@$startdate}}" />
                                        <input type="hidden" name="enddate" id="enddate" value="{{@$enddate}}" />
                                        <input type="hidden" name="page" id="page" value="1" />
                                        <input type="hidden" name="sort" id="sort" value="" />
                                    </div>
                                </div>
                            </div>

                        </form>
                        @if(@$invoices->status==1)

                        @endif
                    </div>
                    <!--div class="row">
                        <div class="col-md-12">
                            <div class="table-pagination mb-2 text-right">
                                <span class="d-inline-block pt-2 pb-2 pl-3 pr-3 brdr-1">Page 1</span>
                                <a class="pt-2 pb-2 pl-3 pr-3 d-inline-block brdr-1" href=""><img style="transform:  rotate(180deg);" src="images/admin/ic_navigation-forward.png"></a>
                                <a class="pt-2 pb-2 pl-3 pr-3 d-inline-block" href=""><img src="images/admin/ic_navigation-forward.png"></a>
                            </div>
                        </div>
                    </div-->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script type="text/javascript">
$(function () {

    $("#startdate").datepicker({
        format: "dd-mm-yyyy",
        showOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        //gotoCurrent: true,
    });

    $("#enddate").datepicker({
        format: "dd-mm-yyyy",
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        //gotoCurrent: true,
    });
    $('#enddate').change(function () {
        $('#dates').submit();
    });

    $('#search').on('keyup', function () {
        var value = $(this).val();
        var role = $('#role').val();
        var status = $('#status').val();
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
        var page = $('#page').val();
        var reverse_order = $('#sort').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{ route('searchjob') }}",
            data: 'val=' + value + "&role=" + role + "&startdate=" + startdate + "&enddate=" + enddate + "&page=" + page + "&order=" + reverse_order + "&status=" + status,
            beforeSend: function () {
            },
            success: function (response) {
                if (response) {
                    $('tbody#data').html('');
                    $('tbody#data').html(response);
                } else {
                    $('tbody#data').html('');
                    $('tbody#data').html('<tr><td colspan="10">There are no data.</td></tr>');
                }
                //$('#links').empty();
            },
            complete: function () {
            },
        });
    });

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#page').val(page);
        var value = $('#search').val();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        var role = $('#role').val();
        var status = $('#status').val();
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
        var reverse_order = $('#sort').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{ route('searchjob') }}",
            data: 'val=' + value + "&role=" + role + "&startdate=" + startdate + "&enddate=" + enddate + "&page=" + page + "&order=" + reverse_order + "&status=" + status,
            beforeSend: function () {
            },
            success: function (response) {
                if (response) {
                    $('tbody#data').html('');
                    $('tbody#data').html(response);
                } else {
                    $('tbody#data').html('');
                    $('tbody#data').html('<tr><td colspan="10">There are no data.</td></tr>');
                }
                //$('#links').empty();
            },
            complete: function () {
            },
        });
    });


});
</script>
@endsection
