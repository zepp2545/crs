<div class="form-group">
  <label for="pickup">Pick Up</label>
  <select class="form-control" name="pickup" id="pickup">
    <option selected disabled>Please select</option>
    @foreach($places as $place)
      @if(isset($student))
          <option value="{{$place['id']}}" {{$place['id']==$student->pickup_id ? 'selected' : ''}}>{{$place['name']}}</option>
      @else
          <option value="{{$place['id']}}" {{$place['id']==old('pickup') ? 'selected' : ''}}>{{$place['name']}}</option>
      @endif
    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="pickupDetails">Pick Up Details</label>
  <input type="text" id="pickupDetails" name="pickupDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{isset($student) ? $student->pickup_details : old('pickupDetails')}}">
</div>
