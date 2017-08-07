<template>
    <div class="o_searchview_facets">
        <searchview_facet
                v-for="facet in filtering"
                :facet="facet"
                :key="facet.name"
                @facet_is_removed="facet_is_removed">

        </searchview_facet>
    </div>
</template>
<script type="text/babel">
    import searchview_facet from './searchview-facet.vue'
    export default {
        props:['filtering'],
        components:{
            'searchview_facet':searchview_facet
        },
        methods:{
            facet_is_removed(data){
                this.$emit('facet_is_removed',data);
            }
        }
    }
</script>
<style>

</style>