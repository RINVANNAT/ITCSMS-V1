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
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                        <textarea style="font-size: 20px; padding: 10px 5px;width: 120px;"
                                                  id="candidate_register_id" name="candidate_register_id"
                                                  placeholder="Register ID"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label>From Exam : </label>
                                    </div>
                                    @if(isset($exams) && count($exams) > 0)
                                        <div class="col-md-12">
                                            <select class="form-control" name="from_previous_year">
                                                <option value="none" selected>None</option>
                                                @foreach($exams as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <table id="choose_department_table">
                                    <tr>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 1</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 2</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 3</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 4</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 5</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 6</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 7</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 8</b></center>
                                        </td>
                                        <td class="choose_department_cell">
                                            <center><b>Choice 9</b></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[1]', null, array('class'=>'form-control department_choice input','id'=>'1_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[2]', null, array('class'=>'form-control department_choice input','id'=>'2_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[3]', null, array('class'=>'form-control department_choice input','id'=>'3_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[4]', null, array('class'=>'form-control department_choice input','id'=>'4_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[5]', null, array('class'=>'form-control department_choice input','id'=>'5_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[6]', null, array('class'=>'form-control department_choice input','id'=>'6_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[7]', null, array('class'=>'form-control department_choice input','id'=>'7_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[8]', null, array('class'=>'form-control department_choice input','id'=>'8_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                        <td class="choose_department_cell">
                                            <div class="col-md-12 col-sm-12">
                                                {!! Form::text('choice_department[9]', null, array('class'=>'form-control department_choice input','id'=>'9_rank','style'=>'padding:0px;border:0;border-bottom: 2px dotted;',"maxlength"=>"1",'required'=>'required')) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <div class="row">
                            <hr/>
                            <div class="col-sm-12">
                                <center>
                                    <button type="button" id="btn-save-candidate" class="btn btn-primary">Save</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0"
                       width="100%" id="candidates-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.candidates.fields.register_id') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
                        <th>No1</th>
                        <th>No2</th>
                        <th>No3</th>
                        <th>No4</th>
                        <th>No5</th>
                        <th>No6</th>
                        <th>No7</th>
                        <th>No8</th>
                        <th>No9</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="clearfix"></div>
            <a href="{{route('admin.candidate.export_chosen_departments')}}?exam_id={{$exam->id}}">
                <button type="button" id="btn-export-candidate" class="btn btn-primary"><i
                            class="fa fa-file-excel-o"></i> Export
                </button>
            </a>
            <a href="{{route('admin.exam.get-form-distribution-engineer-first-year', $exam->id)}}">
                <button type="button" id="btn-generate-candidate" class="btn btn-primary"><i class="fa fa-gears"></i>
                    Generate Result
                </button>
            </a>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
		$(function () {
			var department_size = "{{count($departments)}}";
			var baseUrl = $("#candidate-form").attr('action') + "?exam_id=" + "{{$exam->id}}";
			var candidate_datatable = $('#candidates-table').DataTable({
				processing: true,
				serverSide: true,
				pageLength: "{!! config('app.records_per_page')!!}",
				ajax: {
					url: '{!! route('admin.candidate.list_candidate_department')."?exam_id=".$exam->id !!}',
					method: 'POST',
					data: function (d) {
					}
				},
				columns: [
					{data: 'register_id', name: 'candidates.register_id'},
					{data: 'name_kh', name: 'candidates.name_kh', orderable: false, searchable: false},
					{data: 'name_latin', name: 'candidates.name_latin', orderable: false, searchable: false},
					{data: 'dob', name: 'candidates.dob', orderable: false, searchable: false},
					{data: 'result', name: 'candidates.result', orderable: false, searchable: false},
					{data: 'No1', name: 'candidates.No1', orderable: false, searchable: false},
					{data: 'No2', name: 'candidates.No2', orderable: false, searchable: false},
					{data: 'No3', name: 'candidates.No3', orderable: false, searchable: false},
					{data: 'No4', name: 'candidates.No4', orderable: false, searchable: false},
					{data: 'No5', name: 'candidates.No5', orderable: false, searchable: false},
					{data: 'No6', name: 'candidates.No6', orderable: false, searchable: false},
					{data: 'No7', name: 'candidates.No7', orderable: false, searchable: false},
					{data: 'No8', name: 'candidates.No8', orderable: false, searchable: false},
					{data: 'No9', name: 'candidates.No9', orderable: false, searchable: false},
					{data: 'action', name: 'action', orderable: false, searchable: false}
				]
			});
			enableDeleteRecord($('#candidates-table'));

			function save_department_choice() {
				var candidate_register_id = $("#candidate_register_id").val()
				if (candidate_register_id === undefined || candidate_register_id === '') {
					notify("error", "Error: Some fields are missing!");
					return 0
				}
				var data = $("#candidate-form").serializeArray()
				data.push({name: 'exam_id', value: "{{ $exam->id }}"})
				data.push({name: 'candidate_register_id', value: parseInt(candidate_register_id)})

				$.ajax({
					type: 'POST',
					url: $("#candidate-form").attr('action'),
					data: data,
					success: function (response) {
						if (response.code === 1) {
							notify("info", "Candidate\'s department is registered", "Success");
							candidate_datatable.draw(true);
						} else {
							notify("error", response.message, "Server Response");
						}
					},
					error: function (response) {
						notify("error", "Error: Some fields are missing!");
					}
				});
			}

			function allowNumberOnlyAndNotDuplicate(e, object) {
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
				if (e.keyCode == 13) {
					$("input[name='choice_department[1]']").focus();
				}
			});

			$("#btn-save-candidate").click(function (e) {
				save_department_choice();
				$("#candidate_register_id").focus();
				clear_input();
			})
			$(".department_choice").keydown(function (e) {
				allowNumberOnlyAndNotDuplicate(e, $(this));
			});
			$(".department_choice").keyup(function (e) {
				if ((e.shiftKey || (e.keyCode < 49 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					// Do nothing here
				} else {
					// check 1 more time if the code is redundant
					var check = 0;
					var value = $(this).val();
					$(".department_choice").each(function (index, element) {
						if (value == $(element).val()) {
							check = check + 1;
						}
					})

					if (check > 1) {
						$(this).val("");
						notify("error", "Input Error!", "Redundant choice department!");
					} else {
						$(".department_choice").each(function (index, element) {
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
