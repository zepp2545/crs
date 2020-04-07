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
  <div class="d-none studentList" id="studentList">
    <h5 class="title_model d-none"><span class="trial d-inline-block"></span><small>  体験生</small>&nbsp;&nbsp;<span class="suspended d-inline-block"></span><small>休会中</small></h5>
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
        var date=new Date();
        var cloned_row;

        $.ajax({
          type:'POST',
          url: "{{route('students.lessonList_ajax')}}",
          data:{id:lesson_id,_token:'{{csrf_token()}}'}
        }).done(function(data){ 
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
         $('#studentList').prepend(title_cloned);
         $('.cloned_row').remove();
         
         
         for(let i=0;i<capacity;i++){
            cloned_row=$('.table_row_model').clone();
            cloned_row.removeClass('table_row_model');
            cloned_row.removeClass('d-none');
            cloned_row.addClass('cloned_row');
            
            //set backgroud color, start day and trial day by status
            

            if(data[i]){
              if((data[i]['status']===4 || data[i]['status']===6) && Date.parse(data[i]['trial_date'])+(1000*60*60*24)>=date.getTime()){
                  cloned_row.addClass('trial');
                  cloned_row.find('.jaName').html(data[i]['student']['jaName']+"<br><span class='important'>Trial Date: "+get_date_with_day(data[i]['trial_date'])+"</span>");      
              }else if((data[i]['status']===7 || data[i]['status']===8)&&Date.parse(data[i]['start_date'])+(1000*60*60*24)>=date.getTime()){
                cloned_row.find('.jaName').html(data[i]['student']['jaName']+"<br><span class='important'>Start Date: "+get_date_with_day(data[i]['start_date'])+"</span>");
              }else if(data[i]['status']===9){
                cloned_row.addClass('suspended');
                cloned_row.find('.jaName').html(data[i]['student']['jaName']);
              }else if(data[i]['status']===6){
                cloned_row.find('.jaName').html(data[i]['student']['jaName']+"<br><span class='important'>[検討中]</span>");
              }else if(data[i]['quit_date']){
                cloned_row.find('.jaName').html(data[i]['student']['jaName']+"<br><span class='notice'>Quit Date: "+get_date_with_day(data[i]['quit_date'])+"</span>");
              }else{
                cloned_row.find('.jaName').text(data[i]['student']['jaName']);
              }
              // if addDetails is null, display empty string to prevent it from displaying null
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
         
         
         
         $('#studentList').removeClass('d-none');

        });



      });

      function get_date_with_day(date){

        let weekChars = [ "日", "月", "火", "水", "木", "金", "土"];

        let date_obj=new Date(date);

        return date+'('+weekChars[date_obj.getDay()]+')';
      
      }


    });


    

</script>


@endsection
