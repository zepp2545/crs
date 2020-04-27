<div class="form-group">
  <label for="busUse">Bus Use @if(!Request::is('waitings/*') && !Request::is('trials/*'))<span class="badge badge-danger ml-2">Required</span>@endif</label>
  <select class="form-control" name="busUse" id="busUse">
    <option selected disabled>Please select</option>
    @foreach(config('const.bususes') as $key => $value)
      @if(isset($student))
        <option value="{{$key}}" {{$key==$student->bus ? 'selected' : ''}}>{{$value}}</option>
      @else
        <option value="{{$key}}" {{$key==old('busUse') ? 'selected' : ''}}>{{$value}}</option>
      @endif
    @endforeach
  </select>
</div>
