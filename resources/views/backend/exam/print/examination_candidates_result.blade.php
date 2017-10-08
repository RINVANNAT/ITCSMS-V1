
@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Candidate Result Listes
@stop
@section('after-styles-end')
    <style>

        .left{
            text-align: left;
        }
        .center {
            text-align: center;
        }
        .font_small {
            font-size: 13px;
        }
        .exam_room {
            width: 1.8cm !important;
        }
        .footer {
            bottom: 25px !important;
        }

        table th {
            background-color: grey;
        }

        table td {
            text-align: left;
        }

        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border:1px solid #000000 !important;
        }

        @media print{
            .table-bordered > thead > tr > th,
            .table-bordered > tbody > tr > th,
            .table-bordered > tfoot > tr > th,
            .table-bordered > thead > tr > td,
            .table-bordered > tbody > tr > td,
            .table-bordered > tfoot > tr > td {
                border:1px solid #000000 !important;
            }
            .exam_room {
                width: 1.8cm !important;
            }
            .footer {
                bottom: 25px !important;
            }
            .page {
                height: 288mm !important;
                min-height: 288mm !important;
            }
        }
    </style>
@stop
@section('content')


@if($status)
    @foreach($candidateRes as $key => $candidatesResults)

        <?php
            $first_chunk = array_slice($candidatesResults,0,20);
            $remaining_chunk = array_slice($candidatesResults,20);
            $candidatesResults = array_chunk($remaining_chunk, 22);
            array_unshift($candidatesResults,$first_chunk);
        ?>
        <?php   $page_number = 1;
        $total_page = count($candidatesResults);
        ?>
        <?php $check =0;?>
        <?php $index =0; $female=0;?>
        <?php $i =0;?>

        @foreach($candidatesResults as $candidatesResult)


            <div class="page">

                @if($page_number == 1)
                    <center>
                    <h3>បញ្ជីរាយឈ្មោះបេក្ខជន ជាប់ {{$key}} ចូលរៀនថ្នាក់ឆ្នាំសិក្សាមូលដ្ឋាន</h3>
                    <h3>នៅ​ វបក សម្រាប់ឆ្នាំសិក្សា ២០១៧-២០១៨</h3>
                    </center>
                @endif
                <table class="table table-bordered" width="100%">
                    <tr>
                        <th width="1.2cm" class="center">ល.រ</th>
                        <th width="1.2cm" class="center">បង្កាន់ ដៃ</th>
                        <th class="center exam_room">បន្ទប់ប្រលង</th>
                        <th class="left">ឈ្មោះជាភាសាខ្មែរ</th>
                        <th class="left">ឈ្មោះជាឡាតាំង</th>
                        <th width="1cm" class="center">ភេទ</th>
                        <th width="2.5cm" class="center">ថ្ងៃខែឆ្នាំកំណើត</th>
                        <th width="2.5cm" class="center">ខេត្ត</th>
                    </tr>

                    @foreach($candidatesResult as $result)
                        <?php $i++;?>

                        <?php
                        $index++;
                        if($result->gender == 'F') {
                            $female++;
                        }
                        ?>
                        <tr>
                            <td class="center"><?php echo $i;?></td>
                            <td class="center">{{str_pad($result->register_id, 4, '0', STR_PAD_LEFT)}}</td>
                            <td class="center">{{$result->building.$result->room}}</td>
                            <td class="left font_small">{{$result->name_kh}}</td>
                            <td class="left font_small">{{strtoupper($result->name_latin)}}</td>
                            <td class="center">{{$result->gender}}</td>
                            <td class="center">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->dob)->formatLocalized("%d/%b/%Y")}}</td>
                            <td class="center font_small">{{$result->origin}}</td>
                        </tr>
                    @endforeach
                </table>

                @if($page_number == $total_page)

                    <div class ="col-sm-12 no-padding" style="font-size: 10pt;margin-top: 20px;">
                        បញ្ឈប់បញ្ជីត្រឹម {{$index}} នាក់ ក្នុងនោះមានស្រី {{$female}} នាក់ ។
                    </div>
                    <div class="col-sm-12 no-padding">
                        <div class="col-sm-7">

                        </div>

                        <div class="col-sm-5 no-padding pull-right" style="font-size: 10pt;">
                            <div class="col-sm-12 no-padding text-center">
                                ធ្វើនៅភ្នំពេញ ថ្ងៃទី ០៩ ខែ តុលា ឆ្នាំ ២០១៧
                            </div>
                            <div class="col-sm-12 no-padding text-center">
                                នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                            </div>
                        </div>
                    </div>

                @endif

                <div class="footer">
                    <hr style="margin-top: 5px; margin-bottom: 10px"/>
                    <span>Concours d'entree ITC {{ $academic_year->id - 1 }}</span>
                    <span class="pull-right">Page {{$page_number}} sur {{$total_page}}</span>
                </div>
            </div>
            <?php $page_number++; ?>
        @endforeach

        @if($check==true)

            <div class="page">

                <div class ="col-sm-12 no-padding" style="font-size: 10pt;margin-top: 20px">
                    បញ្ឈប់បញ្ជីត្រឹម {{$index}} នាក់ ក្នុងនោះមានស្រី {{$female}} នាក់ ។
                </div>
                <div class="col-sm-12 no-padding">
                    <div class="col-sm-7">

                    </div>

                    <div class="col-sm-5 no-padding pull-right" style="font-size: 10pt;">
                        <div class="col-sm-12 no-padding text-center">
                            ធ្វើនៅភ្នំពេញ ថ្ងៃទី ០៩ ខែ តុលា ឆ្នាំ ២០១៧
                        </div>
                        <div class="col-sm-12 no-padding text-center">
                            នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <hr/>
                    <span><?php  echo date("l, F Y");?></span>
                    <span class="pull-right">Page {{$page_number}} of {{$total_page+1}}</span>
                </div>
            </div>

        @endif

    @endforeach

@else
    <div class="col-sm-12 alert-danger">
        <h3>There are no result of candidates!!</h3>
    </div>

@endif
@endsection

@section('scripts')
    <script>

    </script>
@stop
