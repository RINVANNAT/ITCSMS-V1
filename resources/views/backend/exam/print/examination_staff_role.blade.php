
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

    <?php   $page_number = 1;
    $total_page = count($roles);
    ?>
    @foreach($roles as $role)

        <div class="page">
            <h2>{{$role->name}}</h2>


            <table class="table" width="100%">
                <tr>
                    <th>Name</th>
                    <th>ROOMS</th>
                </tr>
                    <tr>
                        <td>{{$role->staff_role['text']}}</td>
                        <td>
                            @foreach($role->room as $room)
                                <li>{{$room}}</li>
                            @endforeach
                        </td>
                    </tr>
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
