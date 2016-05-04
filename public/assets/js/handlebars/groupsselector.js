/**
 * Created by snakecc on 11/19/15.
 */
if (typeof SMSFILER !== 'object') {
    SMSFILER = {};
}
(function () {
    SMSFILER.model = SMSFILER.model || {
            fillterModelUrl: ["url","url",],
            fillers:[
            ],
            selectedIndex: {
            },
            fillerstest:[],
            redirect:{},
            fillterShowHide : true,
        };
    SMSFILER.view = SMSFILER.view  || {
            draw: function(){
                $("#groupselectorcontainer").html('');
            },
            template_filter : ""
        };
    SMSFILER.beforeDraw  = function(){
        // move some field in before display it.
        console.log("in the group selectore");
        console.log(SMSFILER.model.fillers);
        $.each(SMSFILER.model.fillers, function(key,filllter){
            if ( SMSFILER.model.fillers[key]["filltername"] == "Departments"){
                //SMSFILER.model.fillers[key]["filltername"]

               keyOptionToRemove = [];
                optionToKeep = [];
                $.each( SMSFILER.model.fillers[key]["options"] , function(key2,filllter2){

                    codeName = filllter2["code"];
                    console.log(codeName);
                    if ( (codeName == "Study Office") || (codeName == "Finance") || (codeName =="Academic" )){
                        keyOptionToRemove.push(key2);
                    }else{
                        optionToKeep.push(filllter2);
                    }

                });
                SMSFILER.model.fillers[key]["options"] = optionToKeep;

                console.log("this is keys to remove");
                console.log(keyOptionToRemove);

                //$.each( keyOptionToRemove, function(value){
                //    $.each( SMSFILER.model.fillers[key]["options"], function(value){
                //
                //
                //        SMSFILER.model.fillers[key]["options"].splice(value, 1);
                //    });
                //});


            }


        });

    };
    SMSFILER.view.draw = function(){
        SMSFILER.beforeDraw();
        var template2 = MYTEMPLATE["fillter"]["option2"];
        var fillterhtml = Mustache.to_html(template2, {fillters:SMSFILER.model.fillers});
        $('#groupselectorcontainer').html(fillterhtml);
    };
    SMSFILER.callback = function() {
    };
    SMSFILER.debug = function() {
        console.log(SMSFILER.model);
    };
    SMSFILER.isAllRequireFillterSelected = function(){
        var selectedfillterlength = Object.keys(SMSFILER.model.selectedIndex).length;
        var requireselectedfilter = 3;
        var result = false;
        if (selectedfillterlength >= requireselectedfilter ){
            result = true;
            SMSFILER.callback(SMSFILER.model.selectedIndex);
        }
        return result;
    };

    SMSFILER.getFillterData = function (){
        var ajaxdone = [];
        $.each(SMSFILER.model.fillterModelUrl, function(key,url){
            var request = $.get(url, function( data ) {
                var sURLVariables = url.split('/');
                var filltername = sURLVariables[sURLVariables.length-1];
                filltername = filltername.charAt(0).toUpperCase() + filltername.slice(1);
                var fillerdbname = sURLVariables[sURLVariables.length-1].slice(0, -1)+"_id";

                SMSFILER.model.fillers.push({filltername:filltername,fillerdbname:fillerdbname, options:data.data});
            }).fail(function() {
                // controller not found
                //
            });
            ajaxdone.push(request);
        });
        $.when.apply($, ajaxdone).always(function() {
            SMSFILER.view.draw();
            if (Object.keys(SMSFILER.model.redirect).length > 0  ){
                $.each(SMSFILER.model.redirect, function(key,value){
                    $(".optionitem[fillerdbname='"+key+"'][optionid='"+value+"']").attr("manuleselected","1");
                });
                SMSFILER.callback(SMSFILER.model.redirect);
            }
        });

    };
    SMSFILER.handlerOptionClick = function(){
        $(document).on("click", ".optionitem", function(){
            var fillerdbname = $(this).attr("fillerdbname");
            var optionid = $(this).attr("optionid");
            SMSFILER.model.selectedIndex[fillerdbname] = optionid;
            $(".optionitem[fillerdbname='"+fillerdbname+"']").attr("manuleselected","0");
            $(this).attr("manuleselected","1");
            SMSFILER.isAllRequireFillterSelected();
        });
    };

    SMSFILER.handlerShowHideFillter = function(){
        $(document).on("click","#hideselectgroup", function(){
            var self = $(this);
            if(SMSFILER.fillterShowHide) {
                $( "#groupselectorcontainer" ).slideUp( "fast", function() {}); self.html("Show Fillter"); SMSFILER.fillterShowHide = false;
            }else{
                $( "#groupselectorcontainer" ).slideDown( "fast", function() {});self.html("Hide Fillter");SMSFILER.fillterShowHide = true;
            }
        });
    };

    SMSFILER.initDataRedirected = function(){

        var isredirect = JSUTILITY.Url.GetURLParameter("redirect");
        if (isredirect == 1){
            var fillterdata = JSUTILITY.Url.GetURLParameter("filter");
            var uri_dec = decodeURIComponent(fillterdata);
            console.log(uri_dec);
            var redirectdata = JSON.parse(uri_dec);
            SMSFILER.model.redirect = redirectdata;
            SMSFILER.model.selectedIndex = redirectdata;
        }


    };


    SMSFILER.handlerLinkToEditClick = function(){

    };


    SMSFILER.config = function(urls, callback){
        //inital data
        SMSFILER.initDataRedirected();
        SMSFILER.model.fillterModelUrl = urls;
        SMSFILER.callback = callback;
        // request data from server
        SMSFILER.getFillterData();
        // even handler
        SMSFILER.handlerShowHideFillter();
        SMSFILER.handlerOptionClick();


    };
}());