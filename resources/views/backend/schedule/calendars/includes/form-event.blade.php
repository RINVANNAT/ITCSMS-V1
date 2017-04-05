{{ csrf_field() }}

<div class="form-group @if($errors->has('title')) has-error @endif">
    <label for="title" class="control-label col-md-2">Title</label>
    <div class="col-md-10">
        <input type="text"
               name="title"
               id="title"
               class="form-control"
               value="{{ old('title') }}"
               placeholder="Title event"/>
    </div>
</div>

<div class="form-group @if($errors->has('category')) has-error @endif">
    <label for="category" class="control-label col-md-2">Category</label>
    <div class="col-md-10">
        <select class="form-control"
                name="category_id"
                id="category">

            <option selected disabled>Chose event category</option>
            @foreach($categoryEvents as $category)

                <option value="{{ $category->id }}">{{ $category->name }}</option>

            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label for="study">
                <input type="checkbox"
                       name="study"
                       id="study"
                       value="1" checked> Allow student study?
            </label>
        </div>
    </div>
</div>