<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\AnswerNew;
use App\Models\Exam;
use App\Models\Exercise;
use App\Models\FlashcardTopic;
use App\Models\Order;
use App\Models\Question;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Traits\FileUpdate;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CommandController extends Controller
{
    use FileUpdate;

    public function banner()
    {
        DB::beginTransaction();
        try {
            $bannerOlds = DB::connection('mysql2')->table('banners')->get();
            $inserts = [];
            if ($bannerOlds->count()) {
                DB::connection('mysql')->table('banners')->truncate();
                foreach ($bannerOlds as $bannerOld) {
                    $file = null;
                    if ($bannerOld->thumbnail) {
                        //Insert file.
                        $data = [
                            'name' => $bannerOld->thumbnail,
                            'url' => env('BASE_URL') . $bannerOld->thumbnail,
                            'size' => null,
                            'type' => 0,
                        ];
                        $file = $this->createFile($data);
                    }
                    $inserts[] = [
                        'image_id' => $file ? $file->id : 0,
                        'title' => $bannerOld->name,
                        'active' => $bannerOld->active,
                        'url' => $bannerOld->link,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('banners')->insert($chunk->toArray());
                }
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function enterprise()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('enterprises')->get();
            if ($olds->count()) {
                $inserts = [];
                DB::connection('mysql')->table('business_partners')->truncate();
                foreach ($olds as $old) {
                    $file = null;
                    if ($old->thumbnail) {
                        //Insert file.
                        $data = [
                            'name' => $old->thumbnail,
                            'url' => env('BASE_URL') . $old->thumbnail,
                            'size' => null,
                            'type' => 0,
                        ];
                        $file = $this->createFile($data);
                    }
                    $inserts[] = [
                        'name' => $old->name,
                        'url' => $old->link_url,
                        'logo' => $file ? $file->id : 0,
                        'description' => $old->description,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('business_partners')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function contact()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('mod_contact')->get();
            if ($olds->count()) {
                $inserts = [];
                DB::connection('mysql')->table('consultancies')->truncate();
                foreach ($olds as $old) {
                    $inserts[] = [
                        'email' => $old->Email,
                        'name' => $old->Name,
                        'phone' => $old->Phone,
                        'status' => $old->RepStatus,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('consultancies')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function popup()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('mod_popup')->get();
            if ($olds->count()) {
                $inserts = [];
                DB::connection('mysql')->table('popups')->truncate();
                foreach ($olds as $old) {
                    $file = null;
                    if ($old->Image) {
                        //Insert file.
                        $data = [
                            'name' => $old->Image,
                            'url' => env('BASE_URL') . $old->Image,
                            'size' => null,
                            'type' => 0,
                        ];
                        $file = $this->createFile($data);
                    }
                    $inserts[] = [
                        'image_id' => $file ? $file->id : 0,
                        'title' => $old->Name,
                        'active' => $old->Activity,
                        'url' => $old->Link,
                        'created_at' => $old->Created,
                        'updated_at' => $old->Updated,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('popups')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function guide()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('sys_page')->where('ParentID', 3850)->get();
            if ($olds->count()) {
                $inserts = [];
                DB::connection('mysql')->table('guides')->truncate();
                foreach ($olds as $old) {
                    $inserts[] = [
                        'title' => $old->Name,
                        'content' => $old->TopContent,
                        'active' => $old->Activity,
                        'created_at' => $old->Created,
                        'updated_at' => $old->Updated,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('guides')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function admin()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('cp_user')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('admins')->truncate();
                $inserts = [];
                foreach ($olds as $key => $old) {
                    $inserts[] = [
                        'business_partner_id' => $old->UserID ?? 0,
                        'name' => $old->LoginName,
                        'email' => $old->Email ?? 'fake_gmail_' . $key . '@gmail.com',
                        'password' => $old->Password,
                        'status' => $old->Activity,
                        'remember_token' => $old->remember_token,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('admins')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function user()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('mod_webuser')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('users')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $file = null;
                    if ($old->File) {
                        //Insert file.
                        $data = [
                            'name' => Str::slug($old->Name),
                            'url' => env('BASE_URL') . $old->File,
                            'size' => null,
                            'type' => 0,
                        ];
                        $file = $this->createFile($data);
                    }
                    if ($old->Source === 'Facebook') {
                        $source = 0;
                    } elseif ($old->Source === 'Google') {
                        $source = 1;
                    } else {
                        $source = 2;
                    }
                    $inserts[] = [
                        'id' => $old->ID,
                        'name' => $old->Name,
                        'email' => $old->Email,
                        'password' => $old->Password,
                        'address' => $old->Address,
                        'phone' => $old->Phone,
                        'avatar_id' => $file ? $file->id : 0,
                        'country' => $old->Country,
                        'type' => $old->role ?? 0,
                        'source' => $source,
                        'birthday' => $old->Birthday,
                        'gender' => $old->Gender ?? 0,
                        'verify' => $old->Verify,
                        'access_token' => $old->AccessToken,
                        'level' => $old->Level ?? 0,
                        'before_login' => $old->BeforeLoginAt,
                        'u_id' => $old->uid,
                        'business_partner_id' => $old->enterprise_id ?? 0,
                        'description' => $old->Content,
                        'created_at' => $old->Created,
                        'updated_at' => $old->Updated,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('users')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function notificationsApp()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('notification_app')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('notifications_app')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $linkAds = null;
                    if ($old->link_ads) {
                        //Insert file.
                        $data = [
                            'name' => 'ads-' . Str::slug($old->title),
                            'url' => env('BASE_URL') . $old->link_ads,
                            'size' => null,
                            'type' => 6,
                        ];
                        $linkAds = $this->createFile($data);
                    }
                    $linkImage = null;
                    if ($old->link_image) {
                        //Insert file.
                        $data = [
                            'name' => 'img-' . Str::slug($old->title),
                            'url' => env('BASE_URL') . $old->link_image,
                            'size' => null,
                            'type' => 6,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $inserts[] = [
                        'flashcard_topic_id' => $old->flashcard_topic_id ?? 0,
                        'push_date' => $old->pushlish,
                        'status' => $old->status,
                        'link_image_id' => $linkImage ? $linkImage->id : 0,
                        'title' => $old->title,
                        'content' => $old->content,
                        'link_ads_id' => $linkAds ? $linkAds->id : 0,
                        'type' => $old->type ?? 0,
                        'created_at' => $old->created_at,
                        'updated_at' => $old->updated_at,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);

                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('notifications_app')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function flashcardTopic()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('flashcard_topics')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('flashcard_topics')->truncate();
                $inserts = [];
                $dataPivot = [];
                foreach ($olds as $old) {
                    $linkImage = null;
                    if ($old->image) {
                        //Insert file.
                        $data = [
                            'name' => 'img-' . Str::slug($old->name),
                            'url' => env('BASE_URL') . $old->image,
                            'size' => null,
                            'type' => 0,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $inserts[] = [
                        'id' => $old->id,
                        'name' => $old->name,
                        'word_to_learn' => $old->number_flashcard_passed,
                        'link_thumbnail' => $linkImage ? $linkImage->id : 0,
                        'status' => $old->status,
                        'created_at' => $old->created,
                        'updated_at' => $old->updated,
                    ];

                    $dataPivot[] = [
                        'flashcard_topic_id' => $old->id,
                        'flashcard_category_id' => $old->category_id,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                $insertDataPivot = collect($dataPivot); // Make a collection to use the chunk method
                $chunksPivot = $insertDataPivot->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('flashcard_topics')->insert($chunk->toArray());
                }
                foreach ($chunksPivot as $chunkPivot) {
                    DB::connection('mysql')->table('flashcard_category_topic')->insert($chunkPivot->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function flashcard()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('flashcards')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('flashcards')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $linkImage = null;
                    if ($old->image) {
                        //Insert file.
                        $data = [
                            'name' => 'img-' . Str::slug($old->name),
                            'url' => env('BASE_URL') . $old->image,
                            'size' => null,
                            'type' => 0,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $linkAudio = null;
                    if ($old->file) {
                        //Insert file.
                        $data = [
                            'name' => 'audio-' . Str::slug($old->name),
                            'url' => env('BASE_URL') . $old->file,
                            'size' => null,
                            'type' => 1,
                        ];
                        $linkAudio = $this->createFile($data);
                    }
                    $inserts[] = [
                        'id' => $old->id,
                        'flashcard_topic_id' => $old->topic_id,
                        'audio_id' => $linkAudio ? $linkAudio->id : 0,
                        'link_thumbnail' => $linkImage ? $linkImage->id : 0,
                        'previous_name' => $old->name,
                        'back_name' => $old->desc,
                        'furigana' => $old->furigana,
                        'latin' => $old->latin,
                        'example' => $old->example,
                        'status' => $old->status,
                        'type' => $old->type,
                        'order' => $old->order,
                        'created_at' => $old->created,
                        'updated_at' => $old->updated,
                    ];

                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('flashcards')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function flashcardCategory()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('flashcard_categories')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('flashcard_categories')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $inserts[] = [
                        'id' => $old->id,
                        'name' => $old->name,
                        'type' => $old->type,
                        'status' => $old->status,
                        'created_at' => $old->created,
                        'updated_at' => $old->updated,
                    ];
                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('flashcard_categories')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function answer()
    {
        DB::beginTransaction();
        try {
            $exerciseQuestions = DB::connection('mysql')->table('questions')->where('id_test', '<>', 0)->get()->toArray();
            $insertAnswer = [];
            foreach ($exerciseQuestions as $exerciseQuestion) {
                $answerItems = DB::connection('mysql2')->table('mod_answer')->where('QuestionID', $exerciseQuestion->id_old)->get()->toArray();
                foreach ($answerItems as $key => $answerItem) {
                    $insertAnswer[] = [
                        'question_id' => $exerciseQuestion->id,
                        'name' => $answerItem->Name ? str_replace("&nbsp;", '', strip_tags($answerItem->Name)) : '',
                        'is_correct' => $answerItem->Type > 0 ? 1 : 0,
                        'order' => $key,
                        'created_at' => $answerItem->Updated,
                        'updated_at' => $answerItem->Updated,
                    ];
                }
            }
            $insertDataAnswer = collect($insertAnswer); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('answers')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function lesson()
    {
        DB::beginTransaction();
        try {
            DB::connection('mysql')->table('lessons')->truncate();
            DB::connection('mysql')->table('courses')->truncate();
            DB::connection('mysql')->table('advertisements')->truncate();
            DB::connection('mysql')->table('tutorials')->truncate();
            DB::connection('mysql')->table('report_lessons')->truncate();
            DB::connection('mysql')->table('comments')->truncate();
            $olds = DB::connection('mysql2')->table('mod_course')->get();
            $oldErrors = DB::connection('mysql2')->table('video_errors')->get();
            $oldParts = DB::connection('mysql2')->table('mod_part')->get()->toArray();
            $oldLessons = DB::connection('mysql2')->table('mod_lesson')->get()->toArray();
            $oldVideos = DB::connection('mysql2')->table('mod_video')->get()->toArray();
            $oldFlashcards = DB::connection('mysql2')->table('lesson_flashcard')->get()->toArray();
            $oldTests = DB::connection('mysql2')->table('mod_test')->get()->toArray();
            $oldQuestionVideos = DB::connection('mysql2')->table('question_videos')->get()->toArray();
            $oldAnswersVideos = DB::connection('mysql2')->table('answer_videos')->get()->toArray();
            $oldTutorials = DB::connection('mysql2')->table('mod_course_tutorials')->get()->toArray();
            $oldComments = DB::connection('mysql2')->table('mod_comment')->get();
            $questionAfter = DB::connection('mysql')->table('questions')->orderBy('id', 'DESC')->first();
            $idQuestionAfter = $questionAfter ? $questionAfter->id : 0;
            $inserts = [];
            $insertQuestion = [];
            $insertAnswer = [];
            $insertAdvertisement = [];
            $insertTutorial = [];
            $insertError = [];
            $insertComment = [];
            $t = 0;
            $course = [];
            $listIds = [];
            $listVideoIds = [];
            $listFlashcardIds = [];
            $listExercises = [];
            $listQuestions = [];
            $listAnswers = [];
            $listErrors = [];
            $listComments = [];
            foreach ($olds as $old) {
                $linkImage = null;
                if ($old->File) {
                    //Insert file.
                    $data = [
                        'name' => $old->Code . '-image-intro',
                        'url' => env('BASE_URL') . $old->File,
                        'size' => null,
                        'type' => 0,
                    ];
                    $linkImage = $this->createFile($data);
                }
                $linkImageThumbnail = null;
                if ($old->image_video_intro) {
                    //Insert file.
                    $data = [
                        'name' => $old->Code . '-image-thumbnail-intro',
                        'url' => env('BASE_URL') . $old->image_video_intro,
                        'size' => null,
                        'type' => 0,
                    ];
                    $linkImageThumbnail = $this->createFile($data);
                }
                $linkVideoIntro = null;
                if ($old->IntroVideo) {
                    //Insert file.
                    $data = [
                        'name' => $old->Code . '-video-intro',
                        'url' => env('BASE_URL') . $old->IntroVideo,
                        'size' => null,
                        'type' => 4,
                    ];
                    $linkVideoIntro = $this->createFile($data);
                }
                //Insert course.
                $course[] = [
                    'id' => $old->ID,
                    'level_id' => $old->Level,
                    'name' => $old->Name,
                    'file_id' => $linkVideoIntro ? $linkVideoIntro->id : 0,
                    'link_avatar' => $linkImage ? $linkImage->id : 0,
                    'link_thumbnail' => $linkImageThumbnail ? $linkImageThumbnail->id : 0,
                    'post_date' => $old->Published,
                    'type' => $old->type,
                    'created_at' => $old->Updated,
                    'updated_at' => $old->Updated,
                ];
                $id = $t + 1;
                $inserts[] = [
                    'id' => $id,
                    'parent_id' => 0,
                    'course_id' => $old->ID,
                    'flashcard_topic_id' => 0,
                    'exercise_id' => 0,
                    'name' => $old->Name,
                    'code' => Str::slug($old->Name),
                    'link_backup' => 0,
                    'link_document' => 0,
                    'thumbnail_id' => 0,
                    'file_id' => 0,
                    'type' => 3,
                    'status' => 1,
                    'trial_study' => 0,
                    'description' => $old->Desc,
                    'order' => $id,
                    'created_at' => $old->Updated,
                    'updated_at' => $old->Updated,
                ];
                foreach ($oldTutorials as $oldTutorial) {
                    if ($oldTutorial->course_id === $old->ID) {
                        $insertTutorial[] = [
                            'id' => $oldTutorial->id,
                            'lesson_id' => $id,
                            'title' => $oldTutorial->name,
                            'content' => $oldTutorial->content,
                            'created_at' => $oldTutorial->created,
                            'updated_at' => $oldTutorial->updated,
                        ];

                    }
                }

                $t = $id;
                foreach ($oldParts as $oldPart) {
                    if ($oldPart->CourseID === $old->ID) {
                        $t = $t + 1;
                        $inserts[] = [
                            'id' => $t,
                            'parent_id' => $id,
                            'course_id' => $oldPart->CourseID ?? 0,
                            'flashcard_topic_id' => 0,
                            'exercise_id' => 0,
                            'name' => $oldPart->Name,
                            'code' => Str::slug($oldPart->Name),
                            'link_backup' => 0,
                            'link_document' => 0,
                            'thumbnail_id' => 0,
                            'file_id' => 0,
                            'type' => 3,
                            'status' => 1,
                            'trial_study' => 0,
                            'description' => null,
                            'order' => $t ?? 0,
                            'created_at' => $oldPart->Updated,
                            'updated_at' => $oldPart->Updated,
                        ];

                        $j = $t;
                        foreach ($oldLessons as $oldLesson) {
                            if ((int)$oldLesson->PartID === (int)$oldPart->ID) {
                                if (!in_array($oldLesson->ID, $listIds)) {
                                    $j = $j + 1;
                                    $listIds[] = $oldLesson->ID;
                                    $inserts[] = [
                                        'id' => $j,
                                        'parent_id' => $t,
                                        'course_id' => $oldLesson->CourseID ?? 0,
                                        'flashcard_topic_id' => 0,
                                        'exercise_id' => 0,
                                        'name' => $oldLesson->Name,
                                        'code' => Str::slug($oldLesson->Name),
                                        'link_backup' => 0,
                                        'link_document' => 0,
                                        'thumbnail_id' => 0,
                                        'file_id' => 0,
                                        'type' => 3,
                                        'status' => 1,
                                        'trial_study' => 0,
                                        'description' => null,
                                        'order' => $j ?? 0,
                                        'created_at' => $oldLesson->Published,
                                        'updated_at' => $oldLesson->Published,
                                    ];
                                    $z = $j;

                                    foreach ($oldVideos as $oldVideo) {
                                        if ((int)$oldLesson->ID === (int)$oldVideo->LessonID) {
                                            if (!in_array($oldVideo->ID, $listVideoIds)) {
                                                $z = $z + 1;
                                                $listVideoIds[] = $oldVideo->ID;
                                                $linkBackUp = null;
                                                if ($oldVideo->link_youtube) {
                                                    //Insert file.
                                                    $data = [
                                                        'name' => $oldVideo->Code . '-video-backup',
                                                        'url' => env('BASE_URL') . $oldVideo->link_youtube,
                                                        'size' => null,
                                                        'type' => 4,
                                                    ];
                                                    $linkBackUp = $this->createFile($data);
                                                }

                                                $linkDocument = null;
                                                if ($oldVideo->file_document) {
                                                    //Insert file.
                                                    $data = [
                                                        'name' => $oldVideo->Code . '-video-document',
                                                        'url' => env('BASE_URL') . $oldVideo->file_document,
                                                        'size' => null,
                                                        'type' => 4,
                                                    ];
                                                    $linkDocument = $this->createFile($data);
                                                }

                                                $linkVideo = null;
                                                if ($oldVideo->Video) {
                                                    //Insert file.
                                                    $data = [
                                                        'name' => $oldVideo->Code . '-video',
                                                        'url' => env('APP_DOMAIN_VIDEO_M3U8') . $oldVideo->Video,
                                                        'size' => null,
                                                        'type' => 4,
                                                    ];
                                                    $linkVideo = $this->createFile($data);
                                                }

                                                $inserts[] = [
                                                    'id' => $z,
                                                    'parent_id' => $j,
                                                    'course_id' => $oldVideo->CourseID ?? 0,
                                                    'flashcard_topic_id' => 0,
                                                    'exercise_id' => 0,
                                                    'name' => $oldVideo->Name,
                                                    'code' => $oldVideo->Code,
                                                    'link_backup' => $linkBackUp ? $linkBackUp->id : 0,
                                                    'link_document' => $linkDocument ? $linkDocument->id : 0,
                                                    'thumbnail_id' => 0,
                                                    'file_id' => $linkVideo ? $linkVideo->id : 0,
                                                    'type' => 0,
                                                    'status' => 1,
                                                    'trial_study' => 0,
                                                    'description' => null,
                                                    'order' => $z ?? 0,
                                                    'created_at' => $oldVideo->Updated,
                                                    'updated_at' => $oldVideo->Updated,
                                                ];

                                                foreach ($oldQuestionVideos as $oldQuestionVideo) {
                                                    if ((int)$oldVideo->ID === (int)$oldQuestionVideo->video_id) {
                                                        if (!in_array($oldQuestionVideo->id, $listQuestions)) {
                                                            $listQuestions[] = $oldQuestionVideo->id;
                                                            //Before insert question.
                                                            $idQuestionAfter = $idQuestionAfter + 1;
                                                            $insertQuestion[] = [
                                                                'id' => $idQuestionAfter,
                                                                'name' => $oldQuestionVideo->name,
                                                                'script' => null,
                                                                'type' => 0,
                                                                'audio_id' => 0,
                                                                'image_id' => 0,
                                                                'suggested_time' => 0,
                                                                'created_at' => $oldQuestionVideo->created_at,
                                                                'updated_at' => $oldQuestionVideo->updated_at,
                                                            ];
                                                            foreach ($oldAnswersVideos as $oldAnswersVideo) {
                                                                if (!in_array($oldAnswersVideo->id, $listAnswers)) {
                                                                    $listAnswers[] = $oldAnswersVideo->id;

                                                                    //Before insert answer.
                                                                    $insertAnswer = [
                                                                        'question_id' => $idQuestionAfter,
                                                                        'name' => $oldAnswersVideo->name ? str_replace("&nbsp;", '', strip_tags($oldAnswersVideo->name)) : '',
                                                                        'is_correct' => $oldAnswersVideo->is_right_answer,
                                                                        'order' => $oldAnswersVideo->order,
                                                                        'created_at' => $oldAnswersVideo->created_at,
                                                                        'updated_at' => $oldAnswersVideo->updated_at,
                                                                    ];
                                                                }
                                                            }

                                                            $insertAdvertisement = [
                                                                'id' => $oldQuestionVideo->id,
                                                                'lesson_id' => $z,
                                                                'file_id' => 0,
                                                                'seen_minute' => Carbon::parse('00:00:00')
                                                                    ->addMinutes($oldQuestionVideo->time)
                                                                    ->format('H:i:s'),
                                                                'questions' => $idQuestionAfter,
                                                                'type' => 0,
                                                                'created_at' => $oldQuestionVideo->created_at,
                                                                'updated_at' => $oldQuestionVideo->updated_at,
                                                            ];
                                                        }
                                                    }
                                                }

                                                foreach ($oldErrors as $oldError) {
                                                    if ((int)$oldVideo->ID === (int)$oldError->video_id) {
                                                        if (!in_array($oldError->id, $listErrors)) {
                                                            $listErrors[] = $oldError->id;
                                                            $file = null;
                                                            if ($oldError->image) {
                                                                //Insert file.
                                                                $data = [
                                                                    'name' => $oldError->image,
                                                                    'url' => env('BASE_URL') . $oldError->image,
                                                                    'size' => null,
                                                                    'type' => 0,
                                                                ];
                                                                $file = $this->createFile($data);
                                                            }
                                                            $insertError[] = [
                                                                'user_id' => $oldError->user_id,
                                                                'lesson_id' => $z,
                                                                'image_id' => $file ? $file->id : 0,
                                                                'type' => $oldError->error_type,
                                                                'status' => $oldError->status,
                                                                'description' => $oldError->desc,
                                                                'created_at' => $oldError->created,
                                                                'updated_at' => $oldError->updated,
                                                            ];
                                                        }
                                                    }
                                                }

                                                foreach ($oldComments as $oldComment) {
                                                    if ((int)$oldVideo->ID === (int)$oldComment->TopicID) {
                                                        if (!in_array($oldComment->ID, $listComments)) {
                                                            $listComments[] = $oldComment->ID;
                                                            $file = null;
                                                            if ($oldComment->File) {
                                                                //Insert file.
                                                                $data = [
                                                                    'name' => $oldComment->File,
                                                                    'url' => env('BASE_URL') . $oldComment->File,
                                                                    'size' => null,
                                                                    'type' => 0,
                                                                ];
                                                                $file = $this->createFile($data);
                                                            }
                                                            $insertComment[] = [
                                                                'id' => $oldComment->ID,
                                                                'user_id' => $oldComment->UserID ?? 0,
                                                                'lesson_id' => $oldComment->TopicID,
                                                                'parent_id' => $oldComment->ParentID ?? 0,
                                                                'file_id' => $file ? $file->id : 0,
                                                                'admin_id' => $oldComment->AdminID ?? 0,
                                                                'approve' => 1,
                                                                'pin' => 0,
                                                                'type' => 0,
                                                                'comment' => $oldComment->Comment,
                                                                'created_at' => $oldComment->CreatedAt,
                                                                'updated_at' => $oldComment->UpdatedAt,
                                                            ];

                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $f = $z;

                                    foreach ($oldFlashcards as $oldFlashcard) {
                                        if ((int)$oldLesson->ID === (int)$oldFlashcard->lesson_id) {
                                            if (!in_array($oldFlashcard->id, $listFlashcardIds)) {
                                                $f = $f + 1;
                                                $listFlashcardIds[] = $oldFlashcard->id;
                                                $flashcardTopic = FlashcardTopic::where('id', $oldFlashcard->flashcard_topic_id)->first();
                                                $inserts[] = [
                                                    'id' => $f,
                                                    'parent_id' => $j,
                                                    'course_id' => $oldLesson->CourseID,
                                                    'flashcard_topic_id' => $oldFlashcard->flashcard_topic_id,
                                                    'exercise_id' => 0,
                                                    'name' => $flashcardTopic ? $flashcardTopic->name : 'flashcard',
                                                    'code' => $flashcardTopic ? Str::slug($flashcardTopic->name) : 'flashcard',
                                                    'link_backup' => 0,
                                                    'link_document' => 0,
                                                    'thumbnail_id' => 0,
                                                    'file_id' => 0,
                                                    'type' => 1,
                                                    'status' => 0,
                                                    'trial_study' => 0,
                                                    'description' => null,
                                                    'order' => $f,
                                                    'created_at' => $flashcardTopic->Updated,
                                                    'updated_at' => $flashcardTopic->Updated,
                                                ];
                                            }
                                        }
                                    }
                                    $ex = $f;
                                    foreach ($oldTests as $oldTest) {
                                        if ((int)$oldLesson->ID === (int)$oldTest->LessonID) {
                                            if (!in_array($oldTest->ID, $listExercises)) {
                                                $ex = $ex + 1;
                                                $listExercises[] = $oldTest->ID;
                                                $inserts[] = [
                                                    'id' => $ex,
                                                    'parent_id' => $j,
                                                    'course_id' => $oldLesson->CourseID,
                                                    'flashcard_topic_id' => 0,
                                                    'exercise_id' => $oldTest->ID,
                                                    'name' => $oldTest->Name,
                                                    'code' => $oldTest->Code,
                                                    'link_backup' => 0,
                                                    'link_document' => 0,
                                                    'thumbnail_id' => 0,
                                                    'file_id' => 0,
                                                    'type' => 2,
                                                    'status' => 1,
                                                    'trial_study' => 0,
                                                    'description' => null,
                                                    'order' => $ex,
                                                    'created_at' => $oldTest->Updated,
                                                    'updated_at' => $oldTest->Updated,
                                                ];
                                            }
                                        }
                                    }
                                    $j = $ex;
                                }
                            }
                        }
                        $t = $j;
                    }
                }
            }
            $insertData = collect($inserts); // Make a collection to use the chunk method
            $chunks = $insertData->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('lessons')->insert($chunk->toArray());
            }

            $insertDataCourse = collect($course); // Make a collection to use the chunk method
            $chunks = $insertDataCourse->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('courses')->insert($chunk->toArray());
            }

            $insertDataAnswer = collect($insertAnswer); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('answers')->insert($chunk->toArray());
            }

            $insertDataAdvertisement = collect($insertAdvertisement); // Make a collection to use the chunk method
            $chunks = $insertDataAdvertisement->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('advertisements')->insert($chunk->toArray());
            }

            $insertDataQuestion = collect($insertQuestion); // Make a collection to use the chunk method
            $chunks = $insertDataQuestion->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('questions')->insert($chunk->toArray());
            }

            $insertDataTutorial = collect($insertTutorial); // Make a collection to use the chunk method
            $chunks = $insertDataTutorial->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('tutorials')->insert($chunk->toArray());
            }

            $insertDataError = collect($insertError); // Make a collection to use the chunk method
            $chunks = $insertDataError->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('report_lessons')->insert($chunk->toArray());
            }

            $insertDataComment = collect($insertComment); // Make a collection to use the chunk method
            $chunks = $insertDataComment->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('comments')->insert($chunk->toArray());
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function exercise()
    {
        DB::beginTransaction();
        try {
            DB::connection('mysql')->table('exercises')->truncate();
            $groupQuestionAfter = DB::connection('mysql')->table('group_questions')->orderBy('id', 'DESC')->first();
            $idGroupQuestionAfter = $groupQuestionAfter ? $groupQuestionAfter->id : 0;
            $inserts = [];
            $insertQuestion = [];
            $groupQuestion = [];
            $exercises = Exercise::with(['question' => function ($query) {
                $query->withCount('answer')
                    ->orderBy('Order', 'ASC');
            }])->get();
            foreach ($exercises as $exercise) {
                $inserts[] = [
                    'id' => $exercise->ID,
                    'name' => $exercise->Name,
                    'number_point_passed' => $exercise->number_question_passed,
                    'time' => $exercise->Time,
                    'show_time' => $exercise->is_time,
                    'show_correct' => $exercise->show_answer,
                    'status' => $exercise->Activity,
                    'order' => $exercise->Order,
                    'created_at' => $exercise->Updated,
                    'updated_at' => $exercise->Updated,
                ];

                foreach ($exercise->question as $key => $question) {
                    if (!$key && $question->answer_count) {
                        $idGroupQuestionAfter = $idGroupQuestionAfter + 1;
                        $groupQuestion[] = [
                            'exercise_id' => $question->TestID,
                            'group_question_id' => $idGroupQuestionAfter,
                            'examination_topic_id' => 0,
                            'name' => null,
                            'order' => $key,
                            'image_id' => 0,
                            'audio_id' => 0,
                            'parent_id' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    if ($question->answer_count) {
                        $linkImage = null;
                        if ($question->File) {
                            //Insert file.
                            $data = [
                                'name' => 'img-question-' . $key,
                                'url' => env('BASE_URL') . $question->File,
                                'size' => null,
                                'type' => 0,
                            ];
                            $linkImage = $this->createFile($data);
                        }
                        $linkAudio = null;
                        if ($question->Mp3) {
                            //Insert file.
                            $data = [
                                'name' => 'audio-question-' . $key,
                                'url' => env('BASE_URL') . $question->Mp3,
                                'size' => null,
                                'type' => 1,
                            ];
                            $linkAudio = $this->createFile($data);
                        }

                        $insertQuestion[] = [
                            'id_old' => $question->ID,
                            'id_test' => $question->TestID ?? 0,
                            'group_question_id' => $idGroupQuestionAfter,
                            'name' => $question->Name,
                            'script' => $question->script,
                            'type' => $question->Type === 2 ? 0 : 1,
                            'audio_id' => $linkAudio ? $linkAudio->id : 0,
                            'image_id' => $linkImage ? $linkImage->id : 0,
                            'suggested_time' => $question->time,
                            'created_at' => $question->Updated,
                            'updated_at' => $question->Updated,
                        ];
                    } else {
                        $idGroupQuestionAfter = $idGroupQuestionAfter + 1;
                        $linkImage = null;
                        if ($question->File) {
                            //Insert file.
                            $data = [
                                'name' => 'img-question-' . $key,
                                'url' => env('BASE_URL') . $question->File,
                                'size' => null,
                                'type' => 0,
                            ];
                            $linkImage = $this->createFile($data);
                        }
                        $linkAudio = null;
                        if ($question->Mp3) {
                            //Insert file.
                            $data = [
                                'name' => 'audio-question-' . $key,
                                'url' => env('BASE_URL') . $question->Mp3,
                                'size' => null,
                                'type' => 1,
                            ];
                            $linkAudio = $this->createFile($data);
                        }
                        $groupQuestion[] = [
                            'exercise_id' => $question->TestID,
                            'group_question_id' => $idGroupQuestionAfter,
                            'examination_topic_id' => 0,
                            'name' => $question->Name,
                            'order' => $key,
                            'image_id' => $linkAudio ? $linkAudio->id : 0,
                            'audio_id' => $linkImage ? $linkImage->id : 0,
                            'parent_id' => 0,
                            'created_at' => $question->Updated,
                            'updated_at' => $question->Updated,
                        ];
                    }

                }

            }
            $insertData = collect($inserts); // Make a collection to use the chunk method
            $chunks = $insertData->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('exercises')->insert($chunk->toArray());
            }

            $insertDataQuestion = collect($insertQuestion); // Make a collection to use the chunk method
            $chunks = $insertDataQuestion->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('questions')->insert($chunk->toArray());
            }
            $insertGroupQuestionData = collect($groupQuestion); // Make a collection to use the chunk method
            $chunks = $insertGroupQuestionData->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('group_questions')->insert($chunk->toArray());
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function ebook()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('mod_ebook')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('ebooks')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $linkImage = null;
                    if ($old->Image) {
                        //Insert file.
                        $data = [
                            'name' => $old->title,
                            'url' => env('BASE_URL') . '/' . $old->Image,
                            'size' => null,
                            'type' => 0,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $levelId = 1;
                    if ($old->levelID === 'khoa-hoc-n2') {
                        $levelId = 4;
                    }
                    if ($old->levelID === 'khoa-hoc-n3') {
                        $levelId = 3;
                    }
                    if ($old->levelID === 'khoa-hoc-n5') {
                        $levelId = 1;
                    }
                    if ($old->levelID === 'khoa-hoc-n4') {
                        $levelId = 2;
                    }
                    if ($old->levelID === 'khoa-hoc-n1') {
                        $levelId = 5;
                    }
                    $inserts[] = [
                        'id' => $old->id,
                        'title' => $old->title,
                        'url' => $old->link,
                        'image_id' => $linkImage ? $linkImage->id : 0,
                        'level_id' => $levelId,
                        'active' => 1,
                        'created_at' => $old->created_at,
                        'updated_at' => $old->updated_at,
                    ];

                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('ebooks')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function feedback()
    {
        DB::beginTransaction();
        try {
            $olds = DB::connection('mysql2')->table('feedbacks')->get();
            if ($olds->count()) {
                DB::connection('mysql')->table('feedbacks')->truncate();
                $inserts = [];
                foreach ($olds as $old) {
                    $linkImage = null;
                    if ($old->file) {
                        //Insert file.
                        $data = [
                            'name' => $old->title,
                            'url' => env('BASE_URL') . '/' . $old->file,
                            'size' => null,
                            'type' => 0,
                        ];
                        $linkImage = $this->createFile($data);
                    }

                    $inserts[] = [
                        'id' => $old->ID,
                        'name' => $old->title,
                        'active' => $old->status,
                        'image_id' => $linkImage ? $linkImage->id : 0,
                        'content' => $old->content,
                        'created_at' => $old->created_at,
                        'updated_at' => $old->updated_at,
                    ];

                }
                $insertData = collect($inserts); // Make a collection to use the chunk method
                $chunks = $insertData->chunk(50);
                foreach ($chunks as $chunk) {
                    DB::connection('mysql')->table('feedbacks')->insert($chunk->toArray());
                }
            }
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function examination()
    {
        DB::beginTransaction();
        try {
            DB::connection('mysql')->table('examinations')->truncate();
            DB::connection('mysql')->table('examination_topic')->truncate();
            DB::connection('mysql')->table('exam_managements')->truncate();
            $exams = DB::connection('mysql2')->table('mod_exam')->get()->toArray();
            $examParts = DB::connection('mysql2')->table('mod_exampart')->get()->toArray();
            $groupQuestionParents = DB::connection('mysql2')->table('mod_exampart2')->get()->toArray();
            $groupQuestionAfter = DB::connection('mysql')->table('group_questions')->orderBy('id', 'DESC')->first();
            $idGroupQuestionAfter = $groupQuestionAfter ? $groupQuestionAfter->id : 0;
            $insertExam = [];
            $insertExamTopic = [];
            $insertGroupQuestions = [];
            $insertExamManagement = [
                [
                    'id' => 1,
                    'name' => 'Từ Vựng - Ngữ pháp',
                    'description' => null,
                    'type' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                [
                    'id' => 2,
                    'name' => 'Đọc Hiểu',
                    'description' => null,
                    'type' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                [
                    'id' => 3,
                    'name' => 'Nghe Hiểu',
                    'description' => null,
                    'type' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

            ];

            foreach ($exams as $exam) {
                $linkSurvey = null;
                if ($exam->Survey) {
                    //Insert file.
                    $data = [
                        'name' => Str::slug($exam->Name),
                        'url' => env('BASE_URL') . '/' . $exam->Survey,
                        'size' => null,
                        'type' => 6,
                    ];
                    $linkSurvey = $this->createFile($data);
                }
                $insertExam[] = [
                    'id' => $exam->ID,
                    'examination_project_id' => 0,
                    'total_point' => $exam->TotalPoint,
                    'pass_point' => $exam->PassPoint,
                    'url' => null,
                    'survey_link_id' => $linkSurvey ? $linkSurvey->id : 0,
                    'name' => $exam->Name,
                    'status' => 1,
                    'type' => $exam->Type === 2 ? 0 : 1,
                    'level_id' => 0,
                    'exam' => 0,
                    'order' => $exam->Order,
                    'created_at' => $exam->Updated,
                    'updated_at' => $exam->Updated,
                ];
            }

            foreach ($examParts as $examPart) {
                $linkMp3 = null;
                if ($examPart->mp3) {
                    //Insert file.
                    $data = [
                        'name' => $examPart->Code,
                        'url' => env('BASE_URL') . '/' . $examPart->mp3,
                        'size' => null,
                        'type' => 2,
                    ];
                    $linkMp3 = $this->createFile($data);
                }
                $insertExamTopic[] = [
                    'id' => $examPart->ID,
                    'examination_id' => $examPart->ExamID,
                    'exam_management_id' => $examPart->type,
                    'title' => $examPart->Name,
                    'time' => $examPart->Time,
                    'break_time' => $examPart->free_time ?? 0,
                    'point' => $examPart->Point ?? 0,
                    'slip_point' => $examPart->SlipPoint ?? 0,
                    'order' => $examPart->ID,
                    'audio_id' => $linkMp3 ? $linkMp3->id : 0,
                    'created_at' => $examPart->Updated,
                    'updated_at' => $examPart->Updated,
                ];
            }

            foreach ($groupQuestionParents as $groupQuestionParent) {
                $idGroupQuestionAfter = $idGroupQuestionAfter + 1;
                $insertGroupQuestions[] = [
                    'id' => $idGroupQuestionAfter,
                    'exercise_id' => 0,
                    'examination_topic_id' => $groupQuestionParent->ExamPartID,
                    'name' => $groupQuestionParent->Name,
                    'order' => $idGroupQuestionAfter,
                    'image_id' => 0,
                    'audio_id' => 0,
                    'parent_id' => 0,
                    'created_at' => $groupQuestionParent->Updated,
                    'updated_at' => $groupQuestionParent->Updated,
                    'id_old' => 0,
                ];
                $groupChild = $idGroupQuestionAfter;
                $childs = DB::connection('mysql2')->table('mod_examgroup')->where('ExamPart2ID', $groupQuestionParent->ID)->get()->toArray();
                foreach ($childs as $groupQuestionChildItem) {
                    $groupChild = $groupChild + 1;
                    $linkMp3 = null;
                    if ($groupQuestionChildItem->Mp3) {
                        //Insert file.
                        $data = [
                            'name' => $groupQuestionChildItem->Content . '-audio-group-question',
                            'url' => env('BASE_URL') . '/' . $groupQuestionChildItem->Mp3,
                            'size' => null,
                            'type' => 2,
                        ];
                        $linkMp3 = $this->createFile($data);
                    }
                    $linkImage = null;
                    if ($groupQuestionChildItem->Image) {
                        //Insert file.
                        $data = [
                            'name' => $groupQuestionChildItem->Content . '-image-group-question',
                            'url' => env('BASE_URL') . '/' . $groupQuestionChildItem->Image,
                            'size' => null,
                            'type' => 2,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $insertGroupQuestions[] = [
                        'id' => $groupChild,
                        'exercise_id' => 0,
                        'examination_topic_id' => $groupQuestionParent->ExamPartID,
                        'name' => $groupQuestionParent->Name,
                        'order' => $idGroupQuestionAfter,
                        'image_id' => $linkImage ? $linkImage->id : 0,
                        'audio_id' => $linkMp3 ? $linkMp3->id : 0,
                        'parent_id' => $idGroupQuestionAfter,
                        'created_at' => $groupQuestionParent->Updated,
                        'updated_at' => $groupQuestionParent->Updated,
                        'id_old' => $groupQuestionChildItem->ID,
                    ];
                }
                $idGroupQuestionAfter = $groupChild;
            }

            DB::connection('mysql')->table('exam_managements')->insert($insertExamManagement);

            $insertDataExam = collect($insertExam); // Make a collection to use the chunk method
            $chunks = $insertDataExam->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('examinations')->insert($chunk->toArray());
            }

            $insertDataExamTopic = collect($insertExamTopic); // Make a collection to use the chunk method
            $chunks = $insertDataExamTopic->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('examination_topic')->insert($chunk->toArray());
            }


            $insertDataGroupQuestion = collect($insertGroupQuestions); // Make a collection to use the chunk method
            $chunks = $insertDataGroupQuestion->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('group_questions')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function resultExam()
    {
        DB::beginTransaction();
        try {
            DB::connection('mysql')->table('exam_results')->truncate();
            DB::connection('mysql')->table('exam_results_detail')->truncate();
            $exams = Result::join('mod_exam', 'mod_exam.ID', '=', 'mod_examhistory.ExamID')
                ->whereBetween('mod_examhistory.StartTime', [Carbon::parse('2019-01-01 00:00:00'), now()])
                ->select(
                    'mod_examhistory.ID as ID',
                    'mod_exam.ID as mod_exam_id',
                    'mod_exam.Type as mod_exam_type',
                    'mod_examhistory.UserID as userId',
                    'mod_examhistory.Result as result',
                    'mod_examhistory.details as details',
                    'mod_examhistory.Complete as complete',
                    'mod_examhistory.works as works',
                    'mod_examhistory.StartTime as startTime',
                )->get();
            $insertResult = [];
//            $insertResultDetail = [];
            foreach ($exams as $key => $result) {
                $id = $key + 1;
//                if ($result->works) {
//                    foreach ($result->works as $work) {
//                        $insertResultDetail[] = [
//                            'exam_result_id' => $id,
//                            'answer_id' => $work,
//                        ];
//                    }
//                }
                $insertResult[] = [
                    'id' => $id,
                    'ex_id' => $result->mod_exam_id,
                    'user_id' => $result->userId,
                    'point' => $result->result,
//                    'language_knowledge' => count($parts) > 0 ? ($parts[0]->Result ?? 0) : 0,
//                    'reading' => count($parts) > 1 ? ($parts[1]->Result ?? 0) : 0,
//                    'listening' => count($parts) > 2 ? ($parts[2]->Result ?? 0) : 0,
                    'details' => $result->details,
                    'type' => $result->mod_exam_type === 1 ? 1 : 0,
                    'status' => $result->complete === 1 ? 1 : 0,
                    'created_at' => $result->startTime,
                    'updated_at' => $result->startTime,
                ];
            }
//            $insertDataResultDetail = collect($insertResultDetail); // Make a collection to use the chunk method
//            $chunks = $insertDataResultDetail->chunk(1000);
//            foreach ($chunks as $chunk) {
//                $temp = [];
//                foreach ($chunk as $item) {
//                    $t = [];
//                    foreach ($item['answer_id'] as $i) {
//                        if ($i !== '') {
//                            $t[] = (int)$i;
//                        }
//                    }
//                    if (count($t)) {
//                        $answerIds = AnswerNew::whereIn('id_old_exam', $t)->get()->pluck('id')->toArray();
//                        foreach ($answerIds as $answerId) {
//                            $temp[] = [
//                                'answer_id' => $answerId,
//                                'exam_result_id' => $item['exam_result_id'],
//                            ];
//                        }
//                    }
//
//                }
//                $insertDataResultTemp = collect($temp); // Make a collection to use the chunk method
//                $chunks = $insertDataResultTemp->chunk(50);
//                foreach ($chunks as $chunk) {
//                    DB::connection('mysql')->table('exam_results_detail')->insert($chunk->toArray());
//                }
//            }


            $insertDataResult = collect($insertResult); // Make a collection to use the chunk method
            $chunks = $insertDataResult->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('exam_results')->insert($chunk->toArray());
            }

            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function removeQuestionAnswer()
    {
        DB::connection('mysql')->table('group_questions')->truncate();
        DB::connection('mysql')->table('group_question_pivot')->truncate();
        DB::connection('mysql')->table('questions')->truncate();
        DB::connection('mysql')->table('answers')->truncate();
        return redirect()->back()->with('msg', 'Success!');
    }

    public function questionExam()
    {
        DB::beginTransaction();
        try {
            $examGroupIds = DB::connection('mysql')->table('group_questions')->where('id_old', '<>', 0)->get()->pluck('id_old')->toArray();
            $questionAfter = DB::connection('mysql')->table('questions')->orderBy('id', 'DESC')->first();
            $idQuestionAfter = $questionAfter ? $questionAfter->id : 0;
            $insertQuestion = [];
            foreach ($examGroupIds as $examGroupId) {
                $questionItems = DB::connection('mysql2')->table('mod_examquestion')->where('ExamGroupID', $examGroupId)->get()->toArray();
                foreach ($questionItems as $key => $questionItem) {
                    $idQuestionAfter = $idQuestionAfter + 1;
                    $linkImage = null;
                    if ($questionItem->File) {
                        //Insert file.
                        $data = [
                            'name' => 'img-question-' . $key,
                            'url' => env('BASE_URL') . $questionItem->File,
                            'size' => null,
                            'type' => 0,
                        ];
                        $linkImage = $this->createFile($data);
                    }
                    $linkAudio = null;
                    if ($questionItem->MP3) {
                        //Insert file.
                        $data = [
                            'name' => 'audio-question-' . $key,
                            'url' => env('BASE_URL') . $questionItem->MP3,
                            'size' => null,
                            'type' => 1,
                        ];
                        $linkAudio = $this->createFile($data);
                    }
                    $insertQuestion[] = [
                        'id' => $idQuestionAfter,
                        'id_old' => $questionItem->ID,
                        'group_question_id' => $questionItem->ExamGroupID,
                        'name' => $questionItem->Name,
                        'script' => null,
                        'type' => $questionItem->Type === 2 ? 0 : 1,
                        'suggested_time' => 0,
                        'image_id' => $linkImage ? $linkImage->id : 0,
                        'audio_id' => $linkAudio ? $linkAudio->id : 0,
                        'created_at' => $questionItem->Updated,
                        'updated_at' => $questionItem->Updated,
                    ];
                }
            }
            $insertDataAnswer = collect($insertQuestion); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('questions')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function answerExam()
    {
        DB::beginTransaction();
        try {
            $examQuestions = DB::connection('mysql')->table('questions')->where('id_old', '<>', 0)->get()->toArray();
            $insertAnswer = [];
            foreach ($examQuestions as $examQuestion) {
                $answerItems = DB::connection('mysql2')->table('mod_examanswer')->where('ExamQuestionID', $examQuestion->id_old)->get()->toArray();

                foreach ($answerItems as $key => $answerItem) {
                    $insertAnswer[] = [
                        'id_old_exam' => $answerItem->ID,
                        'question_id' => $examQuestion->id,
                        'name' => $answerItem->Name ? str_replace("&nbsp;", '', strip_tags($answerItem->Name)) : '',
                        'is_correct' => $answerItem->Point > 0 ? 1 : 0,
                        'order' => $key,
                        'created_at' => $answerItem->Updated,
                        'updated_at' => $answerItem->Updated,
                    ];
                }
            }
            $insertDataAnswer = collect($insertAnswer); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('answers')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function groupQuestionPivot()
    {
        DB::beginTransaction();
        try {
            $examGroupQuestions = DB::connection('mysql')->table('group_questions')
                ->where('id_old', '<>', 0)
                ->select('id', 'id_old')
                ->get()->toArray();
            $insertQuestionPivot = [];
            foreach ($examGroupQuestions as $examGroupQuestion) {
                $questionItems = DB::connection('mysql')->table('questions')->where('group_question_id', $examGroupQuestion->id_old)->get()->toArray();
                foreach ($questionItems as $key => $questionItem) {
                    $point = DB::connection('mysql2')->table('mod_examanswer')
                        ->where('ExamQuestionID', $questionItem->id_old)
                        ->where('Point', '>', 0)
                        ->first();
                    $insertQuestionPivot[] = [
                        'group_question_id' => $examGroupQuestion->id,
                        'question_id' => $questionItem->id,
                        'point' => $point->Point ?? 0,
                        'order' => $key,
                    ];
                }
            }
            $insertDataAnswer = collect($insertQuestionPivot); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('group_question_pivot')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function groupQuestionPivotExercise()
    {
        DB::beginTransaction();
        try {
            $exerciseGroupQuestions = DB::connection('mysql')->table('group_questions')
                ->where('exercise_id', '<>', 0)
                ->select('id', 'exercise_id', 'group_question_id')
                ->get()->toArray();
            $questions = DB::connection('mysql')->table('questions')
                ->where('id_test', '<>', 0)
                ->select('id', 'id_test', 'group_question_id')
                ->get()->toArray();
            $insertQuestionPivot = [];
            foreach ($exerciseGroupQuestions as $exerciseGroupQuestion) {
                foreach ($questions as $key => $question) {
                    if ($exerciseGroupQuestion->group_question_id === $question->group_question_id) {
                        $insertQuestionPivot[] = [
                            'group_question_id' => $exerciseGroupQuestion->id,
                            'question_id' => $question->id,
                            'point' => 1,
                            'order' => $key + 1,
                        ];
                    }

                }
            }
            $insertDataAnswer = collect($insertQuestionPivot); // Make a collection to use the chunk method
            $chunks = $insertDataAnswer->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('group_question_pivot')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }

    public function activate()
    {
        DB::beginTransaction();
        try {
            DB::connection('mysql')->table('activates')->truncate();
            DB::connection('mysql')->table('activate_history')->truncate();
            $orders = DB::connection('mysql2')->table('mod_order')
                ->join('mod_orderdetail', 'mod_orderdetail.OrderID', '=', 'mod_order.ID')
                ->select('mod_order.ID as orderId', 'mod_order.WebUserID as userId', 'mod_order.ActivityTime as activitytime', 'mod_order.type as type',
                    'mod_orderdetail.ID as orderDetailId', 'mod_orderdetail.ProductID as productId', 'mod_orderdetail.ActivityTime as orderDetailActivityTime',
                    'mod_orderdetail.ExpiredTime as orderDetailExpiredTime', 'mod_orderdetail.Period as orderPeriod', 'mod_orderdetail.premium as orderPremium',
                )
                ->get();

            $insertActivateHistory = [];
            $insertActivate = [];
            $idOrders = [];
            foreach ($orders as $order) {
                $insertActivateHistory[] = [
                    'activate_id' => $order->orderId,
                    'period' => $order->orderPeriod ?? 0,
                    'course_id' => $order->productId,
                    'start_time' => $order->orderDetailActivityTime,
                    'end_time' => $order->orderDetailExpiredTime,
                    'type' => $order->orderPremium ?? 0,
                    'created_at' => $order->orderDetailActivityTime,
                    'updated_at' => $order->orderDetailActivityTime,
                ];
                if (!in_array($order->orderId, $idOrders)) {
                    $idOrders[] = $order->orderId;
                    $insertActivate[] = [
                        'id' => $order->orderId,
                        'user_id' => $order->userId,
                        'course_id' => $order->productId,
                        'expired_time' => $order->orderDetailExpiredTime,
                        'type' => $order->orderPremium ?? 0,
                        'method' => 1,
                        'created_at' => $order->orderDetailActivityTime,
                        'updated_at' => $order->orderDetailActivityTime,
                    ];
                }
            }
            $insertDataActivateHistory = collect($insertActivateHistory); // Make a collection to use the chunk method
            $chunks = $insertDataActivateHistory->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('activate_history')->insert($chunk->toArray());
            }

            $insertDataActivate = collect($insertActivate); // Make a collection to use the chunk method
            $chunks = $insertDataActivate->chunk(50);
            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('activates')->insert($chunk->toArray());
            }
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }


    public function truncateAll()
    {
        DB::beginTransaction();
        try {
            Schema::disableForeignKeyConstraints();
            $databaseName = DB::getDatabaseName();
            $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
            foreach ($tables as $table) {
                $name = $table->TABLE_NAME;
                if ($name == 'migrations') {
                    continue;
                }
                DB::table($name)->truncate();
            }
            Schema::enableForeignKeyConstraints();
            DB::commit();
            return redirect()->back()->with('msg', 'Success!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return 1;
        }
    }
}
