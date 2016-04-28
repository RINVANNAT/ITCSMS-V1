@extends('app')

@section('content')
<div class="container">

    @include('common.errors')

    {!! Form::model($score, ['route' => ['scores.update', $score->id], 'method' => 'patch']) !!}

        @include('backend.score.fields')

    {!! Form::close() !!}



</div>
@endsection
