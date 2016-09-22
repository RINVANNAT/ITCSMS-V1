
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
        <?php   $page_number = 1;
        $total_page = count($candidateDUTs);
        ?>

        @foreach($candidateDUTs as $candidatesResult)

            <div class="page">

                @if($page_number ==1)
                    <div class="col-sm-12" style="text-align: center; margin-bottom: 15px">
                        <h2>{{$title}}</h2>
                        <h4>ការជ្រើសរើសចូលរៀនឆ្នាំទី១​​​​​ ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម</h4>
                        <h3>ឆ្នាំសិក្សា: 2015-2016 </h3>
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
                    <?php $i =0;?>
                    @foreach($candidatesResult as $result)
                        <?php $i++;?>
                        <tr>
                            <td><?php echo str_pad($i, 4, '0', STR_PAD_LEFT);?></td>
                            <td><?php echo str_pad($result->register_id, 4, '0', STR_PAD_LEFT);?></td>
                            <td >{{$result->name_kh}}</td>
                            <td>{{$result->name_latin}}</td>
                            <td>{{$result->gender}}</td>
                            <td> <?php $date = explode(' ', $result->birth_date);  $time = strtotime($date[0]); $bDate = date('d/m/Y',$time); echo $bDate;?></td>
                            <td>{{$result->home_town}}</td>
                            <td>{{$result->department_name}}</td>

                        </tr>
                    @endforeach
                </table>
                <div class="footer">
                    <hr/>
                    <span><?php  echo date("l, F Y");?></span>
                    <span class="pull-right">Page {{$page_number}} of {{$total_page}}</span>
                </div>
            </div>
            <?php $page_number++; ?>
        @endforeach

    @endif


    @if($allStudentByDept)

        <?php   $page_number = 1;
        $total_page = count($allStudentByDept);
        ?>

        @foreach($allStudentByDept as $key=> $candidatesResult)

            <div class="page">


                    <div class="col-sm-12" style="text-align: center; margin-bottom: 15px">
                        <h2>{{$title}}</h2>
                        <h4>ការជ្រើសរើសចូលរៀនឆ្នាំទី១​​​​​ ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម</h4>
                        <h3>ដេប៉ាតឺម៉ង់: {{$key}} </h3>
                    </div>


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
                            <td>{{$result->home_town}}</td>
                            <td>{{$result->department_name}}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="footer">
                    <hr/>
                    <span><?php $date=date('now'); echo $date;?></span>
                    <span class="pull-right">Page {{$page_number}} of {{$total_page}}</span>
                </div>
            </div>
            <?php $page_number++; ?>
        @endforeach

    @endif




@endsection

@section('scripts')
    <script>

    </script>
@stop
