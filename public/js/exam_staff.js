function toggleSidebarStaffRole() {

    var right_staff_role = $("#side_window_right_staff_role"),
        content = $("#main_window_staff_role"),
        contentClass = "";
    // determine number of open sidebars
    if (content.hasClass("col-sm-6")) {
        contentClass = "col-sm-12";
        right_staff_role.hide();
    } else {
        contentClass = "col-sm-6";
    }

    // apply class to content
    content.removeClass("col-sm-12 col-sm-9 col-sm-6")
        .addClass(contentClass);

    if(content.hasClass("col-sm-6")){
        // console.log('this me vannat');
        // console.log(right);
        right_staff_role.delay(300).show(0);

    }
}


function initJsTree_StaffRole( object, url_lv1, url_lv2 ) {
    console.log('tes');
    object.jstree({
        "core" : {
            "animation":0,
            "check_callback" : true,
            'force_text' : true,
            "themes" : {
                "variant" : "large",
                "stripes" : true
            },
            "data":{
                'url' : function (node) {
                    return node.id === '#' ? url_lv1 : url_lv2;
                },
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            }
        },
        "checkbox" : {
            "keep_selected_style" : false
        },
        "types" : {
            "#" : { "max_depth" : 3, "valid_children" : ["building","room"] },
            "building" : {
                "icon" : "{{url('plugins/jstree/img/building.png')}}",
                "valid_children" : ["room"]
            },
            "room" :{
                "icon" : "{{url('plugins/jstree/img/door.png')}}",
                "valid_children" : []
            }
        },
        "plugins" : [
            "wholerow",'checkbox', "contextmenu", "dnd", "search", "state","types"
        ]
    });

}
$(function(){
    $("#btn_add_role").click(function () {
        toggleSidebarStaffRole();
        return false;
    });

});