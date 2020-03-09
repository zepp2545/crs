<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
  </head>
  <body>
     <div class="mainRow">
       <div class="sidebar bg-light">
         <div class="container my-4">
           <h2 class="text-center"><a href="#">Menu</a></h2>
           <div class="list mt-4">
             <ul>
               <li class="dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Regular Students</a>
                  <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="{{route('students.index')}}">Student List</a>
                    <a class="dropdown-item" href="{{route('students.lessonList')}}">Lesson List</a>
                    <a class="dropdown-item" href="{{route('students.dropouts')}}">Drop Out List</a>
                  </div>
                </li>
                <li class="dropdown">
                   <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Trial Students</a>
                   <div class="dropdown-menu" aria-labelledby="dropdown02">
                     <a class="dropdown-item" href="{{route('trials.index')}}">Trial Student List</a>
                     <a class="dropdown-item" href="{{route('trials.create')}}">Register Trial Student</a>
                   </div>
                 </li>
                 <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Waiting Students</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown03">
                      <a class="dropdown-item" href="{{route('waitings.index')}}">Waiting Student List</a>
                      <a class="dropdown-item" href="{{route('waitings.create')}}">Register Waiting Student</a>
                    </div>
                  </li>
                  <li>
                    <a href="{{route('bulkemail.create')}}" class="nav-link">Bulk Email</a>
                  </li>
                  <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                      <a class="dropdown-item" href="{{route('settings.lessons')}}">Lessons</a>
                      <a class="dropdown-item" href="{{route('settings.places')}}">Places</a>
                    </div>
                  </li>
             </ul>
           </div>

         </div>
       </div>
       <div class="mainContent p-4">
         <div class="container">
           @yield('content')
         </div>
       </div>


     </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     <script src="{{asset('js/general.js')}}"></script>
     @yield('script')
  </body>
</html>
