<template>
    <ul class="dropdown-menu o_searchview_autocomplete" :class="{'visible':auto_complete_is_visible,'invisible':!auto_complete_is_visible}" role="menu">
        <autocomplete_item
                v-for="(field,pos) in search_fields"
                :key="field.name"
                :search_field="field"
                :index="pos"
                :keyword="keyword"
                :selected_auto_complete="selected_auto_complete"
                @search_facet_is_updated="search_facet_is_updated"
                :is_first="$first(field, search_fields)">
        </autocomplete_item>
    </ul>
</template>
<script type="text/babel">
    import autocomplete_item from './autocomplete-item.vue';
    export default {
        props:['search_fields','auto_complete_is_visible','keyword','selected_auto_complete'],
        components: {
          'autocomplete_item':autocomplete_item
        },
        data () {
            return {

            };
        },
        methods: {
            search_facet_is_updated(){
                this.$emit('search_facet_is_updated');
            }
        }
    };
</script>
<style>

</style>