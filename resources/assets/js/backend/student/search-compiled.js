import vue from 'vue';
import searchbox from './searchbox.vue';

window.Vue = vue;
window.store = {
    selected_auto_complete: 0
};
Vue.prototype.$last = function (item, list) {
    return item === list[list.length - 1];
};
Vue.prototype.$first = function (item, list) {
    return item === list[0];
};

var search = new Vue({
    el: '#student_content',
    components: {
        'searchbox': searchbox
    },
    data: {}
});

//# sourceMappingURL=search-compiled.js.map