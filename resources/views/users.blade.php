@extends('layouts.app')

@section('content')
<div class="container">
    <div class=" row justify-content-center" style="margin-top:30px">

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
                
                <h3 style="padding:20px;padding-bottom:0px;font-weight:bold"> Users Manager </h3>
                <hr style="color:gray;width:95%;margin:auto"> 

                    <table>
                        <thead>
                            <tr class="head">
                                <th> UserId </th>
                                <th class="need"> Image </th>
                                <th class="need"> Name </th>
                                <th> Phone </th>
                                <th> </th>
                                <th> </th> 
                            </tr>
                        </thead>
                        
                        <tbody>
                        @foreach($users as $userr)
                        <tr>
                            <td>{{ $userr->id }} </td>
                            <td class="need">
                            <img src="{{asset($userr->avatar)}}" style="width:50px;height:50px;margin-right:10px">
                            </td>
                            <td class="need">{{ $userr->name }} </td>
                            <td>{{ $userr->phone }}</td>
                            @if($userr->activate == 1)
                            
                            <td>
                            <a href="{{ url('/activateUser/' . $userr->id ) }}" class="btn btn-xs btn-success pull-right"> Active</a>
                            </td>
                            
                            @elseif($userr->activate == 2)
                            
                            <td>
                            <a href="{{ url('/notactivateUser/' . $userr->id ) }}" class="btn btn-xs btn-success pull-right">Not Active</a>
                            </td>
                            
                            @endif
                            <td>
                            <a href="{{ url('/deleteUser/' . $userr->id ) }}" class="btn btn-xs btn-danger pull-right">Delete</a>
                            </td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                       {{$users->links()}}
            </div>

    </div>
</div>
@endsection
