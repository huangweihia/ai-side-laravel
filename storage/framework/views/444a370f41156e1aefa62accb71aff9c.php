<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <a href="<?php echo e(route('articles.index')); ?>" class="text-blue-500 hover:text-blue-700">← 返回列表</a>
    
    <div class="bg-white shadow-md rounded p-6 mt-4">
        <h1 class="text-2xl font-bold mb-4"><?php echo e($article->title); ?></h1>
        <div class="prose max-w-none">
            <?php echo $article->content; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/articles/show.blade.php ENDPATH**/ ?>