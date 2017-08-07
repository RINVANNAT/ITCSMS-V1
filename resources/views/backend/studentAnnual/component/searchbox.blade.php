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
            <searchview_facets :filtering="filtering"></searchview_facets>
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
        <div class="btn-group o_search_options">
            <div class="btn-group o_dropdown">
                <button class="o_dropdown_toggler_btn btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="fa fa-filter"></span> Department <span class="caret"></span>
                </button>
                <ul class="dropdown-menu o_filters_menu" role="menu">
                    <li data-index="0" class="selected">
                        <a href="#">GIC</a>
                    </li>
                    <li class="divider"></li>
                    <li data-index="0">
                        <a href="#">GIM</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="btn-group o_search_options">
            <div class="btn-group o_dropdown">
                <button class="o_dropdown_toggler_btn btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="fa fa-filter"></span> Degree <span class="caret"></span>
                </button>
                <ul class="dropdown-menu o_filters_menu" role="menu">
                    <li data-index="0" class="selected">
                        <a href="#">Engineer</a>
                    </li>
                    <li class="divider"></li>
                    <li data-index="0">
                        <a href="#">Associate</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>