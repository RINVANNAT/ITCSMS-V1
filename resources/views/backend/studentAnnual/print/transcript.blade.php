@extends('backend.layouts.printing_id_card_a4')
@section('title')
    ITC-SMS | អត្តសញ្ញាណបណ្ណនិស្សិត
@stop

@section('after-styles-end')
    <style>



    </style>
@stop
@section('content')

    @if($type == "front")
        <?php $pages = array_chunk($studentAnnuals->toArray(),9); ?>
        @foreach($pages as $page)
            <?php $rows1 = array_chunk($page, 3); ?>
            <div class="page">

                @foreach($rows1 as $row1)
                    <div class="row" style="margin:0px; padding-left: 0.5mm !important; padding-top: 10mm !important;">

                        @foreach($row1 as $front)
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
        <?php $pages = array_chunk($studentAnnuals->toArray(),9); ?>
        @foreach($pages as $page)
            <?php $rows2 = array_chunk($page, 3);?>
            <div class="page">
                @foreach($rows2 as $row2)
                    <div class="row" style="margin:0px; padding-top: 10mm !important;">
                        @foreach($row2 as $back)
                            <div class="col-sm-4 col-xs-4" style="padding:0px; float: right!important;">
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
