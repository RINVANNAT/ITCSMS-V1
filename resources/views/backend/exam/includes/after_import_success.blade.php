@extends ('backend.layouts.popup_master')

@section ('title', 'Temporary Employees'. ' | ' . 'importted success')

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Importted Success</h3>
        </div>


        <!-- /.box-header -->

        <div class="box-body">
            <div class="row no-margin">
                @if($status)
                    <div class="form-group col-sm-12 alert-info">

                        <h3> You have importted file!!</h3>


                    </div>
                @else
                    <div class="form-group col-sm-12 alert-danger">

                        <h3> Please Choose File Before Importing !!</h3>


                    </div>
                @endif
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->

@stop

@section('after-scripts-end')
    {!! Html::script('js/backend/plugin/jstree/jstree.min.js') !!}
    {!! Html::script('js/backend/access/roles/script.js') !!}

    <script>
        $( document ).ready(function() {
            setTimeout(function(){
                window.close();
            },3000);
        });
    </script>
@stop