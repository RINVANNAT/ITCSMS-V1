<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">

                    {{$courseAnnual->name_en. ' |~ '.(($courseAnnual->department_id == config('access.departments.sa'))?'SA' :' SF').'-'.(($courseAnnual->degree_id == config('access.degrees.degree_engineer'))?'I':'T').$courseAnnual->grade_id}}

                </h4>
            </div>
            <div class="modal-body">

                {!! Form::open(['route' => ['course_annual.competency_score.import', $courseAnnual->id],'id' => 'import_course_annual_score', 'role'=>'form','files' => true])!!}
                <div class="box box-success">

                    {{--<div class="box-header with-border">
                        <h3 class="box-title"> Import Score Sheet </h3>
                    </div><!-- /.box-header -->--}}

                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="form-group col-sm-12" style="padding: 20px;">
                                <span>Select the .CSV file to import. if you need a sample importable file, you can use the export tool to generate one.</span>
                            </div>
                        </div>

                        <div class="row no-margin" style="padding-left: 20px;padding-right: 20px;">
                            <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                                {!! Form::label('import','Selected File (csv, xls, xlsx)') !!}
                                {!! Form::file('import', null) !!}
                                {{--{{ Form::hidden('group_id',  ($group !=null)?$group:null, ['class' => 'form-control', 'id'=>'name_kh', 'required' => 'required']) }}--}}

                            </div>

                        </div>
                    </div><!-- /.box-body -->
                </div><!--box-->

                <div class="box box-success">
                    <div class="box-body">
                        <div class="pull-left">
                            {{--<button class="btn btn-danger btn-xs" id="cancel_import">cancel</button>--}}
                        </div>

                        <div class="pull-right">
                            <input type="submit" class="btn btn-success btn-xs" id = "submit_score" value="{{ trans('buttons.general.import') }}"/>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.box-body -->
                </div><!--box-->
                {!! Form::close() !!}

            </div>
           {{-- <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>--}}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>