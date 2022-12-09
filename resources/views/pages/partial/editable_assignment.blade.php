<div class="form-group col-md-12">
    <label for="" class="col-md-4 text-left">Course Code/Name</label>
    <div class="input-group col-md-8">
        <input type="text" id="assignCourseName{{ $post->id }}" value="{{$post->course_name or ""}}" class="form-control">
    </div>
</div>
<div class="form-group col-md-12">
    <label for="" class="col-md-4 text-left">Assignment given at</label>
    <div class="input-group col-md-8">
        <input type="text" id="assignGivenDate{{ $post->id }}" value="{{$post->given_date}}" class="assignGivenDate form-control">
    </div>
</div>
<div class="form-group col-md-12">
    <label for="" class="col-md-4 text-left">Deadline</label>
    <div class="input-group col-md-8">
        <input type="text" id="assignDeadline{{ $post->id }}" value="{{$post->deadline}}" class="assignDeadline form-control">
    </div>
</div>
<div class="form-group col-md-12">
    <div class="input-group col-md-12">
        <label class="text-left">Assignment topics or other details (Optional) : </label>
        <textarea name="assignmentDetails" id="editableassignment{{ $post->id }}" rows="4" class="col-md-12"></textarea>
    </div>
</div>
<div class="form-group col-md-12">
    <button class="spanBtn cancelUpdatePostBtn" post-type="assignment" post-id="{{ $post->id }}" style="color: #fff; background-color: #f00">Cancel</button>
    <button class="spanBtn updatePostBtn" post-type="assignment" post-id="{{ $post->id }}">Update</button>
</div>