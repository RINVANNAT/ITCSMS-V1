@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.secret_code.title'))
@section ('after-styles-end')
    <style>
        .duplicate {
            background-color: yellow;
        }
    </style>
@endsection
@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
            <input type="button" id="btn-auto" class="btn btn-success btn-xs pull-right" style="margin-left: 5px; margin-top: 3px;" value="{{ trans('labels.backend.exams.secret_code.generate_auto') }}" />
            <span class="pull-right" id="form-secret-code" style="display: none">
                <input type="number" id="min_range"/> -
                <input type="number" id="max_range"/>

                <input type="button" id="btn-auto-save" class="btn btn-danger btn-xs pull-right" style="margin-left: 5px; margin-top: 3px;" value="{{ trans('buttons.general.ok') }}" />
            </span>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="table_secret_code">
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
                <a href="{{route('admin.exam.export_room_secret_code',$exam_id)}}" id="btn-export" class="btn btn-info btn-xs">{{ trans('buttons.general.export') }}</a>
                <input type="button" id="btn-save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        function is_secret_code_exist(scode){
            var found=false;
            $(document).find('.secret_code').each(function(){
                if($(this).val() == scode){
                    console.log("code exist");
                    found = true;
                }
            });
            return found;
        }
        function highlight_duplicate_code(){
          $('.secret_code').removeClass("duplicate");
          var codes = [];
          $('.secret_code').each(function() {
            // check if there is another one with the same value
            //console.log($('.secret_code[value="' + $(this).val() + '"]').size());
            if(jQuery.inArray($(this).val(),codes) == -1){
              codes.push($(this).val());
            } else {
              $(this).addClass("duplicate");
              $(this).focus();
            }
          });
        }

        $(function() {
            $("#btn-auto").click(function () {
                $(this).hide();
                $('#form-secret-code').show();
            });

            $("#btn-auto-save").click(function () {
                // Remove highlight if exist
                $('#table_secret_code').find('.duplicate').each(function() {
                  $(this).removeClass('duplicate');
                });
                var min = parseInt($('#min_range').val());
                var max = parseInt($('#max_range').val());
                var rooms = $(document).find('.secret_code');
                if(min>max){
                    notify("error","info", "You entered incorrect number!");
                } else {
                    if(rooms.length > (max-min)){
                        notify("error","info", "Bigger number gap is required!");
                    } else {
                        var code;
                        $.each(rooms,function(){
                            $(this).prop('disabled', false);
                            while(true){
                                code = Math.floor(Math.random()*(max-min + 1)) + min;
                                console.log(is_secret_code_exist(code));
                                if(is_secret_code_exist(code)){
                                  // don't break the loop, generate a new code
                                } else {
                                  break;
                                }
                            }
                            $(this).val(code);
                        });

                        $('#form-secret-code').hide();
                        $('#btn-auto').show();
                    }
                }

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
                      if(resultData.success == true){
                        notify('success','Generate secret code', resultData.message);
                        $(document).find('.secret_code').each(function() {
                          $(this).prop('disabled', true);
                        });
                      } else {
                        // Something wrong. Duplicate code
                        notify('error',resultData.message,'Error');
                        // Find duplicate records and highlight them
                        highlight_duplicate_code();
                      }
                    }
                });
            });

            $("#btn-cancel").click(function () {
                window.close();
            });

        });
    </script>
@stop