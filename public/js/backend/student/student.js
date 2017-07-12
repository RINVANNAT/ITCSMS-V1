function formatRepoStudent (repo) {

    if (repo.loading) {
        return repo.text;
    }
    if (repo.newOption) {
        return '<a href="#" class=""><em>Select student below</em></a>';
    } else {
        var photo = "avatar.png";
        var gender = "NA";
        if(repo.photo != "" && repo.photo != null){
            photo = repo.photo;
        }
        if(repo.gender != null) {
            gender = repo.gender;
        }
        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img src='"+base_url+"/"+photo+"' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.id_card+" | "+repo.text + "</div>"+
            "<div class='select2-result-repository__description'>" + repo.name_latin + "</div>"+
            "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks'><i class='fa fa-bank'></i> " + repo.department+ "</div>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-venus-mars'></i> " + gender + "</div>" +
            "</div>" +
            "</div>"+
            "</div>";
        return markup;

    }
}


function formatRepoSelectionStudent (data, container) {

    $('#student_lists').val(data.id)
        .attr('id_card', data.id_card)
        .attr('name_kh', data.text)
        .attr('name_latin', data.name_latin)
        .attr('photo', data.photo)
        .attr('student_annual_id', data.id);

    return data.text || data.name_latin;
}

var new_id_card_photo = function(id_card_url, id_card, name_kh, name_latin, student_annual_id, profile) {

    var name_latin_div = '';

    var id = '<div class="col-sm-3" style="margin-bottom: 15px;">' +
        '<div class="page">' +
        '<div class="background">' +
        '<img width="100%" src="'+id_card_url+'">' +
        '</div>' +
        '<div class="detail">'+
        '<span class="department" >'+
        'អត្តលេខនិស្សិត/ID : ' +
        '<strong>'+id_card+'</strong>'+
        '</span>' +
        '<div class="avatar">'+
        '<div class="crop">' +
        ' <img src="'+profile+'">'+
        '</div>'+
        '</div>' +
        '<span class="name_kh">' +name_kh+'</span>';

        if(name_latin.length < 25) {
            name_latin_div = ' <span class="name_latin">'+name_latin+'</span>';
            id +=name_latin_div;
        } else {
            name_latin_div = '<span class="name_latin" style="font-size: 13px !important;">'+name_latin+'</span>';
            id +=name_latin_div;
        }
        id +=   '</div>'+
                '</div>'+
                '</div>'+
                '<div class="col-sm-1" style="margin-bottom: 15px;">' +
                '<input type="checkbox" checked class="checkbox" data-id="'+student_annual_id+'">' +
                '</div>';

        return id;
}