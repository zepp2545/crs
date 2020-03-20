<div class="form-group">
  <label for="lesson">Lesson <span class="badge badge-danger ml-2">Required</span></label>
  <select class="form-control" name="lesson" id="lesson">
    <option selected disabled>Please select</option>
    @foreach($lessons as $lesson)
      @if(isset($student))
       <option value="{{$lesson['id']}}" {{$lesson['id']==$student->lesson_id ? 'selected' : ''}}>{{$lesson['name']}}</option>
      @else
       <option value="{{$lesson['id']}}" {{$lesson['id']==old('lesson') ? 'selected' : ''}}>{{$lesson['name']}}</option>
      @endif

    @endforeach
  </select>
</div>
