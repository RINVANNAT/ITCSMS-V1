/**
 * Created by snakecc on 11/19/15.
 */



if (typeof SMSFILERLONG !== 'object') {
    SMSFILERLONG = {};
}
(function () {
    SMSFILERLONG.model = SMSFILERLONG.model || {
            fillterModelUrl: ["url","url",],
            fillers:{},
            selectedIndex: {
            },
            redirect:{},
        };
    SMSFILERLONG.view = SMSFILERLONG.view  || {
            template_filter : ""
        };
    SMSFILERLONG.view.draw = function(){
        var template2 = MYTEMPLATE["fillter"]["longtemplate"];
        var fillterhtml = Mustache.to_html(template2, SMSFILERLONG.model.fillers);
        console.log(SMSFILERLONG.model.fillers);


        //var fillterhtml = Mustache.to_html(template2, {fillterlongname:"Course"});
        $(''+SMSFILERLONG.view.tagResult).html(fillterhtml);
    };

    SMSFILERLONG.view.drawForRedirect = function(){
        $.each(SMSFILERLONG.model.redirect, function(key,value){
            $(".optionlongitem[fillerdbname='"+key+"'][optionid='"+value+"']").attr("manuleselected","1");
        });
        SMSFILERLONG.callback(SMSFILERLONG.model.redirect);
    }
    SMSFILERLONG.callback = function() {
    };
    SMSFILERLONG.debug = function() {
    };
    SMSFILERLONG.isAllRequireFillterSelected = function(){
        var selectedfillterlength = Object.keys(SMSFILERLONG.model.selectedIndex).length;
        var requireselectedfilter = 3;
        var result = false;
        if (selectedfillterlength >= requireselectedfilter ){
            result = true;
        }
        if(result == true){

            SMSFILERLONG.callback(SMSFILERLONG.model.selectedIndex);
        }
        return result;
    };
    SMSFILERLONG.getFillterData = function (){
        var ajaxdone = [];
        console.log(SMSFILERLONG.model.fillterModelUrl);
        $.each(SMSFILERLONG.model.fillterModelUrl, function(key,url){
            console.log("in get filterdata:"+url);
            var request = $.get(url, function( data ) {
                var sURLVariables = url.split('/');
                var filltername = sURLVariables[sURLVariables.length-1];
                console.log("filtername");

                var ifhavequestionmark = filltername.split('?');
                var filltername = ifhavequestionmark[0];
                console.log(filltername);
                filltername = filltername.charAt(0).toUpperCase() + filltername.slice(1);
                var fillerdbname = filltername.slice(0, -1).replace(/\.?([A-Z])/g, function (x,y){return "_" + y.toLowerCase()}).replace(/^_/, "")+"_id";
                SMSFILERLONG.model.fillers = {filltername:filltername,fillterlongname:filltername,fillerdbname:fillerdbname, options:data.data};
                console.log(data);

            }).fail(function() {
                // controller not found
                //
            });
            ajaxdone.push(request);
        });
        $.when.apply($, ajaxdone).always(function() {
            SMSFILERLONG.orderModelBeforeRender();
            SMSFILERLONG.view.draw();
            if ( Object.keys(SMSFILERLONG.model.selectedIndex).length > 0){
                SMSFILERLONG.view.drawForRedirect();
            };
        });

    }

    SMSFILERLONG.orderModelBeforeRender = function () {

        console.log("ordering filter");

        console.log(SMSFILERLONG.model.fillers );
        $.each(SMSFILERLONG.model.fillterModelUrl, function(key,url){

        });
    }
    SMSFILERLONG.handleRedirect = function (){

    };
    SMSFILERLONG.handlerOptionClick = function (){
        $(document).on("click", ".optionlongitem", function(){
            var fillerdbname = $(this).attr("fillerdbname");
            var optionid = $(this).attr("optionid");

            SMSFILERLONG.model.selectedIndex[fillerdbname] = optionid;
            $(".optionlongitem[fillerdbname='"+fillerdbname+"']").attr("manuleselected","0");
            $(this).attr("manuleselected","1");
            SMSFILERLONG.callback(SMSFILERLONG.model.selectedIndex);
        });
    };
    SMSFILERLONG.initDataRedirected = function(){
        var isredirect = JSUTILITY.Url.GetURLParameter("redirect");
        if (isredirect == 1) {
            var fillterdata = JSUTILITY.Url.GetURLParameter("filter");
            var uri_dec = decodeURIComponent(fillterdata);
            var redirectdata = JSON.parse(uri_dec);
            SMSFILERLONG.model.redirect = redirectdata;
            SMSFILERLONG.model.selectedIndex = redirectdata;
        }
    };
    SMSFILERLONG.clone =  function clone(obj) {
        if (null == SMSFILERLONG || "object" != typeof SMSFILERLONG) return SMSFILERLONG;
        var copy = SMSFILERLONG.constructor();
        for (var attr in SMSFILERLONG) {
            if (SMSFILERLONG.hasOwnProperty(attr)) copy[attr] = SMSFILERLONG[attr];
        }
        return copy;
    }



    SMSFILERLONG.config = function(urls, callback,tagResult){
        SMSFILERLONG.initDataRedirected();
        SMSFILERLONG.model.fillterModelUrl = urls;
        SMSFILERLONG.callback = callback;
        SMSFILERLONG.handlerOptionClick();
        SMSFILERLONG.getFillterData();
        SMSFILERLONG.handleRedirect();
        if (typeof tagResult === 'undefined') { tagResult = '#groupselectorcontainerlong'; }
        SMSFILERLONG.view.tagResult = tagResult;

    };

}());


//New Object
var SMSFILERLONGo = function (urls, callback,tagResult) {

    //this holder

    // Data
    this.fillterModelUrl =  urls;
    this.fillers = {};
    this.selectedIndex ={};
    this.redirect = {};
    this.callback = callback;
    //View
    if (typeof tagResult === 'undefined') { tagResult = '#groupselectorcontainerlong'; }
    this.tagResult = tagResult;

    //
    this.callbackf = function(){
        console.log(this.fillterModelUrl);
        this.callback(this.selectedIndex);
    }

    this.draw = function(){
        console.log("call from"+this.fillterModelUrl);
        var template2 = MYTEMPLATE["fillter"]["longtemplate"];
        var fillterhtml = Mustache.to_html(template2, this.fillers);
        console.log("call from"+self.tagResult);
        $(''+this.tagResult).html(fillterhtml);
    };
    //
    this.isAllRequireFillterSelected = function(){

        var selectedfillterlength = Object.keys(this.selectedIndex).length;
        var requireselectedfilter = 3;
        var result = false;
        if (selectedfillterlength >= requireselectedfilter ){
            result = true;
        }
        if(result == true){

            this.callback(this.selectedIndex);
        }
        return result;
    };
    //get filter data
    this.getFillterData = function (){

        var ajaxdone = [];
        var self = this;

        $.each(this.fillterModelUrl, function(key,url){
            var request = $.get(url, function( data ) {
                var sURLVariables = url.split('/');
                var filltername = sURLVariables[sURLVariables.length-1];
                var ifhavequestionmark = filltername.split('?');
                var filltername = ifhavequestionmark[0];
                filltername = filltername.charAt(0).toUpperCase() + filltername.slice(1);
                var fillerdbname = filltername.slice(0, -1).replace(/\.?([A-Z])/g, function (x,y){return "_" + y.toLowerCase()}).replace(/^_/, "")+"_id";

                console.log("long filter");
                console.log(data.data);

                self.fillers = {filltername:filltername,fillterlongname:filltername,fillerdbname:fillerdbname, options:data.data};


            }).fail(function() {
                // controller not found
                //
            });
            ajaxdone.push(request);
        });
        $.when.apply($, ajaxdone).always(function() {
            self.draw();
            if ( Object.keys(self.selectedIndex).length > 0){
                self.drawForRedirect();
            };
        });
    }
    //Event
    this.handlerOptionClick = function (){
        var self = this;
        $(document).on("click", ".optionlongitem", function(){
            var fillerdbname = $(this).attr("fillerdbname");
            var optionid = $(this).attr("optionid");

            self.selectedIndex[fillerdbname] = optionid;
            $(".optionlongitem[fillerdbname='"+fillerdbname+"']").attr("manuleselected","0");
            $(this).attr("manuleselected","1");

            self.callbackf(self.selectedIndex);
        });
    };
    //redirect
    this.initDataRedirected = function(){
        var isredirect = JSUTILITY.Url.GetURLParameter("redirect");
        if (isredirect == 1) {
            var fillterdata = JSUTILITY.Url.GetURLParameter("filter");
            var uri_dec = decodeURIComponent(fillterdata);
            var redirectdata = JSON.parse(uri_dec);
            this.redirect = redirectdata;
            this.selectedIndex = redirectdata;
        }
    };
    this.drawForRedirect = function(){
        $.each(this.redirect, function(key,value){
            $(".optionlongitem[fillerdbname='"+key+"'][optionid='"+value+"']").attr("manuleselected","1");
        });
        this.callback(this.redirect);
    }



    //inital function
    this.initDataRedirected();
    this.getFillterData();
    this.handlerOptionClick();


};
