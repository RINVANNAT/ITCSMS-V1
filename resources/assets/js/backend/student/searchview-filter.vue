<template>
    <div class="btn-group o_search_options">
        <div class="btn-group o_dropdown">
            <button class="o_dropdown_toggler_btn btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="fa fa-filter"></span> {{filter.name}} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu o_filters_menu" role="menu">
                <template v-for="filter_value in filter.filters">
                    <searchview_filter_item
                            @filter_selected="update_selected_filter_item"
                            :filter="filter_value"
                            :filter_name="filter.name"
                            :filtering="filtering"
                            :key="filter_value.name">
                    </searchview_filter_item>
                    <li v-show="!$last(filter_value,filter.filters)" class="divider"></li>
                </template>
            </ul>
        </div>
    </div>
</template>
<script type="text/babel">
    import searchview_filter_item from './searchview-filter-item.vue';
    export default {
        props:['filter','filtering'],
        components: {
            'searchview_filter_item' : searchview_filter_item
        },
        data(){
            return {

            }
        },
        methods: {
            update_selected_filter_item(data){
                //console.log("new item selected: "+data);
                data['name']=this.filter.name;
                this.$emit("selected_filter_updated",data);
            }
        }
    }
</script>
<style>

</style>