@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.secret_code.title'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
            <input type="button" id="btn-auto" class="btn btn-success btn-xs pull-right" style="margin-left: 5px; margin-top: 3px;" value="{{ trans('labels.backend.exams.secret_code.generate_auto') }}" />
            <span class="pull-right" id="form-secret-code" style="display: none">
                <input type="text" id="min_range"/> -
                <input type="text" id="max_range"/>

                <input type="button" id="btn-auto-save" class="btn btn-danger btn-xs pull-right" style="margin-left: 5px; margin-top: 3px;" value="{{ trans('buttons.general.save') }}" />
            </span>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Room Name</th>
                    <th style="width: 50px;text-align: center;">Room Secet Code</th>
                </tr>
                <?php
                        $i = 1;
                ?>
                @foreach($rooms as $room)
                <tr>
                    <td>{{$i}}.</td>
                    <td>{{$room['name']." ".$room['building']['code']}}</td>
                    <td>
                        <input class="secret_code" name="{{$room['id']}}" id="{{$room['id']}}" style="text-align: center;" disabled type="text" placeholder=" - " value="{{$room['roomcode']}}"/>
                    </td>
                </tr>
                <?php $i++?>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn-cancel" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn-save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        function is_secret_code_exist(code){
            var found = false;
            $(document).find('.secret_code').each(function(){
                if($(this).val() == code){
                    console.log("code exist");
                    found = true;
                }
            });
            return found;
        }

        $(function() {
            $("#btn-auto").click(function () {
                $(this).hide();
                $('#form-secret-code').show();
            });

            $("#btn-auto-save").click(function () {
                var min = parseInt($('#min_range').val());
                var max = parseInt($('#max_range').val());
                $(document).find('.secret_code').each(function(){
                    $(this).prop('disabled', false);
                    var code = Math.floor(Math.random()*(max-min + 1)) + min;
                    while(is_secret_code_exist(code)){
                        code = Math.floor(Math.random()*(max-min + 1)) + min;
                    }
                    $(this).val(code);
                });

                $('#form-secret-code').hide();
                $('#btn-auto').show();
            });

            $("#btn-save").click(function () {
                var rooms = new Array();

                $(".secret_code").each(function(){
                    var obj = {};
                    obj.room_id = $(this).attr("name");
                    obj.secret_code = $(this).val();

                    rooms.push(obj);
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.exam.save_room_secret_code',$exam_id)}}",
                    data: {room_ids:JSON.stringify(rooms)},
                    dataType: "json",
                    success: function(resultData) {
                        alert('success');
                        $(document).find('.secret_code').each(function() {
                            $(this).prop('disabled', true);
                        });
                    }
                });
            });

            $("#btn-cancel").click(function () {
                window.close();
            });

        });
    </script>
@stop