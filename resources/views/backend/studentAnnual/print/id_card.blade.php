@extends('backend.layouts.printing_id_card')
@section('title')
    ITC-SMS | អត្តសញ្ញាណបណ្ណនិស្សិត
@stop

@section('after-styles-end')
    <style>
        .background {
            position: absolute;
            top:0;
            left:0;
            width: 2.125in;
            height: 3.375in;
        }

        .detail{
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }

        .id_card {
            left: 1.24in;
            top: 0.81in;
            font-size: 11px;
            position: absolute;
        }
    </style>
@stop
@section('content')
    <div class="page">
        <div class="background">
            <img width="100%" src="{{url('img/id_card/id_card_front.png')}}">
        </div>
        <div class="detail">
            {{--<span class="name_en">ENG RATANA</span>--}}
            {{--<span class="name_kh">អេង រតនា</span>--}}
            <span class="id_card">e20160001</span>
        </div>

    </div>
    <div class="page">
        <div class="background">
            <img width="100%" src="{{url('img/id_card/id_card_front1.png')}}">
        </div>
        <div class="detail">
            {{--<span class="name_en">SOK PISETH</span>--}}
            {{--<span class="name_kh">សុខ⁣ ពិសិដ្ធ</span>--}}
            <span class="id_card">e20160002</span>
        </div>
    </div>

@endsection

@section('scripts')
    <script>

    </script>
@stop
