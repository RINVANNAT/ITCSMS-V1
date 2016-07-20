@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.candidates.title') . ' | ' . trans('labels.backend.candidates.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="alert alert-info">
        <h4><i class="icon fa fa-info"></i> Operation is successful!</h4>
        New candidate has been inserted into our database. You may continue your work!
    </div>

    <a onclick="return_back();" href="javascript:void(0);" class="btn btn-success">Close</a>
@stop

@section('after-scripts-end')
    <script>
        function return_back(){
            try {
                window.opener.opener.refresh_candidate_list();
            } catch (err) {
                alert(err.description || err) //or console.log or however you debug
            }

            self.close();
        }
    </script>
@stop