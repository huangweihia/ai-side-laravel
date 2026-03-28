<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\ProfileMessage;
use App\Models\ViewHistory;
use App\Models\VipUrgentNotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * 首页 - 展示今日精选内容
     */
    public function index()
    {
        // 今日精选项目（按 stars 排序，取 5 个）
        $featuredProjects = Project::where('is_featured', true)
            ->orderBy('stars', 'desc')
            ->limit(5)
            ->get();
        
        // 如果没有精选项目，取热门项目
        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::orderBy('stars', 'desc')
                ->limit(5)
                ->get();
        }
        
        // 今日精选文章（按 view_count 排序，取 5 个）
        $featuredArticles = Article::where('is_published', true)
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();
        
        // 热门分类
        $categories = Category::withCount(['projects', 'articles'])
            ->orderBy('sort', 'desc')
            ->get();
        
        // 社会证明数据
        $stats = [
            'users' => User::count(),
            'projects' => Project::count(),
            'articles' => Article::count(),
        ];
        
        return view('home.index', compact(
            'featuredProjects',
            'featuredArticles',
            'categories',
            'stats'
        ));
    }

    /**
     * 个人中心（需要登录）
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // 我的收藏
        $favorites = Favorite::where('user_id', $user->id)
            ->with('favoritable')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // 我的评论
        $comments = Comment::where('user_id', $user->id)
            ->with('commentable')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // 浏览历史（ViewHistory 无 timestamps，使用 viewed_at 排序）
        $histories = ViewHistory::where('user_id', $user->id)
            ->with('viewable')
            ->orderBy('viewed_at', 'desc')
            ->limit(10)
            ->get();

        // 他人发给我的主页留言（个人中心展示）
        $profileMessagesReceived = ProfileMessage::where('recipient_id', $user->id)
            ->with('sender')
            ->latest()
            ->limit(8)
            ->get();
        
        // 统计数据
        $stats = [
            'favorites' => Favorite::where('user_id', $user->id)->count(),
            'comments' => Comment::where('user_id', $user->id)->count(),
            'histories' => ViewHistory::where('user_id', $user->id)->count(),
            'profile_messages' => ProfileMessage::where('recipient_id', $user->id)->count(),
        ];
        
        return view('dashboard', compact('user', 'favorites', 'comments', 'histories', 'stats', 'profileMessagesReceived'));
    }
    
    /**
     * 用户主页（公开）
     */
    public function userProfile($id)
    {
        $user = User::findOrFail($id);

        $stats = [
            'comments' => Comment::where('user_id', $user->id)->count(),
            'favorites' => Favorite::where('user_id', $user->id)->count(),
            'histories' => ViewHistory::where('user_id', $user->id)->count(),
            'profile_messages' => ProfileMessage::where('recipient_id', $user->id)->count(),
        ];

        $profileMessages = null;
        $urgentSentToday = false;
        $profileMessagesSent = null;

        if (auth()->check() && (int) auth()->id() === (int) $user->id) {
            $profileMessages = ProfileMessage::where('recipient_id', $user->id)
                ->with('sender')
                ->latest()
                ->paginate(15);
            $urgentSentToday = VipUrgentNotificationLog::query()
                ->where('sender_user_id', $user->id)
                ->whereDate('sent_at', now()->toDateString())
                ->exists();
        } elseif (auth()->check()) {
            // 访客：展示自己发给主页主人的留言记录（分页参数 sent_page）
            $profileMessagesSent = ProfileMessage::query()
                ->where('recipient_id', $user->id)
                ->where('sender_id', auth()->id())
                ->latest()
                ->paginate(10, ['*'], 'sent_page');
        }

        return view('users.show', compact('user', 'stats', 'profileMessages', 'urgentSentToday', 'profileMessagesSent'));
    }

    /**
     * 上传头像
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $user = auth()->user();

            // 若请求体过大，PHP 会直接丢弃文件字段
            if (!$request->hasFile('avatar')) {
                $message = sprintf(
                    '上传失败：未收到文件。请检查 PHP 配置 upload_max_filesize=%s, post_max_size=%s',
                    ini_get('upload_max_filesize'),
                    ini_get('post_max_size')
                );

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return back()->with('error', $message);
            }

            $file = $request->file('avatar');

            if (!$file->isValid()) {
                $message = '上传失败：文件上传错误码 ' . $file->getError() . '（请检查 php.ini 的 upload_tmp_dir / upload_max_filesize / post_max_size）';

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return back()->with('error', $message);
            }

            $validator = Validator::make($request->all(), [
                'avatar' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,webp,bmp,avif,tif,tiff,heic,heif', 'max:20480'],
            ], [
                'avatar.mimes' => '仅支持 jpeg/jpg/png/gif/webp/bmp/avif/tif/tiff/heic/heif 图片格式',
                'avatar.max' => '图片大小不能超过 20MB',
                'avatar.uploaded' => '文件上传失败，请检查服务器上传限制',
            ]);

            if ($validator->fails()) {
                $message = '上传失败：' . $validator->errors()->first('avatar');

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return back()->with('error', $message);
            }

            $uploadDir = public_path('avatars');
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    $message = '上传失败：创建上传目录失败（' . $uploadDir . '）';
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $message], 500);
                    }
                    return back()->with('error', $message);
                }
            }

            if (!is_writable($uploadDir)) {
                $message = '上传失败：上传目录不可写（' . $uploadDir . '）';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 500);
                }
                return back()->with('error', $message);
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $ext;
            $file->move($uploadDir, $filename);

            if (!empty($user->avatar) && str_starts_with($user->avatar, '/avatars/')) {
                $oldAvatarAbsolutePath = public_path(ltrim($user->avatar, '/'));
                if (is_file($oldAvatarAbsolutePath)) {
                    @unlink($oldAvatarAbsolutePath);
                }
            }

            $avatarUrl = '/avatars/' . $filename;

            $user->update([
                'avatar' => $avatarUrl,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '头像上传成功！',
                    'avatar_url' => $avatarUrl,
                ]);
            }

            return redirect()->route('dashboard')->with('success', '头像上传成功！');
        } catch (\Throwable $e) {
            $message = '上传失败：' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return back()->with('error', $message);
        }
    }
}
