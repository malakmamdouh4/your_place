@extends('layouts.app')

@section('content')
<div class="container">
    <div class=" row justify-content-center" style="margin-top:30px;">

            <div class="sidebar" style=" float:left ; background-color:#fff;border-radius:20px;padding:12px"> 
                <p style="padding-left:15px;font-size:20px"> 

                <img src="{{asset($user->avatar)}}" style="width:50px;height:50px;margin-right:10px">
                    {{ $user->name }}  

                </p>
                <hr style="color:gray">

               <div class="hovering">
                    <i class="fas fa-table" style="color:#dbdcde ; padding:12px;font-size:20px"></i>
                    <a href="{{route('home')}}"> Dashboard </a>
               </div>

               <div class="hovering">
                    <i class="fas fa-user-friends" style="color:#dbdcde ; padding:12px;font-size:20px"></i>
                    <a href="{{route('users')}}"> Users Manager </a>
               </div>

            </div>

            <div class="posts" style="float:left ; background-color:#fff;border-radius:20px;padding:12px"> 
                <h3 style="padding:20px;padding-bottom:0px;font-weight:bold"> Posts </h3>
                <hr style="color:gray;width:95%;margin:auto"> 

                <div class="portfolio-menu" style="padding:10px">
                    <ul>
                        <li > <a class="active" href="{{route('home')}}"> All Posts </a> </li>
                        <li > <a href="{{route('pending')}}"> Pending </a> </li>
                        <li > <a href="{{route('accepted')}}"> Accepted </a> </li>
                    </ul>
                </div>

                <div>
               
                        @if (count($posts) > 0)
                        
                        @foreach($posts as $post)

                        @if(count($post->images) > 0 )
                        <img src="{{asset($post->images[0]['path'])}}" alt="image of post" style="width:320px;height:200px;border-radius:30px;padding:10px"> 
                        <h4> {{ $post->title }}</h4>
                        <p style="color:gray"> {{ $post->description }} . <span style="color:#154c79;font-weight: bold;"> Price : {{ $post->price }} </span> </p>

                        @if($post->activate  == 2 )

                        <form action="{{ url('/accept/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-primary pull-right" style="width:48%;float:left;margin-left:2%"> Accept </button> 
                        </form> 


                        <form action="{{ url('/delete/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-danger pull-right" style="width:48%;float:left;margin-left:2%"> Reject </button> 
                        </form> 

                        @elseif($post->activate  == 1)


                        <form action="{{ url('/delete/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-danger pull-right" style="width:100%"> Delete this post </button> 
                        </form> 

                        @endif
                        <br> <br>
                        <hr style="color:gray;width:95%;margin: 25px auto">
                        
                        @elseif(count($post->images) == 0)
                       
                        <h4> {{ $post->title }}</h4>
                        <p style="color:gray"> {{ $post->description }} . <span style="color:#154c79;font-weight: bold;"> Price : {{ $post->price }} </span> </p>
                        @if($post->activate  == 2 )

                        <form action="{{ url('/accept/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-primary pull-right" style="width:48%;float:left;margin-left:2%"> Accept </button> 
                        </form> 


                        <form action="{{ url('/delete/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-danger pull-right" style="width:48%;float:left;margin-left:2%"> Reject </button> 
                        </form>

                        @elseif($post->activate  == 1)

                        <form action="{{ url('/delete/' . $post->id ) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-danger pull-right" style="width:100%"> Delete this post </button> 
                        </form> 

                        @endif
                        <br> <br>
                        <hr style="color:gray;width:95%;margin: 25px auto">
                        @endif
                        @endforeach
                           {{$posts->links()}}  
                           
                        @else
                        <h2 style="padding:25px"> There Is No Posts Yet </h2>
                        @endif
                        

                </div>

            </div>
    </div>
</div>
@endsection
