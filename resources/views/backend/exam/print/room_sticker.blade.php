@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Room Sticker
@stop
@section('after-styles-end')
    <style>
        .sticker{
            padding: 5mm;
        }
        .border{
            border: 2px solid black;
        }

        h2{
            font-weight: 900;
        }
    </style>
@endsection
@section('content')
    <?php
        $page_number = 1;
        $total_page = count($rooms);
    ?>
    @foreach($rooms as $room)
        <div class="page">

            @foreach($room->candidates as $candidate)
                <div class="col-md-3 col-sm-3 col-xs-3 sticker">
                    <div class="col-md-12 col-sm-12 col-xs-12 border">
                        <center><h2>{{$candidate->register_id}}</h2></center>
                        <span>{{$room->name}}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop
