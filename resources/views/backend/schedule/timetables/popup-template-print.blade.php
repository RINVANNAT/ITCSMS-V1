@extends ('backend.layouts.popup_master')

@section ('title', 'Template Print Timetable | Timetable Management')

@section('after-styles-end')

    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}

    <style type="text/css">
        body {
            line-height: 0.8 !important;
        }

        .content-wrapper {
            background-image: none !important;
            background-color: #fff;
        }

        * {
            box-sizing: border-box;
        }

        .row {
            page-break-after: always;
        }

        table.timetable {
            width: 100%;
        }

        table.timetable tr td {
            margin: 0px !important;
            border: 1px solid #c7c7c7;
            border-collapse: collapse;
        }

        table.timetable td {
            /*padding-top: 2px !important;
            padding-bottom: 2px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;*/
        }

        table.timetable th {
            padding: 10px !important;
        }

        table.timetable p, table.timetable tr td {
            font-size: 14px !important;
            margin: 8px !important;
            padding: 0 !important;
            line-height: 1 !important;
            height: 15px !important;
        }

        th {
            height: 50px !important;
        }

        img.image-logo {
            margin: 0px;
            padding: 0px !important;
            width: 50px;
            height: 50px;
            text-align: left !important;
            float: left;
        }

        @media print {
            .row {
                page-break-after: always !important;
            }

            .row:last-child {
                page-break-after: auto !important;
            }

            table.timetable tr td {
                border: 0.1px solid #c7c7c7;
            }
        }
    </style>
@stop

@section('content')

    @if(isset($timetables))
        @foreach($timetables as $timetable)
            <div class="row">
                <div class="col-md-12">
                    <table class="timetable">
                        <thead>
                        <tr style="border: none !important; margin-bottom: 200px !important;">
                            <th rowspan="3" style="text-align: center;border: none !important;">
                                <img src="{{ asset('img/timetable/logo-print.jpg') }}" class="image-logo"/>
                            </th>
                            <th colspan="5" rowspan="2"
                                style="border: none !important;text-align: center;line-height: 1.5;">
                                EMPLOI DU TEMPS {{ $timetable->academicYear->name_latin }}<br/>
                                Groupe: {{ $timetable->degree->code }}{{ $timetable->grade->code }}@if($timetable->group != null)
                                    ({{ $timetable->group->code }}) @endif-{{ $timetable->department->code }}
                            </th>
                            <th rowspan="2" style="line-height: 1.5;border: none !important;">Semestre
                                - @if($timetable->semester->id==1) I @else II @endif
                                <br/>Semaine {{ $timetable->week->id }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr align="center" style="height: 30px !important;">
                            <td style="font-weight: bold;">Horaire</td>
                            <td style="font-weight: bold; width: 15% !important;">Lundi</td>
                            <td style="font-weight: bold; width: 15% !important;">Mardi</td>
                            <td style="font-weight: bold; width: 15% !important;">Mercredi</td>
                            <td style="font-weight: bold; width: 15% !important;">Jeudi</td>
                            <td style="font-weight: bold; width: 15% !important;">Vendredi</td>
                            <td style="font-weight: bold; width: 15% !important;">Samedi</td>
                        </tr>
                        {{--7 to 8--}}
                        {{--@php echo "<pre>"; var_dump($timetable->timetableSlots); echo "</pre>"; @endphp--}}
                        <tr>
                            <td align="center" valign="middle">07h00 - 07h55</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        {{--Case 01--}}
                                        @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 7 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 8 )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        {{--Case O2--}}
                                        @elseif( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 7 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 9)
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        {{-- 8 to 9--}}
                        <tr>
                            <td align="center" valign="middle">08h00 - 08h55</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 8 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 9 )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(((new \Carbon\Carbon($timetableSlot->start))->hour) == 7 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 9)
                                            @php $tmp = false; @endphp
                                            @continue
                                        @elseif( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 8 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 10)
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>

                        {{--9 to 10--}}
                        <tr>
                            <td align="center" valign="middle">09h10 - 10h05</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if(((new \Carbon\Carbon($timetableSlot->start))->hour) == 8 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 10)
                                            @php $tmp = false; @endphp
                                            @continue
                                        @elseif( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 9 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 10 )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 9 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 11)
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        {{--10 to 11--}}
                        <tr>
                            <td align="center" valign="middle">10h10 - 10h:05</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) >= 10 &&  ((new \Carbon\Carbon($timetableSlot->end))->hour == 11))
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(((new \Carbon\Carbon($timetableSlot->start))->hour) == 9 && ((new \Carbon\Carbon($timetableSlot->end))->hour) == 11)
                                            @php $tmp = false; @endphp
                                            @continue
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        <tr style="height: 30px !important;">
                            <td colspan="7"></td>
                        </tr>
                        {{--1 to 2--}}
                        <tr>
                            <td align="center" valign="middle">13h00 - 13h55</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( (new \Carbon\Carbon($timetableSlot->start))->hour == 13 && (new \Carbon\Carbon($timetableSlot->end))->hour == 14 )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 13 && (new \Carbon\Carbon($timetableSlot->end))->hour == 15 )
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        {{--2 to 3--}}
                        <tr>
                            <td align="center" valign="middle">14h00 - 14h55</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( (new \Carbon\Carbon($timetableSlot->start))->hour == 13 && (new \Carbon\Carbon($timetableSlot->end))->hour == 15 )
                                            @php $tmp = false; @endphp
                                            @continue
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 14 && (new \Carbon\Carbon($timetableSlot->end))->hour == 15 )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 14 && (new \Carbon\Carbon($timetableSlot->end))->hour == 16 )
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        {{--3 to 4--}}
                        <tr>
                            <td align="center" valign="middle">15h10 - 16h05</td>

                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( (new \Carbon\Carbon($timetableSlot->start))->hour == 14 && (new \Carbon\Carbon($timetableSlot->end))->hour == 16 )
                                            @php $tmp = false; @endphp
                                            @continue
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 15 && (new \Carbon\Carbon($timetableSlot->end))->hour == 16  )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 15 && (new \Carbon\Carbon($timetableSlot->end))->hour == 17 )
                                            <td rowspan="2" style="position: relative !important;">
                                                <p style="text-align: right;position: absolute;top: 0; right: 0;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right;position: absolute;bottom: 0; right: 0;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor

                        </tr>
                        {{--4 to 5--}}
                        <tr>
                            <td align="center" valign="middle">16h10 - 17h05</td>
                            @for($i=2; $i<=7; $i++)
                                @php $tmp = true; @endphp
                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                        @if( (new \Carbon\Carbon($timetableSlot->start))->hour == 15 && (new \Carbon\Carbon($timetableSlot->end))->hour == 17 )
                                            @php $tmp = false; @endphp
                                            @continue
                                        @elseif( (new \Carbon\Carbon($timetableSlot->start))->hour == 16 && (new \Carbon\Carbon($timetableSlot->end))->hour == 17  )
                                            <td>
                                                <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                                <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                                <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                                <p style="text-align: right">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}-{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td style="height: 60px !important;"></td>
                                @endif
                            @endfor
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}
@stop

