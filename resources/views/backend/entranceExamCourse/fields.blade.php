<input type="hidden" class="form-control" id="exam_id" name="exam_id" value="{{$exam_id}}">
<div class="form-group required">
    <label for="name_kh" class="col-sm-3 control-label required">Course Name - khmer</label>

    <div class="col-sm-9">
        <input type="text" class="form-control" value="{{isset($entranceExamCourse)?$entranceExamCourse->name_kh:null}}" id="name_kh" name="name_kh" placeholder="Name in Khmer" required>
    </div>
</div>

<div class="form-group">
    <label for="name_en" class="col-sm-3 control-label">Course Name - English</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="{{isset($entranceExamCourse)?$entranceExamCourse->name_en:null}}" id="name_en" name="name_en" placeholder="Name in English">
    </div>
</div>

<div class="form-group">
    <label for="name_fr" class="col-sm-3 control-label">Course Name - French</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="{{isset($entranceExamCourse)?$entranceExamCourse->name_fr:null}}" id="name_fr" name="name_fr" placeholder="Name in French">
    </div>
</div>

<div class="form-group">
    <label for="description" class="col-sm-3 control-label">Description</label>

    <div class="col-sm-9">
        <textarea class="form-control" value="{{isset($entranceExamCourse)?$entranceExamCourse->description:null}}" id="description" name="description" placeholder="Description"></textarea>
    </div>
</div>
<div class="form-group">
    <label for="total_score" class="col-sm-3 control-label">Total Questions</label>

    <div class="col-sm-3">
        <input type="number" class="form-control" value="{{isset($entranceExamCourse)?$entranceExamCourse->total_question:null}}" id="total_question" name="total_question" placeholder="30" value="30">
    </div>
</div>