<div class="page1">
    <div class="background">
        <img width="100%" src="{{url('img/id_card/front_id_card.png')}}">
    </div>
    <div class="detail">
        {{--<span class="name_en">ENG RATANA</span>--}}
        {{--<span class="name_kh">អេង រតនា</span>--}}
        <span class="department" >
            @if($front->degree_id != 5)
                @if($front->department_id == 4 || $front->department_id == 5)
                ដេប៉ាតឺម៉ង់ {{isset($front->department)?$front->department:""}}
                @else
                មហាវិទ្យាល័យ
                    @if($front->department_id == 1)
                        មហាវិទ្យាល័យគីមីឧស្សាហកម្ម
                    @elseif($front->department_id == 2)
                        មហាវិទ្យាល័យសំណង់
                    @elseif($front->department_id == 3)
                        មហាវិទ្យាល័យបច្ចេកទេសអគ្គិសនី
                    @elseif($front->department_id == 6)
                        មហាវិទ្យាល័យវារីសាស្ត្រ
                    @elseif($front->department_id == 7)
                        មហាវិទ្យាល័យរ៉ែនិងភូគព្ភសាស្ត្រ
                    @endif
                @endif
            @else
                {{isset($front->department)?$front->department:""}}
            @endif

        </span>
        <span class="id_card">អត្តលេខនិស្សិត/ID : <strong>{{isset($front->id_card)?$front->id_card:""}}</strong></span>
        <div class="avatar">
            <div class="crop">
                <img src="{{$smis_server->value}}/img/profiles/{{isset($front->photo)?$front->photo:"avatar.png"}}">
            </div>
        </div>

        <span class="name_kh">{{isset($front->name_kh)?$front->name_kh:""}}</span>
        @if(strlen(isset($front->name_latin)?$front->name_latin:"") < 25)
            <span class="name_latin">{{strtoupper(isset($front->name_latin)?$front->name_latin:"")}}</span>
        @else
            <span class="name_latin" style="font-size: 13px !important;">{{strtoupper(isset($front->name_latin)?$front->name_latin:"")}}</span>
        @endif
    </div>

</div>