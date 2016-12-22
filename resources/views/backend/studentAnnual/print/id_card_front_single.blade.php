<div class="page1">
    <div class="background">
        <img width="100%" src="{{url('img/id_card/front_id_card.png')}}">
    </div>
    <div class="detail">
        {{--<span class="name_en">ENG RATANA</span>--}}
        {{--<span class="name_kh">អេង រតនា</span>--}}
        <span class="department" >
        ដេប៉ាតឺម៉ង់ {{isset($front->department)?$front->department:""}}
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