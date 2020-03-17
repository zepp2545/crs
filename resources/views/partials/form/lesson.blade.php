<div class="form-group">
  <label for="lesson">Lesson @if(Request::is('waitings/'))<span class="badge badge-danger ml-2">Required</span>@endif</label>
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
