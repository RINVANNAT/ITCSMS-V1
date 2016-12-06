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
    <style>
        .page {
            width: 2.125in;
            height: 3.375in;
        }
        .background {
            position: absolute;
            width: 2.125in;
            height: 3.375in;
        }

        .detail {
            position: absolute;
            width: 2.125in;
            height: 3.375in;
            z-index: 9999;
        }

        .id_card {
            font-family: "khmersantepheap";
            width: 100%;
            /*font-weight: bold;*/
            text-align: center;
            top: 1in;
            font-size: 10px;
            position: absolute;
        }

        .avatar {
            position: absolute;
            top:1.2in;
            width: 100%;

        }
        .avatar .crop {
            width: 1.2in;
            height: 1.55in;
            display: block;
            /*border: 1px solid white;*/
            overflow: hidden;
            margin-left: auto;
            margin-right: auto;
        }
        .avatar img {
            width: 100%;
        }
        .name_kh {
            position: absolute;
            font-family: "khmersantepheap";
            top:2.85in;
            font-weight: bold;
            font-size:19px;
            text-align: center;
            width: 100%;
        }
        .name_latin {
            position: absolute;
            font-family: "Calibri";
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            line-height: 14px;
            top:3.1in;
            width: 100%;
        }

        .barcode {
            position: absolute;
            top:2.5in;
            width: 100%;

        }
        .barcode img {
            width: 1.9in;
            height: 0.8cm;
            display: block;
            margin-left: auto;
            margin-right: auto;
            image-orientation: from-image;
        }

        .barcode_value {
            width: 100%;
            font-size: 8px;
            text-align: right;
            position: absolute;
            top:2.84in;
            right: 0.14in;
        }

        .expired_date {
            font-family: khmersantepheap;
            width: 100%;
            text-align: center;
            font-size: 9px;
            position: absolute;
            top:2.33in;
        }

        .address_title {
            width: 100%;
            font-weight: bold;
            text-align: center;
            font-family: khmersantepheap;
            font-size: 15px;
            color: #0c4da2 !important;
            top:0.6in;
            position: absolute;
        }

        .address {
            width: 100%;
            text-align: center;
            font-family: khmersantepheap;
            font-size: 10px;
            top:0.9in;
            position: absolute;
        }

        .message {
            width: 100%;
            text-align: center;
            font-family: khmersantepheap;
            /*font-weight: bold;*/
            font-size: 9px;
            top:3in;
            position: absolute;
        }

        .icheckbox_square-red {
            float: right;
        }

        @media screen {
            .department{
                font-family: "khmersantepheap";
                width: 100%;
                /*font-weight: bold;*/
                text-align: center;
                top:0.79in;
                font-size: 10.5px !important;
                color: #ffffff !important;
                position: absolute;
            }
        }
        @media print {
            .department{
                font-family: "khmersantepheap";
                width: 100%;
                text-align: center;
                /*font-weight: bold;*/
                top:0.79in;
                font-size: 10.5px !important;
                color:#fff !important;
                -webkit-print-color-adjust: exact;
                position: absolute;
            }
        }



    </style>
@stop

@section('content')

    <div class="box box-success">
        @if(sizeof($studentAnnuals_front) > 50)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Error!</h4>
            You are printing more than 50 students, this will cause memory over used and many more errors might be occurred. <br/>
            Please filter more to get less than 50 students.
        </div>
        @else
        <div class="box-header with-border">
            <h3 class="box-title">Printing Student ID Card</h3>
            <div class="pull-right">
                <input id="orderby" type="checkbox" checked />
                <label style="margin-right: 20px;">ASC</label>

                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm btn-print" data-value="front"><i class="fa fa-print"></i> FRONT</button>
                    <button type="button" class="btn btn-default btn-sm btn-print" data-value="back"><i class="fa fa-print"></i> BACK</button>
                </div>
                <button type="button" class="btn btn-warning btn-sm btn-inform-success">
                    Inform Success
                </button>
                <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                    <i class="fa fa-square-o"></i>
                </button>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body id_card_table">
            <div class="row">
                @foreach($studentAnnuals_front as $front)
                        <div class="col-sm-3" style="margin-bottom: 15px;">
                            <div class="page">
                                <div class="background">
                                    <img width="100%" src="{{url('img/id_card/front_id_card.png')}}">
                                </div>
                                <div class="detail">
                                    {{--<span class="name_en">ENG RATANA</span>--}}
                                    {{--<span class="name_kh">អេង រតនា</span>--}}
                                    <span class="department" >
                                        ដេប៉ាតឺម៉ង់ {{$front->department}}
                                    </span>
                                    <span class="id_card">អត្តលេខនិស្សិត/ID : <strong>{{$front->id_card}}</strong></span>
                                    <div class="avatar">
                                        <div class="crop">
                                            <img src="{{config('app.smis_server')}}/img/profiles/{{$student->photo}}">
                                        </div>
                                    </div>

                                    <span class="name_kh">{{$front->name_kh}}</span>
                                    @if(strlen($front->name_latin) < 25)
                                        <span class="name_latin">{{strtoupper($front->name_latin)}}</span>
                                    @else
                                        <span class="name_latin" style="font-size: 13px !important;">{{strtoupper($front->name_latin)}}</span>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-1" style="margin-bottom: 15px;">
                            <input type="checkbox" class="checkbox" data-id="{{$front->id}}">
                        </div>


                @endforeach
            </div>
        </div>
        @endif
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    <script>
        var selected_ids = null;
        $('.id_card_table input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red'
        });


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").on('click',function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".id_card_table input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".id_card_table input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $(".btn-print").on("click",function(){
            var type = $(this).data('value');
            selected_ids = [];
            $('.id_card_table input:checked').each(function(){
               selected_ids.push($(this).data('id'));
            });


            var orderby = "DESC";
            if($("#orderby").is(":checked")){
                orderby = "ASC";
            }
            var url = "{{ route('admin.student.print_id_card') }}";

            PopupCenterDual(
                    url
                    + '?ids='+JSON.stringify(selected_ids)+"&orderby="+orderby+"&type="+type,
                    'Printing','450','800');

        });

        $(".btn-inform-success").on("click",function(){
            var baseUrl = "{{route('admin.student.print_inform_success')}}";
            selected_ids = [];
            $('.id_card_table input:checked').each(function(){
                selected_ids.push($(this).data('id'));
            });
            $.ajax({
                type: 'GET',
                url: baseUrl+'?ids='+JSON.stringify(selected_ids),
                dataType:"json",
                success: function(resultData) {

                    if(resultData.success == true) {
                        notify("success","info", resultData.message);
                        oTable.draw();
                    } else {
                        notify("error","info", resultData.message);
                    }


                }
            });
        });

    </script>
@stop