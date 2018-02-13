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
            <page size="A4" class="each-page">
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
                    @if(!has_half_hour($timetable))
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
                        @if($timetable->degree->id != 2)
                            {{--7 to 8--}}
                            <tr>
                                <td align="center" valign="middle">07h00 - 07h55</td>

                                @for($i=2; $i<=7; $i++)
                                    @php $tmp = true; @endphp

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @if( ((new \Carbon\Carbon($timetableSlot['start']))->hour) == 7)
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold">{{ $timetableSlot['course_name'] }}</div>
                                                    <div class="lang-info">
                                                        @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                            @if($key % 2 !== 0)
                                                                <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                    ({{ $item['building'] }}-{{$item['room']}})
                                                                </div>
                                                            @else
                                                                <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                    ({{ $item['building'] }}-{{$item['room']}})
                                                                </div>
                                                            @endif
                                                            <div class="clearfix"></div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 7)
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot->end))->hour - (new \Carbon\Carbon($timetableSlot->start))->hour) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ?  smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start==7 && ($end==9 || $end==10 || $end==11)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==8 && ($end==9 || $end==10 || $end==11)))
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach


                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start==7 && ($end==9 || $end==10 || $end==11)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==8 && ($end==9 || $end==10 || $end==11)))

                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot->end))->hour - (new \Carbon\Carbon($timetableSlot->start))->hour) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ?  smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start==7 && ($end==10 || $end==11)) || ($start==8 && ($end==10 || $end==11)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==9 && ($end==10 || $end==11)))
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start==7 && ($end==10 || $end==11)) || ($start==8 && ($end==10 || $end==11)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==9 && ($end==10 || $end==11)))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp
                                            @if(($start==7 && $end==11) || ($start==8 && $end==11) || ($start==9 && $end==11))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==10 && $end==11))
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp
                                            @if(($start==7 && $end==11) || ($start==8 && $end==11) || ($start==9 && $end==11))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==10 && $end==11))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if( $start == 13)
                                                <td rowspan="{{ $end-$start }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) == 13)
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start==13 && ($end==15 || $end==16 || $end==17)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==14 && ($end==15 || $end==16 || $end==17)))
                                                <td rowspan="{{ $end-$start }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start==13 && ($end==15 || $end==16 || $end==17)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==14 && ($end==15 || $end==16 || $end==17)))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start==13 && ($end==16 || $end==17)) || ($start==14 && ($end==16 || $end==17)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==15 && ($end==16 || $end==17)))
                                                <td rowspan="{{ $end-$start }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif

                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start==13 && ($end==16 || $end==17)) || ($start==14 && ($end==16 || $end==17)))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==15 && ($end==16 || $end==17)))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start==13 && $end==17) || ($start==14 && $end==17) || ($start==15 && $end==17))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==16 && $end==17))
                                                <td rowspan="{{ $end-$start }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start==13 && $end==17) || ($start==14 && $end==17) || ($start==15 && $end==17))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==16 && $end==17))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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
                        @else
                            {{--17 to 18--}}
                            <tr>
                                <td align="center" valign="middle">17h30 - 18h05</td>

                                @for($i=2; $i<=7; $i++)
                                    @php $tmp = true; @endphp

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @if( ((new \Carbon\Carbon($timetableSlot['start']))->hour) >= 17)
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    <div class="lang-info">
                                                        @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                            @if($key % 2 !== 0)
                                                                <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                    ({{ $item['building'] }}-{{$item['room']}})
                                                                </div>
                                                            @else
                                                                <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                    ({{ $item['building'] }}-{{$item['room']}})
                                                                </div>
                                                            @endif
                                                            <div class="clearfix"></div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )
                                            @if( ((new \Carbon\Carbon($timetableSlot->start))->hour) >= 7)
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot->end))->hour - (new \Carbon\Carbon($timetableSlot->start))->hour) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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
                            {{--18 to 19--}}
                            <tr>
                                <td align="center" valign="middle">18h10 - 19h05</td>

                                @for($i=2; $i<=7; $i++)
                                    @php $tmp = true; @endphp

                                    @foreach($timetablesSlotsLang as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                            @php $start = ((new \Carbon\Carbon($timetableSlot['start']))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot['end']))->hour); @endphp

                                            @if(($start >= 17 && $end == 19))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==18 && $end==19))
                                                <td rowspan="{{ ( (new \Carbon\Carbon($timetableSlot['end']))->hour - (new \Carbon\Carbon($timetableSlot['start']))->hour) }}">
                                                    <div class="col-md-12 text-center text-bold"
                                                         style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @php $tmp = false; @endphp
                                                @break
                                            @endif
                                        @endif
                                    @endforeach


                                    @foreach($timetable->timetableSlots as $timetableSlot)
                                        @if( $i == ((new \Carbon\Carbon($timetableSlot->start))->day) )

                                            @php $start = ((new \Carbon\Carbon($timetableSlot->start))->hour); @endphp
                                            @php $end = ((new \Carbon\Carbon($timetableSlot->end))->hour); @endphp

                                            @if(($start>=17 && $end ==19))
                                                @php $tmp = false; @endphp
                                                @continue
                                            @elseif(($start==17 && $end==19))
                                                <td rowspan="{{ ($end-$start) }}">
                                                    <div class="course_type">{{ $timetableSlot->type }}</div>
                                                    <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                    <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                    <div class="room_name">
                                                        {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A'}}
                                                    </div>
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
                        @endif

                        </tbody>
                    @else
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

                        {{--7 - 7:30--}}
                        <tr>
                            <td align="center" valign="middle">07h00 - 07h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp
                                        @if(get_date_str($start) == '7')
                                            <td rowspan="{{ get_rowspan($start,$end) }}">
                                                <div class="course_name">{{ $timetableSlot['course_name'] }}</div>
                                                <div class="lang-info">
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                        <div class="clearfix"></div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(get_date_str($start) == '7')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">

                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>

                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--7:30 8--}}
                        <tr>
                            <td align="center" valign="middle">07h30 - 08h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(get_date_str($start) == '7' && (get_date_str($end) == '8' || get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '7:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(get_date_str($start) == '7' && (get_date_str($end) == '8' || get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '7:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--8 - 8:30--}}
                        <tr>
                            <td align="center" valign="middle">08h00 - 08h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '8')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif

                                        @if(get_date_str($start) == '8')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '8:30' || get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '8')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--8:30 - 9--}}
                        <tr>
                            <td align="center" valign="middle">08h30 - 09h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '8:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9' || get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '8:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--9 - 9:30--}}
                        <tr>
                            <td align="center" valign="middle">09h00 - 09h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '9')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '9:30' || get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '9')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--9:30 - 10--}}
                        <tr>
                            <td align="center" valign="middle">09h30 - 10h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '9:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10' || get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '9:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--10 - 10:30--}}
                        <tr>
                            <td align="center" valign="middle">10h00 - 10h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                             (get_date_str($start) == '7' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '10')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '7:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '8:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            ||
                                            (get_date_str($start) == '9:30' && (get_date_str($end) == '10:30' || get_date_str($end) == '11'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '10')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--10:30 - 11--}}
                        <tr>
                            <td align="center" valign="middle">10h30 - 11h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '7:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '10' && get_date_str($end) == '11')
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '10:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '7' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '7:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '10' && get_date_str($end) == '11')
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '10:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>

                        <tr>
                            <td colspan="7" style="height: 30px;"></td>
                        </tr>
                        {{--13 - 13:30--}}
                        <tr>
                            <td align="center" valign="middle">13h00 - 13h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp
                                        @if(get_date_str($start) == '13')
                                            <td rowspan="{{ get_rowspan($start,$end) }}">
                                                <div class="course_name">{{ $timetableSlot['course_name'] }}</div>
                                                <div class="lang-info">
                                                    @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                        @if($key % 2 !== 0)
                                                            <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @else
                                                            <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                                ({{ $item['building'] }}-{{$item['room']}})
                                                            </div>
                                                        @endif
                                                        <div class="clearfix"></div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '7' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '7:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '8:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '9:30' && get_date_str($end) == '11')
                                            ||
                                            (get_date_str($start) == '10' && get_date_str($end) == '11')
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '10:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(get_date_str($start) == '13')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--13:30 - 14--}}
                        <tr>
                            <td align="center" valign="middle">13h30 - 14h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(get_date_str($start) == '13' && (get_date_str($end) == '14' || get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            @php $tmp = false; @endphp
                                            @break
                                        @elseif(get_date_str($start) == '13:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(get_date_str($start) == '13' && (get_date_str($end) == '14' || get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '13:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--14:30 - 15--}}
                        <tr>
                            <td align="center" valign="middle">14h00 - 14h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '14')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '14:30' || get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '14')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--15:30 - 16--}}
                        <tr>
                            <td align="center" valign="middle">14h30 - 15h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '14:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15' || get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '14:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--16:30 - 17--}}
                        <tr>
                            <td align="center" valign="middle">15h00 - 15h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '15')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '15:30' || get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '15')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--17:30 - 18--}}
                        <tr>
                            <td align="center" valign="middle">15h30 - 16h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '15:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16' || get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '15:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--18:30 - 19--}}
                        <tr>
                            <td align="center" valign="middle">16h00 - 16h30</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp

                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif

                                        @if(get_date_str($start) == '16')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '13:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '14:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            ||
                                            (get_date_str($start) == '15:30' && (get_date_str($end) == '16:30' || get_date_str($end) == '17'))
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '16')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        {{--19:30 - 20--}}
                        <tr>
                            <td align="center" valign="middle">16h30 - 17h00</td>
                            @for($day = 2; $day <= 7; $day++)
                                @php $tmp = true; @endphp
                                @foreach($timetablesSlotsLang as $timetableSlot)
                                    @if( $day == ((new \Carbon\Carbon($timetableSlot['start']))->day) )
                                        @php
                                            $start = $timetableSlot['start'];
                                            $end = $timetableSlot['end'];
                                        @endphp

                                        @if(
                                            (get_date_str($start) == '13' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '13:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '16' && get_date_str($end) == '11')
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif

                                        @if(get_date_str($start) == '16:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="col-md-12 text-center text-bold"
                                                     style="margin-bottom: 10px;">{{ $timetableSlot['course_name'] }}</div>
                                                @foreach($timetableSlot['slotsForLanguage'] as $key => $item)
                                                    @if($key % 2 !== 0)
                                                        <div class="lang-info-right">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @else
                                                        <div class="lang-info-left">Gr: {{ $item['group'] }}
                                                            ({{ $item['building'] }}-{{$item['room']}})
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                    @endif
                                @endforeach

                                @foreach($timetable->timetableSlots as $timetableSlot)
                                    @php
                                        $start = $timetableSlot->start;
                                        $end = $timetableSlot->end;
                                    @endphp
                                    @if((new \Carbon\Carbon($timetableSlot->start))->day == $day)
                                        @if(
                                            (get_date_str($start) == '13' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '13:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '14:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '15:30' && get_date_str($end) == '17')
                                            ||
                                            (get_date_str($start) == '16' && get_date_str($end) == '11')
                                            )
                                            @php $tmp = false; @endphp
                                            @break
                                        @endif
                                        @if(get_date_str($start) == '16:30')
                                            <td rowspan="{{ get_rowspan($start, $end) }}">
                                                <div class="course_type">{{ $timetableSlot->type }}</div>
                                                <div class="course_name">{{ smis_str_limit($timetableSlot->course_name, 20) }}</div>
                                                <div class="teacher_name">{{ $timetableSlot->employee->name_latin }}</div>
                                                <div class="room_name">
                                                    {{ $timetableSlot->room != null ? smis_concat_str($timetableSlot->room->name, $timetableSlot->room->building->code) : 'N/A' }}
                                                </div>
                                            </td>
                                            @php $tmp = false; @endphp
                                            @break
                                        @else
                                            @php $tmp = true; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if($tmp)
                                    <td></td>
                                @endif
                            @endfor
                        </tr>

                        </tbody>
                    @endif
                </table>
            </page>
        @endforeach
    @endif
@stop

@section('after-scripts-end')

    {!! Html::script('plugins/iCheck/icheck.js') !!}
    {!! Html::script('js/backend/schedule/clone-timetable.js') !!}
    {!! Html::script('js/backend/schedule/timetable-print.js') !!}

    <script type="text/javascript">
        $(function () {
            window.print();
        })
    </script>
@stop