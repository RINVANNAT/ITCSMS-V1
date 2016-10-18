
@extends('backend.layouts.printing_portrait_a4')
@section('title')
    ITC-SMS | Candidate Result Listes
@stop
@section('after-styles-end')
    <style>

        .left{
            text-align: left;
        }

        table th, table td {
            font-size: 10pt;
            border: 1px solid black;
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }
    </style>
@stop
@section('content')


    @if($candidateDUTs)
        <?php
        $page_number = 1;

        $first_chunk = array_slice($candidateDUTs,0,27);
        $remaining_chunk = array_slice($candidateDUTs,27);
        $candidateDUTs = array_chunk($remaining_chunk, 30);
        array_unshift($candidateDUTs,$first_chunk);

        $total_page = count($candidateDUTs);
        ?>

        <?php
        $i =0;
        $female=0;
        ?>

        @foreach($candidateDUTs as $candidatesResult)
            <?php $check =0;?>
            <div class="page">

                @if($page_number ==1)
                    <div class="col-sm-12" style="text-align: center; margin-bottom: 15px">
                        <h2>{!! $title !!}</h2>
                        <h4>ការជ្រើសរើសចូលរៀនឆ្នាំទី១​​​​​ ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម</h4>
                        <h3>ឆ្នាំសិក្សា: @foreach($candidatesResult as $result)  @if($check==0) {{$result->academic_year}} <?php $check++;?> @endif @endforeach </h3>
                    </div>
                @endif

                <table class="" width="100%">
                    <tr>
                        <th>ល.រ</th>
                        <th>អត្តលេខ</th>
                        <th>ឈ្មោះខ្មែរ</th>
                        <th>ឈ្មោះឡាតាំង</th>
                        <th>ភេទ</th>
                        <th>ថ្ងៃខែឆ្នាំកំនើត</th>
                        <th>ប្រភពសិក្សា</th>
                        <th>ដេប៉ាតឺម៉ង់</th>

                    </tr>

                    @foreach($candidatesResult as $result)
                        <?php $i++;
                            if($result->gender == 'ស្រី') {
                                $female++;
                            }
                        ?>

                        <tr>
                            <td><?php echo str_pad($i, 4, '0', STR_PAD_LEFT);?></td>
                            <td><?php echo str_pad($result->register_id, 4, '0', STR_PAD_LEFT);?></td>
                            <td class="left">{{$result->name_kh}}</td>
                            <td class="left">{{strtoupper($result->name_latin)}}</td>
                            <td>{{$result->gender}}</td>
                            <td> <?php $date = explode(' ', $result->birth_date);  $time = strtotime($date[0]); $bDate = date('d/m/Y',$time); echo $bDate;?></td>
                            <td>{{$result->province_name}}</td>
                            <td>{{$result->department_name}}</td>

                        </tr>
                    @endforeach
                </table>
                    <div class="col-sm-12">

                    </div>
                @if($page_number == $total_page)
                    <?php
                        if($i > 27) {
                            $div = $i%27;
                            if($div <= 20) {
                        $check = false;
                    ?>

                        <div class ="col-sm-12 no-padding" style="font-size: 10pt;margin-top: 20px">
                            បញ្ឈប់បញ្ជីត្រឹម {{$i}} នាក់ ក្នុងនោះមានស្រី {{$female}} នាក់ ។
                        </div>
                        <div class="col-sm-12 no-padding">
                            <div class="col-sm-7">

                            </div>

                            <div class="col-sm-5 no-padding pull-right" style="font-size: 10pt;">
                                <div class="col-sm-12 no-padding text-center">
                                    ធ្វើនៅភ្នំពេញ ថ្ងៃទី ....... ខែ ............ ឆ្នាំ ២០
                                </div>
                                <div class="col-sm-12 no-padding text-center">
                                    នាយកវិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា
                                </div>
                            </div>
                        </div>


                    <?php
                            } else {
                                $check = true;
                            }
                        }
                    ?>


                @endif
                <div class="footer">
                    <hr/>
                    <span><?php  echo date("l, F Y");?></span>
                    <span class="pull-right">Page {{$page_number}} of {{$total_page}}</span>
                </div>
            </div>
            <?php $page_number++; ?>
        @endforeach


        @if($check==true)

            <div class="page">

                <div class ="col-sm-12 no-padding" style="font-size: 10pt;margin-top: 20px">
                    បញ្ឈប់បញ្ជីត្រឹម {{$i}} នាក់ ក្នុងនោះមានស្រី {{$female}} នាក់ ។
                </div>
                <div class="col-sm-12 no-padding">
                    <div class="col-sm-7">

                    </div>

                    <div class="col-sm-5 no-padding pull-right" style="font-size: 10pt;">
                        <div class="col-sm-12 no-padding text-center">
                            ធ្វើនៅភ្នំពេញ ថ្ងៃទី ....... ខែ ............ ឆ្នាំ ២០
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

    @endif


    @if($allStudentByDept)

        <?php   $page_number = 1;
        $total_page = count($allStudentByDept);
        ?>

        @foreach($allDepts as $dept)

            <?php

                $arrayCands = array_chunk($allStudentByDept[$dept], 27);

            ?>
            @foreach($arrayCands as $candidatesResult)
                <?php $check=0;?>
                <div class="page">
                    <div class="col-sm-12" style="text-align: center; margin-bottom: 15px">
                        <h2>{{$title}}</h2>
                        <h4>ការជ្រើសរើសចូលរៀនឆ្នាំទី១​​​​​ ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម</h4>
                        <h3>ដេប៉ាតឺម៉ង់: <strong>{{$dept}} </strong> ឆ្នាំសិក្សា:   @foreach($candidatesResult as $result)  @if($check==0) {{$result->academic_year}} <?php $check++;?> @endif @endforeach </h3>
                    </div>

                    <table class="" width="100%">
                        <tr>
                            <th>ល.រ</th>
                            <th>អត្តលេខ</th>
                            <th>ឈ្មោះខ្មែរ</th>
                            <th>ឈ្មោះឡាតាំង</th>
                            <th>ភេទ</th>
                            <th>ថ្ងៃខែឆ្នាំកំនើត</th>
                            <th>ផ្សេងៗ</th>
                        </tr>
                        <?php $i =0;?>
                        @foreach($candidatesResult as $result)
                            <?php $i++;?>
                            <tr>
                                <td><?php echo str_pad($i, 3, '0', STR_PAD_LEFT);?></td>
                                <td><?php echo str_pad($result->register_id, 3, '0', STR_PAD_LEFT);?></td>
                                <td>{{$result->name_kh}}</td>
                                <td>{{$result->name_latin}}</td>
                                <td>{{$result->gender}}</td>
                                <td> <?php $date = explode(' ', $result->birth_date);  $time = strtotime($date[0]); $bDate = date('d/m/Y',$time); echo $bDate;?></td>
                                <td>  </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="footer">
                        <hr/>
                        <span><?php  echo  date("l, F Y");?></span>
                        <span class="pull-right">Page {{$page_number}} of {{$total_page}}</span>
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
