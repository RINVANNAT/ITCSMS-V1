@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.error.reporting.title') . ' | ' . trans('labels.backend.error.reporting.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.error.reporting.title') }}
        <small>{{ trans('labels.backend.error.reporting.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.error.reporting.sub_create_title') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="col-lg-8">
                <span class="show_label col-sm-2">{{trans('labels.backend.error.reporting.fields.title')}}</span>
                <span class="show_value col-sm-10">{{$reporting->title}} &nbsp;</span>

                <span class="show_label col-sm-2">{{trans('labels.backend.error.reporting.fields.description')}}</span>
                <span class="show_value col-sm-10">{{$reporting->description}} &nbsp;</span>

                @if(isset($reporting->image))
                    <span class="show_label col-sm-2">{{trans('labels.backend.error.reporting.fields.image')}}</span>
                    <span class="show_value col-sm-10"><img src="{{url('img/reporting/'.$reporting->image)}}"/></span>
                @endif
            </div>
            <div class="col-lg-4">
                @if (access()->hasRole('Administrator'))
                <div class="form-group">
                    {!! Form::label('name', trans('labels.backend.error.reporting.fields.status'), ['class' => 'col-lg-2 control-label']) !!}
                    <div class="col-lg-10">
                        {!! Form::select('status',['Pending'=>'Pending','In Progress'=>'In Progress','Done'=>'Done','Rejected'=>'Rejected'], $reporting->status, ['class' => 'form-control','id'=>'status']) !!}
                    </div>
                </div><!--form control-->
                @endif
            </div>


        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        $(function(){
            $('#status').change(function(){
                //alert($(this).val());

                $.ajax({
                    url : "{{route('admin.reporting.status',$reporting->id)}}",
                    type: "POST",
                    data : {status:$(this).val()},
                    success: function(data, textStatus, jqXHR)
                    {
                        alert('success! status is changed');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('something went wrong!');
                    }
                });
            });
        });
    </script>
@stop