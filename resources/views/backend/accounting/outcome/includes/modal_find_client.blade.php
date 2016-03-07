<div class="modal fade" id="modal_find_client">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 style="font-size: 20px;"><i class="glyphicon glyphicon-search"></i> {!! trans('labels.backend.outcomes.search_client') !!}
                </h3>
            </div>

            <div class="modal-body">
                <div class="box-body with-border text-muted well well-sm no-shadow">
                    {!! Form::open(['#','id'=>'form_client_search']) !!}
                    <div class="row no-margin">
                        <div class="form-group col-sm-6">
                            {!! Form::label('client_type',trans('labels.backend.outcomes.fields.is'),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('client_type', ['employees'=>'បុគ្គលិក','studentAnnuals'=>'សិស្ស','customers'=>'ផ្សេងទៀត'], null, array('class'=>'form-control','id'=>'client_type','style'=>'width:80%')) !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('name_kh',trans('labels.backend.outcomes.fields.name'),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('name_kh', null, array('class'=>'form-control','placeholder'=>'ឈ្មោះ')) !!}
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <div class="row no-margin" style="padding-right: 30px;">
                        <button type="button" class="btn btn-danger pull-right" id="btn_modal_client_search"><i class="glyphicon glyphicon-search"></i> {{trans('buttons.general.search')}}</button>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{trans('labels.backend.outcomes.search_result')}}</h3>
                    </div>
                    <div class="box-body with-border" id="client_result">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">{{trans('alerts.backend.generals.no_result_found')}}.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div><!-- /.modal -->