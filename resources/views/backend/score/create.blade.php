@extends('app')

@section('content')
<div class="container">

    @include('common.errors')

    {!! Form::open(['route' => 'scores.store']) !!}

        @include('backend.score.fields')

    {!! Form::close() !!}
</div>
@endsection
