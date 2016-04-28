<div class="modal fade" id="add_outcome_modal">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::open(['route' => 'admin.accounting.outcomes.store','id'=>'payslip_outcome_form', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) !!}
                            @include('backend.accounting.outcome.fields')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{trans('buttons.general.close')}}</button>
                <button id="submit_outcome" type="button" class="btn btn-primary">{{trans('buttons.general.save')}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->