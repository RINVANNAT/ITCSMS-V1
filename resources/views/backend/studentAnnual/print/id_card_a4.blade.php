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
            width: 2.145in;
            height: 3.395in;
            margin-left:auto;
            margin-right:auto;
            display:block;
            border:1px dashed black;
        }
        .page2 {
            width: 2.145in;
            height: 3.395in;
            margin-left:auto;
            margin-right:auto;
            display:block;
        }


    </style>
@stop
@section('content')

    <?php $pages = array_chunk($studentAnnuals->toArray(),9); ?>
    @if($type == "front")
        @foreach($pages as $page)
            <?php $rows = array_chunk($page, 3); ?>
            <div class="page">

                @foreach($rows as $row)
                    <div class="row" style="margin:0px; padding-left: 2mm !important; padding-top: 10mm !important;">
                        @foreach($row as $front)
                            <?php $front = (object)$front; ?>

                            <div class="col-sm-4 col-xs-4" style="padding:0px;">
                                @include("backend.studentAnnual.print.id_card_front_single")
                            </div>
                        @endforeach
                    </div><!---this end of row: has three images ---->
                @endforeach
            </div><!---end of one page: has nine images---->
        @endforeach

    @elseif($type=="back")
        @foreach($pages as $page)
            <?php $rows = array_chunk($page, 3);?>
            <div class="page">
                @foreach($rows as $row)
                    <div class="row" style="margin:0px; padding-top: 10mm !important;">
                        <?php $row = array_reverse($row); //dd($row);?>
                        @foreach($row as $back)
                            <div class="col-sm-4 col-xs-4" style="padding:0px;">
                                @include("backend.studentAnnual.print.id_card_back_single")
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    @elseif($type=="duplex")
        @foreach($pages as $page)
            <?php $rows = array_chunk($page, 3);?>
            <div class="page">
                @foreach($rows as $row)
                    <div class="row" style="margin:0px; padding-right: 2mm !important; padding-top: 10mm !important;">
                        @foreach($row as $front)
                            <?php $front = (object)$front; ?>
                            <div class="col-sm-4 col-xs-4" style="padding:0px;">
                                @include("backend.studentAnnual.print.id_card_front_single")
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="page">
                @foreach($rows as $row)
                    <div class="row" style="margin:0px; padding-top: 10mm !important;">
                        <?php $row_reverse = array_reverse($row);?>
                        @foreach($row_reverse as $back)
                            <div class="col-sm-4 col-xs-4" style="padding:0px;">
                                @include("backend.studentAnnual.print.id_card_back_single")
                            </div>
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
