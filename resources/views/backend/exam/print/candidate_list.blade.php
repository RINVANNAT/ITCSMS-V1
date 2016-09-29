@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | បញ្ជីឈ្មោះបេក្ខជន⁣
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
        }
    </style>
@stop
@section('content')
    <?php
        $page_number = 1;
        $total_page = count($rooms);
    ?>
    @foreach($rooms as $room)
        <div class="page">
            <h2>បញ្ជីឈ្មោះបេក្ខជន⁣ <span class="pull-right"> &nbsp;&nbsp;បន្ទប់ {{$room->building->code."-".$room->name}}</span></h2>

            <table class="table" width="100%">
                <tr>
                    <th width="20px;">ល.រ</th>
                    <th>លេខបង្កាន់ដៃ</th>
                    <th>ឈ្មោះ</th>
                    <th>ឈ្មោះឡាតាំង</th>
                    <th>ភេទ</th>
                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                </tr>
                <?php
                $index = 1;
                ?>
                @foreach($room->candidates()->with('gender')->orderBy('register_id')->get() as $candidate)
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT)}}</td>
                        <td class="left">{{$candidate->name_kh}}</td>
                        <td class="left">{{$candidate->name_latin}}</td>
                        <td>{{$candidate->gender->code}}</td>
                        <td class="left">{{$candidate->dob->formatLocalized("%d/%b/%Y")}}</td>
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

@endsection

@section('scripts')
    <script>

    </script>
@stop
