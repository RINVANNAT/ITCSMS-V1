@extends ('backend.layouts.popup_master')

@section ('title', 'Template Print Timetable | Timetable Management')

@section('after-styles-end')

    {!! Html::style('plugins/iCheck/all.css') !!}
    {!! Html::style('plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/backend/schedule/timetable.css') !!}
    {!! Html::style('css/backend/schedule/print.css') !!}

@stop

@section('content')

    @if(isset($timetables))
        @foreach($timetables as $timetable)
            <div class="row">
                <table class="timetable">
                    <thead>
                    <tr style="border: none !important; margin-bottom: 200px !important;">
                        <th rowspan="3" style="text-align: center;border: none !important;">
                            <img src="{{ asset('img/timetable/logo-print.jpg') }}" class="image-logo"/>
                        </th>
                        <th colspan="5" rowspan="2"
                            style="border: none !important;text-align: center;line-height: 1.5;">
                            {{ trans('labels.backend.schedule.timetable.popup_print_template.title') }} {{ $timetable->academicYear->name_latin }}
                            <br/>
                            {{ trans('labels.backend.schedule.timetable.popup_print_template.group') }}
                            : {{ $timetable->degree->code }}{{ $timetable->grade->code }}@if($timetable->group != null)
                                ({{ $timetable->group->code }}) @endif-{{ $timetable->department->code }}
                        </th>
                        <th rowspan="2"
                            style="line-height: 1.5;border: none !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.semester') }}
                            - @if($timetable->semester->id==1) I @else II @endif
                            <br/>{{ trans('labels.backend.schedule.timetable.popup_print_template.week') }} {{ $timetable->week->id }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr align="center" style="height: 30px !important;">
                        <td style="font-weight: bold;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.hours') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.monday') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.tuesday') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.wednesday') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.thursday') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.friday') }}</td>
                        <td style="font-weight: bold; width: 15% !important;">{{ trans('labels.backend.schedule.timetable.popup_print_template.days.saturday') }}</td>
                    </tr>
                    {{--7 to 8--}}
                    {{--@php echo "<pre>"; var_dump($timetable->timetableSlots); echo "</pre>"; @endphp--}}
                    <tr>
                        <td align="center" valign="middle">07h00 - 07h55</td>

                        @for($i=2; $i<=7; $i++)
                            @php $tmp = true; @endphp
                            @foreach($timetable->timetableSlots as $timetableSlot)
                                @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                    @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 7)
                                        <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot->end))->hour - (new \Carbon\Carbon($timetableSlot->start))->hour) }}"
                                            style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                    {{--8 to 9--}}
                    <tr>
                        <td align="center" valign="middle">08h00 - 08h55</td>

                        @for($i=2; $i<=7; $i++)
                            @php $tmp = true; @endphp
                            @foreach($timetable->timetableSlots as $timetableSlot)
                                @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if(($start==7 && ($end==9 || $end==10 || $end==11)))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==8 && ($end==9 || $end==10 || $end==11)))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if(($start==7 && ($end==10 || $end==11)) || ($start==8 && ($end==10 || $end==11)))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==9 && ($end==10 || $end==11)))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp
                                    @if(($start==7 && $end==11) || ($start==8 && $end==11) || ($start==9 && $end==11))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==10 && $end==11))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                    <tr style="height: 30px !important;">
                        <td colspan="7"></td>
                    </tr>
                    {{--1 to 2--}}
                    <tr>
                        <td align="center" valign="middle">13h00 - 14h55</td>

                        @for($i=2; $i<=7; $i++)
                            @php $tmp = true; @endphp
                            @foreach($timetable->timetableSlots as $timetableSlot)
                                @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 13)
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                        <td align="center" valign="middle">14h00 - 15h55</td>

                        @for($i=2; $i<=7; $i++)
                            @php $tmp = true; @endphp
                            @foreach($timetable->timetableSlots as $timetableSlot)
                                @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if(($start==13 && ($end==15 || $end==16 || $end==17)))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==14 && ($end==15 || $end==16 || $end==17)))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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

                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if(($start==13 && ($end==16 || $end==17)) || ($start==14 && ($end==16 || $end==17)))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==15 && ($end==16 || $end==17)))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
                        <td align="center" valign="middle">16h10 - 17h:05</td>

                        @for($i=2; $i<=7; $i++)
                            @php $tmp = true; @endphp
                            @foreach($timetable->timetableSlots as $timetableSlot)
                                @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                    @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                    @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                    @if(($start==13 && $end==17) || ($start==14 && $end==17) || ($start==15 && $end==17))
                                        @php $tmp = false; @endphp
                                        @continue
                                    @elseif(($start==16 && $end==17))
                                        <td rowspan="{{ ($end-$start) }}" style="position: relative !important;">
                                            <p style="text-align: right;">{{ $timetableSlot->type }}</p>
                                            <p style="text-align: center; font-weight: bold;">{{ $timetableSlot->course_name }}</p>
                                            <p style="text-align: center;">{{ $timetableSlot->teacher_name }}</p>
                                            <p style="text-align: right;">@if($timetableSlot->room != null) {{ $timetableSlot->room->name }}
                                                -{{ $timetableSlot->room->building->code }} @else NULL @endif</p>
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
        @endforeach
    @endif

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}

@stop

