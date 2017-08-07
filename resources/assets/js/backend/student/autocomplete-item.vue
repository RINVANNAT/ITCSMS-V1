<template>
    <li @mousedown="select_item"
        @mouseover="mouseHover"
        @mouseleave="mouseLeave"
        :class="{'o-selection-focus':is_selected || is_hover }">
        <a href="#">Search <em>{{search_field.name}}</em> for: <strong>{{keyword}}</strong></a>
    </li>
</template>
<script type="text/babel">
    export default {
        props:['is_first','search_field','keyword','index','selected_auto_complete'],
        data () {
            return {
                is_hover:false,
                shared:store
            }
        },
        computed: {
            is_selected(){
                return this.index === this.selected_auto_complete;
            }
        },
        methods: {
            mouseHover(){
                this.is_hover=true;
            },
            mouseLeave(){
                this.is_hover=false;
            },
            select_item(){
                this.shared.selected_auto_complete = this.index;
                this.$emit('search_facet_is_updated');
            }
        }
    }
</script>
<style>

</style>