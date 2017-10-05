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

        }
        .name_kh, .name_latin {
            font-size: 13px;
        }
    </style>
@stop
@section('content')
    <?php
        $total_page = count($rooms);
    ?>
    @if(count($courses)==0)
        <div class="alert alert-danger alert-dismissible" style="margin: 50px;">
            <h4><i class="icon fa fa-ban"></i> Courses for exam are missing!</h4>
            Add some courses for exam in the course section.
        </div>
    @else
        @foreach($courses as $course)
            <?php
                $page_number = 1;
            ?>
            @foreach($rooms as $room)
                <div class="page">
                    <h3>បញ្ជីវត្តមានបេក្ខជន⁣ បន្ទប់ {{$room->building->code."-".$room->name}} <span class="pull-right">{{$course->name_kh}}</span></h3>

                    <table class="table" width="100%">
                        <tr>
                            <th width="1.2cm">ល.រ</th>
                            <th width="1.5cm">លេខ បង្កាន់ដៃ</th>
                            <th>ឈ្មោះ</th>
                            <th>ឈ្មោះឡាតាំង</th>
                            <th>ភេទ</th>
                            <th width="2.5cm">ថ្ងៃខែឆ្នាំកំណើត</th>
                            <th width="1.5cm">ហត្ថលេខា</th>
                        </tr>
                        <?php
                        $index = 1;
                        ?>
                        @foreach($room->candidates()->with('gender')->orderBy('register_id')->get() as $candidate)

                            <tr>
                                <td>{{$index}}</td>
                                <td>{{str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT)}}</td>
                                <td class="left name_kh">{{$candidate->name_kh}}</td>
                                <td class="left name_latin">{{strtoupper($candidate->name_latin)}}</td>
                                <td>{{$candidate->gender->code}}</td>
                                <td class="left">{{$candidate->dob->formatLocalized("%d/%b/%Y")}}</td>
                                <td></td>
                                <?php $index++; ?>
                            </tr>
                        @endforeach
                    </table>
                    <div class="footer">
                        <hr/>
                        <span>Concours d'entree ITC {{$academic_year->id - 1}}</span>
                        <span class="pull-right">Page {{$page_number}} sur {{$total_page}}</span>
                    </div>
                </div>
                <?php $page_number++; ?>
            @endforeach
        @endforeach
    @endif
@endsection

@section('scripts')
    <script>

    </script>
@stop
