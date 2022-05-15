<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Mail\ForgetPassword;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
], function ($router)
{

    //.................          user               ................//

    // register
    Route::post('/register', [UserController::class, 'register']);

    // validate on unique email
    Route::post('/unique-email', [UserController::class, 'uniqueEmail']);

    // validate on unique phone
    Route::post('/unique-phone', [UserController::class, 'uniquePhone']);

    // user login
    Route::post('/login', [UserController::class, 'login']);

    // user profile
    Route::get('/user-profile', [UserController::class, 'userProfile']);

    // forget passowrd
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);

    // send code verification to user by email
    Route::post('/get-code',[UserController::class,'getCode']);

    // reset password after verify code
    Route::post('/reset-password', [UserController::class, 'reset']);

    // login by email only
    Route::post('/loginbyemail',[UserController::class,'loginbyemail']);

    // upload user avatar
    Route::post('/upload-avatar',[UserController::class,'uploadAvatar']);

    Route::post('/upload-user-avatar',[UserController::class,'uploadUserAvatar']);

    // change user name
    Route::post('/edit-name',[UserController::class,'editName']);

     // change user password
    Route::post('/edit-password',[UserController::class,'editPassword']);

    // get code to change user phone
    Route::post('/edit-phone',[UserController::class,'editPhone']);
    
     // get code to change user phone
    Route::post('/get-phone',[UserController::class,'getPhone']);

    // get all ads that belongs to auth user
    Route::post('/add-to-saved', [UserController::class, 'addToSaved']);

    // contacts
    Route::post('/contact-us', [UserController::class, 'contactUs']);

    // get all chats that belongs to user
    Route::post('/get-chats',[UserController::class,'getChats']);

    // get all messages that belongs to user
    Route::post('/get-messages',[UserController::class,'getMessages']);

    // send message to user ( sms )
    Route::post('/send-message',[UserController::class,'sendMessage']);

    // logout
    Route::post('/logout', [UserController::class, 'logout']);


    //.................       NewsFeed ( posts )       ..................//


    // create new post
    Route::post('/new-post', [PostController::class, 'newPost']);

    // save images to post
    Route::post('/upload-images', [PostController::class, 'uploadImages']);

    Route::post('/show-image360', [PostController::class, 'showImage360']);

    Route::post('/upload-multi-images', [PostController::class, 'uploadMultiImages']);

    // show all posts
    Route::post('/show-posts', [PostController::class, 'showPosts']);

    // update post
    Route::post('/update-post', [PostController::class, 'updatePost']);

    // delete post ( update post's activate to 0 instead of 1 )
    Route::post('/delete-post', [PostController::class, 'deletePost']);

    // show specific post when click on it
    Route::post('/post-info', [PostController::class, 'postInfo']);

    // get all ads that belongs to auth user
    Route::post('/user-ads', [PostController::class, 'userAds']);

    // get all binding posts that belongs to auth user
    Route::post('/user-pending', [PostController::class, 'userPending']);

    // get all saved posts that belongs to auth user
    Route::post('/user-saved', [PostController::class, 'userSaved']);

    // show others profile
    Route::post('/others-profile', [PostController::class, 'othersProfile']);

    // show others profile
    Route::get('/show-lists', [PostController::class, 'showLists']);



    /*.................              Admin                ......................*/

    // share post
    Route::post('/share-post', [AdminController::class, 'sharePost']);

    // update user activate
    Route::post('/update-user-activate', [AdminController::class, 'updateUserActivate']);

    // test
    Route::get('/get-users', [AdminController::class, 'getUsers']);

    // get all saved posts that belongs to auth user
    Route::post('/update-activate', [AdminController::class, 'updateActivate']);

});
