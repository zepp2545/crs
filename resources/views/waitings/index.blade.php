@extends('layouts.app')


@section('content')
  @include('partials.alerts.success')
  <div class="selectLesson">
    <h2>Waiting Students List</h2>
      @include('partials.form.lesson')
  </div>

  <div class="studentList">
         <div class="status_description">
         白は「ウェイティング中」です。
            <span class="ml-4 continue d-inline-block"></span>体験待ち
            <span class="ml-4 cancel d-inline-block"></span>キャンセル済み</small>
            <span class="ml-4 consider d-inline-block"></span> 体験検討中（連絡待ち）
         </div>
            
    <table class="table table table-bordered">
      <thead class="thead-dark">
        <tr>
            <th class="application_date">Application Date</th>
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
          <td class="application_date"></td>
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
          <td class="bus"></td>
          <td class="pickUp"></td>
          <td class="send"></td>
          <td class="note"></td>
          <td class="status">
            <select name="status">
               @foreach(Config::get('const.statuses') as $key=>$value)
                @if($key!==1 && $key!==2 && $key!==3 && $key!==4)
                  @continue
                @else
                 <option value="{{$key}}">{{$value}}</option>
                @endif
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
      var cloned_row;
      
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
             cloned_row=$('.table_row_model').clone();
             var addDetails=data[i]['student']['addDetails']?data[i]['student']['addDetails']:'';  // if addDetails is null, display empty string to prevent it from displaying null
             var pickup_details=data[i]['pickup_details']?data[i]['pickup_details']:'';
             var send_details=data[i]['send_details']?data[i]['send_details']:'';
             var pickup=data[i]['pickup']?data[i]['pickup']['name']:'';
             var send=data[i]['send']?data[i]['send']['name']:'';
             var application_date=new Date(data[i]['created_at']);
             cloned_row.removeClass('d-none');
             cloned_row.removeClass('table_row_model');
             cloned_row.addClass('cloned_row');
             cloned_row.attr('data-id',data[i]['id']);
             cloned_row.find('.application_date').text(application_date.getFullYear()+"-"+"0"+String(application_date.getMonth()+1)+"-"+application_date.getDate());
             cloned_row.find('.grade').text(data[i]['student']['grade']);
             cloned_row.find('.jaName').text(data[i]['student']['jaName']);
             cloned_row.find('.kanaName').text(data[i]['student']['kanaName']);
             cloned_row.find('.enName').text(data[i]['student']['enName']);
             cloned_row.find('.lesson').text(data[i]['lesson_group']['name']);
             cloned_row.find('.tel1').text(data[i]['student']['tel1']);
             cloned_row.find('.tel2').text(data[i]['student']['tel2']);
             cloned_row.find('.email1').text(data[i]['student']['email1']);
             cloned_row.find('.email2').text(data[i]['student']['email2']);
             cloned_row.find('.province').text(data[i]['student']['province']);
             if(data[i]['student']['address']){
              cloned_row.find('.address').html(data[i]['student']['address']['name']+'<br>'+addDetails);
             }
             cloned_row.find('.bus').text(data[i]['bus'] in bususes ? bususes[data[i]['bus']] : '');
             cloned_row.find('.pickUp').html(pickup+'<br>'+pickup_details);
             cloned_row.find('.send').html(send+'<br>'+send_details);
             cloned_row.find('.note').text(data[i]['note']);
             for(key in statuses){
               if(data[i]['status']===parseInt(key)){

                 if(data[i]['status']==2){
                  cloned_row.find(".status option[value='"+key+"']").attr('selected','selected');
                  cloned_row.css('background-color','red');
                 }else if(data[i]['status']==3){
                  cloned_row.find(".status option[value='"+key+"']").attr('selected','selected');
                  cloned_row.css('background-color','yellow');
                 }else if(data[i]['status']==4){
                  cloned_row.find(".status option[value='"+key+"']").attr('selected','selected');
                  cloned_row.css('background-color','#ddd');
                 }else{
                  cloned_row.find(".status option[value='"+key+"']").attr('selected','selected');
                 }
                 
                   
               }
             }
             cloned_row.find('.edit a').attr('href',url+'/'+data[i]['id']);
             cloned_row.appendTo('tbody');

           }

           $('.table').show();

        });



      });
      
      
      //change status
      var pre_status;
      $(document).on('click','.status select',function(){
        pre_status=$(this).val();
      });
      
      $(document).on('change','.status select',function(){
        if(confirm('Are you sure you want to change the status of this student?')){
          var student_lesson_id=$(this).parents('tr').data('id');
          var status=$(this).children('option:selected').val();

          console.log(student_lesson_id);

          $.ajax({
            type:'POST',
            url: "{{route('waiting.status')}}",
            data:{id:student_lesson_id,status:status,_token:'{{csrf_token()}}'}
          }).done(function(data){
            if(status==4){
              $("[data-id="+student_lesson_id+"]").css('background-color','#ddd');
              window.location.href="{{url('/trials')}}"+"/"+data.id+"/edit";
            }else if(status==2){
              $("[data-id="+student_lesson_id+"]").css('background-color','red');
            }else if(status==3){
              $("[data-id="+student_lesson_id+"]").css('background-color','yellow');
            }else{
              $("[data-id="+student_lesson_id+"]").css('background-color','white');
            }

          });

        }else{
          $(this).val(pre_status);
          return;
        }


      });




      




    });

</script>


@endsection
