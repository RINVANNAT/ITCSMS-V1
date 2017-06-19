@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.score.request_score_form'))

@section('content')
    <style>

        {!! Html::style('plugins/iCheck/all.css') !!}

    </style>

        <div class="box box-success">
            <div class="box-header with-border text_font">
                <strong class="box-title">
                    <span class="text_font">
                        Clone Score :
                        <span data-toggle="tooltip" data-placement="right" title="{{$department->code.'-'.$degree->code.$grade->code.' | S_'.$courseAnnual->semester_id. ' | '.$academicYear->name_latin}} " >
                            {{$courseAnnual->name_en}}
                        </span>
                    </span></strong>
            </div>
            <!-- /.box-header -->
            <div class=" box-body">
                @if(count($groups) > 1)

                    <div class="alert alert-info">
                        <p style="font-size: 12pt"><i class="icon fa fa-info"></i> Information </p>
                        <p>
                            Please select groups of students which you want to clone the score, and you are able to select up to {{\App\Models\Enum\GroupEnum::TEN}} groups. This case is to avoid loading too much data. Thank!
                        </p>
                    </div>
                @else

                    {{--<label for="all_group" class="btn btn-primary btn-xs" style="width: 80px"> <input style="font-size: 14pt;" type="checkbox" name="all_group" id="all_group" > Groups</label>--}}

                @endif

               @foreach( $groups as $group)
                   <div class=" no-padding" style="margin-bottom: 10px">
                       @foreach($group as $p)
                           <label for="{{$p['code']}}" class="btn btn-primary btn-xs " style="width: 80px"> <input style="font-size: 14pt;" class="group_clone" type="checkbox" name="group_id[]" value="{{$p['id']}}" id="{{$p['code']}}" > <span style="font-size: 14pt"> {{$p['code']}}</span></label>
                       @endforeach
                   </div>
               @endforeach
            </div>
        </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="clearfix"></div>

            <button class="btn btn-danger btn-xs pull-left" id="cancel"> Cancel </button>

            <button class="btn btn-success btn-xs pull-right" id="ok"> Clone </button>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
@stop
@section('after-scripts-end')

    {!! Html::script('plugins/iCheck/icheck.js') !!}

    <script>
        $(document).ready(function(){

            var baseData;
            baseData = countCheck(baseData);
            $(document).on('click', '#cancel', function (e) {
                window.close();
            });
            $(document).on('click', '#ok', function (e) {
                if(baseData.group_id.length > 0) {
                    cloning(baseData);
                } else {
                    notify('warning', 'Please Select Group!', 'Clone Score');
                }
            })
        });

        function cloning(baseData ) {

            toggleLoading(true)
            $.ajax({
                type: 'GET',
                url: '{{route('course_annual.clone_score')}}',
                data: baseData,
                dataType: "JSON",
                success: function(resultData) {
                    if(resultData.status) {
                        toggleLoading(false)
                        notify('success', resultData.message, 'Clone Score!')
                        window.opener. switch_course(baseData);
                        //window.close();
                    } else {
                        toggleLoading(false);
                        notify('error', resultData.message, 'Clone Score!')
                    }
                },
                error:function(error) {

                    toggleLoading(false);
                    console.log(error);

                }
            });
        }


        function countCheck(baseData){

            var data = [];
            $(document).on('change', '.group_clone', function () {
                if($(this).is(':checked')) {
                    if(data.length < '{{\App\Models\Enum\GroupEnum::TEN}}') {
                        data.push($(this).val())
                    } else {
                        notify('error', 'Sorry you are not able to select groups more than 8!')
                        $(this).prop('checked', false);
                    }
                } else {
                    var new_element = [];
                    for( var int = 0; int < data.length; int++ ) {
                        if(data[int] != $(this).val()) {
                            new_element.push(data[int])
                        }
                    }
                    data = new_element;
                }
            });

            baseData = {
                course_annual_id : '{{$courseAnnual->id}}',
                group_id: data
            }

            return baseData;
        }
    </script>


@stop