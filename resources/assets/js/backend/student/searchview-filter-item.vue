<template>
    <li @click="select" data-index="0" :class="{'selected':is_selected}">
        <a href="#">{{filter.code}}</a>
    </li>
</template>
<script type="text/babel">
    export default {
        props:['filter','filter_name','filtering'],
        data(){
            return {

            }
        },
        computed: {
            is_selected(){
                for(var filtered_field of this.filtering){
                    if (this.filter_name === filtered_field.name){
                        for(var filtered_value of filtered_field.value){
                            if(filtered_value.key === this.filter.id){
                                return true;
                            }
                        }
                        return false;
                    }
                }
                return false;
            }
        },
        methods: {
            select(){
                this.$emit('filter_selected',this.filter);
            }
        }
    }
</script>
<style>

</style>