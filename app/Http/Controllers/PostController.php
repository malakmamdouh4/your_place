<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon ;
use Illuminate\Support\Str;
use File;

class PostController extends Controller
{

    // create new post
    public function newPost(Request $request)
    {

        $user = User::find($request->user_id) ;
      
        $amenities = $request->input('amenities');
      
        $newDate = Carbon::createFromFormat('Y-m-d', Carbon::now()->toDateString())->format('d M Y');;
        $dayName = substr(Carbon::parse($newDate)->dayName, 0,3) ;


        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => ' user not found ',
            ]);
        }
        elseif ($user)
        {

            $post = Post::create([
                'title' => $request->input('title') ,
                'description' => $request->input('description') ,
                'price' => $request->input('price') ,
                'phone' => $request->input('phone') ,
                'longitude' => $request->input('longitude') ,
                'latitude' => $request->input('latitude') ,
                'name' => $request->input('name') ,
                'date' => $dayName . ', ' . $newDate  ,
                'category' => $request->category ,
                'company' => $request->company ,
                'type' => $request->type ,
                'bedrooms' => $request->bedrooms ,
                'bathrooms' => $request->bathrooms ,
                'area' => $request->area ,
                'level' => $request->level ,
                'furnished' => $request->furnished ,
                'compound' => $request->compound ,
                'deliveryDate' => $request->deliveryDate ,
                'deliveryTerm' => $request->deliveryTerm ,
                'user_id' =>$user->id*1,
            ]);
            foreach ($amenities as $amenity)
            {
                Amenity::firstOrCreate([
                    'name' => $amenity ,
                    'post_id' => $post->id*1
                ]);
            }

            return response()->json([
                'status' => 1 ,
                'message' => $post->id*1,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed to create post',
            ]);
        }

    }
    
    
    // save images to specific user 
    public function uploadImages(Request $request)
    {
        $files = $request->file('image');
        $post = Post::find($request->post_id);
        $allowedfileExtension=['jpg','png','jpeg','gif'];

        
        if(!$post || $request->post_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'post not found',
            ]);
        }
        elseif(!$request->hasFile('image')) {
            return response()->json([
                'status' => 0 ,
                'message' => 'upload_file not found',
            ]);
        }
        elseif($post && $request->hasFile('image'))
        {
            foreach ($files as $file)
            {

                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);

                if($check)
                {
                    foreach($request->image as $mediaFiles)
                    {
                        $path = $mediaFiles->store('public');
                        $truepath = substr($path, 7);

                        Image::firstOrCreate([
                            'path' => URL::to('/') . '/storage/' . $truepath ,
                            'post_id' => $post->id*1
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'status' => 0 ,
                        'message' => 'invalid_file_format',
                    ]);
                }
                
                return response()->json([
                        'status' => 1 ,
                        'message' => 'images saved successfully',
                    ]);
                
            }
        }
        
        
    }
    
    
    
    public function uploadMultiImages(Request $request)
    {

        $post = Post::find($request->post_id);
        $images = $request->image ;

        if(!$post || $request->post_id == null )
        {
             return response()->json([
                        'status' => 0 ,
                        'message' => 'post not found',
                    ]);    
        }
        elseif(is_countable($images) && count($images) > 0 )
        {
            foreach ($images as $imagee)
            {
                
                $imageName=$this->generateRandomString();
                
                $image = str_replace('data:image/png;base64,', '', $imagee);
                $image = str_replace(' ', '+', $image);
        
                $file = base64_decode($image);

                foreach($request->image as $data)
                {
                    $data = 'storage/' . $imageName . "asddd" . '.jpg';

                    $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
                    file_put_contents($data, $file);
                    
                    $tmpFile = new File($data);
                
                  Image::firstOrCreate([
                            'path' => "http://localhost:8000/" . $data ,
                            'post_id' =>$request->post_id*1
                        ]);       
                //   echo $data . " _ ";
                
                }

            }

             return response()->json([
                        'status' => 1 ,
                        'message' => 'images saves successfully',
                    ]);            
        }
        else
        {
           return response()->json([
                        'status' => 0 ,
                        'message' => 'error or images not found',
                    ]);     
        }

    }
    


    public function showImage360(Request $request)
    {
        $post = Post::find($request->post_id);
        if($request->post_id == null)
        {
            return response()->json([
                'status' => 1 ,
                'message' => 'please enter post id',
            ]);
        }
        elseif(!$post)
        {
            return response()->json([
                'status' => 1 ,
                'message' => 'post not found',
            ]);
        }
        elseif($post && $request->post_id != null)
        {
            return response()->json([
                'status' => 1 ,
                'message' => $post->avatar,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 1 ,
                'message' =>'error',
            ]);
        }
    
    }



    // show all posts in home page with paginate ( activate = 1 )
    public function showPosts(Request $request)
    {

         $posts = Post::select('id','title','description' ,'price' ,'phone','name','date',
             'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
              'compound' , 'deliveryDate' , 'deliveryTerm' ,'user_id')
             ->with('images','user:id,name,avatar','amenities')
             ->with('saves', function ($query) use ($request) {
                 $query->where('user_id',$request->user_id);
             })
             ->where('activate',1)
             ->simplePaginate(10)->toArray();

         $postsCount = Post::where('activate',1)->count();

         if($postsCount > 0 )
         {
             return response()->json([
                 'status' => 1 ,
                 'message' => 'All Posts',
                 'currentPage' => $posts['current_page'],
                 'firstPageUrl' => $posts['first_page_url'],
                 'nextPageUrl' => $posts['next_page_url'],
                 'prevPageUrl' => $posts['prev_page_url'],
                 'perPage' => $posts['per_page'],
                 'countOfPages' => ceil($postsCount/2),
                 'posts' => $posts['data'],
             ]);
         }
         else
         {
             return response()->json([
                 'status' => 1 ,
                 'message' => 'No posts',
                 'currentPage' => null ,
                 'firstPageUrl' => null ,
                 'nextPageUrl' => null ,
                 'prevPageUrl' => null ,
                 'perPage' => null ,
                 'countOfPages' => 0 ,
                 'posts' => null ,
             ]);

         }
    }


    // show specific post
    public function postInfo(Request $request)
    {
        $postId = Post::find($request->post_id);
        $post = Post::select('id','title','description' ,'price' ,'phone','name','date','longitude','latitude',
            'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
            'compound' , 'deliveryDate' , 'deliveryTerm','user_id')
            ->with('images','user:id,name,avatar','amenities')
            ->with('saves', function ($query) use ($request) {
                $query->where('user_id',$request->user_id);
            })
            ->where('id',$request->post_id)->first();

        if(!$postId || $request->post_id == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'post not found',
                'post' => null
            ]);
        }
        elseif($post )
        {
            return response()->json([
                'status' => 1 ,
                'message' => 'Post info',
                'post' => $post
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error',
                'post' => null
            ]);
        }
    }


    // update post
    public function updatePost(Request $request)
    {
        $user = User::find($request->user_id) ;
        $post = Post::find($request->post_id);
        $amenities = $request->amenities;

        if (!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif (!$post || $request->post_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'post not found',
            ]);
        }
        elseif($post->user_id != $request->user_id)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'you are unauthenticated',
            ]);
        }
        elseif ($post && $post->user_id == $request->user_id)
        {
            
            if($request->input('title') != null )
            {
              $post->title = $request->input('title');
              $post->save();
            }
            elseif($request->input('title') == null)
            {
              $post->title = $post->title  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in title',
                ]);   
            }
            
            if($request->input('description') != null )
            {
              $post->description = $request->input('description');
              $post->save();
            }
            elseif($request->input('description') == null)
            {
              $post->description = $post->description  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in description',
                ]);   
            }
            
            if($request->input('price') != null )
            {
              $post->price = $request->input('price');
              $post->save();
            }
            elseif($request->input('price') == null)
            {
              $post->price = $post->price  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in price',
                ]);   
            }
            
            if($request->input('phone') != null )
            {
              $post->phone = $request->input('phone');
              $post->save();
            }
            elseif($request->input('phone') == null)
            {
              $post->phone = $post->phone  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in phone',
                ]);   
            }
            
            if($request->input('name') != null )
            {
              $post->name = $request->input('name');
              $post->save();
            }
            elseif($request->input('name') == null)
            {
              $post->name = $post->name  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in name',
                ]);   
            }
            
            if($request->input('company') != null )
            {
              $post->company = $request->input('company');
              $post->save();
            }
            elseif($request->input('company') == null)
            {
              $post->company = $post->company  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in company',
                ]);   
            }
            
            if($request->input('longitude') != null )
            {
              $post->longitude = $request->input('longitude');
              $post->save();
            }
            elseif($request->input('longitude') == null)
            {
              $post->longitude = $post->longitude  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in longitude',
                ]);   
            }
            
            if($request->input('latitude') != null )
            {
                $post->latitude = $request->input('latitude'); 
                $post->save();
            }
            elseif($request->input('latitude') == null )
            {
              $post->latitude = $post->latitude;
              $post->save();
            }
             else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in latitude',
                ]);   
            }
            
            if($request->input('category') != null )
            {
              $post->category = $request->input('category');
              $post->save();
            }
            elseif($request->input('category') == null)
            {
              $post->category = $post->category  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in category',
                ]);   
            }
            
            if($request->input('company') != null )
            {
              $post->company = $request->input('company');
              $post->save();
            }
            elseif($request->input('company') == null)
            {
              $post->company = $post->company  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in company',
                ]);   
            }
            
            if($request->input('type') != null )
            {
              $post->type = $request->input('type');
              $post->save();
            }
            elseif($request->input('type') == null)
            {
              $post->type = $post->type  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in type',
                ]);   
            }
            
            if($request->input('bedrooms') != null )
            {
              $post->bedrooms = $request->input('bedrooms');
              $post->save();
            }
            elseif($request->input('bedrooms') == null)
            {
              $post->bedrooms = $post->bedrooms  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in bedrooms',
                ]);   
            }
            
            if($request->input('bathrooms') != null )
            {
              $post->bathrooms = $request->input('bathrooms');
              $post->save();
            }
            elseif($request->input('bathrooms') == null)
            {
              $post->bathrooms = $post->bathrooms  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in bathrooms',
                ]);   
            }
            
            if($request->input('level') != null )
            {
              $post->level = $request->input('level');
              $post->save();
            }
            elseif($request->input('level') == null)
            {
              $post->level = $post->level  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in level',
                ]);   
            }
            
            if($request->input('furnished') != null )
            {
              $post->furnished = $request->input('furnished');
              $post->save();
            }
            elseif($request->input('furnished') == null)
            {
              $post->furnished = $post->furnished  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in furnished',
                ]);   
            }
            
            if($request->input('compound') != null )
            {
              $post->compound = $request->input('compound');
              $post->save();
            }
            elseif($request->input('compound') == null)
            {
              $post->compound = $post->compound  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in compound',
                ]);   
            }
            
            if($request->input('deliveryDate') != null )
            {
              $post->deliveryDate = $request->input('deliveryDate');
              $post->save();
            }
            elseif($request->input('deliveryDate') == null)
            {
              $post->deliveryDate = $post->deliveryDate  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in deliveryDate',
                ]);   
            }
            
            if($request->input('deliveryTerm') != null )
            {
              $post->deliveryTerm = $request->input('deliveryTerm');
              $post->save();
            }
            elseif($request->input('deliveryTerm') == null)
            {
              $post->deliveryTerm = $post->deliveryTerm  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in deliveryTerm',
                ]);   
            }
            
            if($request->input('area') != null )
            {
              $post->area = $request->input('area');
              $post->save();
            }
            elseif($request->input('area') == null)
            {
              $post->area = $post->area  ; 
              $post->save();
            }
            else
            {
                 return response()->json([
                    'status' => 0 ,
                    'message' => 'error in area',
                ]);   
            }


            if(count($amenities) > 0 )
            {
                Amenity::where('post_id',$request->post_id)->delete();
                 foreach ($amenities as $amenity)
                {
                    Amenity::firstOrCreate([
                        'name' => $amenity ,
                        'post_id' => $post->id*1
                    ]);
                }
            
                return response()->json([
                    'status' => 1 ,
                    'message' => 'Post updated',
                ]);
            }
            else
            {
                
            }
            
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error',
            ]);
        }
    }


    // delete post
    public function deletePost(Request $request)
    {
        $user = User::find($request->user_id);
        $post = Post::find($request->post_id);

        if (!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif (!$post || $request->post_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'post not found',
            ]);
        }
        elseif($post->user_id != $request->user_id)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'you are unauthenticated',
            ]);
        }
        elseif ($user && $post && $post->user_id*1 == $request->user_id && $post->activate !==3)
        {
            $post->activate = 3*1 ;
            $post->save();

            return response()->json([
                'status' => 1 ,
                'message' => 'post deleted ',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error',
            ]);
        }
    }


    // get all ads that belongs to auth user
    public function userAds(Request $request)
    {
        $user= User::find($request->user_id) ;

        $ads = Post::select('id','title','description' ,'price' ,'phone','name','date',
            'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
            'compound' , 'deliveryDate' , 'deliveryTerm','user_id')
            ->with('images','user:id,name,avatar','amenities')
            ->where([['user_id',$request->user_id],['activate',1]])
            ->get();

        if(!$user || $request->user_id == null)
        {
            return response()->json([
                'status'  => 0 ,
                'message' => ' user not found ' ,
                'posts' => null
            ]);
        }
        elseif ($user && count($ads) > 0 )
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user ads' ,
                'posts' => $ads
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no posts yet' ,
                'posts' => null
            ]);
        }
    }


    // get all ads that belongs to auth user
    public function userPending(Request $request)
    {
        $user = User::find($request->user_id);

        $pendings = Post::select('id','title','description' ,'price' ,'phone','name','date',
            'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
            'compound' , 'deliveryDate' , 'deliveryTerm','user_id')
            ->with('images','user:id,name,avatar','amenities')
            ->where([['user_id',$request->user_id],['activate',2]])
            ->get();

        if(!$user || $request->user_id == null)
        {
            return response()->json([
                'status'  => 0 ,
                'message' => ' user not found ' ,
                'posts' => null
            ]);
        }
        if ($user && count($pendings) > 0 )
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user pending' ,
                'posts' => $pendings
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no posts yet' ,
                'posts' => null
            ]);
        }
    }


    // show user saved
    public function userSaved(Request $request)
    {
        $user = User::find($request->user_id) ;

        $saved = Post::select('id','title','description' ,'price' ,'phone','name','date',
            'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
            'compound' , 'deliveryDate' , 'deliveryTerm','user_id')
            ->with('images','user:id,name,avatar','amenities')
            ->whereHas('saves', function ($query) use ($user,$request) { $query->where('user_id',$request->user_id) ; })
            ->where([['activate',1]])
            ->get();

        if(!$user || $request->user_id == null)
        {
            return response()->json([
                'status'  => 0 ,
                'message' => ' user not found ' ,
                'posts' => null
            ]);
        }
        elseif ($user && count($saved) > 0)
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user saved' ,
                'posts' => $saved
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no posts saved yet' ,
                'posts' => null
            ]);
        }

    }


    // show others profile
    public function othersProfile(Request $request)
    {
        $user  = User::find($request->user_id);
        $otherProfile = Post::select('id','title','description' ,'price' ,'phone','name','date',
            'category' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' ,
            'compound' , 'deliveryDate' , 'deliveryTerm','user_id')
            ->with('images','user:id,name,avatar','amenities')
            ->where([['user_id',$request->user_id],['activate',1]])
            ->get();

        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'user not found' ,
                'userId' => 0*1,
                'userName' => null,
                'userPhone' => null,
                'userAvatar' => null,
                'posts' => null
            ]);
        }
        elseif($user && count($otherProfile) > 0)
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'user posts' ,
                'userId' => $user->id*1,
                'userName' => $user->name,
                'userPhone' => $user->phone,
                'userAvatar' => $user->avatar,
                'posts' => $otherProfile
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 0 ,
                'message' => 'no posts here' ,
                'userId' => 0*1,
                'userName' => null,
                'userPhone' => null,
                'userAvatar' => null,
                'posts' => null
            ]);
        }

    }


    // show lists of categories
    public function showLists(Request $request)
    {
        $categoriesEn = config('newsfeedEn.categories');
        $typesEn = config('newsfeedEn.types');
        $amenitiesEn = config('newsfeedEn.amenities');
        $bedroomsEn = config('newsfeedEn.bedrooms');
        $bathroomsEn = config('newsfeedEn.bathrooms');
        $levelsEn = config('newsfeedEn.levels');
        $furnishedEn = config('newsfeedEn.furnished');
        $compoundsEn = config('newsfeedEn.compounds');
        $deliveryDatesEn = config('newsfeedEn.deliveryDates');
        $deliveryTermsEn = config('newsfeedEn.deliveryTerms');

        $categoriesAr = config('newsfeedAr.categories');
        $typesAr = config('newsfeedAr.types');
        $amenitiesAr = config('newsfeedAr.amenities');
        $bedroomsAr = config('newsfeedAr.bedrooms');
        $bathroomsAr = config('newsfeedAr.bathrooms');
        $levelsAr = config('newsfeedAr.levels');
        $furnishedAr = config('newsfeedAr.furnished');
        $compoundsAr = config('newsfeedAr.compounds');
        $deliveryDatesAr = config('newsfeedAr.deliveryDates');
        $deliveryTermsAr = config('newsfeedAr.deliveryTerms');


        if ( $request->header('language') == 'ar')
        {
          return response()->json([
                'status'  => 1 ,
                'message' => 'كل القوائم' ,
                'categories' => $categoriesAr,
                'types' => $typesAr,
                'amenities' => $amenitiesAr ,
                'bedrooms' => $bedroomsAr,
                'bathrooms' => $bathroomsAr,
                'levels' => $levelsAr ,
                'furnished' => $furnishedAr,
                'compounds' => $compoundsAr,
                'deliveryDates' => $deliveryDatesAr,
                'deliveryTerms' => $deliveryTermsAr
            ]);
        }
        else
        {
            return response()->json([
                'status'  => 1 ,
                'message' => 'all lists' ,
                'categories' => $categoriesEn,
                'types' => $typesEn,
                'amenities' => $amenitiesEn ,
                'bedrooms' => $bedroomsEn,
                'bathrooms' => $bathroomsEn,
                'levels' => $levelsEn ,
                'furnished' => $furnishedEn,
                'compounds' => $compoundsEn,
                'deliveryDates' => $deliveryDatesEn,
                'deliveryTerms' => $deliveryTermsEn
            ]);
        }

    }
    
    
     public function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
    
    public function __construct()
    {
        set_time_limit(300);
    }



}
