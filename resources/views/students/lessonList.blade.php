@extends('layouts.app')


@section('content')
  
  <div class="top">
  <h2 class="mb-3">Lesson List</h2>
    <div class="form-group">
      <label for="lesson">Select Lesson</label>
      <select name="lesson" id="lesson" class="form-control">
          <option disabled selected>選択してください。</option>
        @foreach($lessons as $lesson)
          <option value="{{$lesson->id}}">{{$lesson->name}}</option>
        @endforeach
      </select>
    </div>
    
  </div>
  <div class="studentList d-none">
    <h5 class="title_model d-none"><span class="trial d-inline-block"></span><small>  体験生</small></h5>
    <table class="table table-bordered lessonList">
        
        <thead class="thead-dark">
          <tr>
              <th class="number">No.</th>
              <th class='grade'>Grade</th>
              <th class='jaName'>名前</th>
              <th class="enName">Name</th>
              <th class="bus">Bus Use</th>
              <th class="pickUp">Pick up</th>
              <th class="send">Send</th>
              <th class="note">Note</th>
          </tr>
        </thead>

        <tbody>
          <tr class="table_row_model d-none">
            <td class="number"></td>
            <td class="grade"></td>
            <td class="jaName"></td>
            <td class="enName"></td>
            <td class="bus"></td>
            <td class="pickUp"></td>
            <td class="send"></td>
            <td class="note"></td>
          </tr>

        </tbody>


      </table>



  </div>


@endsection



@section('script')

<script>
    $(document).ready(function(){

      var bususes={!! json_encode(Config::get('const.bususes'))!!};

      $('#lesson').change(function(){
        var lesson_id=$(this).children('option:selected').val();
        var lesson_name=$(this).children('option:selected').text();
        
        

        $.ajax({
          type:'POST',
          url: "{{route('students.lessonList_ajax')}}",
          data:{id:lesson_id,_token:'{{csrf_token()}}'}
        }).done(function(data){ 
         console.log(data);
         if(data[0]){
          var capacity=parseInt(data[0]['lesson']['capacity']);
         }else{
          var capacity=data['capacity'];
         }
         $('.cloned_title').remove();
         let title_cloned=$('.title_model').clone();
         title_cloned.removeClass('d-none');
         title_cloned.removeClass('title_model');
         title_cloned.addClass('cloned_title');
         title_cloned.prepend(lesson_name+' 定員:'+capacity+'名'+'\xa0\xa0\xa0\xa0\xa0\xa0');
         title_cloned.prependTo('.studentList');

         $('.cloned_row').remove();
          for(let i=0;i<capacity;i++){
     
            let cloned_row=$('.table_row_model').clone();
            cloned_row.addClass('cloned_row');
            cloned_row.removeClass('d-none');
            cloned_row.removeClass('table_row_model');
            // if addDetails is null, display empty string to prevent it from displaying null
            if(data[i]){
              if(data[i]['status']===4 || data[i]['status']===6){
                cloned_row.addClass('trial');
                cloned_row.find('.jaName').html(data[i]['student']['jaName']+"<br><span class='important'>Trial Date: "+data[i]['trial_date']);
              }else{
                cloned_row.find('.jaName').text(data[i]['student']['jaName']);
              }
              let pickup_details=data[i]['pickup_details']?data[i]['pickup_details']:'';
              let send_details=data[i]['send_details']?data[i]['send_details']:'';
              let pickup=data[i]['pickup']?data[i]['pickup']['name']:'';
              let send=data[i]['send']?data[i]['send']['name']:'';
              cloned_row.find('.grade').text(data[i]['student']['grade']);
              
              cloned_row.find('.enName').text(data[i]['student']['enName']);
              cloned_row.find('.bus').text(data[i]['bus'] in bususes ? bususes[data[i]['bus']] : '');
              cloned_row.find('.pickUp').html(pickup+'<br>'+pickup_details);
              cloned_row.find('.send').html(send+'<br>'+send_details);
              cloned_row.find('.note').text(data[i]['note']);
            }
            cloned_row.find('.number').text(i+1);
            cloned_row.appendTo('tbody');
           }
       
           $('.studentList').removeClass('d-none');

        });



      });


    });

</script>


@endsection
