<div class="box-header with-border">
    <h3 class="box-title">Printing Student ID Card</h3>
    <div class="pull-right">

        <input id="card_a4" type="checkbox" />
        <label style="margin-right: 20px;">A4 Paper</label>

        <input id="orderby" type="checkbox" checked />
        <label style="margin-right: 20px;">ASC</label>

        <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm btn-print" data-value="front"><i class="fa fa-print"></i> FRONT</button>
            <button type="button" class="btn btn-default btn-sm btn-print" data-value="back"><i class="fa fa-print"></i> BACK</button>
            <button type="button" class="btn btn-default btn-sm btn-print" data-value="duplex"><i class="fa fa-print"></i> DUPLEX</button>
        </div>
        <button type="button" class="btn btn-warning btn-sm btn-inform-success">
            Inform Success
        </button>
        <button type="button" class="btn btn-default btn-sm checkbox-toggle">
            <i class="fa fa-check-square-o"></i>
        </button>

        <button type="button" class="btn btn-success btn-sm" id="add_student" >
            <i class="fa fa-plus"></i>
        </button>

    </div>

</div><!-- /.box-header -->

<div class="pull-right box search_student" style="margin-bottom: 20%;" >

    {!! Form::select('student_id_card',[],null,['id'=>'select_student_id_card','class'=>"form-control col-sm-10",'style'=>'width:100%;']) !!}
    {{ Form::hidden('student_id', null, ['class' => 'form-control', 'id'=>'student_lists']) }}


</div>

<div class="box-body id_card_table">

    <div class="row">

    </div>
</div>