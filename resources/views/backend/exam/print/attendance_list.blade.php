@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | បញ្ជីវត្តមានបេក្ខជន⁣
@stop
@section('content')
    <?php
        $page_number = 1;
        $total_page = count($rooms);
    ?>
    @foreach($courses as $course)
        @foreach($rooms as $room)
            <div class="page">
                <h3>បញ្ជីវត្តមានបេក្ខជន⁣ បន្ទប់ {{$room->name}} <span class="pull-right">{{$course->name_kh}}</span></h3>

                <table class="table" width="100%">
                    <tr>
                        <th>លេខរៀង</th>
                        <th>លេខបង្កាន់ដៃ</th>
                        <th>ឈ្មោះ</th>
                        <th>ឈ្មោះឡាតាំង</th>
                        <th>ភេទ</th>
                        <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                        <th>ហត្ថលេខា</th>
                    </tr>
                    <?php
                    $index = 1;
                    ?>
                    @foreach($room->candidates as $candidate)
                        <tr>
                            <td>{{$index}}</td>
                            <td>{{$candidate->register_id}}</td>
                            <td>{{$candidate->name_kh}}</td>
                            <td>{{$candidate->name_latin}}</td>
                            <td>{{$candidate->gender->code}}</td>
                            <td>{{$candidate->dob->toFormattedDateString()}}</td>
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

    </script>
@stop
