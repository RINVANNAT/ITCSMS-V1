@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.students.title') . ' | ' . trans('labels.backend.students.sub_print_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Printing Certificate</h3>
            <div class="pull-right">
                <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                    <i class="fa fa-check-square-o"></i>
                </button>
                <button class="btn btn-success btn-sm btn-print"><i class="fa fa-print"></i> Print Selected</button>
                <button type="button" class="btn btn-success btn-sm" id="add_student" >
                    <i class="fa fa-plus"></i>
                </button>

            </div>

        </div><!-- /.box-header -->

        <div class="pull-right box search_student" style="margin-bottom: 20%;" >
            {!! Form::select('student_id_card',[],null,['id'=>'select_student_id_card','class'=>"form-control col-sm-10",'style'=>'width:100%;']) !!}
            {{ Form::hidden('student_id', null, ['class' => 'form-control', 'id'=>'student_lists']) }}
        </div>

        <div class="box-body certificate_table">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th></th>
                        <th>ID Card</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Class</th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($students as $student)
                        <tr>
                            <td><input type="checkbox" checked class="checkbox" data-id="{{$student->student_annual_id}}"></td>
                            <td>{{$student->id_card}}</td>
                            <td>{{$student->name_kh}} <br/>
                                {{$student->name_latin}}
                            </td>
                            <td>{{$student->code}}</td>
                            <td>{{$degrees[$student->degree_id].$student->grade_id.$departments[$student->department_id]}}</td>
                            <td></td>
                            <td>
                                <button data-id="{{$student->student_annual_id}}" style="float: right" class="btn btn-block btn-default btn-sm btn-single-print"><i class="fa fa-print"></i> Print</button>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    {!! HTML::script('js/backend/student/student.js') !!}
    <script>
        var selected_ids = null;
        var print_url = "{{ route('course_annual.competency.print_certificate') }}";


        function initIcheker() {

            $('.certificate_table input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            });
        }
        initIcheker();


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").on('click',function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".certificate_table input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            } else {
                //Check all checkboxes
                $(".certificate_table input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $(".btn-print").on("click",function(){
            selected_ids = [];
            $('.certificate_table input:checked').each(function(){
               selected_ids.push($(this).data('id'));
            });
            PopupCenterDual(
                    print_url
                    + '?ids='+JSON.stringify(selected_ids),
                    'Printing','1200','800');

        });

        $(".btn-single-print").on("click", function(){
            var id = $(this).data('id');
            PopupCenterDual(
                    print_url
                    + '?ids='+JSON.stringify([id]),
                    'Printing','1200','800');
        });


        $('div.search_student').hide()
        $('#add_student').on('click', function (e) {
            $('div.search_student').slideToggle('fast');


        });

        var $search_url = "{{route('admin.student.search')}}";
        var student_search_box = $('select#select_student_id_card').select2({
            placeholder: 'Enter id card ...',
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
                method:'GET',
                url: $search_url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term || '', // search term
                        page: params.page || 1
                    };
                },
                cache: true,

            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 3,
            templateResult: formatRepoStudent, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelectionStudent // omitted for brevity, see the source of this page
        });

        $('#select_student_id_card').on("select2:select", function(e) {

            var attrs = getAttr();

            {{--var new_id = new_id_card_photo(--}}
                    {{--'{{url('img/id_card/front_id_card.png')}}',--}}
                    {{--attrs.id_card,--}}
                    {{--attrs.name_kh,--}}
                    {{--attrs.name_latin,--}}
                    {{--attrs.student_annual_id,--}}
                    {{--'{{$smis_server->value.'/img/profiles/'}}'+attrs.photo--}}
            {{--);--}}
            var check = is_added_student(attrs.student_annual_id)
            if(!check) {
                $('.certificate_table').children('.row').prepend(new_id);
                initIcheker();

            } else {
                notify('error', 'Student already added!', 'Attention!')
            }

            $('div.search_student').slideToggle('fast');

        })


        function getAttr()
        {

            var attrs = {
                id_card: $('#student_lists').attr('id_card'),
                name_kh: $('#student_lists').attr('name_kh'),
                name_latin: $('#student_lists').attr('name_latin'),
                student_annual_id: $('#student_lists').attr('student_annual_id'),
                photo: $('#student_lists').attr('photo')
            }

            return attrs;

        }

         function is_added_student (student_annual_id) {

            var check= 0;
             $(document).find('div.certificate_table input:checked').each(function () {

                 if(parseInt(student_annual_id) == parseInt($(this).data('id'))) {
                     check++;

                 }
             });

             if(check > 0)  {
                 return true;
             } else {
                 return false;
             }


        }

    </script>
@stop