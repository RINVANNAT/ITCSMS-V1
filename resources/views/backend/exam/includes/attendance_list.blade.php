@extends('backend.layouts.printing_portrait_a4')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <?php
        $page_number = 1;
        $total_page = count($rooms);
    ?>
    @foreach($rooms as $room)
        <div class="page">
            <h2>បញ្ជីវត្តមានបេក្ខជន⁣ បន្ទប់ {{$room['name']}}</h2>

            <table class="table">
                <tr>
                    <th>លេខរៀង</th>
                    <th>លេខបង្កាន់ដៃ</th>
                    <th>ឈ្ោះ</th>
                    <th>ឈ្ោះឡាតាំង</th>
                    <th>ភេទ</th>
                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th>ហត្ថលេខា</th>
                </tr>
                <?php
                $index = 1;
                ?>
                @foreach($room['candidates'] as $candidate)
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{$candidate['register_id']}}</td>
                        <td>{{$candidate['name_kh']}}</td>
                        <td>{{$candidate['name_latin']}}</td>
                        <td>{{$candidate['gender']['code']}}</td>
                        <td>{{$candidate['dob']}}</td>
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

@endsection

@section('scripts')
    <script>

    </script>
@stop
