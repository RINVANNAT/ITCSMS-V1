var $search_url = "/admin/internship/student-search"
var base_url = 'http://192.168.51.88:7070/img/profiles'

function setValues(company) {
	if (company.hasOwnProperty('address')) {
		$('#address').val(company.address)
		$('#phone').val(company.phone)
		$('#hot_line').val(company.hp)
		$('#e_mail_address').val(company.mail)
		$('#web').val(company.web)
		// $('#title').val(company.title)
		$('#training_field').val(company.training_field)
	} else {
		$('#address').val('')
		$('#phone').val('')
		$('#hot_line').val('')
		$('#e_mail_address').val('')
		$('#web').val('')
		// $('#title').val('')
		$('#training_field').val('')
	}
}

function formatRepoEmployee(repo) {
	
	if (repo.loading) {
		return repo.text
	}
	if (repo.newOption) {
		return '<a href="#" class=""><em>Select employee below</em></a>'
	} else {
		var photo = "avatar.png"
		var gender = "NA"
		if (repo.photo != "" && repo.photo != null) {
			photo = repo.photo
		}
		if (repo.gender != null) {
			gender = repo.gender
		}
		var markup = "<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__avatar'><img src='" + base_url + "/" + repo.id_card + ".jpg' /></div>" +
			"<div class='select2-result-repository__meta'>" +
			"<div class='select2-result-repository__title'>" + repo.id_card + " | " + repo.text + "</div>" +
			"<div class='select2-result-repository__description'>" + repo.name_latin + "</div>" +
			"<div class='select2-result-repository__statistics'>" +
			"<div class='select2-result-repository__forks'><i class='fa fa-bank'></i> " + repo.department + "</div>" +
			"<div class='select2-result-repository__stargazers'><i class='fa fa-venus-mars'></i> " + gender + "</div>" +
			"</div>" +
			"</div>" +
			"</div>"
		return markup
		
	}
}

$(function () {
	$('#phone,#hot_line').inputmask('(+999) 99 99 99 99[9]')
	
	$('#academic_year').select2({
		theme: "bootstrap"
	})
	
	$('#issue_date, #start, #end').datetimepicker({
		format: 'YYYY-MM-DD'
	})
	
	$('#period').daterangepicker()
	
	$("#students").select2({
		placeholder: 'Enter name ...',
		theme: "bootstrap",
		allowClear: false,
		tags: true,
		createTag: function (params) {
			return {
				id: params.term,
				name: params.term,
				group: 'customer',
				newOption: true
			}
		},
		ajax: {
			url: $search_url,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					term: params.term || '',
					academic_year_id: $('#academic_year').val(),
					page: params.page || 1
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) {
			return markup;
		},
		minimumInputLength: 3,
		templateResult: formatRepoEmployee,
		templateSelection: formatRepoSelectionEmployee,
		multiple: true
	});
	
	$(".companies").select2({
		placeholder: 'Enter name...',
		theme: "bootstrap",
		allowClear: true,
		tags: true,
	}).on('change', function (e) {
		try {
			var company = JSON.parse(e.target.value)
			setValues(company)
		} catch (e) {
			setValues({})
		}
		
	})
})