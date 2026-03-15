@extends('adminlte::page')
@section('content')
<div class="container">
      <div class="row">
          <h2>Mostrar Asset</h2>
      </div>
      <div class="row">
       <img class="card-img-top" src="{{ route('imageVideo', $asset->image) }}" />


       <div class="video-content">
               <video width="500" height="350" controls autoplay muted>
                   <source src="{{ route('fileVideo', $asset->video_path) }}" type="video/mp4" />
                   Tu navegador no es compatible con HTML5
               </video>
           </div>


      </div>
</div>
@endsection
