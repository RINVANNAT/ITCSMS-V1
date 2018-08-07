new Vue({
	el: '#app',
	data() {
		return {
			average: 50.00
		}
	},
	methods: {
		getAverage() {
			axios.post('/admin/define-average/get-average', {
				academic_year_id: $('#filter_academic_year').val(),
				department_id: $('#filter_dept').val()
			}).then(response => {
				if (response.data.code === 1) {
					if (response.data.data !== null) {
						this.average = response.data.data.value
					} else {
						this.average = 50.00
					}
				}
			})
		},
		storeAverage() {
			axios.post('/admin/define-average/store-average', {
				academic_year_id: $('#filter_academic_year').val(),
				department_id: $('#filter_dept').val(),
				value: this.average
			}).then(response => {
				if (response.data.code === 1) {
					if (response.data.data !== null) {
						this.average = response.data.data.value
					}
				}
				window.location.reload(true)
			})
		}
	},
	mounted() {
		this.getAverage()
	}
})