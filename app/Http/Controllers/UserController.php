<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Chat;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Save;
use App\Models\Share;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\ForgetPassword;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;


class UserController extends Controller
{

    // user register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:100|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 0 ,
                'message' => 'failed ,name and password are required & phone must be unique',
            ]);
        }

        User::create([
            'name' => $request->name ,
            'password' => bcrypt($request->password),
            'phone' => $request->phone ,
            'login_type' => $request->login_type ,
        ]);


        $code = rand(1000 ,9999);

        $sender = urlencode("IdeaProg EG") ;    
        $language = 1 ; 
        $mobile   = 2 . $request->phone ;
        $msg  = "Code to verify your account in Your_place is : " . $code;
        $message = urlencode($msg);
    
        
        $url = "https://smsmisr.com/api/webapi/?username=ON3ZWiC2&password=qUvWEvY727&language=".$language."&sender=".$sender."&mobile=".$mobile."&message=".$message."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
        $output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);    

        $user = User::where('phone',$request->phone)->first() ;
        $user->code = $code ;
        $user->save();
       

        return response()->json([
                'status' => 1 ,
                'message' => 'suucess , please check your messages to verify your account',
        ]);

    }


    // unique email
    public function uniqueEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 0 ,
                'message' => 'The email has already been taken',
            ]);
        }
        return response()->json([
            'status' => 1 ,
            'message' => 'email correct and unique',
        ]);
    }


    // unique phone number
    public function uniquePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone',
        ]);


        if($request->phone == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'The email is required',
            ]);
        }
        elseif($validator->fails()){
            return response()->json([
                'status' => 0 ,
                'message' => 'The email has already been taken',
            ]);
        }
        return response()->json([
            'status' => 1 ,
            'message' => 'email correct and unique',
        ]);
    }


    // user login
    public function login(Request $request)
    {
        $credentials  =  $request->only('phone', 'password');
        $user = User::where('phone',$request->phone)->first();

        if (! $token = auth()->attempt($credentials))
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'Unauthorized',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' => 0*1
            ]);
        }
        elseif($user->activate == 2)
        {
            return response()->json([
                'status' => 2 ,
                'message' => 'user did not activate yet',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' => 2*1
            ]);
        }
            return $this->createNewToken($token);

     }


    // login by email only
    public function loginbyemail(Request $request)
    {

        $user = User::where('email',$request->email)->first();
        if($user && $user->login_type == $request->login_type)
        {
            return response()->json([
                'status' => 1 ,
                'message' => 'email is correct',
                'userId' => $user->id ,
                'userName' => $user->name ,
                'userEmail' =>$user->email ,
                'userPhone' => $user->phone ,
                'userLoginType' => $user->login_type,
                'activate' =>$user->activate
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed to login',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' => 4*1
            ]);
        }
    }


    // send email for user when forget password
    public function forgotPassword(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();
        if($user){
           
            $code = rand(1000 ,9999);

            $sender = urlencode("IdeaProg EG") ;    
            $language = 1 ; 
            $mobile   = 2 . $request->phone ;
            $msg  = "Code to reset password/phone in Your_place is : " . $code;
            $message = urlencode($msg);
        
            
            $url = "https://smsmisr.com/api/webapi/?username=ON3ZWiC2&password=qUvWEvY727&language=".$language."&sender=".$sender."&mobile=".$mobile."&message=".$message."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
            curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
            $output = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);    
    
            $user->code = $code ;
            $user->save();


            return response()->json([
                'status' => 1 ,
                'message' => 'please check your phone to reset passoword/phone',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error,failed to send code',
            ]);
        }

    }


     // get code from user to verify his phone
    public function getCode(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();

        if(!$user || $user->phone == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' =>0*1
            ]);
        }
        elseif ($user->code == null || $user->code !== $request->code)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'code is incorrect',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' => 0*1
            ]);
        }
        elseif($user && $user->code == $request->code)
        {
            $user->email_verified_at = Carbon::now();
            $user->activate = 1*1 ;
            $user->code = null ;
            $user->save() ;
            return response()->json([
                'status' => 1 ,
                'message' => 'user loggined successfully',
                'userId' => $user->id*1 ,
                'userName' => $user->name ,
                'userEmail' =>$user->email ,
                'userPhone' => $user->phone ,
                'userLoginType' => $user->login_type ,
                'activate' => $user->activate*1
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'error',
                'userId' => 0*1 ,
                'userName' => null ,
                'userEmail' =>null ,
                'userPhone' => null,
                'userLoginType' => null,
                'activate' =>0*1
            ]);
        }
    }

    // reset new password
    public function reset(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'user not found ',
            ]);
        }
        elseif ($request->phone == null)
        {
            return response()->json([
                'status' => 0,
                'message' => 'enter phone ',
            ]);
        }

        elseif ($request->password == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'please enter password ',
            ]);
        }
        elseif ($user && $request->password !== null && $request->phone !== null )
        {
                $user->password = Hash::make($request->password);
                $user->code = null;
                $user->save();
                return response()->json([
                    'status' => 1 ,
                    'message' => 'password changed successfully',
                ]);
        }
        else
        {
            return response()->json([
                'status' => 1 ,
                'message' => 'failed to reset password',
            ]);
        }
    }


    // upload user avatar
    public function uploadAvatar(Request $request)
    {
        $user = User::find($request->user_id);
        $file = $request->avatar;

        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0,
                'message' => 'user not found ',
            ]);
        }
        elseif ($file == null )
        {
            return response()->json([
                'status' => 0,
                'message' => 'avatar not found ',
            ]);
        }
        elseif($user && $file)
        {
            $extension = $file->getClientOriginalExtension();
            $path = $file->store('public');
            $truepath = substr($path, 7);

            $user->avatar = URL::to('/') . '/storage/' . $truepath ;
            $user->save();

            return response()->json([
                'status' => 1 ,
                'message' => 'image changed successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed to upload image',
            ]);

        }
        

    }

    public function uploadUserAvatar(Request $request)
    {
        
        $user = User::find($request->user_id);
        $post = Post::find($request->post_id);

        $imageStr = $request->avatar;
        
        $imageName=$this->generateRandomString();
        
        $image = str_replace('data:image/png;base64,', '', $imageStr);
        $image = str_replace(' ', '+', $image);

        $file = base64_decode($image);
        $data = 'storage/' . $imageName . "asddd" . '.jpg';
        
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($data, $file);
        
        $tmpFile = new File($data);


        if ($file == null )
        {
            return response()->json([
                'status' => 0,
                'message' => 'avatar not found ',
            ]);
        }
        elseif($request->user_id == null)
        {
            
            $post->avatar = "http://yourplace360.com/" . $data ;;
            $post->save();
            
            return response()->json([
                'status' => 1,
                'message' => $post->avatar,
            ]);
        }
        elseif($request->post_id == null)
        {

            $user->avatar = "http://yourplace360.com/" . $data ;
            $user->save();

            return response()->json([
                'status' => 1 ,
                'message' => $user->avatar ,
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
    
    

    // edit user name
    public function editName(Request $request)
    {
        $user = User::find($request->user_id);

        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0,
                'message' => 'user not found ',
            ]);
        }
        elseif ($request->name == null)
        {
            return response()->json([
                'status' => 0,
                'message' => 'name is required ',
            ]);
        }
        elseif($user && $request->name !== null)
        {
            $user->name = $request->name ;
            $user->save();

            return response()->json([
                'status' => 1 ,
                'message' => 'Name changed successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed, invalid id',
            ]);

        }
    }

    // edit user phone
    public function editPassword(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user || $request->user_id == null)
        {
                return response()->json([
                    'status' => 0 ,
                    'message' => 'user not found',
                ]);
        }
        elseif($request->old_password == null || !Hash::check($request->old_password, $user->password))
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'The old password is not correct',
            ]);
        }
        elseif ($request->new_password == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed, please enter new password',
            ]);
        }
        elseif ($user && Hash::check($request->old_password, $user->password) && $request->new_password !== null)
        {
            $user->password = bcrypt($request->input('new_password'));
            $user->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Password changed successfully',
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


    // get phone from user 
   public function getPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone',
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if($request->phone == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'The phone is required',
            ]);
        }
        if($request->user_id == null || !$user)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif($validator->fails()){
            return response()->json([
                'status' => 0 ,
                'message' => 'The phone has already been taken',
            ]);
        }
        
        // $this->sendMessage($request);
        
        
        
         $code = rand(1000 ,9999);

        $sender = urlencode("IdeaProg EG") ;    
        $language = 1 ; 
        $mobile   = 2 . $request->phone ;
        $msg  = "Code to verify your account in Your_place is : " . $code;
        $message = urlencode($msg);

    
        
        $url = "https://smsmisr.com/api/webapi/?username=ON3ZWiC2&password=qUvWEvY727&language=".$language."&sender=".$sender."&mobile=".$mobile."&message=".$message."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
        $output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);    

        
        
        $user->code = $code ;
        $user->save();

        return response()->json([
            'status' => 1 ,
            'message' => 'code sent',
        ]);

    }

    // get code from user to verify his phone
    public function editPhone(Request $request)
    {

        $user = User::find($request->user_id);


        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif ($request->phone == null)
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'please enter your phone',
            ]);
        }
        elseif ( $user->code !== $request->code || $request->code == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed, incorrect code',
            ]);
        }
        elseif ( $user && $user->code == $request->code && $request->phone !== null)
        {
            $user->phone = $request->phone ;
            $user->email_verified_at = Carbon::now();
            $user->activate = 1*1 ;
            $user->code = null ;
            $user->save() ;

            return response()->json([
                'status' => 1 ,
                'message' => 'phone changed',
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


    // add user 
    public function addUser(Request $request)
    {
        $file = $request->avatar;
        $extension = $file->getClientOriginalExtension();
        $path = $file->store('public');
        $truepath = substr($path, 7);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'activate' => 1*1,
            'groupId' => 1*1 , 
            'avatar' => URL::to('/') . '/storage/' . $truepath
        ]);
        return $user ;
    }

    public function test(Request $request)
    {
        $user = User::find(11);
        return $user->created_at ;
    }


    //  add to saved posts
    public function addToSaved(Request $request)
    {
        $user = User::find($request->user_id) ;
        $post = Post::find($request->post_id) ;
        $saved = Save::where([['user_id',$request->user_id],['post_id',$request->post_id]])->first();

        if(!$user || $request->user_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'user not found',
            ]);
        }
        elseif(!$post || $request->post_id == null )
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'post not found',
            ]);
        }
        elseif ($saved)
        {
            $saved->delete();
            return response()->json([
                'status' => 1 ,
                'message' => 'post removed form saved',
            ]);
        }
        elseif(!$saved && $user && $post && $post->activate == 1)
        {
                Save::firstOrCreate([
                'user_id'  => $request->user_id,
                'post_id'  => $request->post_id,
            ]);

            return response()->json([
                'status' => 1 ,
                'message' => 'post add to saved',
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


    // contact us
    public function contactUs(Request $request)
    {
        $user = User::find($request->user_id) ;
        if ($user)
        {
            Contact::create([
               'email' => $request->email ,
               'subject' => $request->subject ,
               'message' => $request->message ,
               'user_id' => $request->user_id,
                'replied' => 0*1
            ]);
            return response()->json([
                'status' => 1 ,
                'message' => 'thanks for your msg, we will reply soon',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0 ,
                'message' => 'failed to send message',
            ]);
        }
    }


    // get all chats that belongs to user
    public function getChats(Request $request)
    {
        $sender = User::find($request->user_id);     

        $chats = Chat::where(function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->orWhere('receiver_id', $request->user_id);
        })
        ->select('user_id','receiver_id')
        ->orderBy('date')->distinct()->get();


        $arr=[];
        $reciverArr=[];

       foreach ($chats as $chat)
        {
            
            if ($request->user_id != $chat->receiver_id ) {

                 $receiveres = array();
                 $receiveres = ["receiver_id"=>$chat->receiver_id]; 
                 array_push($arr ,$receiveres);
                 

            } else{

                $receiveres = array();
                $receiveres = ["receiver_id"=>$chat->user_id]; 
                array_push($arr ,$receiveres);

            }
           
        }

        $result = [];
                foreach ($arr as $chat)
                    { 
                        $y=false;

                    if(sizeof($result)==0 ){
                        $result [] = $chat['receiver_id'] ;
                    }
                        else{
                        for ($x=0 ; $x < sizeof($result) ; $x++) {
                            if ($chat['receiver_id'] == $result[$x] ) {
                                $y=true ;
                            } 
                        
                        }
                        if(!$y)
                        $result [] = $chat['receiver_id'] ;    
        }

               
    } 
    

    $arrays=[];
       foreach ($result as $value)
        {
            $receiver = User::find($value);            
            $message = Chat::where(function($query) use ($request,$value){
                $query->where('user_id', $request->user_id);
                $query->orWhere('user_id', $value);
            })
            ->where(function($query) use ($request,$value){
                $query->where('receiver_id', $value);
                $query->orWhere('receiver_id', $request->user_id);
            })
            ->select('message','date')->orderBy('date')->first() ;
            
            
            $data = ["id" => $receiver->id,"name" => $receiver->name,"avatar" => $receiver->avatar,"message" => $message];
             array_push($arrays , $data);

               }
        echo json_encode($arrays) ;

    }


    // get all messages for specific user
    public function getMessages(Request $request)
    {

        $sender = User::find($request->user_id);     
        $receiver = User::find($request->receiver_id);

        $chat = Chat::where(function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->orWhere('user_id', $request->receiver_id);
        })
        ->where(function($query) use ($request){
            $query->where('receiver_id', $request->receiver_id);
            $query->orWhere('receiver_id', $request->user_id);
        })
        ->orderBy('date')->get();

        return response()->json([
            'status' => 1 ,
            'message' => 'All messages between sender & receiver',
            'senderAvatar'=> $sender->avatar ,
            'receiverAvatar'=> $receiver->avatar ,
            'allMessages'=> $chat
        ]);
    }
 
  
    // send message to user
    public function sendMessage(Request $request)
    {

         $code = rand(1000 ,9999);

        $sender = urlencode("IdeaProg EG") ;    
        $language = 1 ; 
        $mobile   = 2 . $request->phone ;
        $msg  = "Code to verify your account in Your_place is : " . $code;
        $message = urlencode($msg);

    
        
        $url = "https://smsmisr.com/api/webapi/?username=ON3ZWiC2&password=qUvWEvY727&language=".$language."&sender=".$sender."&mobile=".$mobile."&message=".$message."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
        $output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);    

        if ($err) {
            return response()->json([
                'status' => 0 ,
                'message' => $err,
            ]);
            }
             else 
            {
            return response()->json([
                'status' => 1 ,
                'message' => $output,
            ]);
        }
         

    }


    // user token
    protected function createNewToken($token)
    {
       $user = auth()->user() ;
        return response()->json([
            'status' => 1 ,
            'message' => 'user loggined successfully',
            'userId' => $user->id*1 ,
            'userName' => $user->name ,
            'userEmail' =>$user->email ,
            'userPhone' => $user->phone ,
            'userLoginType' => $user->login_type ,
            'activate' => $user->activate*1
        ]);
    }


    // user logout
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status' => 1 ,
            'message' => 'User successfully signed out',
        ]);
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

}
