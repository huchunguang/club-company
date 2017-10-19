<?php $__env->startSection("content"); ?>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">文章列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-hover">
                            <tbody><tr>
                                <th style="width: 10px">#</th>
                                <th class="info">文章标题</th>
                                <th class="info">操作</th>
                            </tr>
                            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($post->id); ?>.</td>
                                <td><a href="/posts/<?php echo e($post->id); ?>"><?php echo e($post->title); ?></a></td>
                                <td>
                                    <button type="button" class="btn  btn-success post-audit" post-id="<?php echo e($post->id); ?>" post-action-status="1" >通过</button>
                                    <button type="button" class="btn  btn-danger post-audit" post-id="<?php echo e($post->id); ?>" post-action-status="-1" >拒绝</button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody></table>
                    </div>
                    <?php echo e($posts->links()); ?>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("admin.layout.main", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>