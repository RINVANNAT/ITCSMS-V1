<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border: 1px solid #dddddd;">
            <div class="box-header with-border class-header">
                <h4<strong>Classes</strong></h4>
            </div>
            <div class="box-body courses-sessions">
                @php
                    $classes = ['I3GCA', 'I4GCA', 'I5GCA', 'T1GCA', 'T2GCA']
                @endphp
                <div class="row">
                    @foreach($classes as $item)
                        <div class="col-md-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> {{$item}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

