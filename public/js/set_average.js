new Vue({
	el: '#app',
	data() {
		return {
			average: 50.00,
			gpa: [
				{label: 'A', start: 85, end: 100, value: 4.0, mentioned: 'Excellent'},
				{label: 'B+', start: 80, end: 84, value: 3.5, mentioned: 'Very Good'},
				{label: 'B', start: 70, end: 79, value: 3.0, mentioned: 'Good'},
				{label: 'C+', start: 65, end: 69, value: 2.5, mentioned: 'Fairly Good'},
				{label: 'C', start: 50, end: 64, value: 2.0, mentioned: 'Fair'},
				{label: 'D', start: 45, end: 49, value: 1.5, mentioned: 'Poor'},
				{label: 'E', start: 40, end: 44, value: 1.0, mentioned: 'Very Poor'},
				{label: 'F', start: 40, end: 0, value: 0.0, mentioned: 'Failure'}
			]
		}
	},
	methods: {
		getAverage() {
			axios.post('/admin/define-average/get-average', {
				academic_year_id: $('#filter_academic_year').val(),
				department_id: $('#filter_dept').val(),
				semester_id: $('#filter_semester').val()
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
				semester_id: $('#filter_semester').val(),
				value: this.average
			}).then(response => {
				if (response.data.code === 1) {
					if (response.data.data !== null) {
						this.average = response.data.data.value
					}
				}
				this.$refs.btnCloseModal.click()
			})
		},
		generateGpa (passedScore) {
			if (passedScore == 50) {
				return this.gpa
			}
		}
	},
	mounted() {
		this.getAverage()
		this.generateGpa(50)
	}
})