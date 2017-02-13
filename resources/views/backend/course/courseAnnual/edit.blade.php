@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</small>
    </h1>
@endsection
@section('after-styles-end')
    {!! Html::style('plugins/select2/select2.min.css') !!}
@stop

@section('content')
    {!! Form::model($courseAnnual, ['route' => ['admin.course.course_annual.update', $courseAnnual->id],'class' => 'form-horizontal edit_course_annual', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.course.courseAnnual.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop


@section('after-scripts-end')
    {!! Html::script('js/backend/course/courseAnnual/course_annual.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}

    <script>
        var $search_url = "{{route('admin.employee.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var get_group_url = "{{route('course_annual.get_group_filtering')}}";


        @if(isset($courseAnnual->employee))
            var selected_user_id = '{{$courseAnnual->employee->id}}';
            var selected_user = "{{$courseAnnual->employee->name_latin}}";
        @else
            var selected_user_id =  null;
            var selected_user = null;

        @endif
        var course_program_id = '{{$courseAnnual->course_id}}';

        $(document).ready(function() {
            // Search course program
            $("#course_id").select2({
                placeholder: "Select a course program",
                allowClear: true
            });

            // Search lecturer
            var employee_search_box = $(".select_employee").select2({
                placeholder: 'Enter name ...',
                allowClear: true,
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        name: params.term,
                        group: 'customer',
                        newOption: true
                    }
                },
                ajax: {
                    url: $search_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: formatRepoEmployee, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelectionEmployee, // omitted for brevity, see the source of this page
                initSelection : function (element, callback) {
                    var data = {id: selected_user_id, text: selected_user};
                    callback(data);
                }
            });

            // On department change, change option
            if($('select[name=department_id] :selected').val()) {
                var department_id = $('select[name=department_id] :selected').val();
                $(".department_"+department_id).show();
            }
            $('select[name=department_id]').on('change', function() {
                $(".department_option").hide();
                var department_id = $('select[name=department_id] :selected').val();
                $(".department_"+department_id).show();

            });

            // On degree, grade, department, option is changed, change group
            $("#academic_year_id").on('change', function (){
                load_group();
            });
            $("#degree_id").on('change', function (){
                load_group();
            });
            $("#grade_id").on('change', function (){
                load_group();
            });
            $("#department_id").on('change', function (){
                load_group();
            });
            $("#department_option_id").on('change', function (){
                load_group();
            });



            if(val = $('select#midterm_score_id :selected').val()) {

                $('#final_score_id').val(parseInt('{{\App\Models\Enum\ScoreEnum::Midterm_Final}}') - val);
            }

            $('select[name=midterm_score]').on('change', function() {
                $('#final_score_id').val(parseInt('{{\App\Models\Enum\ScoreEnum::Midterm_Final}}') - $(this).val());
            });
        })
    </script>
@stop