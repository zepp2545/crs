@extends('layouts.app')

@section('title')
  Places Setting
@endsection

@section('content')

@include('partials.alerts.success')
@include('partials.alerts.error')

<h2>Places Settings</h2>
<ul class="mt-4">
  <li>
    この設定で登録されているコンドやムーバーンがRegister時のAddress,Pick up,Sendに表示されます。
    登録されていないコンドやムーバーンはここから登録してください。
  </li>
</ul>

<div class="d-flex justify-content-end">
 <button  type="button" class="btn btn-primary btn-sm mr-3 ml-3" data-toggle="modal" data-target="#add">Add Place</button>
</div>
 <!-- The Modal -->
<div class="modal" id="add">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Place</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form action="{{route('settings.places.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Please type a name of the condo or mubaan">

            </div>
            
            
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button class="btn btn-info" type="submit">Create</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>

    </div>
  </div>
</div>
        

<ul class="list-group mt-4">

  @foreach($places as $place)
  
    <li class="list-group-item d-flex justify-content-between">
      {{$place->name}}
      <div class="buttons">

        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$place->id}}">Edit</button>
        <form action="{{route('settings.places.delete',['id'=>$place->id])}}" method="post" class="d-inline-block">
          @csrf 
          @method('delete')
          <button class="btn btn-danger place_delete_button" type="submit">Delete</button>
        </form>
             
              <!-- The Modal for edit place-->
              <div class="modal" id="myModal{{$place->id}}">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title">Edit {{$place->name}}</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{route('settings.places.update',['id'=>$place->id])}}" method="post">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" name="name" value="{{$place->name}}" placeholder="Please type a name of the condo or mubaan">

                      </div>

                    
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button class="btn btn-info" type="submit">Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    </form>

                  </div>
                </div>
              </div>

      </div>
      
    </li>

  @endforeach


</ul>
  


@endsection



@section('script')

<script>

$(document).ready(function(){

//show dialog when deleting place.
$('.place_delete_button').click(function(){

    if(!confirm('Are you sure you want to delete this place? If you delete this place, this will cause some problems in lists.')){
        return false;
    }

});




});

</script>

@endsection



