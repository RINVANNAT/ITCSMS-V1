@extends('backend.layouts.printing_portrait_a3')
@section('title')
    ITC-SMS | បញ្ជីបេក្ខជននិស្សិតវិស្វកម្ម
@stop
@section('after-styles-end')
    <style>

        .left{
            text-align: left;
        }

        table th, table td {
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }

        table th {
            background-color: grey;
        }
    </style>
@stop
@section('content')

    <?php
    $page_number = 1;
    $total_page = count($chunk_candidates);
    $index = 1;
    function cmp($a, $b)
    {
        return strcmp($a["code"], $b["code"]);
    }
    ?>
    @foreach($chunk_candidates as $chunk)

        <div class="page">

            <table class="table table-striped table-bordered" width="100%">
                <tr>
                    <th width="15px;">ល.រ</th>
                    <th>លេខបង្កាន់ដៃ</th>
                    <th>ឈ្មោះខ្មែរ</th>
                    <th>ឈ្មោះឡាតាំង</th>
                    <th>ភេទ</th>
                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th>វិទ្យាល័យ</th>
                    <th>ប្រភព</th>
                    <th>Bac Year</th>
                    {{--<th>BacII Score</th>--}}
                    <th>Bac</th>
                </tr>
                @foreach($chunk as $candidate)
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{str_pad($candidate['register_id'], 4, '0', STR_PAD_LEFT)}}</td>
                        <td class="left">{{$candidate['name_kh']}}</td>
                        <td class="left">{{strtoupper($candidate['name_latin'])}}</td>
                        <td>{{$candidate['gender']}}</td>
                        <td class="left">{{\Carbon\Carbon::createFromFormat("Y-m-d h:i:s",$candidate['dob'])->formatLocalized("%d/%b/%Y")}}</td>
                        <td class="left">{{$candidate['highschool']}}</td>
                        <td class="left">{{$candidate['origin']}}</td>
                        <td class="left">{{$candidate['bac_year']}}</td>
                        {{--<td>{{$candidate['bac_percentile']}}</td>--}}
                        <td>{{$candidate['bac_total_grade']}}</td>
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

@endsection

@section('scripts')
    <script>

    </script>
@stop
