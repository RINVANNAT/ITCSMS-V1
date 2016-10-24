@extends('backend.layouts.printing_id_card')
@section('title')
    ITC-SMS | អត្តសញ្ញាណបណ្ណនិស្សិត
@stop

@section('after-styles-end')
    <style>

    </style>
@stop
@section('content')
    <div class="card">
        <div class="card-background">
            <img src="{{url('img/id_card/id_card_front.png')}}">
        </div>
        <div class="card-body">

        </div>
    </div>

    <div class="card">
        <div class="card-background">
            <img src="{{url('img/id_card/id_card_back.png')}}">
        </div>
        <div class="card-body">

        </div>
    </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop
