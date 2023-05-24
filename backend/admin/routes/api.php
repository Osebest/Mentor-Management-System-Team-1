<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth endpoints
Route::prefix('auth')->group(function () {
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'timezone'])->group(function () {
        // GET
        Route::get('user', [App\Http\Controllers\AuthController::class, 'user']);

        // POST
        Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
        Route::post('logout-all', [App\Http\Controllers\AuthController::class, 'logoutAll']);
        Route::post('logout-other', [App\Http\Controllers\AuthController::class, 'logoutOther']);
        Route::post('logout-device', [App\Http\Controllers\AuthController::class, 'logoutDevice']);
        Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);

        // Change password
        Route::post('password/update', [App\Http\Controllers\AuthController::class, 'updatePassword']);
    });

    // Add login with social media
    Route::prefix('social')->group(function () {
        Route::post('login', [App\Http\Controllers\AuthController::class, 'socialLogin']);
        Route::get('redirect/{provider}', [App\Http\Controllers\AuthController::class, 'socialLoginRedirect']);
    });

    Route::prefix('password')->group(function () {
        Route::post('reset', [App\Http\Controllers\AuthController::class, 'resetPassword']);
        Route::post('change', [App\Http\Controllers\AuthController::class, 'changePassword']);

        // Check if token is valid
        Route::get('check/{token}', [App\Http\Controllers\AuthController::class, 'checkToken']);
    });
});

// Version 1
Route::prefix('v1')->group(function () {
    // Country endpoints
    Route::prefix('countries')->group(function () {
        // Returns country list
        Route::get('/', [App\Http\Controllers\CountryController::class, 'index']);

        // Returns country details including states and cities
        Route::get('{country}/cities', [App\Http\Controllers\CountryController::class, 'getCities']);
        Route::get('{country}/states', [App\Http\Controllers\CountryController::class, 'getStates']);

        // Returns state details including cities
        Route::get('states/{state}/cities', [App\Http\Controllers\CountryController::class, 'getStateCities']);
    });

    Route::middleware(['auth:sanctum', 'email.verified', 'timezone'])->group(function () {
        // User endpoints
        Route::prefix('user')->group(function () {
            // Get user data
            Route::get('/', [App\Http\Controllers\UserController::class, 'getUser']);

            // Get all users
            Route::get('all', [App\Http\Controllers\UserController::class, 'getUsers']);

            // Alive check
            Route::get('alive', [App\Http\Controllers\UserController::class, 'alive']);

            // Search users based on roles and keywords
            Route::get('search/role/{role}', [App\Http\Controllers\UserController::class, 'searchUsersByRole']);

            // Search users based on metadata and keywords
            Route::get('search/metadata/{metadata_group}/{value}', [App\Http\Controllers\UserController::class, 'searchUsersByMetadata']);

            // Search users
            Route::get('search/{keyword}', [App\Http\Controllers\UserController::class, 'searchUsers']);

            // Update user profile
            Route::patch('/', [App\Http\Controllers\UserController::class, 'update']);

            // Get user preferences
            Route::get('preferences', [App\Http\Controllers\UserController::class, 'getPreferences']);

            // Update user preferences
            Route::patch('preferences', [App\Http\Controllers\UserController::class, 'updatePreferences']);

            // Get avatar url only
            Route::get('avatar', [App\Http\Controllers\UserController::class, 'getAvatarUrl']);

            // Get user avatar image
            Route::get('avatar/{filename}', [App\Http\Controllers\UserController::class, 'getAvatar'])
                ->withoutMiddleware(['auth:sanctum', 'email.verified', 'timezone'])
                ->name('user.avatar');

            // Update avatar
            Route::post('avatar', [App\Http\Controllers\UserController::class, 'updateAvatar']);

            // Get specific user data
            Route::get('{user_id}', [App\Http\Controllers\UserController::class, 'getUserById']);
        });

        // Support endpoints
        Route::prefix('support')->group(function () {
            // Get support data
            Route::get('/', [App\Http\Controllers\SupportController::class, 'getSupport']);

            // Get specific support data
            Route::get('{support_id}', [App\Http\Controllers\SupportController::class, 'getSpecificSupport']);

            // Get support image
            Route::get('image/{filename}', [App\Http\Controllers\SupportController::class, 'getSupportImage'])->name('support.image');

            // Update support data
            Route::post('/', [App\Http\Controllers\SupportController::class, 'createSupport']);
            Route::patch('{support_id}', [App\Http\Controllers\SupportController::class, 'updateSupport']);

            // Accept support ticket
            Route::post('accept/{support_id}', [App\Http\Controllers\SupportController::class, 'acceptSupport']);

            // Close support ticket
            Route::post('close/{support_id}', [App\Http\Controllers\SupportController::class, 'closeSupport']);

            // Open support ticket
            Route::post('open/{support_id}', [App\Http\Controllers\SupportController::class, 'openSupport']);

            // Assign ticket to user
            Route::post('assign/{support_id}', [App\Http\Controllers\SupportController::class, 'assignSupport']);

            // Delete support data
            Route::delete('{support_id}', [App\Http\Controllers\SupportController::class, 'deleteSupport']);

            // Delete all support data
            Route::delete('/', [App\Http\Controllers\SupportController::class, 'deleteAllSupport']);
        });

        // FAQ endpoints
        Route::prefix('faq')->group(function () {
            // Get faq data
            Route::get('/', [App\Http\Controllers\FaqsController::class, 'getFaqs']);

            // Update faq data
            Route::post('/', [App\Http\Controllers\FaqsController::class, 'createFaq']);
            Route::patch('{faq_id}', [App\Http\Controllers\FaqsController::class, 'updateFaq']);

            // Delete faq data
            Route::delete('{faq_id}', [App\Http\Controllers\FaqsController::class, 'deleteFaq']);
        });

        // Notification endpoints
        Route::prefix('notification')->group(function () {
            // Get notification data
            Route::get('/', [App\Http\Controllers\NotificationController::class, 'getNotifications']);

            // Mark all notification as read
            Route::post('read/all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);

            // / Mark notification as read
            Route::post('read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);

            // Delete notification data
            Route::delete('{notification_id}', [App\Http\Controllers\NotificationController::class, 'deleteNotification']);

            // Delete all notification data
            Route::delete('/', [App\Http\Controllers\NotificationController::class, 'deleteAllNotifications']);

            // Get notification count
            Route::get('count', [App\Http\Controllers\NotificationController::class, 'getNotificationCount']);
        });

        // Message endpoints
        Route::prefix('message')->group(function () {
            // Send message
            Route::post('/', [App\Http\Controllers\MessageController::class, 'sendMessage']);

            // Get overview of message threads
            Route::get('threads', [App\Http\Controllers\MessageController::class, 'getMessageThreads']);

            // Get message thread
            Route::get('thread/{room_id}', [App\Http\Controllers\MessageController::class, 'getMessageThread']);

            // Mark message as read
            Route::post('read/{uuid}', [App\Http\Controllers\MessageController::class, 'markMessageAsRead']);

            // Mark the whole thread as read
            Route::post('read/thread/{room_id}', [App\Http\Controllers\MessageController::class, 'markThreadAsRead']);

            // Forward message
            Route::post('forward', [App\Http\Controllers\MessageController::class, 'forwardMessage']);

            // Broadcast message
            Route::post('broadcast/{user_role?}', [App\Http\Controllers\MessageController::class, 'broadcastMessage']);

            // Mark message as unread
            // Commented out because I'm not sure if this is needed, just good to have incase it is needed. - @ghostscypher
            // Route::post('unread/{uuid}', [App\Http\Controllers\MessageController::class, 'markMessageAsUnread']);

            // Delete message data
            Route::delete('{uuid}', [App\Http\Controllers\MessageController::class, 'deleteMessage']);

            // Delete all message data
            Route::delete('thread/{room_id}', [App\Http\Controllers\MessageController::class, 'deleteAllMessagesInThread']);

            // Get broadcast messages sent by user
            Route::get('broadcast', [App\Http\Controllers\MessageController::class, 'getBroadcastMessages']);
        });

        // Attachment endpoints
        Route::prefix('attachment')
            ->withoutMiddleware(['auth:sanctum', 'email.verified'])
            ->middleware(['throttle:30,1', 'signed'])
            ->group(function () {
                // Display attachment
                Route::get('{uuid}', [App\Http\Controllers\AttachmentController::class, 'getAttachment'])->name('attachment.show');

                // Download attachment
                Route::get('download/{uuid}', [App\Http\Controllers\AttachmentController::class, 'downloadAttachment'])->name('attachment.download');

                // Display attachment thumbnail
                Route::get('thumbnail/{uuid}', [App\Http\Controllers\AttachmentController::class, 'getAttachmentThumbnail'])->name('attachment.thumbnail');
            });

        // Post endpoints
        Route::prefix('post')->group(function () {
            // Get post data
            Route::get('/', [App\Http\Controllers\PostController::class, 'getPosts']);

            // Get specific post data
            Route::get('{post_id}', [App\Http\Controllers\PostController::class, 'getSpecificPost']);

            // Get post image
            Route::get('image/{filename}', [App\Http\Controllers\PostController::class, 'getPostImage'])->name('post.image');

            // Update post data
            Route::post('/', [App\Http\Controllers\PostController::class, 'createPost']);
            Route::patch('{post_id}', [App\Http\Controllers\PostController::class, 'updatePost']);

            // Delete post data
            Route::delete('{post_id}', [App\Http\Controllers\PostController::class, 'deletePost']);

            // Delete all post data
            Route::delete('/', [App\Http\Controllers\PostController::class, 'deleteAllPosts']);

            // Post comments endpoints
            Route::prefix('comment')->group(function () {
                // Get post comments
                Route::get('{post_id}', [App\Http\Controllers\PostController::class, 'getPostComments']);

                // Get specific post comment
                Route::get('{post_id}/{comment_id}', [App\Http\Controllers\PostController::class, 'getSpecificPostComment']);

                // Update post comment
                Route::post('{post_id}', [App\Http\Controllers\PostController::class, 'createPostComment']);
                Route::patch('{post_id}/{comment_id}', [App\Http\Controllers\PostController::class, 'updatePostComment']);

                // Delete post comment
                Route::delete('{post_id}/{comment_id}', [App\Http\Controllers\PostController::class, 'deletePostComment']);

                // Delete all post comments
                Route::delete('{post_id}', [App\Http\Controllers\PostController::class, 'deleteAllPostComments']);
            });
        });

        // Tasks endpoints
        Route::prefix('task')->group(function () {
            // Get task data
            Route::get('/', [App\Http\Controllers\TaskController::class, 'getTasks']);

            // Search task
            Route::get('search/{keyword}', [App\Http\Controllers\TaskController::class, 'searchTasks']);

            // Get Specific task
            Route::get('{task_id}', [App\Http\Controllers\TaskController::class, 'getTask']);

            // Update task data
            Route::post('/', [App\Http\Controllers\TaskController::class, 'createTask']);
            Route::patch('{task_id}', [App\Http\Controllers\TaskController::class, 'updateTask']);

            // Delete task data
            Route::delete('{task_id}', [App\Http\Controllers\TaskController::class, 'deleteTask']);

            // Delete all task data
            Route::delete('/', [App\Http\Controllers\TaskController::class, 'deleteAllTasks']);

            // Get cached task assignments i.e. users assigned to a task
            Route::get('assignment/cached/{task_id}', [App\Http\Controllers\TaskController::class, 'getCachedTaskAssignments']);

            // Invalidate cached task assignments
            Route::get('assignment/cached/invalidate/{task_id}', [App\Http\Controllers\TaskController::class, 'invalidateCachedTaskAssignments']);
        });

        // Reports endpoints
        Route::prefix('report/{report_type}')->group(function () {
            // Get report data
            Route::get('/', [App\Http\Controllers\ReportController::class, 'getReports']);

            // Update report data
            Route::post('/', [App\Http\Controllers\ReportController::class, 'createReport']);
            Route::patch('{report_id}', [App\Http\Controllers\ReportController::class, 'updateReport']);

            // Delete report data
            Route::delete('{report_id}', [App\Http\Controllers\ReportController::class, 'deleteReport']);

            // Delete all report data
            Route::delete('/', [App\Http\Controllers\ReportController::class, 'deleteAllReports']);
        });

        // Mentor endpoints
        Route::prefix('mentor')->group(function () {
            // Get mentor data
            Route::get('/', [App\Http\Controllers\MentorController::class, 'getMentors']);

            // Search mentor
            Route::get('search/{keyword}', [App\Http\Controllers\MentorController::class, 'searchMentors']);

            // Get specific mentor data
            Route::get('{mentor_id}', [App\Http\Controllers\MentorController::class, 'getMentor']);

            // Invite mentor
            Route::post('invite', [App\Http\Controllers\MentorController::class, 'inviteMentor']);

            // Update mentor data
            Route::patch('{mentor_id}', [App\Http\Controllers\MentorController::class, 'updateMentor']);

            // Delete mentor data
            Route::delete('{mentor_id}', [App\Http\Controllers\MentorController::class, 'deleteMentor']);
        });

        // Mentor Manager endpoints
        Route::prefix('mentor-manager')->group(function () {
            // Get mentor manager data
            Route::get('/', [App\Http\Controllers\MentorManagerController::class, 'getMentorManagers']);

            // Search mentor manager
            Route::get('search/{keyword}', [App\Http\Controllers\MentorManagerController::class, 'searchMentorManagers']);

            // Get specific mentor manager data
            Route::get('{mentor_manager_id}', [App\Http\Controllers\MentorManagerController::class, 'getMentorManager']);

            // Invite mentor manager
            Route::post('invite', [App\Http\Controllers\MentorManagerController::class, 'inviteMentorManager']);

            // Update mentor manager data
            Route::patch('{mentor_manager_id}', [App\Http\Controllers\MentorManagerController::class, 'updateMentorManager']);

            // Delete mentor manager data
            Route::delete('{mentor_manager_id}', [App\Http\Controllers\MentorManagerController::class, 'deleteMentorManager']);
        });

        // Program endpoints
        Route::prefix('program')->group(function () {
            // Get program data
            Route::get('/', [App\Http\Controllers\ProgramController::class, 'getPrograms']);
            Route::get('archived', [App\Http\Controllers\ProgramController::class, 'getArchivedPrograms']);

            // Search program
            Route::get('search/{keyword}', [App\Http\Controllers\ProgramController::class, 'searchPrograms']);

            // Get specific program data
            Route::get('{program_id}', [App\Http\Controllers\ProgramController::class, 'getProgram']);

            // Update program data
            Route::post('/', [App\Http\Controllers\ProgramController::class, 'createProgram']);
            Route::post('restore/{program_id}', [App\Http\Controllers\ProgramController::class, 'restoreProgram']);
            Route::patch('{program_id}', [App\Http\Controllers\ProgramController::class, 'updateProgram']);

            // Delete program data
            Route::delete('{program_id}', [App\Http\Controllers\ProgramController::class, 'archiveProgram']);

            // Delete all program data
            Route::delete('/', [App\Http\Controllers\ProgramController::class, 'archiveAllPrograms']);

            // Program criteria endpoints
            Route::prefix('criteria')->group(function () {
                // Get program criteria
                Route::get('{program_id}', [App\Http\Controllers\ProgramController::class, 'getProgramCriteria']);

                // Get specific program criteria
                Route::get('{program_id}/{criteria_id}', [App\Http\Controllers\ProgramController::class, 'getSpecificProgramCriteria']);

                // Update program criteria
                Route::post('{program_id}', [App\Http\Controllers\ProgramController::class, 'createProgramCriteria']);
                Route::patch('{program_id}/{criteria_id}', [App\Http\Controllers\ProgramController::class, 'updateProgramCriteria']);

                // Delete program criteria
                Route::delete('{program_id}/{criteria_id}', [App\Http\Controllers\ProgramController::class, 'deleteProgramCriteria']);

                // Delete all program criteria
                Route::delete('{program_id}', [App\Http\Controllers\ProgramController::class, 'deleteAllProgramCriteria']);
            });
        });
    });
});
