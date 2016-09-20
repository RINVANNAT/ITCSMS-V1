@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('after-styles-end')
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection


@section('content')
    {!! Form::model($candidate, ['route' => ['admin.candidates.update', $candidate->id],'class' => 'form-horizontal', 'id'=> 'candidate-form', 'role'=>'form', 'method' => 'patch']) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.candidates.sub_create_title') }}</h3>
            <div class="pull-right">
                <a href="#" id="btn-cancel" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                <input id="btn-submit" type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
            </div>
        </div><!-- /.box-header -->

        <div class="box-body" style="background-color: #ddd !important;">
            @include('backend.candidate.fields')
        </div><!-- /.box-body -->
    </div><!--box-->

    {!! Form::close() !!}
@stop

@section('after-scripts-end')

    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    {!! JsValidator::formRequest('App\Http\Requests\Backend\Candidate\UpdateCandidateRequest') !!}

    <script>


        function save_candidate(){

            var status = $( "#candidate-form" ).validate().form();
            if(status ==true){
                var disabled = $("#candidate-form" ).find(':input:disabled').removeAttr('disabled');
                var data = $("#candidate-form" ).serializeArray();
                $.each(data, function(key, data)
                {
                    if (this.name == "highschool_id")
                        this.value=$("#highschool_id").attr('value');
                });
                disabled.attr('disabled','disabled');

                 $.ajax({
                     type: 'POST',
                     url: $("#candidate-form" ).attr('action'),
                     data: data,
                     success: function(response) {
                        if(typeof response.status !== 'undefined'){
                            if(response.status == true){
                                return_back();
                            } else {
                                notify("error","Candidate Error",response.toString());
                            }
                        } else {
                            notify("error","Candidate Error",response.toString());
                        }
                     },
                     error:function(response){
                         console.log(response.toString());
                         notify("error","Error: Some fields are missing!");
                     }

                 });
            } else {
                console.log("false");
            }
        }

        function return_back(){
            try {
                if(window.opener.opener != null){
                    window.opener.opener.refresh_candidate_list();
                } else {
                    window.opener.refresh_candidate_list();
                }

                window.close();
            } catch (err) {
                alert(err.description || err) //or console.log or however you debug
            }

            self.close();
        }

        function formatRepo (repo) {
            console.log(repo);
            if (repo.loading) {
                return repo.text;
            }
            if (repo.newOption) {
                return '<a href="#" class="btn_add_new_customer"><em>Add new high school</em> "'+repo.name+'"</a>';
            } else {
                var markup =    "<div class='select2-result-repository clearfix'>" +
                                    "<div class='select2-result-repository__meta'>" +
                                        "<div class='select2-result-repository__title'>" + repo.name + "</div>"+
                                    "</div>"+
                                "</div>";
                return markup;

            }
        }

        function formatRepoSelection (repo) {
            console.log(repo);
            $('#candidate_highschool_name').val(repo.name);
            $('#highschool_id').val(repo.id);

            return repo.text || repo.name;
        }


        $(document).ready(function(){
            var $search_url = "{{route('admin.configuration.highSchool.search')}}";

            $('#candidate_dob').datetimepicker({
                format: 'DD/MM/YYYY',
            });
            var highschool_search_box = $("#candidate_highschool_name").select2({
                placeholder: 'Enter name ...',
                allowClear: true,
                tags: true,
                createTag: function (params) {
                    return {
                        id: params.term,
                        name: params.term,
                        group:'highschool',
                        newOption: true
                    }
                },
                ajax: {
                    url: $search_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection, // omitted for brevity, see the source of this page
            });


            $('.input').keypress(function (e) {
                if (e.which == 13) {
                    save_candidate();
                    return false;    //<---- Add this line
                }
            });


            $("#btn-submit").on("click", function(e){
                e.preventDefault();
                save_candidate();
            });



            $("#btn-cancel").on("click",function(){
                window.close();
            });

            $("#candidate_register_id").keydown(function (e) {
                allowNumberOnly(e);
            });
        });



    </script>
@stop