@extends('app')

@section('content')

    <div class="container">

        @include('flash::message')

        <div class="row">
            <h1 class="pull-left">Scores</h1>
            <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('scores.create') !!}">Add New</a>
        </div>

        <div class="row">
            @if($scores->isEmpty())
                <div class="well text-center">No Scores found.</div>
            @else
                @include('backend.score.table')
            @endif
        </div>

        @include('common.paginate', ['records' => $scores])


    </div>
@endsection