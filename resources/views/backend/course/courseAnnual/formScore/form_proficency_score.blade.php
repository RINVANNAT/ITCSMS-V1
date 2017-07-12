@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.score.request_score_form'))

@section('content')
    <style>

        .current_row td {

            gradient(to bottom, rgba(181, 209, 255, 0.34) 0, rgba(181, 209, 255, 0.34) 100 %);
                background-image: linear-gradient(rgba(181, 209, 255, 0.5) 0px, rgba(181, 209, 255, 0.341176) 100%);
                background-position-x: initial;
                background-position-y: initial;
                background-size: initial;
                background-repeat-x: initial;
                background-repeat-y: initial;
                background-attachment: initial;
                background-origin: initial;
                background-clip: initial;
                background-color: #fff !important;
        }

    </style>
    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery/jquery-2.1.4.min.js')}}"><\/script>')</script>


    <div class="box box-success">
        <div class="box-header with-border text_font">
            <strong class="box-title">
                <span class="text_font" >
                    Input Score:
                    <span style=" color: #00a157;">
                        {{$courseAnnual->name_en. ' |~ '.(($courseAnnual->department_id == config('access.departments.sa'))?'SA' :' SF').'-'.(($courseAnnual->degree_id == config('access.degrees.degree_engineer'))?'I':'T').$courseAnnual->grade_id}}
                    </span>
                </span>
            </strong>


            <button class="btn btn-primary btn-xs pull-right" id="import">
                <i class="fa fa-upload"></i>
                Import
            </button>

            <button class="btn btn-warning btn-xs pull-right" style="margin-right: 5px" id="export">
                <i class="fa fa-download"> </i>
                Export
            </button>


        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <input type="hidden" name="course_annual_id" value="{{$courseAnnual->id}}">
            <input type="hidden" name="token" value="{{csrf_token()}}">
            {{--here what i need to write --}}

            <div id="score_table" class="handsontable htColumnHeaders">

            </div>
        </div>
    </div>

    <div class="box box-success" id="box_footer">
        <div class="box-body">
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop
@section('after-scripts-end')
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('score/js/proficency_score.js') !!}
    <script>

        $(document).ready(function() {
            var container = document.getElementById('score_table');
            toggleLoading(true)

             $.ajax({
                type: 'POST',
                url: '/admin/course/course-annual/proficency/score-data',
                data: {course_annual_id: $('input[name=course_annual_id]').val(), _token:$('input[name=token]').val()},
                dataType: "json",
                success: function (resultData) {

                    @if($courseAnnual->department_id == config('access.departments.sa'))

                            setting.nestedHeaders = EN_HEADER();
                            setting.data = resultData;
                            calculateSite(hotInstance);
                            hotInstance = new Handsontable(container, setting);
                    @else
                            setting.data = resultData;
                            setting.nestedHeaders = FR_HEADER();
                            calculateSite(hotInstance);
                            hotInstance = new Handsontable(container, setting);
                    @endif

                    toggleLoading(false)
                },

                error:function(response) {
                    toggleLoading(false)
                }
            });

        });


    </script>
@stop