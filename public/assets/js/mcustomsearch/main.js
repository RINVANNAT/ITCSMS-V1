//define search follow MVC
/*
    urlgetsearchfields:host/schearch=true;

*/
var SEARCHMODEL = SEARCHMODEL || {
        urlGetSearchfields: '',
        urlsearch: '',
        searchableFields:[],
        searchDataModels:[],
        indexSearchFieldSelected: -1,
        keys : {
            "Backspace":8,
            "Delete":46,
            "Enter":13,
            "Down":40,
            "Up":38,
            "Left":39,
            "Right":37,
        },
        filterdata : [],
        jqhrs:[],
        isjqhrsfound:true,
        virtualfield: [],
        hidefield:[],
        page: null,
};
var SEARCHVIEW = SEARCHVIEW  || {
        html:""
};

var SEARCHCONTROLLER = SEARCHCONTROLLER  || {
        model: SEARCHMODEL,
        view: SEARCHVIEW,
};

// buildgenericfuctions call when SEARCHController.main was called.
// this prevent other function execude on other page that not use search
// save loading time on each page.

SEARCHCONTROLLER.buildgenericfuctions = function(){
    //Model
    SEARCHMODEL.config = function(onController){
        SEARCHMODEL.urlsearch = onController+"/?schearch=true"
        SEARCHMODEL.urlGetSearchfields = onController+"/getsearchfields"
    }
    SEARCHMODEL.findvirtualfield = function (name){
        var isFound = false;
        $.each( SEARCHMODEL.virtualfield, function( index, v ) {
            if (name == v.display){
                isFound = true;
            }
        });
        return isFound;
    }
    SEARCHMODEL.getIndexVirtualfield = function (name){
        var indexf = -1;
        $.each( SEARCHMODEL.virtualfield, function( index, v ) {
            if (name == v.display){
                indexf = index;
            }
        });
        return indexf;
    }
    //View
    SEARCHVIEW.buildInterfaceSearchField = function(data){
        //console.log("SEARCHVIEW.buildInterfaceSearchField");
        //console.log(data);
        var htmlsearchfile = '<ul id="dropdown2" >';
        for (i = 0; i < data.length; i++) {
            var readablefield = data[i].replace("_id", " ").replace("_"," ");
            htmlsearchfile += ' <li class="searchfield1" key="'+data[i]+'"> Search by : '+ readablefield.toUpperCase() +'</li> ';
        }
        htmlsearchfile+'</ul>';
        $("#dropdown").html(htmlsearchfile);
    };
    SEARCHVIEW.buildInterfaceSearchFieldHightlight = function(data,indexhightlight){
        SEARCHCONTROLLER.buildInterfaceSearchField();
        $("ul#dropdown2 > li.searchfield1").each(function(i) {
            if(i==indexhightlight){
                $(this).addClass("dropdownelement");
            }
        });
    };
    SEARCHVIEW.addSearchFields = function (){

        searchDataModels = SEARCHMODEL.searchDataModels;
        var html ="";
        for (i = 0; i < searchDataModels.length; i++) {
            var htmlsearch = '<div class="oe_searchview_facet" index1="'+
                i+'"> <span class="oe_facet_remove">x</span>  <span class="label-default-hide">'+
                searchDataModels[i]["key"] +'</span> <span class="label-default"> '+searchDataModels[i]["key"].replace("_", " ").toUpperCase()+' </span> <span class="oe_facet_values"> '+
                searchDataModels[i]["value"] +'</span></div>';
            html+=htmlsearch
        }
        html+='<div id="searchinput" contenteditable="true" tabindex="1"></div>'
        $('#searchfield').html("");
        //console.log(html);
        $('#searchfield').html(html);
    };
    SEARCHVIEW.focusSearchField = function (){
        $("#searchinput").focus();
        var el = document.getElementById("searchinput");
        var range = document.createRange();
        var sel = window.getSelection();
        range.setStart(el, 0);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
    };
    //controller
    SEARCHCONTROLLER.updateDropdownPositon =  function (){
        var offsetTop =  $('#searchfield').offset().top + $('#searchfield').outerHeight() - $( window ).scrollTop(),
            offsetLeft =  $('#searchfield').offset().left;
        dropdownHeight = $(window).height() - offsetTop - 20;
        $("#dropdown").css({
            'max-height': dropdownHeight,
            position: 'fixed',
            top: offsetTop,
            left: offsetLeft,
            width: $('#searchfield').outerWidth()
        });
        $("#dropdown").css({
            'max-height': dropdownHeight,
            'overflow': 'auto',
            '-webkit-overflow-scrolling': 'touch'
        });
    };
    SEARCHCONTROLLER.updateSearchFieldPositon =  function (){
        var offsetTop =  $('#searchfielwrapper1').offset().top + $('#searchfielwrapper1').outerHeight() - $( window ).scrollTop(),
            offsetLeft =  $('#searchfielwrapper1').offset().right;
        offsetTop = offsetTop-30;
        console.log(offsetLeft);
        dropdownHeight = $(window).height() - offsetTop - 20;
        $("#searchfield").css({
            'max-height': dropdownHeight,
            position: 'fixed',
            top: offsetTop,
            left: offsetLeft,
        });
        $("#searchfield").css({
            'max-height': dropdownHeight,
            'overflow': 'auto',
            '-webkit-overflow-scrolling': 'touch'
        });
    };
    SEARCHCONTROLLER.buildInterfaceSearchField =  function (){
        SEARCHCONTROLLER.view.buildInterfaceSearchField(SEARCHCONTROLLER.model.searchableFields)
    };
    SEARCHCONTROLLER.buildInterfaceSearchFieldHightlight =  function (indexhightlight){
        SEARCHCONTROLLER.view.buildInterfaceSearchFieldHightlight(SEARCHCONTROLLER.model.searchableFields,indexhightlight)
    };
    SEARCHCONTROLLER.getsearchfields =  function (){
    };
    SEARCHCONTROLLER.searchgg = function () {
        //var searchDataModels = SEARCHCONTROLLER.model.searchDataModels;
        var request2 = "";
        var self = this;
        //alert("request sent");
        SEARCHCONTROLLER.model.jqhrs = [];
        $.each( SEARCHCONTROLLER.model.searchDataModels, function( index, searchDataModel ) {
            var url = SEARCHCONTROLLER.model.urlGetSearchfields;
            SEARCHCONTROLLER.model.isjqhrsfound == true;
            //console.log(url);
            if(SEARCHCONTROLLER.model.findvirtualfield(searchDataModel["key"])){
                //alert("found");
                //console.log(SEARCHCONTROLLER.model.hostname);
                var index = SEARCHCONTROLLER.model.getIndexVirtualfield(searchDataModel["key"]);

                var controller = SEARCHCONTROLLER.model.virtualfield[index]["controller"];
                //console.log(controller);
                var urlgetid = SEARCHCONTROLLER.model.hostname +"/"+controller+"/getid?search="+searchDataModel["value"];
                //console.log(urlgetid);
                //ctodo handle request fail
                var request = $.get(urlgetid, function( data ) {
                    //console.log(data);
                    SEARCHCONTROLLER.model.filterdata =  data;
                    if(data["isfound"]==false){
                        $(".nav-tabs-custom").prepend('<ul class="alert alert-danger" style="list-style-type: none">  <li>Search not found</li> </ul>');
                        //alert("note found");
                        SEARCHCONTROLLER.model.isjqhrsfound == false;
                    }else if (data["isfound"]==true ) {
                        request2+="&"+"student_id"+"="+data["id"];
                    }
                }).fail(function() {
                    // controller not found
                    //
                });
                SEARCHCONTROLLER.model.jqhrs.push(request);

            } else if (searchDataModel["key"].search("_id") >= 0){
                function toTitleCase(str)
                {
                    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                }
                //console.log(searchDataModel["key"]);
                var l = searchDataModel["key"].split("_");
                var controllerx = l[0];
                $.each( l.slice(1, l.length -1 ), function( index, element ) {
                    var tmpxx = toTitleCase(element);
                    controllerx = controllerx + tmpxx;
                });
                controllerx = controllerx + "s";
                //console.log(controllerx);

                //var controller = searchDataModel["key"].split("_")[0]+"s";
                var controller = controllerx;
                //console.log(controller);
                var urlgetid = SEARCHCONTROLLER.model.hostname +"/"+controller+"/getid?search="+searchDataModel["value"];
                //console.log(urlgetid);
                //ctodo handle request fail
                var request = $.get(urlgetid, function( data ) {
                    //console.log(data);
                    SEARCHCONTROLLER.model.filterdata =  data;
                    if(data["isfound"]==false){
                        //alert("note found");
                        SEARCHCONTROLLER.model.isjqhrsfound == false;
                    }else{
                        request2+="&"+searchDataModel["key"]+"="+data["id"];

                    }

                }).fail(function() {
                    // controller not found
                    //
                    //$(".box-body").prepend('<ul class="alert alert-danger" style="list-style-type: none">  <li>Search not found</li> </ul>');
                    //alert( "error 2" );
                });
                SEARCHCONTROLLER.model.jqhrs.push(request);


            }else{

                request2+="&"+searchDataModel["key"]+"="+searchDataModel["value"];
            }
            if (SEARCHCONTROLLER.model.page != null){
                console.log("page not null");
                console.log(SEARCHCONTROLLER.model.page);
                request2+="&page="+SEARCHCONTROLLER.model.page;
                SEARCHCONTROLLER.model.page = null;
            }
        });


        $.when.apply($, SEARCHCONTROLLER.model.jqhrs).always(function() {
            if (SEARCHCONTROLLER.model.isjqhrsfound ==true){
                toggleLoading(true);
                $(this).html("<i class='fa fa-refresh fa-spin'></i>");
                $("#block-overlay").show();
                //ctodo handle request fail
                var finish = $.get( SEARCHCONTROLLER.model.urlsearch+request2, function( data2 ) {
                    //console.log(data2);
                    $( "#searchresult" ).html( data2 );
                    toggleLoading(false);
                    //alert('datareturn');
                    //alert($(".oe_facet_value").text());
                    //console.log(data);
                    //console.log("reply");
                }).fail(function() {
                    // wrong type
                    //
                    toggleLoading(false);
                    //$(".box-body").prepend('<ul class="alert alert-danger" style="list-style-type: none">  <li>Search not found</li> </ul>');
                        //alert("note found");

                    alert( "error 3" );
                }).done(function(){
                    $('.pagination').on('click', 'a',function(event){

                        event.preventDefault();
                        urlpage = $(this).attr('href');
                        var n = urlpage.indexOf("?");
                        if (n > 0 ){
                            var res = urlpage.substring(n+1);
                            var sURLVariables = res.split('&');
                            var sParam="page";
                            for (var i = 0; i < sURLVariables.length; i++)
                            {
                                var sParameterName = sURLVariables[i].split('=');
                                if (sParameterName[0] == sParam)
                                {
                                    //console.log(sParameterName[1]);
                                    SEARCHCONTROLLER.model.page = sParameterName[1];
                                    SEARCHCONTROLLER.searchgg();
                                    SEARCHCONTROLLER.view.addSearchFields();

                                }
                            }

                        }
                    });
                    console.log("log: is page search was execute2");
                });
            }
        });

        //console.log("before request");
        //console.log(SEARCHCONTROLLER.model.urlsearch+request2);

    };
};
//end generic function
SEARCHCONTROLLER.main = function () {
    SEARCHCONTROLLER.model.jqhr.always(function() {
        console.log("log: is page search was execute1");
        SEARCHCONTROLLER.buildInterfaceSearchField();
    });



    $('#searchfield').on('paste', function (e) {
        //alert("pasteinputsearch");
        e.preventDefault();
        var text = (e.originalEvent || e).clipboardData.getData('text/html') || prompt('Paste something..');
        //e.preventDefault();
        //e.stopPropagation();
        $('#searchinput').html(text);
        $('#searchinput').html($('#searchinput').text());
        $("#dropdown").show();
        //console.log("past handler");
    });



    $( "body" ).on( "click", ".searchfield1", function() {
        $("#dropdown").hide();
    });
    $('#searchfield').on('input',function(e){
        $("#dropdown").show();
    });
    $( "body" ).on( "click", "li.searchfield1", function() {
        var searchDataModel2 = {};
        searchDataModel2["key"] = $(this).attr('key');
        searchDataModel2["value"] = $('#searchinput').text();
        searchDataModel2["operation"] = "=";
        //console.log("search by click");
        if (searchDataModel2["value"]){
            SEARCHCONTROLLER.model.searchDataModels.push(searchDataModel2);
            SEARCHCONTROLLER.view.addSearchFields();
            SEARCHCONTROLLER.searchgg();
            $("#dropdown2").hide();
            SEARCHCONTROLLER.updateDropdownPositon();
        }
        SEARCHCONTROLLER.view.focusSearchField();
    });
    $( "body" ).on( "click", "span.oe_facet_remove", function() {
        var parent = $(this).parent();
        var index = parent.attr('index1');
        SEARCHCONTROLLER.model.searchDataModels.splice(index, 1);
        SEARCHCONTROLLER.view.addSearchFields();
        SEARCHCONTROLLER.searchgg();
        SEARCHCONTROLLER.view.focusSearchField();
        $("#dropdown2").hide();
    });
    $( "body" ).on( "click", "div.oe_searchview_facet", function() {
        SEARCHCONTROLLER.view.focusSearchField();
    });
    $( "body" ).on( "click", "span.oe_facet_values", function() {
        SEARCHCONTROLLER.view.focusSearchField();
    });
    $( "body" ).on( "click", "span.label-default", function() {
        SEARCHCONTROLLER.view.focusSearchField();
    });
    $( "body" ).on( "keydown", "div.searchfield", function(event) {
        //console.log("in keydown searchfield");

        $("#dropdown2").show();
        SEARCHCONTROLLER.updateDropdownPositon();
        //console.log(event);
        if ( event.which == SEARCHCONTROLLER.model.keys["Enter"] ) {
            event.preventDefault();
            event.stopPropagation();
            //console.log("search by enter");
            var searchDataModel2 = {};
            searchDataModel2["key"] = $("li.dropdownelement").attr('key');
            if (typeof searchDataModel2["key"] === "undefined") {
                searchDataModel2["key"] = SEARCHCONTROLLER.model.searchableFields[0];
            }
            searchDataModel2["value"] = $('#searchinput').text();
            if (searchDataModel2["value"] == "" ) {
                //console.log("String Empty");
                searchDataModel2["key"] = SEARCHCONTROLLER.model.searchableFields[0];
            }

            searchDataModel2["operation"] = "=";
            if (searchDataModel2["value"]){
                SEARCHCONTROLLER.model.searchDataModels.push(searchDataModel2);
                SEARCHCONTROLLER.view.addSearchFields();
                SEARCHCONTROLLER.searchgg();
                $("#dropdown2").hide();
                SEARCHCONTROLLER.updateDropdownPositon();
            }
            SEARCHCONTROLLER.view.focusSearchField();
        }else if(event.which == SEARCHCONTROLLER.model.keys["Backspace"] || event.which == SEARCHCONTROLLER.model.keys["Delete"]){
            var searchtextlenght = $('#searchinput').text().length;
            if ( searchtextlenght == 1){
                $('#searchinput').text("")
                event.preventDefault();
                event.stopPropagation();
            } else if( searchtextlenght <= 0){

                SEARCHCONTROLLER.model.searchDataModels.splice(SEARCHCONTROLLER.model.searchDataModels.length-1,1);
                SEARCHCONTROLLER.view.addSearchFields();
                event.preventDefault();
                event.stopPropagation();
                SEARCHCONTROLLER.searchgg();
                SEARCHCONTROLLER.view.focusSearchField();
            }
            SEARCHCONTROLLER.updateDropdownPositon();
            $("#dropdown2").hide();
        }else if (event.which == SEARCHCONTROLLER.model.keys["Down"]){

            SEARCHMODEL.indexSearchFieldSelected +=1;
            if(SEARCHMODEL.indexSearchFieldSelected > (SEARCHMODEL.searchableFields.length - 1) ){
                SEARCHMODEL.indexSearchFieldSelected -= SEARCHMODEL.searchableFields.length;
            }
            SEARCHCONTROLLER.buildInterfaceSearchFieldHightlight(SEARCHMODEL.indexSearchFieldSelected);
            event.preventDefault();
            event.stopPropagation();

        }else if (event.which == SEARCHCONTROLLER.model.keys["Up"]){
            SEARCHMODEL.indexSearchFieldSelected -=1;
            if(SEARCHMODEL.indexSearchFieldSelected == -1){
                SEARCHMODEL.indexSearchFieldSelected =(SEARCHMODEL.searchableFields.length - 1);
            }
            SEARCHCONTROLLER.buildInterfaceSearchFieldHightlight(SEARCHMODEL.indexSearchFieldSelected);
            event.preventDefault();
            event.stopPropagation();
        }
    });
    SEARCHCONTROLLER.updateDropdownPositon();
    $("#dropdown").hide();
    $( window ).scroll(function() {
        SEARCHCONTROLLER.updateDropdownPositon();

    });
    //End Main fuction

};
SEARCHCONTROLLER.config = function (url,virtualfield, hidefield){

    function get_hostname(url) {
        var m = url.match(/^http:\/\/[^/]+/);
        return m ? m[0] : null;
    }
    SEARCHCONTROLLER.model.urlsearch = url+"/?schearch=true";

    console.log(SEARCHCONTROLLER.model.urlsearch);
    SEARCHCONTROLLER.model.hostname = get_hostname(url);
    SEARCHCONTROLLER.model.urlGetSearchfields = url+"/getsearchfields"
    $("body").append("<div id='dropdown' style='background-color: #3c8dbc;  color:white; '> </div>");
    //ctodo handle request fail
    SEARCHCONTROLLER.model.jqhr = $.get(SEARCHCONTROLLER.model.urlGetSearchfields, function( data ) {
        SEARCHCONTROLLER.model.searchableFields =  data;
        //console.log(data);
        // add custom search field
        $.each( SEARCHCONTROLLER.model.virtualfield, function( index, v ) {
           //console.log(v);
            SEARCHCONTROLLER.model.searchableFields.unshift(v.display);
        });
        // remove hide field
        $.each( SEARCHCONTROLLER.model.hidefield, function( index, v ) {
            var indexx = -1;
            $.each( SEARCHCONTROLLER.model.searchableFields, function( index2, v2 ) {

                if (v2 == v){
                    indexx = index2;
                }

            });
            if (indexx >= 0 ){
                SEARCHCONTROLLER.model.searchableFields.splice(indexx, 1);
            }
        });

        // todo if searchabledFields have _id at the end
        // ajax get data, fix data and store at
        // SEACONTROLLER.model.relatesearchValueKeyPaire.
        // SEACONTROLLER.model.
        // syncronose request : http://stackoverflow.com/questions/6685249/jquery-performing-synchronous-ajax-requests
        //
    });
    SEARCHCONTROLLER.buildgenericfuctions();
    SEARCHCONTROLLER.main();
    if (typeof virtualfield !== "undefined") {
        SEARCHCONTROLLER.model.virtualfield = virtualfield;
    }
    if (typeof hidefield !== "undefined") {
        SEARCHCONTROLLER.model.hidefield = hidefield;
    }
};