<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    // update post activate to 1
    public function updateUserActivate(Request $request){
        $user = User::where('id',$request->id)->first();

        if($user && $user->activate == 2)
        {
            $user->activate = 1;
            $user->save();

            return response()->json([
                'status'  => 1 ,
                'message' => 'user updated' ,
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'not updated' ,
            ]);
        }
    }


    // update post activate to 1
    public function updateActivate(Request $request){
        $post = Post::where('id',$request->id)->first();

        if($post && $post->activate == 2)
        {
            $post->activate = 1;
            $post->save();

            return response()->json([
                'status'  => 1 ,
                'message' => 'post updated' ,
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'not updated' ,
            ]);
        }
    }


    // test users
    public function users()
    {
        $user = User::find(auth()->user()->id);
        $users = User::whereNotIn('activate',[3])->latest()->simplePaginate(20);
        return view('users')->with('users',$users)->with('user',$user) ;
    }


    public function activateUser($userid)
    {
        $userr = User::find($userid);
        $userr->activate = 2*1;
        $userr->save();
        return redirect()->back() ;
    }
    
    
    public function notactivateUser($userid)
    {
        $userr = User::find($userid);
        $userr->activate = 1*1;
        $userr->save();
        return redirect()->back() ;
    }
    

    public function deleteUser($userid)
    {
        $userr = User::find($userid);
        $userr->activate = 3*1;
        $userr->save();
        return redirect()->back() ;
    }
    

    // share posts
    public function sharePost(Request $request)
    {
        if ( User::find(auth()->user()->id) && Post::find($request->post_id))
        {
            Share::create([
                'user_id'  => auth()->user()->id,
                'post_id'  => $request->post_id,
            ]);

            $shares = Share::where('post_id',$request->post_id)->get()->count();

            return response()->json([
                'status' => 1 ,
                'message' => 'share done',
                'sharesCount' => $shares
            ]);
        }

        return response()->json([
            'status' => 0 ,
            'message' => 'share failed',
            'sharesCount' => 0*1
        ]);
    }


     public function acceptPost($postId)
    {
        $post = Post::find($postId);
        $post->activate = 1*1 ;
        $post->save();

        return redirect()->back();
    }


    public function deletePost($postId)
    {
        $post = Post::find($postId);
        $post->activate = 3*1 ;
        $post->save();

        return redirect()->back();
    }


    public function pending()
    {
        $user = User::find(auth()->user()->id);
        $posts = Post::where('activate', 2)->latest()->simplePaginate(5);
        return view('home')->with('user',$user)->with('posts',$posts) ;
    }


    public function accepted()
    {
        $user = User::find(auth()->user()->id);
        $posts = Post::where('activate', 1)->latest()->simplePaginate(5);
        return view('home')->with('user',$user)->with('posts',$posts) ;
    }


}
