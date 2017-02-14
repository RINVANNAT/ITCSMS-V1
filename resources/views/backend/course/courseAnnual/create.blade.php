@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/select2/select2.min.css') !!}
@stop

@section('content')
    {!! Form::open(['route' => 'admin.course.course_annual.store', 'class' => 'form-horizontal create_course_annual', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.courseAnnuals.sub_create_title') }}</h3>
            </div>

            <div class="box-body">
                @include('backend.course.courseAnnual.fields')
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" id="submit_form" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {{--{!! Html::script('js/backend/access/roles/script.js') !!}--}}
    {!! Html::script('js/backend/course/courseAnnual/course_annual.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}

    <script>
        var $search_url = "{{route('admin.employee.search')}}";
        var base_url = '{{url('img/profiles/')}}';
        var get_group_url = "{{route('course_annual.get_group_filtering')}}";

        $(document).ready(function() {

            $('.create_course_annual').submit(function() {
                toggleLoading(true);
            });

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
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });
            $("#degree_id").on('change', function (){
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });
            $("#grade_id").on('change', function (){
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });
            $("#department_id").on('change', function (){
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });
            $("#department_option_id").on('change', function (){
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });

            if(val = $('select#midterm_score_id :selected').val()) {

                $('#final_score_id').val(parseInt('{{\App\Models\Enum\ScoreEnum::Midterm_Final}}') - val);
            }

            $('select[name=midterm_score]').on('change', function() {
                $('#final_score_id').val(parseInt('{{\App\Models\Enum\ScoreEnum::Midterm_Final}}') - $(this).val());
            });

            $('Document').ready(function() {
                if($('#course_id :selected').val()) {
                    setTimeCourseTpTd();
                    setNameKhEnFr();
                    // setSelectedField();
                    // load_group();
                }
            });

            $('#course_id').on('change', function() {
                setTimeCourseTpTd();
                setNameKhEnFr();
                setSelectedField();
                load_group('{{\App\Models\Enum\CourseAnnualEnum::CREATE}}');
            });

        })
    </script>
@stop