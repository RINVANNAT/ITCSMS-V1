<template>
    <div class="o_control_panel">
        <div class="o_cp_searchview">
            <div class="o_searchview">
                <span class="o_searchview_more fa fa-search-minus" title="Advanced Search..."></span>
                <searchview_autocomplete
                        :auto_complete_is_visible="auto_complete_is_visible"
                        :keyword="keyword"
                        :selected_auto_complete="shared.selected_auto_complete"
                        :search_fields="search_fields"
                        v-on:search_facet_is_updated = "search_facet_is_updated">

                </searchview_autocomplete>
                <searchview_facets
                        :filtering="filtering"
                        @facet_is_removed="remove_facet">

                </searchview_facets>
                <input v-model="keyword"
                       v-on:keyup="toggle_auto_complete"
                       v-on:blur="hide_auto_complete"
                       v-on:keydown.40.prevent="down_select_autocomplete"
                       v-on:keydown.38.prevent="up_select_autocomplete"
                       v-on:keydown.13.prevent="register_new_search_facet"
                       class="o_searchview_input"
                       placeholder="Search..."
                       type="text">
            </div>
        </div>
        <div class="o_cp_right">
            <searchview_filter
                    v-for="filter_field in filter_fields"
                    :key="filter_field.name"
                    @selected_filter_updated="update_selected_filter"
                    :filtering="filtering"
                    :filter="filter_field">
            </searchview_filter>

        </div>
    </div>
</template>
<script type="text/babel">
    import searchview_autocomplete from './searchview-autocomplete.vue';
    import searchview_facets from './searchview-facets.vue';
    import searchview_filter from './searchview-filter.vue';
    export default {
        data(){
          return {
              auto_complete_is_visible: false,
              //auto_complete_is_selected: false,
              keyword: "",
              filtering: [
                  {
                      'name': 'this is name',
                      'value': [
                          {'key':'key1', 'value':'value1'},
                          {'key':'key2', 'value':'value2'},
                      ]
                  }
              ],
              search_fields: [
                  {'name':'name'},
                  {'name':'id card'}
              ],
              filter_fields:[
                  {
                      'name': 'Academic Year',
                      'multiple': false,
                      'deletable':false,
                      'filters' : [
                          {'id': 1,'code':'2016-2017'},
                          {'id': 2,'code':'2015-2016'}
                      ]
                  },
                  {
                      'name': 'Semesters',
                      'deletable':true,
                      'multiple': false,
                      'filters' : [
                          {'id': 1,'code':'S1'},
                          {'id': 2,'code':'S2'}
                      ]
                  },
                  {
                      'name': 'Degrees',
                      'deletable':true,
                      'multiple': false,
                      'filters' : [
                          {'id': 1,'code':'I'},
                          {'id': 2,'code':'T'}
                      ]
                  },
                  {
                      'name': 'Grades',
                      'deletable':true,
                      'multiple': false,
                      'filters' : [
                          {'id': 1,'code':'Year 1'},
                          {'id': 2,'code':'Year 2'}
                      ]
                  },
                  {
                      'name': 'Departments',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'GIC'},
                          {'id': 2,'code':'GIM'},
                          {'id': 3,'code':'GEE'},
                          {'id': 4,'code':'GCI'}
                      ]
                  },
                  {
                      'name': 'Options',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'_EE'},
                          {'id': 2,'code':'_ET'}
                      ]
                  },
                  {
                      'name': 'Genders',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'Male'},
                          {'id': 2,'code':'Female'}
                      ]
                  },
                  {
                      'name': 'Origins',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'GIC'},
                          {'id': 2,'code':'GIM'},
                          {'id': 3,'code':'GEE'},
                          {'id': 4,'code':'GCI'}
                      ]
                  },
                  {
                      'name': 'Redoubles',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'GIC'},
                          {'id': 2,'code':'GIM'},
                          {'id': 3,'code':'GEE'},
                          {'id': 4,'code':'GCI'}
                      ]
                  },
                  {
                      'name': 'Radies',
                      'deletable':true,
                      'multiple': true,
                      'filters' : [
                          {'id': 1,'code':'GIC'},
                          {'id': 2,'code':'GIM'},
                          {'id': 3,'code':'GEE'},
                          {'id': 4,'code':'GCI'}
                      ]
                  }
              ],
              shared: store
          }
        },
        components: {
            'searchview_autocomplete': searchview_autocomplete,
            'searchview_facets': searchview_facets,
            'searchview_filter': searchview_filter
        },
        methods: {
            toggle_auto_complete(){
                if(this.keyword == ""){
                    this.auto_complete_is_visible = false;
                } else {
                    this.auto_complete_is_visible = true;
                }
            },
            hide_auto_complete(){
                this.keyword= "";
                this.toggle_auto_complete();
            },
            down_select_autocomplete(){
                if(this.shared.selected_auto_complete<this.search_fields.length -1) {
                    this.shared.selected_auto_complete = this.shared.selected_auto_complete + 1;
                }
            },
            up_select_autocomplete(){
                if(this.shared.selected_auto_complete>0){
                    this.shared.selected_auto_complete = this.shared.selected_auto_complete-1;
                }
            },
            add_new_search_facet(filter_name,filter_key,filter_value){
                let found = null;
                // loop through current filtering value
                for (i = 0; i < this.filtering.length; i++) {
                    // check if filtering name is already exist
                    if(this.filtering[i].name === filter_name){
                        found = i;
                        break;
                    }
                }
                // filtering field is not found, add another filtering name with its value
                if (found == null) {
                    this.filtering.push({
                        'name':filter_name,
                        'value': [
                            {'key':filter_key,'value':filter_value}
                        ]
                    });
                } else {
                    // filtering field is found,
                    // but we need to check first if the filtering value is already exist
                    let exist = false;
                    let object_to_filter = {'key':filter_key,'value':filter_value};
                    for (j=0;j<this.filtering[found].value.length;j++){
                        let filtered_value = this.filtering[found].value[j];
                        if ((object_to_filter.key === filtered_value.key) && (object_to_filter.value === filtered_value.value)){
                            // already here, remove it
                            this.filtering[found].value.splice(j,1);
                            if(this.filtering[found].value.length == 0){
                                this.filtering.splice(found,1);
                            }
                            exist = true;
                            break;
                        }
                    }
                    if(!exist){
                        // not yet, then add it
                        this.filtering[found].value.push(object_to_filter);
                    }
                }
            },
            register_new_search_facet(){
                let name = this.search_fields[this.shared.selected_auto_complete].name;
                this.add_new_search_facet(name,name,this.keyword);
                this.keyword = "";
                this.shared.selected_auto_complete = 0;
            },
            search_facet_is_updated(){
                this.register_new_search_facet();
            },
            update_selected_filter(new_selected_filter){
                this.add_new_search_facet(new_selected_filter.name,new_selected_filter.id,new_selected_filter.code);
            },
            remove_facet(facet_to_remove){
                for(i=0;i<this.filtering.length;i++){
                    let filtered_object = this.filtering[i];
                    if(filtered_object.name === facet_to_remove.name){
                        this.filtering.splice(i,1);
                        break;
                    }
                }
            }
        }
    }
</script>
<style>

</style>