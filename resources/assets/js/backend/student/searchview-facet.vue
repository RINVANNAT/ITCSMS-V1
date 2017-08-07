<template>
    <div class="o_searchview_facet" tabindex="0">
        <span class="o_searchview_facet_label">{{facet.name}}</span>
        <div class="o_facet_values">
            <template v-for="filtering_value in facet.value">
                <span>{{filtering_value.value}}</span>
                <span v-if="!$last(filtering_value, facet.value)" class="o_facet_values_sep"> or </span>
            </template>
        </div>
        <div @click="remove_facet" class="fa fa-sm fa-remove o_facet_remove"></div>
    </div>
</template>
<script type="text/babel">
    export default {
        props:['facet'],
        data() {
            return {}
        },
        methods: {
            remove_facet(){
                this.$emit('facet_is_removed',this.facet);
            }
        }
    }
</script>
<style>

</style>