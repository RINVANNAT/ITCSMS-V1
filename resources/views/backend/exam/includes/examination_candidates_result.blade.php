
@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Candidates Result')

@section('content')

    <div class="box box-success">

        <style>
            .enlarge-text{
                font-size: 36px;
            }
            .enlarge-number{
                font-size: 28px;
            }


            .modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.8);
                z-index: 1;
            }
        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Candidates Result</h3>
            <div class="pull-right">
                <button class="btn btn-primary" id="print_candidate_result"> Print </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="candidates-table">
                <thead>
                <tr>
                    <th>{{ trans('labels.backend.candidates.fields.register_id') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.name_kh') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.name_latin') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.gender_id') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.dob') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.province_id') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.highschool_id') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.bac_total_grade') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.room_id') }}</th>
                    <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
                    <th>{{ trans('labels.general.actions') }}</th>
                </tr>
                </thead>
            </table>

        </div>

    </div>
@stop

@section('after-scripts-end')
    <script>
        candidate_datatable = $('#candidates-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: {!! config('app.records_per_page')!!},
            ajax: {
                url: '{!! route('admin.candidate.data')."?exam_id=".$exam->id !!}',
                method: 'POST'
            },
            columns: [
                { data: 'register_id', name: 'candidates.register_id'},
                { data: 'name_kh', name: 'candidates.name_kh'},
                { data: 'name_latin', name: 'candidates.name_latin'},
                { data: 'gender_name_kh', name: 'genders.name_kh'},
                { data: 'dob', name: 'candidates.dob'},
                { data: 'province', name: 'origins.name_kh'},
                { data: 'high_school', name: 'highSchools.name_kh'},
                { data: 'bac_total_grade', name: 'bac_total_grade'},
                { data: 'room', name: 'candidates.room', searchable:false},
                { data: 'result', name: 'candidates.result'},
                { data: 'action', name: 'action',orderable: false, searchable: false}
            ]
        });

        window.onload = function() {
            $('.modal').style.display = "none";
        };

        var exam_id = JSON.parse('<?php echo $examId; ?>');

        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data:baseData,
                success: function(result) {
                    console.log(result);
                    if(result.status) {
                        window.close();
                        var printUrl = "{!! route('print_candidate_result_lists') !!}";
                        window_print_candidate_result = PopupCenterDual(printUrl+'?status='+'print_page'+'&exam_id='+exam_id,'print candidates result','1000','1200');
                    }
                }
            });
        }

        $('#print_candidate_result').on('click', function() {

            var baseUrl  = "{!! route('print_candidate_result_lists') !!}";

            var baseData = {status: 'request_print_page'}

            ajaxRequest('GET', baseUrl+'?status='+'export_page'+'&exam_id='+exam_id, baseData);
        })
    </script>
@stop