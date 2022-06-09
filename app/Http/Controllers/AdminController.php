<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;


class AdminController extends Controller
{


    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'Posts' ;
    }
    



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
        $users = User::whereNotIn('activate',[3])->where('id', '!=', auth()->id())->latest()->simplePaginate(20);
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
        $key = $postId ;
        $post = Post::find($postId) ;
        $post->activate = 1*1 ;
        $post->save();

        if($post->activate == 3 )
        {
            $delete = 1*1 ;
        }
        else
        {
            $delete = 0*1 ;
        }

        if($post->avatar != null )
        {
            $avatar = 1*1 ;
        }
        else
        {
            $avatar = 0*1 ;
        }
        
        if($post->latitude != null )
        {
            $latitude = $post->latitude;
        }
        else
        {
            $latitude ='null'  ;
        }

        if($post->longitude != null )
        {
            $longitude = $post->longitude ;
        }
        else
        {
            $longitude = 'null';
        }

        $postData = [
            'is360' => $avatar ,
            'isDeleted' => $delete ,
            'longitude' => $longitude ,
            'latitude' =>  $latitude ,
            'postID' =>  $post->id,
            'userID' => $post->user_id
        ] ;
        $postRef = $this->database->getReference($this->tablename)->getChild($key)->update($postData);  

        return redirect()->back();
    }


    public function deletePost($postId)
    {

        $post = Post::find($postId);
        $post->activate = 3*1 ;
        $post->save();

        $key = $postId ;
        $postUpdate = [
            'isDeleted' => 1*1,
        ] ;

        $postRef = $this->database->getReference($this->tablename.'/'.$key)->update($postUpdate); 

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


    public function save($postId)
    {
        $key = $postId ;
        $post = Post::find($postId) ;

        if($post->activate == 3 )
        {
            $delete = 1*1 ;
        }
        else
        {
            $delete = 0*1 ;
        }

        if($post->avatar != null )
        {
            $avatar = 1*1 ;
        }
        else
        {
            $avatar = 0*1 ;
        }
        
        if($post->latitude != null )
        {
            $latitude = $post->latitude;
        }
        else
        {
            $latitude ='null'  ;
        }

        if($post->longitude != null )
        {
            $longitude = $post->longitude ;
        }
        else
        {
            $longitude = 'null';
        }

        $postData = [
            'is360' => $avatar ,
            'isDeleted' => $delete ,
            'longitude' => $longitude ,
            'latitude' =>  $latitude ,
            'postID' =>  $post->id,
            'userID' => $post->user_id
        ] ;
        $postRef = $this->database->getReference($this->tablename)->getChild($key)->push($postData);  
        
        return redirect()->back();
    }



}
