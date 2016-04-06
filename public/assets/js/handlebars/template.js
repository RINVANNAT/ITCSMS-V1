/**
 * Created by snakecc on 11/18/15.
 */

MYTEMPLATE = {};
MYTEMPLATE["entry"] = ' <h1>{{firstName}} {{lastName}}</h1>Blog: {{blogURL}}';
MYTEMPLATE["fillter"] ={};

MYTEMPLATE["fillter"]["option"] =  '<div> <div class="option"> '+
    '<span>{{filltername}} :</span>'+
    '<span class="optionitem"  filltername="{{filltername}}" optionname="{{optionname}}" optionid="{{optionid}}" manuleselected="0"> {{optionname}} </span>'+
    '<span class="optionitem"  filltername="{{filltername}}" optionname="{{optionname}}" optionid="{{optionid}}" manuleselected="0"> {{optionname}} </span>'+
'</div>';

MYTEMPLATE["fillter"]["option2"] =  '<div class="groupselector">'+
    '<div id="swapper">'+
        '{{#fillters}}'+
            '<div class="option"> '+
            '<span>{{filltername}} :</span>'+
                '{{#options}}'+
                    '<span class="optionitem"  fillerdbname="{{fillerdbname}}" optionid="{{id}}" manuleselected="0"> {{code}} </span>'+
                '{{/options}}'+
            '</div>'+
        '{{/fillters}}'+
    '</div><div id="groupselectorcontainerlong"> </div></div>';
MYTEMPLATE["fillter"]["longtemplate"] =  ''+
    '<div id="swapper">'+
    '<div class="optionlong"> '+
    '<span>{{filltername}} :</span>'+
    '{{#options}}'+
        '{{#name}}'+
            '<span class="optionlongitem"  fillerdbname="{{fillerdbname}}" optionid="{{id}}" manuleselected="0"> {{name}} </span>'+
        '{{/name}}'+
        '{{^name}}'+
        '<span class="optionlongitem"  fillerdbname="{{fillerdbname}}" optionid="{{id}}" manuleselected="0">  {{code}} </span>'+
        '{{/name}}'+

    '{{/options}}'+
    '{{^options}}'+
    '<span class="optionlongitem"  fillerdbname="{{fillerdbname}}" optionid="{{id}}" manuleselected="0"> Empty </span>'+
    '{{/options}}'+

    '</div>'+
    '</div>';
