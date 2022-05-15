

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class=" row justify-content-center" style="margin-top:30px">

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
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($userr->id); ?> </td>
                            <td class="need">
                            <img src="<?php echo e(asset($userr->avatar)); ?>" style="width:50px;height:50px;margin-right:10px">
                            </td>
                            <td class="need"><?php echo e($userr->name); ?> </td>
                            <td><?php echo e($userr->phone); ?></td>
                            <?php if($userr->activate == 1): ?>
                            
                            <td>
                            <a href="<?php echo e(url('/activateUser/' . $userr->id )); ?>" class="btn btn-xs btn-success pull-right"> Active</a>
                            </td>
                            
                            <?php elseif($userr->activate == 2): ?>
                            
                            <td>
                            <a href="<?php echo e(url('/notactivateUser/' . $userr->id )); ?>" class="btn btn-xs btn-success pull-right">Not Active</a>
                            </td>
                            
                            <?php endif; ?>
                            <td>
                            <a href="<?php echo e(url('/deleteUser/' . $userr->id )); ?>" class="btn btn-xs btn-danger pull-right">Delete</a>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php echo e($users->links()); ?>

            </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\laravel-projects\your_place\resources\views/users.blade.php ENDPATH**/ ?>