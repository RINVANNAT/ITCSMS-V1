
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
            text-align: center;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }
    </style>
@stop
@section('content')


@if($status)
    <?php   $page_number = 1;
    $total_page = count($candidatesResults);
    ?>

    @foreach($candidatesResults as $candidatesResult)

        <div class="page">
            <h2>Result of Standadize Testing Exam 2016-2017</h2>

            <table class="table" width="100%">
                <tr>
                    <th>Order</th>
                    <th>Khmer</th>
                    <th>Latin</th>
                    <th>Result</th>
                    <th>Score</th>
                </tr>
                <?php $i =0;?>
                @foreach($candidatesResult as $result)
                    <?php $i++;?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td>{{$result->name_kh}}</td>
                        <td>{{$result->name_latin}}</td>
                        <td>{{$result->result}}</td>
                        <td>{{$result->total_score}}</td>
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
