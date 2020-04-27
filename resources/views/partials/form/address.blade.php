<div class="form-group">
  <label for="address">Address @if(!Request::is('waitings/*') && !Request::is('trials/*'))<span class="badge badge-danger ml-2">Required</span>@endif @include('partials.add_place')</label>
  <select class="form-control" name="address" id="address">
    <option selected disabled>Please select</option>
    @foreach($places as $place)
      @if(isset($student))
          <option value="{{$place['id']}}" {{$place['id']==optional($student->student)->address_id ? 'selected' : ''}}>{{$place['name']}}</option>
      @else
          <option value="{{$place['id']}}" {{$place['id']==old('address') ? 'selected' : ''}}>{{$place['name']}}</option>
      @endif

    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="addDetails">Address Details</label>
  <input type="text" id="addDetails" name="addDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? optional($student->student)->addDetails : old('addDetails')}}">
</div>
