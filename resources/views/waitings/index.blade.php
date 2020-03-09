@extends('layouts.app')


@section('content')
  @include('partials.alerts.success')
  <div class="selectLesson">
    <h2>Waiting Students List</h2>
      @include('partials.form.lesson')
  </div>

  <div class="studentList">
    <table class="table table table-bordered">
      <thead class="thead-dark">
        <tr>
            <th class='grade'>Grade</th>
            <th class='jaName'>名前</th>
            <th class='kanaName'>カナ</th>
            <th class="enName">Name</th>
            <th class='lesson'>Lesson</th>
            <th class="tel1">tel1</th>
            <th class="tel2">tel2</th>
            <th class='email email'>Email</th>
            <th class='email2 email'>Email2</th>
            <th class='province'>Province</th>
            <th class="adress">Address</th>
            <th class="bus">Bus Use</th>
            <th class="pickUp">Pick up</th>
            <th class="send">Send</th>
            <th class="note">Note</th>
            <th class="status">Status</th>
            <th class='delete'>Edit</th>
        </tr>
      </thead>

      <tbody>
        <tr class="table_row_model d-none">
          <td class="grade"></td>
          <td class="jaName"></td>
          <td class="kanaName"></td>
          <td class="enName"></td>
          <td class="lesson"></td>
          <td class="tel1"></td>
          <td class="tel2"></td>
          <td class="email1 email"></td>
          <td class="email2 email"></td>
          <td class="province"></td>
          <td class="address"></td>
          <td class="bus">


          </td>
          <td class="pickUp"></td>
          <td class="send"></td>
          <td class="note"></td>
          <td class="status">
            <select name="status">
               @foreach(Config::get('const.statuses') as $key=>$value)
                 <option value="{{$key}}">{{$value}}</option>
               @endforeach
            </select>
          </td>
          <td class="edit"><a class="btn btn-primary text-white" href="">Edit</a></td>
        </tr>

      </tbody>


    </table>


  </div>


@endsection



@section('script')

<script>
    $(document).ready(function(){
      var url="{{route('waitings.edit',['id'=>''])}}";
      var statuses={!!json_encode(Config::get('const.statuses'))!!}
      var bususes={!! json_encode(Config::get('const.bususes'))!!}
      
      $('.table').hide();

      $('#lesson').change(function(){
        var lesson_id=$(this).children('option:selected').val();

        $.ajax({
          type:'POST',
          url: "{{route('waitings.get_student_ajax')}}",
          data:{id:lesson_id,_token:'{{csrf_token()}}'}
        }).done(function(data){
            $('.cloned_row').remove();


           for(let i=0;i<data.length;i++){
             var cloned_row=$('.table_row_model').clone();
             var addDetails=data[i]['student']['addDetails']?data[i]['student']['addDetails']:'';  // if addDetails is null, display empty string to prevent it from displaying null
             var pickup_details=data[i]['pickup_dtails']?data[i]['pickup_dtails']:'';
             var send_details=data[i]['send_dtails']?data[i]['send_dtails']:'';
             var pickup=data[i]['pickup']?data[i]['pickup']['name']:'';
             var send=data[i]['send']?data[i]['send']['name']:'';
            
             cloned_row.removeClass('d-none');
             cloned_row.addClass('cloned_row');
             cloned_row.find('.grade').text(data[i]['student']['grade']);
             cloned_row.find('.jaName').text(data[i]['student']['jaName']);
             cloned_row.find('.kanaName').text(data[i]['student']['kanaName']);
             cloned_row.find('.enName').text(data[i]['student']['enName']);
             cloned_row.find('.lesson').text(data[i]['lesson']['name']);
             cloned_row.find('.tel1').text(data[i]['student']['tel1']);
             cloned_row.find('.tel2').text(data[i]['student']['tel2']);
             cloned_row.find('.email1').text(data[i]['student']['email1']);
             cloned_row.find('.email2').text(data[i]['student']['email2']);
             cloned_row.find('.province').text(data[i]['student']['province']);
             cloned_row.find('.address').html(data[i]['student']['address']['name']+'<br>'+addDetails);
             cloned_row.find('.bus').text(data[i]['bus'] in bususes ? bususes[data[i]['bus']] : '');
             cloned_row.find('.pickUp').html(pickup+'<br>'+pickup_details);
             cloned_row.find('.send').html(send+'<br>'+send_details);
             cloned_row.find('.note').text(data[i]['note']);
             for(key in statuses){
               
               if(data[i]['status']===parseInt(key)){
                cloned_row.find(".status option[value='"+key+"']").attr('selected','selected');
               }
             }
             cloned_row.find('.edit a').attr('href',url+'/'+data[i]['id']);
             cloned_row.appendTo('tbody');



           }

           $('.table').show();

        });



      });



      




    });

</script>


@endsection
