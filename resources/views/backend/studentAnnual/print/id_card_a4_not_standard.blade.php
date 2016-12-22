@extends('backend.layouts.printing_id_card_a4')
@section('title')
    ITC-SMS | អត្តសញ្ញាណបណ្ណនិស្សិត
@stop

@section('after-styles-end')
    <style>
        .background {
            position: absolute;
            width: 2.125in;
            height: 3.375in;
        }

        .detail {
            position: absolute;
            width: 2.125in;
            height: 3.375in;
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
            /*border: 1px solid white;*/
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
            top:2.85in;
            font-weight: bold;
            font-size:19px;
            text-align: center;
            width: 100%;
        }
        .name_latin {
            position: absolute;
            font-family: "Calibri";
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            line-height: 14px;
            top:3.1in;
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
            image-orientation: from-image;
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
                top:0.79in;
                font-size: 10.5px !important;
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
                top:0.79in;
                font-size: 10.5px !important;
                color:#fff !important;
                -webkit-print-color-adjust: exact;
                position: absolute;
            }
        }
        .page1 {
            width: 2.146in;
            height: 3.390in;
            margin-left:auto;
            border:1px solid darkgrey;
            margin-right:auto;
            float:left;
            margin-right: 2mm;
            display:block;
        }
        .page2 {
            width: 2.146in;
            height: 3.390in;
            margin-left:auto;
            border:none;
            margin-right:auto;
            float:right;
            margin-right: 2mm;
            display:block;
        }



    </style>
@stop
@section('content')


    @if($type == "front")
        <?php
                $pages = array_chunk($studentAnnuals->toArray(),9);

        ?>
        @foreach($pages as $page)

            <?php
            $rows = array_chunk($page, 3);
            ?>
            <div class="page">

                @foreach($rows as $row)
                    <div class="row" style="margin:0px; padding-left: 10mm !important;padding-top: 2mm !important;">
                        @foreach($row as $front)
                            <?php $front = (object)$front; ?>

                            {{--<div class="col-sm-4 col-xs-4" style="padding:0px;">--}}

                                <div class="page1">
                                    <div class="background">
                                        <img width="100%" src="{{url('img/id_card/front_id_card.png')}}">
                                    </div>
                                    <div class="detail">
                                        {{--<span class="name_en">ENG RATANA</span>--}}
                                        {{--<span class="name_kh">អេង រតនា</span>--}}
                                        <span class="department" >
                                        ដេប៉ាតឺម៉ង់ {{isset($front->department)?$front->department:""}}
                                        </span>
                                        <span class="id_card">អត្តលេខនិស្សិត/ID : <strong>{{isset($front->id_card)?$front->id_card:""}}</strong></span>
                                        <div class="avatar">
                                            <div class="crop">
                                                <img src="{{$smis_server->value}}/img/profiles/{{isset($front->photo)?$front->photo:"avatar.png"}}">
                                            </div>
                                        </div>

                                        <span class="name_kh">{{isset($front->name_kh)?$front->name_kh:""}}</span>
                                        @if(strlen(isset($front->name_latin)?$front->name_latin:"") < 25)
                                            <span class="name_latin">{{strtoupper(isset($front->name_latin)?$front->name_latin:"")}}</span>
                                        @else
                                            <span class="name_latin" style="font-size: 13px !important;">{{strtoupper(isset($front->name_latin)?$front->name_latin:"")}}</span>
                                        @endif
                                    </div>

                                </div>
                            {{--</div>--}}
                        @endforeach
                    </div><!---this end of row: has three images ---->
                @endforeach
            </div><!---end of one page: has nine images---->
        @endforeach

    @elseif($type=="back")
        <?php
            $pages = array_chunk($studentAnnuals->toArray(), 9);
        ?>

        @foreach($pages as $page)

            <?php
            $rows = array_chunk($page, 3);
            ?>
            <div class="page">

                @foreach($rows as $row)

                    <div class="row" style="margin:0px; padding-top: 2mm !important; padding-right: 11mm !important;">
                        <?php $row1 = array_reverse($row); //dd($row);?>
                        @foreach($row1 as $back)

                            {{--<div class="col-sm-4 col-xs-4" style="padding:0px;">--}}
                                <div class="page2">

                                    <div class="background">
                                        <img width="100%" src="{{url('img/id_card/back_id_card.png')}}">
                                    </div>
                                    <div class="detail">
                                <span class="address_title">
                                    អាសយដ្ឋាន ៖
                                </span>
                                <span class="address">
                                    ប្រអប់សំបុត្រលេខ៨៦​ មហាវិថីសហព័ន្ធរុស្សុី<br/>
                                    រាជធានីភ្នំពេញ ប្រទេសកម្ពុជា <br/>
                                    ទូរស័ព្ទ: (៨៥៥) ២៣ ៨៨០ ៣៧០/៨៨២ ៤០៤ <br/>
                                    ទូរសារ: (៨៥៥) ២៣ ៨៨០ ៣៦៩ <br/>
                                    សារអេឡិចត្រូនិច: info@itc.edu.kh <br/>
                                    គេហទំព័រ: www.itc.edu.kh

                                </span>
                                        <?php
                                        $date = null;
                                        $count = 0;
                                        if($back['degree_id'] == 1){
                                            if($back['grade_id'] < 3){
                                                $count = 2 - $back['grade_id'];
                                            } else {
                                                $count = 5 - $back['grade_id'];
                                            }
                                        } else if ($back['degree_id'] == 2){
                                            $count = 2 - $back['grade_id'];
                                        }
                                        ?>
                                        <span class="expired_date">ថ្ងៃផុតកំណត់/Expiry date: 30 September {{$back['academic_year_id'] + $count}}</span>
                                        <div class="barcode">
                                            <img src="data:image/png;base64,{{\Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG(substr($back['id_card'], 1), 'C39')}}" alt="barcode" />
                                        </div>
                                        <span class="barcode_value">{{$back['id_card']}}</span>
                                <span class="message">
                                    ប្រសិនបើរើសបាន សូមជួយយកមកប្រគល់ឱ្យ <br/>
                                    ការិយាល័យសិក្សា នៃវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                </span>
                                    </div>
                                </div>

                            {{--</div>--}}

                        @endforeach

                    </div>

                @endforeach


            </div>

        @endforeach
    @endif

@endsection

@section('scripts')
    <script>

    </script>
@stop
