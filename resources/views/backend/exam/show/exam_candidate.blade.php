<div style="padding-bottom: 20px;">
    <!-- Check all button -->
    <a href="{!! route('admin.candidates.create') !!}">
        <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
        </button>
    </a>
    <!-- /.btn-group -->
    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
</div>


@include('backend.candidate.includes.index_table_header')