@extends('app')

@section('content')
        <!-- Content Header (Page header) -->
<section class="content-header">
        <h1>
                Degree
                <small> Import data from csv file into database</small>
        </h1>
        <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-gears"></i> Configuration</a></li>
                <li class="active">Degree </li>
        </ol>
</section>

<section class="content">
        <div class="row">
                <div class="col-xs-12">
                        <div class="box box-primary">
                                <!-- /.box-header -->
                                <div class="box-body table-responsive">
                                @include('common.errors')
                                {!! Form::open(array('files'=>true)) !!}
                                {!! Form::label('file','Select file to import',array('id'=>'','class'=>'')) !!}
                                {!! Form::file('file','',array('id'=>'','class'=>'')) !!}
                                {!!   Form::hidden('summited', 'true') !!}
                                <br/>
                                <!-- submit buttons -->
                                {!! Form::submit('Import') !!}
                                <!-- reset buttons -->
                                {!! Form::reset('Reset') !!}
                                {!! Form::close() !!}
                                </div>
                        </div>
                </div>
        </div>

</section><!-- /.content -->


@endsection
