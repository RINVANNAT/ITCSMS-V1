@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.exams.title'))

@section('page-header')
    <h1>
        Candidates
        <small>Choose priority departments</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                {!! Form::open(['route' => 'admin.candidate.store_candidate_department', 'id'=> 'candidate-form', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}
                <div class="row no-margin">
                    <div class="form-group col-sm-12" id="choose_department">
                        <div class="row">
                            <div class="col-sm-2">
                                <textarea style="font-size: 20px; padding: 10px 5px;width: 120px;" id="candidate_register_id" name="candidate_register_id" placeholder="Register ID"></textarea>
                            </div>
                            <div class="col-sm-10">
                                <table id="choose_department_table">
                                    <tr>
                                        @foreach($departments as $department)
                                            <td class="choose_department_cell"><center><b>{!! $department->code !!}</b></center></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @if(isset($candidate) && $candidate != null)
                                            @foreach($departments as $department)
                                                <td class="choose_department_cell">
                                                    <div class="col-md-12 col-sm-12">
                                                        {!! Form::text('choice_department['.$department->id.']', $candidate_departments[$department->id], array('class'=>'form-control department_choice input','id'=>$department->code.'_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                                    </div>
                                                </td>
                                            @endforeach
                                        @else
                                            @foreach($departments as $department)
                                                <td class="choose_department_cell">
                                                    <div class="col-md-12 col-sm-12">
                                                        {!! Form::text('choice_department['.$department->id.']', null, array('class'=>'form-control department_choice input','id'=>$department->code.'_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                                    </div>
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <div class="row">
                            <hr/>
                            <div class="col-sm-12">
                                <center><button type="button" id="btn-save-candidate" class="btn btn-primary">Save</button></center>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="candidates-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.candidates.fields.register_id') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
                        <th>GCA</th>
                        <th>GCI</th>
                        <th>OAC</th>
                        <th>GEE</th>
                        <th>GTR</th>
                        <th>GIC</th>
                        <th>GIM</th>
                        <th>GGG</th>
                        <th>GRU</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {
            var department_size = {{count($departments)}};
            var baseUrl = $("#candidate-form" ).attr('action') + "?exam_id=" + {{$exam->id}};
            var candidate_datatable = $('#candidates-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"candidate_filter">frtip',
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.candidate.list_candidate_department')."?exam_id=".$exam->id !!}',
                    method: 'POST',
                    data:function(d){}
                },
                columns: [
                    { data: 'register_id', name: 'candidates.register_id'},
                    { data: 'name_kh', name: 'candidates.name_kh'},
                    { data: 'name_latin', name: 'candidates.name_latin'},
                    { data: 'dob', name: 'candidates.dob'},
                    { data: 'result', name: 'candidates.result'},
                    { data: 'GCA', name: 'candidates.GCI'},
                    { data: 'GCI', name: 'candidates.GCI'},
                    { data: 'OAC', name: 'candidates.OAC'},
                    { data: 'GEE', name: 'candidates.GCI'},
                    { data: 'GTR', name: 'candidates.GTR'},
                    { data: 'GIC', name: 'candidates.GCI'},
                    { data: 'GIM', name: 'candidates.GCI'},
                    { data: 'GGG', name: 'candidates.GGG'},
                    { data: 'GRU', name: 'candidates.GCI'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#candidates-table'));

            function save_department_choice() {
                var data = $("#candidate-form" ).serializeArray();
                $.ajax({
                    type: 'POST',
                    url: baseUrl,
                    data: data,
                    success: function(response) {
                        if(typeof response.status !== 'undefined'){
                            if(response.status == true){
                                return_back();
                            } else {
                                notify("error","Candidate Error",response.toString());
                            }
                        } else {
                            notify("error","Candidate Error",response.toString());
                        }
                    },
                    error:function(response){
                        notify("error","Error: Some fields are missing!");
                    }
                });
            }
            function allowNumberOnlyAndNotDuplicate(e,object){
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: Ctrl+C
                    (e.keyCode == 67 && e.ctrlKey === true) ||
                    // Allow: Ctrl+X
                    (e.keyCode == 88 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 49 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }
            function clear_input() {
                $("input").val(null);
                $("textarea").val(null);
            }
            $("#candidate_register_id").keydown(function (e) {
                allowNumberOnly(e);
                if(e.keyCode == 13) {
                    $( "input[name='choice_department[1]']").focus();
                }
            });

            $("#btn-save-candidate").click(function (e) {
                save_department_choice();
                $("#candidate_register_id").focus();
                clear_input();
                candidate_datatable.draw();
            })
            $(".department_choice").keydown(function (e) {
                allowNumberOnlyAndNotDuplicate(e,$(this));
            });
            $(".department_choice").keyup(function (e) {
                if ((e.shiftKey || (e.keyCode < 49 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    // Do nothing here
                } else {
                    // check 1 more time if the code is redundant
                    var check = 0;
                    var value = $(this).val();
                    $(".department_choice").each(function(index,element){
                        if(value == $(element).val()){
                            check = check + 1;
                        }
                    })

                    if(check>1){
                        $(this).val("");
                        notify("error","Input Error!","Redundant choice department!");
                    } else {
                        $(".department_choice").each(function(index,element) {
                            if ($(element).val() > department_size) {
                                $(element).css("background-color", "red")
                            } else {
                                $(element).css("background-color", "white")
                            }
                        })
                        $(this).closest('.choose_department_cell').next('.choose_department_cell').find('.department_choice').focus();
                    }
                }
            });
        });
    </script>
@stop
