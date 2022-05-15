<?php $__env->startSection('content'); ?>
<div class="container">
    <div class=" row justify-content-center" style="margin-top:30px;">

            <div class="sidebar" style=" float:left ; background-color:#fff;border-radius:20px;padding:12px"> 
                <p style="padding-left:15px;font-size:20px"> 

                <img src="<?php echo e(asset($user->avatar)); ?>" style="width:50px;height:50px;margin-right:10px">
                    <?php echo e($user->name); ?>  

                </p>
                <hr style="color:gray">

               <div class="hovering">
                    <i class="fas fa-table" style="color:#dbdcde ; padding:12px;font-size:20px"></i>
                    <a href="<?php echo e(route('home')); ?>"> Dashboard </a>
               </div>

               <div class="hovering">
                    <i class="fas fa-user-friends" style="color:#dbdcde ; padding:12px;font-size:20px"></i>
                    <a href="<?php echo e(route('users')); ?>"> Users Manager </a>
               </div>

            </div>

            <div class="posts" style="float:left ; background-color:#fff;border-radius:20px;padding:12px"> 
                <h3 style="padding:20px;padding-bottom:0px;font-weight:bold"> Posts </h3>
                <hr style="color:gray;width:95%;margin:auto"> 

                <div class="portfolio-menu" style="padding:10px">
                    <ul>
                        <li > <a class="active" href="<?php echo e(route('home')); ?>"> All Posts </a> </li>
                        <li > <a href="<?php echo e(route('pending')); ?>"> Pending </a> </li>
                        <li > <a href="<?php echo e(route('accepted')); ?>"> Accepted </a> </li>
                    </ul>
                </div>

                <div>
                        <?php if(count($posts) > 0): ?>

                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php if(count($post->images) > 0 ): ?>
                        <img src="<?php echo e(asset($post->images[1]['path'])); ?>" style="width:320px;height:200px;border-radius:30px;padding:10px"> 
                        <h4> <?php echo e($post->title); ?></h4>
                        <p style="color:gray"> <?php echo e($post->description); ?> . <span style="color:#154c79;font-weight: bold;"> Price : <?php echo e($post->price); ?> </span> </p>
                        <?php if($post->activate  == 2 ): ?>
                        <a href="<?php echo e(url('/accept/' . $post->id )); ?>" class="btn btn-xs btn-primary pull-right" style="width:48%">Accept</a>
                        <a href="<?php echo e(url('/delete/' . $post->id )); ?>" class="btn btn-xs btn-danger pull-right" style="width:48%">Reject</a>
                        <?php elseif($post->activate  == 1): ?>
                        <a href="<?php echo e(url('/delete/' . $post->id )); ?>" class="btn btn-xs btn-danger pull-right" style="width:100%">Delete this post</a>
                        <?php endif; ?>
                        <br> <br>
                        <hr style="color:gray;width:95%;margin: 25px auto">
                        <?php else: ?>
                        <h4> <?php echo e($post->title); ?></h4>
                        <p style="color:gray"> <?php echo e($post->description); ?> . <span style="color:#154c79;font-weight: bold;"> Price : <?php echo e($post->price); ?> </span> </p>
                        <?php if($post->activate  == 2 ): ?>
                        <a href="<?php echo e(url('/accept/' . $post->id )); ?>" class="btn btn-xs btn-primary pull-right" style="width:48%">Accept</a>
                        <a href="<?php echo e(url('/delete/' . $post->id )); ?>" class="btn btn-xs btn-danger pull-right" style="width:48%">Reject</a>
                        <?php elseif($post->activate  == 1): ?>
                        <a href="<?php echo e(url('/delete/' . $post->id )); ?>" class="btn btn-xs btn-danger pull-right" style="width:100%">Delete this post</a>
                        <?php endif; ?>
                        <br> <br>
                        <hr style="color:gray;width:95%;margin: 25px auto">
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>


                          <?php echo e($posts->links()); ?>


                </div>

            </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\laravel-projects\your_place\resources\views/home.blade.php ENDPATH**/ ?>