@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | បញ្ជីវត្តមានបេក្ខជន⁣
@stop

@section('after-styles-end')
    <style>

        .left{
            text-align: left;
        }

        table th, table td {
            padding-left: 0px !important;
            padding-right: 0px !important;
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
            border-bottom: 1px solid black;
            border-top: 1px solid black;
            border-collapse: collapse;
            font-size-adjust: 0.58;
        }
        td span, th span {
            display: block;
            white-space: nowrap;
            width: 100px;
            overflow: hidden;
            font-size: 100%;
        }
    </style>
@stop
@section('content')
    <?php
        $total_page = count($rooms);
    ?>
    @foreach($courses as $course)
        <?php
            $page_number = 1;
        ?>
        @foreach($rooms as $room)
            <div class="page">
                <h3>បញ្ជីវត្តមានបេក្ខជន⁣ បន្ទប់ {{$room->building->code."-".$room->name}} <span class="pull-right">{{$course->name_kh}}</span></h3>

                <table class="table" width="100%">
                    <tr>
                        <th width="1.2cm"><span>ល.រ</span></th>
                        <th width="1.5cm"><span>លេខ បង្កាន់ដៃ</span></th>
                        <th><span>ឈ្មោះ</span></th>
                        <th><span>ឈ្មោះឡាតាំង</span></th>
                        <th><span>ភេទ</span></th>
                        <th width="2.5cm"><span>ថ្ងៃខែឆ្នាំកំណើត</span></th>
                        <th width="1.5cm"><span>ហត្ថលេខា</span></th>
                    </tr>
                    <?php
                    $index = 1;
                    ?>
                    @foreach($room->candidates()->with('gender')->orderBy('register_id')->get() as $candidate)

                        <tr>
                            <td><span>{{$index}}</span></td>
                            <td><span>{{str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT)}}</span></td>
                            <td class="left"><span>{{$candidate->name_kh}}</span></td>
                            <td class="left"><span>{{strtoupper($candidate->name_latin)}}</span></td>
                            <td><span>{{$candidate->gender->code}}</span></td>
                            <td class="left"><span>{{$candidate->dob->formatLocalized("%d/%b/%Y")}}</span></td>
                            <td></td>
                            <?php $index++; ?>
                        </tr>
                    @endforeach
                </table>
                <div class="footer">
                    <hr/>
                    <span>Concours d'entree ITC 2016</span>
                    <span class="pull-right">Page {{$page_number}} sur {{$total_page}}</span>
                </div>
            </div>
            <?php $page_number++; ?>
        @endforeach
    @endforeach

@endsection

@section('scripts')
    <script>
        $(function() {
            $('td span').each(function() {
                var fontSize = 100;
                while (this.scrollWidth > $(this).width() && fontSize > 0) {
                    // adjust the font-size 5% at a time
                    fontSize -= 5;
                    $(this).css('font-size', fontSize + '%');
                }
            });
        });
    </script>
@stop
