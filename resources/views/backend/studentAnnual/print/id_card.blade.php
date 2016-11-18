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
            font-family: "khmersantepheap";
            width: 100%;
            /*font-weight: bold;*/
            text-align: center;
            top: 1in;
            font-size: 10px;
            position: absolute;
        }
        .avatar {
            position: absolute;
            top:1.2in;
            width: 100%;

        }
        .avatar .crop {
            width: 1.2in;
            height: 1.55in;
            display: block;
            border: 1px solid white;
            overflow: hidden;
            margin-left: auto;
            margin-right: auto;
        }
        .avatar img {
            width: 100%;
        }
        .name_kh {
            position: absolute;
            font-family: "khmersantepheap";
            top:2.8in;
            font-weight: bold;
            font-size:19px;
            text-align: center;
            width: 100%;
        }
        .name_latin {
            position: absolute;
            font-family: "Verdana";
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            top:3.05in;
            width: 100%;
        }

        .barcode {
            position: absolute;
            top:2.5in;
            width: 100%;

        }
        .barcode img {
            width: 1.9in;
            height: 0.8cm;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .barcode_value {
            width: 100%;
            font-size: 8px;
            text-align: right;
            position: absolute;
            top:2.84in;
            right: 0.14in;
        }

        .expired_date {
            font-family: khmersantepheap;
            width: 100%;
            text-align: center;
            font-size: 9px;
            position: absolute;
            top:2.33in;
        }

        .address_title {
            width: 100%;
            font-weight: bold;
            text-align: center;
            font-family: khmersantepheap;
            font-size: 15px;
            color: #0c4da2 !important;
            top:0.6in;
            position: absolute;
        }

        .address {
            width: 100%;
            text-align: center;
            font-family: khmersantepheap;
            font-size: 10px;
            top:0.9in;
            position: absolute;
        }

        .message {
            width: 100%;
            text-align: center;
            font-family: khmersantepheap;
            /*font-weight: bold;*/
            font-size: 9px;
            top:3in;
            position: absolute;
        }

        @media screen {
            .department{
                font-family: "khmersantepheap";
                width: 100%;
                /*font-weight: bold;*/
                text-align: center;
                top:0.78in;
                font-size: 12px !important;
                color: #ffffff !important;
                position: absolute;
            }
        }
        @media print {
            .department{
                font-family: "khmersantepheap";
                width: 100%;
                text-align: center;
                /*font-weight: bold;*/
                top:0.78in;
                font-size: 12px !important;
                color:#fff !important;
                -webkit-print-color-adjust: exact;
                position: absolute;
            }
        }


    </style>
@stop
@section('content')
    @foreach($studentAnnuals_front as $front)
    <div class="page">
        <div class="background">
            <img width="100%" src="{{url('img/id_card/front_id_card.png')}}">
        </div>
        <div class="detail">
            {{--<span class="name_en">ENG RATANA</span>--}}
            {{--<span class="name_kh">អេង រតនា</span>--}}
            <span class="department">ដេប៉ាតឺម៉ង់{{$front->department}}</span>
            <span class="id_card">អត្តលេខនិស្សិត/ID : {{$front->id_card}}</span>
            <div class="avatar">
                <div class="crop">
                    <img src="{{$front->photo !=""?url('img/profiles/'.$front->photo):url('img/profiles/avatar.png')}}" onerror="this.src='{{url('img/profiles/avatar.png')}}'">
                </div>
            </div>

            <span class="name_kh">{{$front->name_kh}}</span>
            <span class="name_latin">{{strtoupper($front->name_latin)}}</span>
        </div>

    </div>
    @endforeach
    @foreach($studentAnnuals_back as $back)
    <div class="page">
        <div class="background">
            <img width="100%" src="{{url('img/id_card/back_id_card.png')}}">
        </div>
        <div class="detail">
            <span class="address_title">
                អាសយដ្ធាន ៖
            </span>
            <span class="address">
                ប្រអប់សំបុត្រលេខ៨៦​ មហាវិថីសហព័ន្ធរុស្សី <br/>
                រាជធានីភ្នំពេញ ប្រទេសកម្ពុជា <br/>
                ទូរស័ព្ទ: (៨៥៥) ២៣ ៨៨០ ៣៧០/៩៨២ ៤០៤ <br/>
                ទូរសារ: (៨៥៥) ២៣ ៨៨០ ៣៦៩ <br/>
                សារអេឡិចត្រូនិច: info@itc.edu.kh <br/>
                គេហទំព័រ: www.itc.edu.kh

            </span>
            <span class="expired_date">ថ្ងៃផុតកំណត់/Expiry date: 31 July {{date('Y', strtotime('+1 year'))}}</span>
            <div class="barcode">
                <img src="data:image/png;base64,{{\Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG(substr($back->id_card, 1), 'C39')}}" alt="barcode" />
            </div>
            <span class="barcode_value">{{$back->id_card}}</span>
            <span class="message">
                ប្រសិនបើរើសបាន សូមជួយយកមកប្រគល់អោយ <br/>
                ការិយាល័យសិក្សា នៃវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
            </span>
        </div>
    </div>
    @endforeach

@endsection

@section('scripts')
    <script>

    </script>
@stop
