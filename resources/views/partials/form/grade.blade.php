<div class="form-group">
  <label for="grade">Grade<span class="badge badge-danger ml-2">Required</span></label>
  <select class="form-control" name="grade" id="grade">
    <option disabled selected>Please Select</option>
    @foreach(config('const.grades') as $grade)
     @if(isset($student))
      <option value="{{$grade}}" {{$grade==$student->student->grade ? 'selected' : ''}}>{{$grade}}</option>
     @else
      <option value="{{$grade}}" {{$grade==old('grade') ? 'selected' : ''}}>{{$grade}}</option>
     @endif
    @endforeach
  </select>
</div>
