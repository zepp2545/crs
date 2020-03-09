<div class="form-group">
  <label for="pickup">Pick Up</label>
  <select class="form-control" name="pickup" id="pickup">
    <option selected disabled>Please select</option>
    @foreach($places as $place)
      <option value="{{$place['id']}}">{{$place['name']}}</option>
    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="pickupDetails">Pick Up Details</label>
  <input type="text" id="pickupDetails" name="pickupDetails">
</div>
