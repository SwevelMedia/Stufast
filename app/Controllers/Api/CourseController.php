<?php



namespace App\Controllers\Api;



use App\Controllers\Api\ReviewController as ApiReviewController;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Course;

use App\Models\CourseCategory;

use App\Models\CourseType;

use App\Models\CourseTag;

use App\Models\TypeTag;

use App\Models\Tag;

use App\Models\Resume;

use App\Models\Video;

use App\Models\VideoCategory;

use App\Models\Users;

use App\Models\UserVideo;

use App\Models\TimelineVideo;

use App\Models\Review;

use App\Models\Jobs;

use App\Models\UserCourse;

use App\Models\Cart;

use App\Models\CourseBundling;

use App\Models\OrderCourse;

use App\Models\Order;

use App\Models\UserView;

use CodeIgniter\HTTP\RequestInterface;

use Firebase\JWT\JWT;

use getID3;



class CourseController extends ResourceController

{

    private $getID3;
    private $key;
    private $path;
    private $pathVideo;
    private $pathVideoThumbnail;
    private $modelCourseCategory;
    private $modelCourseType;
    private $modelTypeTag;
    private $modelVideo;
    private $modelVideoCategory;
    private $modelReview;
    private $modelUser;
    private $modelJob;
    private $modelUserCourse;
    private $userVideo;
    private $timelineVideo;
    private $modelResume;
    private $modelUserBundling;
    private $userView;


    function __construct()
    {
        include_once('getid3/getid3.php');

        $this->getID3 = new getID3;

        $this->key = getenv('TOKEN_SECRET');


        $this->path = site_url() . 'upload/course/thumbnail/';

        $this->pathVideo = site_url() . 'upload/course-video/';

        $this->pathVideoThumbnail = site_url() . 'upload/course-video/thumbnail/';



        $this->model = new Course();

        $this->modelCourseCategory = new CourseCategory();

        $this->modelCourseType = new CourseType();

        $this->modelTypeTag = new TypeTag();

        $this->modelVideo = new Video();

        $this->modelVideoCategory = new VideoCategory();

        $this->modelReview = new Review();

        $this->modelUser = new Users();

        $this->modelJob = new Jobs();

        $this->modelUserCourse = new UserCourse();

        $this->userVideo = new UserVideo();

        $this->timelineVideo = new TimelineVideo();

        $this->modelResume = new Resume();

        $this->modelUserBundling = new CourseBundling();

        $this->userView = new UserView();
    }



    public function getTopic($id = null)

    {

        $model = new Course;



        $data = $model

            ->select('video.*')

            ->join('video_category', 'video_category.course_id = course.course_id')

            ->join('video', 'video.video_category_id = video_category.video_category_id')

            ->where('course.course_id', $id)

            ->findAll();



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];

            $data[$i]['video'] = $this->pathVideo . $data[$i]['video'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function index()

    {



        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();



        $data = $model->orderBy('course_id', 'DESC')->where('service', 'course')->findAll();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author_fullname'] = $author['fullname'];

            $data[$i]['author_company'] = $author['company'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }


            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }


            if ($videoCat) {
                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = ["total" => $result];
                }
            } else {
                $data[$i]['video'] = [];

                $data[$i]['total_video_duration'] = ["total" => "0 Jam : 0 Menit : 0 Detik"];
            }



            $cek_course = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



            if ($cek_course != null) {

                $reviewcourse = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



                $rating_raw = 0;

                $rating_final = 0;

                for ($n = 0; $n < count($reviewcourse); $n++) {

                    $rating_raw += $reviewcourse[$n]['score'];

                    $rating_final = $rating_raw / count($reviewcourse);



                    $data[$i]['rating_course'] = $rating_final;
                }
            } else {

                $data[$i]['rating_course'] = 0;
            }



            // $rating_course = $controllerreview->ratingcourse($data[$i]['course_id']);

            // $data[$i]['rating_course'] = $rating_course;



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function all()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);
        }

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $modelUserReview = new Review();

        $modelUserVideo = new UserVideo();



        $data = $model->orderBy('course_id', 'DESC')->where('service', 'course')->findAll();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author_fullname'] = $author['fullname'];

            $data[$i]['author_company'] = $author['company'];

            unset($data[$i]['author_id']);





            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            if ($videoCat) {
                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();


                    $data[$i]['is_review'] = false;

                    $data[$i]['score'] = "0";

                    $data[$i]['mengerjakan_video'] = '0 / ' . count($video);

                    $data[$i]['lolos'] = false;


                    if ($header) {

                        $userReview = $modelUserReview

                            ->where('user_id', $decoded->uid)

                            ->where('course_id',  $data[$i]['course_id'])

                            ->first();



                        if ($userReview) {

                            $data[$i]['is_review'] = true;
                        }

                        $scoreCourseRaw = 0;



                        $videoCategory = $modelVidCat->where('course_id', $data[$i]['course_id'])->first();

                        $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                        $total_video_yang_dikerjakan = 0;

                        $dari = count($video);

                        foreach ($video as $key => $video_) {

                            $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();



                            if ($userVideo) {

                                $total_video_yang_dikerjakan++;

                                $scoreCourseRaw += $userVideo['score'];
                            }
                        }

                        $data[$i]['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . $dari;



                        if ($total_video_yang_dikerjakan == count($video)) {

                            $data[$i]['lolos'] = true;
                        }



                        $scoreCourse = $scoreCourseRaw / count($video);

                        $data[$i]['score'] = $scoreCourse;
                    }



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = $result;
                }
            } else {
                $data[$i]['is_review'] = false;

                $data[$i]['score'] = "0";

                $data[$i]['mengerjakan_video'] = '0 / 0';

                $data[$i]['lolos'] = false;

                $data[$i]['video'] = [];

                $data[$i]['total_video_duration'] = "0 Jam : 0 Menit : 0 Detik";
            }



            $cek_course = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



            if ($cek_course != null) {

                $reviewcourse = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



                $rating_raw = 0;

                $rating_final = 0;



                for ($n = 0; $n < count($reviewcourse); $n++) {

                    $rating_raw += $reviewcourse[$n]['score'];

                    $rating_final = $rating_raw / count($reviewcourse);



                    $data[$i]['rating_course'] = $rating_final;
                }
            } else {

                $data[$i]['rating_course'] = 0;
            }



            // $rating_course = $controllerreview->ratingcourse($data[$i]['course_id']);

            // $data[$i]['rating_course'] = $rating_course;



            $data[$i]['category'] = $category['name'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function pagination()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {
            $token = explode(' ', $header)[1];
            $decoded = JWT::decode($token, $key, ['HS256']);
        }

        $model = new Course();

        $modelCourseCategory = new CourseCategory();
        $modelCourseType = new CourseType();
        $modelCourseTag = new CourseTag();
        $modelTypeTag = new TypeTag();
        $modelUser = new Users();
        $modelVidCat = new VideoCategory();
        $modelVideo = new Video();
        $modelUserReview = new Review();
        $modelUserVideo = new UserVideo();
        $modelUserCourse = new UserCourse();


        $filter = $this->request->getJSON();

        $data_raw = $model->select('course.*')
            ->join('course_type', 'course.course_id = course_type.course_id', 'left')
            ->orderBy($filter->sort->value, $filter->sort->order)
            ->where('service', 'course')->where('course_type.type_id', 1)->where('author_id', 2)
            ->limit(12)
            ->get()
            ->getResultArray();

        $tag = [];
        $data = [];


        for ($i = 0; $i < count($data_raw); $i++) {

            $category = $modelCourseCategory
                ->where('course_id', $data_raw[$i]['course_id'])
                ->join('category', 'category.category_id = course_category.category_id')
                ->orderBy('course_category.course_category_id', 'DESC')
                ->first();

            $type = $modelCourseType
                ->where('course_id', $data_raw[$i]['course_id'])
                ->join('type', 'type.type_id = course_type.type_id')
                ->orderBy('course_type.course_type_id', 'DESC')
                ->findAll();

            $tag = $modelCourseTag
                ->select('course_tag_id, course_tag.tag_id, name')
                ->where('course_id', $data_raw[$i]['course_id'])
                ->join('tag', 'tag.tag_id = course_tag.tag_id')
                ->orderBy('course_tag.course_tag_id', 'DESC')
                ->findAll();

            if ($type) {

                $data_raw[$i]['type'] = $type[0]["name"];

                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag
                        ->where('course_type.course_id', $data_raw[$i]['course_id'])
                        ->where('type.type_id', $type[$k]['type_id'])
                        ->join('type', 'type.type_id = type_tag.type_id')
                        ->join('tag', 'tag.tag_id = type_tag.tag_id')
                        ->join('course_type', 'course_type.type_id = type.type_id')
                        ->orderBy('course_type.course_id', 'DESC')
                        ->select('tag.*')
                        ->findAll();

                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data_raw[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data_raw[$i]['type'] = null;
            }

            $tag_filter = [];

            if ($tag) {

                $tags = [];

                $tag_filter = [];

                foreach ($tag as $item) {

                    $tags[] = $item;

                    $tag_filter[] = $item['name'];
                }

                $data_raw[$i]['tag'] = $tags;
            } else {

                $data_raw[$i]['tag'] = null;
            }

            $data_raw[$i]['category'] = $category['name'];

            if (count($filter->tag) > 0 && count($tag_filter) > 0) {

                if (count(array_intersect($filter->tag, $tag_filter)) == 0) {

                    continue;
                }
            }

            if (count($filter->category) > 0) {

                if (count(array_intersect($filter->category, [$data_raw[$i]['category']])) == 0) {

                    continue;
                }
            }

            if ($filter->search) {

                if (stripos($data_raw[$i]['title'], $filter->search) === false) {

                    continue;
                }
            }

            $data[] = $data_raw[$i];
        }

        $total_item = count($data);

        $offset = ($filter->page - 1) * 12;

        $data = array_slice($data, $offset, 12);


        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data_raw[$i]['author_id'])->first();
            $data[$i]['author_fullname'] = $author['fullname'];
            $data[$i]['author_company'] = $author['company'];
            unset($data[$i]['author_id']);
            $data[$i]['thumbnail_2'] = $data[$i]['thumbnail'];
            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];
            $data[$i]['owned'] = false;

            if ($header) {

                $owned = $modelUserCourse->where('user_id', $decoded->uid)->where('course_id', $data[$i]['course_id'])->first();

                if ($owned) $data[$i]['owned'] = true;
            }

            $videoCat = $modelVidCat
                ->where('course_id', $data[$i]['course_id'])
                ->orderBy('video_category.video_category_id', 'DESC')
                ->findAll();

            if ($videoCat) {

                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo
                        ->select('video_id, video, thumbnail')
                        ->where('video_category_id', $videoCat[$x]['video_category_id'])
                        ->orderBy('order', 'ASC')
                        ->findAll();


                    $data[$i]['is_review'] = false;
                    $data[$i]['score'] = "0";
                    $data[$i]['mengerjakan_video'] = '0 / ' . count($video);
                    $data[$i]['lolos'] = false;


                    if ($header) {

                        $userReview = $modelUserReview
                            ->where('user_id', $decoded->uid)
                            ->where('course_id',  $data[$i]['course_id'])
                            ->first();

                        if ($userReview) {
                            $data[$i]['is_review'] = true;
                        }

                        $scoreCourseRaw = 0;
                        $videoCategory = $modelVidCat->where('course_id', $data[$i]['course_id'])->first();
                        $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();

                        $total_video_yang_dikerjakan = 0;
                        $dari = count($video);

                        foreach ($video as $key => $video_) {
                            $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();

                            if ($userVideo) {
                                $total_video_yang_dikerjakan++;
                                $scoreCourseRaw += $userVideo['score'];
                            }
                        }

                        $data[$i]['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . $dari;

                        if ($total_video_yang_dikerjakan == count($video)) {
                            $data[$i]['lolos'] = true;
                        }

                        $scoreCourse = $scoreCourseRaw / count($video);
                        $data[$i]['score'] = $scoreCourse;
                    }

                    for ($z = 0; $z < count($video); $z++) {
                        $this->path = 'upload/course-video/';
                        $filename = $video[$z]['video'];
                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];
                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');

                        if (!$checkIfVideoIsLink) {
                            $file = $this->getID3->analyze($this->path . $filename);

                            if (isset($file['error'][0])) {
                                $checkFileIsExist = false;
                            } else {
                                $checkFileIsExist = true;
                            }

                            if ($checkFileIsExist) {
                                if (isset($file['playtime_string'])) {
                                    $duration = ["duration" => $file['playtime_string']];
                                } else {
                                    $duration = ["duration" => '00:00:00'];
                                }

                                $data[$i]['video'][$z] = $duration;
                                $video[$z] += $duration;
                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];
                                $video[$z] += $duration;
                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];
                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');
                    $totalTime = 0;
                    $dataTime = $data[$i]['video'];

                    foreach ($dataTime as $element) {
                        $time = implode($element);
                        if (substr_count($time, ':') == 1) {
                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));
                        $timeInSec = strtotime($strTime) - $sum;
                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);
                    $totalTime = $totalTime - ($hours * 3600);
                    $minutes = intval($totalTime / 60);
                    $second = $totalTime - ($minutes * 60);
                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");
                    $data[$i]['total_video_duration'] = $result;
                }
            } else {
                $data[$i]['is_review'] = false;
                $data[$i]['score'] = "0";
                $data[$i]['mengerjakan_video'] = '0 / 0';
                $data[$i]['lolos'] = false;
                $data[$i]['video'] = [];
                $data[$i]['total_video_duration'] = "0 Jam : 0 Menit : 0 Detik";
            }

            $cek_course = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();

            if ($cek_course != null) {
                $reviewcourse = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();
                $rating_raw = 0;
                $rating_final = 0;

                for ($n = 0; $n < count($reviewcourse); $n++) {
                    $rating_raw += $reviewcourse[$n]['score'];
                    $rating_final = $rating_raw / count($reviewcourse);
                    $data[$i]['rating_course'] = $rating_final;
                }
            } else {
                $data[$i]['rating_course'] = 0;
            }
        }

        if (count($data) > 0) {
            $response = [
                'current_page' => $filter->page,
                'total_page' => ceil($total_item / 12),
                'total_item' => $total_item,
                'course' => $data
            ];

            return $this->respond($response);
        } else {
            $response = [
                'current_page' => $filter->page,
                'total_page' => 0,
                "total_item" => 0,
                'course' => []
            ];

            return $this->respond($response);
        }
    }



    public function getLatestCourseByAuthor($id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        if (isset($_GET['limit'])) {

            $key = $_GET['limit'];

            $data = $model->limit($key)

                ->select('course.*,  users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', 'course')

                ->orderBy('course.course_id', 'DESC')->find();
        } else {

            $key = null;

            $data = $model->select('course.*, users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', 'course')

                ->orderBy('course.course_id', 'DESC')->find();
        }



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author'] = $author['fullname'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category["name"];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    //
    public function filterByCategory($filter = null, $id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        if (isset($_GET['cat'])) {

            $key = $_GET['cat'];

            $data = $model->select('course.*, users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', $filter)

                ->like('category.name', $key)

                ->orderBy('course.course_id', 'DESC')->find();
        } else {

            $key = null;

            $data = $model->select('course.*, users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', $filter)

                ->orderBy('course.course_id', 'DESC')->find();
        }



        $tag = [];

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category["name"];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    //
    public function getCourseById($id, $loggedIn = false)

    {

        if ($loggedIn) {

            $header = $this->request->getServer('HTTP_AUTHORIZATION');

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $this->key, ['HS256']);

            $user = new Users;

            $userCourse = $this->modelUserCourse->where('course_id', $id)->where('user_id', $decoded->uid)->first();

            $courseBundling = $this->modelUserBundling

                ->select('course_bundling.*')

                ->where('course_bundling.course_id', $id)

                ->where('user_course.user_id', $decoded->uid)

                ->join('user_course', 'user_course.bundling_id = course_bundling.bundling_id')

                ->findAll();

            if (isset($courseBundling)) {

                foreach ($courseBundling as $value) {

                    $userBundling = $this->modelUserCourse->where('bundling_id', $value['bundling_id'])->where('user_id', $decoded->uid)->first();
                }
            }
        }



        // Jika video ditemukan

        if ($this->model->find($id)) {

            $video = [];

            $total_video_yang_dikerjakan = 0;

            $modelCourseTag = new CourseTag();



            $data = $this->model->where('course_id', $id)->first();

            $author = $this->modelUser->where('id', $data['author_id'])->first();

            unset($data['author_id']);

            $data['author_fullname'] = $author['fullname'];

            $data['author_company'] = $author['company'];



            if ($loggedIn) {

                $checkUserReview = $this->modelReview->where('user_id', $decoded->uid)->where('course_id', $id)->first();

                if ($checkUserReview) {

                    $data['is_review'] = true;
                } else {

                    $data['is_review'] = false;
                }

                if (isset($userCourse) || isset($userBundling)) {

                    $data['owned'] = true;
                } else {

                    $data['owned'] = false;
                }
            }

            $data['thumbnail'] = $this->path . $data['thumbnail'];



            $category = $this->modelCourseCategory

                ->where('course_id', $id)

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $this->modelCourseType

                ->where('course_id', $id)

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->first();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $id)

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCategory = $this->modelVideoCategory

                ->where('course_id', $id)

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();



            if (isset($videoCategory[0]) && $videoCategory[0]['title'] != '') {

                $data['video_category'] = $videoCategory;
            }

            if ($videoCategory) {

                for ($l = 0; $l < count($videoCategory); $l++) {

                    $video = $this->modelVideo

                        ->where('video_category_id', $videoCategory[$l]['video_category_id'])

                        // ->join('timeline_video', 'timeline_video.id_video = video.video_id')

                        ->orderBy('order', 'ASC')

                        ->findAll();

                    if ($videoCategory[0]['title'] != '') {

                        $data['video_category'][$l]['video'] = $video;



                        if ($loggedIn)

                            for ($p = 0; $p < count($video); $p++) {

                                $user_video = $this->userVideo

                                    ->select('score')

                                    ->where('user_id', $decoded->uid)

                                    ->where('video_id', $video[$p]['video_id'])

                                    ->findAll();



                                $timeline_video = $this->timelineVideo

                                    ->select('tanggal_tayang')

                                    ->where('id_video', $video[$p]['video_id'])

                                    ->findAll();



                                $user_view = $this->userView

                                    ->where('id_user', $decoded->uid)

                                    ->where('id_video', $video[$p]['video_id'])

                                    ->first();



                                $resume = $this->modelResume

                                    ->where('user_id', $decoded->uid)

                                    ->where('video_id', $data['video_category'][$l]['video'][$p]['video_id'])

                                    ->first();



                                if ($resume) {

                                    $data['video_category'][$l]['video'][$p]['resume'] = $resume;
                                } else {

                                    $data['video_category'][$l]['video'][$p]['resume'] = null;
                                }

                                date_default_timezone_set('Asia/Jakarta');

                                $today = date("Y-m-d H:i:s");



                                // if ($user_view) {

                                //     $data['video_category'][$l]['video'][$p]['is_viewed'] = TRUE;

                                // } else {

                                //     $data['video_category'][$l]['video'][$p]['is_viewed'] = FALSE;

                                // }



                                if ((isset($userCourse["is_timeline"]) && $userCourse["is_timeline"] == 1) || (isset($userBundling["is_timeline"]) && $userBundling["is_timeline"] == 1)) {

                                    if ($timeline_video) {

                                        $data['video_category'][$l]['video'][$p]['tanggal_tayang'] = $timeline_video[0]['tanggal_tayang'];
                                    } else {

                                        $data['video_category'][$l]['video'][$p]['tanggal_tayang'] = null;
                                    }



                                    if ($user_view) {

                                        $data['video_category'][$l]['video'][$p]['is_viewed'] = "true";
                                    } else {

                                        $data['video_category'][$l]['video'][$p]['is_viewed'] = "false";
                                    }
                                } else {

                                    $data['video_category'][$l]['video'][$p]['tanggal_tayang'] = null;

                                    $data['video_category'][$l]['video'][$p]['is_viewed'] = "true";
                                }



                                // if ($timeline_video) {

                                //     $data['video_category'][$l]['video'][$p]['tanggal_tayang'] = $timeline_video[0]['tanggal_tayang'];

                                // } else {

                                //     $data['video_category'][$l]['video'][$p]['tanggal_tayang'] = null;

                                // }



                                if ($user_video) {

                                    $total_video_yang_dikerjakan++;

                                    $data['video_category'][$l]['video'][$p]['score'] = $user_video[0]['score'];
                                } else {

                                    $data['video_category'][$l]['video'][$p]['score'] = "0";
                                }
                            }
                    } else {

                        $data['video'] = $video;



                        if ($loggedIn)

                            for ($p = 0; $p < count($video); $p++) {

                                $user_video = $this->userVideo

                                    ->select('score')

                                    ->where('user_id', $decoded->uid)

                                    ->where('video_id', $video[$p]['video_id'])

                                    ->findAll();



                                $timeline_video = $this->timelineVideo

                                    ->select('tanggal_tayang')

                                    ->where('id_video', $video[$p]['video_id'])

                                    ->findAll();



                                $resume = $this->modelResume

                                    ->where('user_id', $decoded->uid)

                                    ->where('video_id', $data['video'][$p]['video_id'])

                                    ->first();



                                $user_view = $this->userView

                                    ->where('id_user', $decoded->uid)

                                    ->where('id_video', $video[$p]['video_id'])

                                    ->first();



                                if ($resume) {

                                    $data['video'][$p]['resume'] = $resume;
                                } else {

                                    $data['video'][$p]['resume'] = null;
                                }



                                // if ($user_view) {

                                //     $data['video'][$p]['is_viewed'] = TRUE;

                                // } else {

                                //     $data['video'][$p]['is_viewed'] = FALSE;

                                // }



                                if ((isset($userCourse["is_timeline"]) && $userCourse["is_timeline"] == 1) || (isset($userBundling["is_timeline"]) && $userBundling["is_timeline"] == 1)) {

                                    if ($timeline_video) {

                                        $data['video'][$p]['tanggal_tayang'] = $timeline_video[0]['tanggal_tayang'];
                                    } else {

                                        $data['video'][$p]['tanggal_tayang'] = null;
                                    }



                                    if ($user_view) {

                                        $data['video'][$p]['is_viewed'] = "true";
                                    } else {

                                        $data['video'][$p]['is_viewed'] = "false";
                                    }
                                } else {

                                    $data['video'][$p]['tanggal_tayang'] = null;

                                    $data['video'][$p]['is_viewed'] = "true";
                                }



                                // if ($timeline_video) {

                                //     $data['video'][$p]['tanggal_tayang'] = $timeline_video[0]['tanggal_tayang'];

                                // } else {

                                //     $data['video'][$p]['tanggal_tayang'] = null;

                                // }



                                if ($user_video) {

                                    $total_video_yang_dikerjakan++;

                                    $data['video'][$p]['score'] = $user_video[0]['score'];
                                } else {

                                    $data['video'][$p]['score'] = "0";
                                }
                            }
                    }
                }
            } else {
                $data['video'] = [];

                $data['total_video_duration'] = "0 Jam : 0 Menit : 0 Detik";
            }



            if (isset($data['video'])) {

                $duratsi = [];

                for ($i = 0; $i < count($data['video']); $i++) {

                    $this->path = 'upload/course-video/';



                    $filename = $data['video'][$i]['video'];

                    $data['video'][$i]['thumbnail'] = $this->pathVideoThumbnail . $data['video'][$i]['thumbnail'];



                    $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                    if (!$checkIfVideoIsLink) {

                        $file = $this->getID3->analyze($this->path . $filename);



                        if (isset($file['error'][0])) {

                            $checkFileIsExist = false;
                        } else {

                            $checkFileIsExist = true;
                        }



                        if ($checkFileIsExist) {

                            if (isset($file['playtime_string'])) {

                                $duration = ["duration" => $file['playtime_string']];
                            } else {

                                $duration = ["duration" => '00:00:00'];
                            }


                            $durasi[$i] = $duration;

                            $data['video'][$i] += $duration;

                            $data['video'][$i]['video'] = $this->pathVideo . $data['video'][$i]['video'];
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $data['video'][$i] += $duration;

                            $durasi[$i] = $duration;
                        }
                    } else {

                        $duration = ["duration" => '00:00:00'];

                        $data['video'][$i] += $duration;
                    }


                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $durasi;



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");


                    $data['total_video_duration'] = $result;



                    if ($loggedIn) {

                        if (isset($userCourse) || isset($userBundling)) {
                        } else {
                            if ($i != 0) {

                                $data['video'][$i]['video'] = null;
                            }
                        }
                    } else {

                        if ($i != 0) {

                            $data['video'][$i]['video'] = null;
                        }
                    }
                }
            } elseif (isset($data['video_category'])) {

                for ($l = 0; $l < count($videoCategory); $l++) {

                    if ($loggedIn) {

                        for ($i = 0; $i < count($data['video_category'][$l]['video']); $i++) {

                            $this->path = 'upload/course-video/';

                            $filename = $data['video_category'][$l]['video'][$i]['video'];



                            $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                            if (!$checkIfVideoIsLink) {

                                $file = $this->getID3->analyze($this->path . $filename);

                                $checkFileIsExist = stristr($file['error'][0], '!file_exists') ? false : true;



                                if ($checkFileIsExist) {

                                    $duration = ["duration" => $file['playtime_string']];

                                    $data['video_category'][$l]['video'][$i] += $duration;

                                    $data['video_category'][$l]['video'][$i]['video'] = $this->pathVideo . $data['video_category'][$l]['video'][$i]['video'];
                                } else {

                                    $duration = ["duration" => null];

                                    $data['video_category'][$l]['video'][$i] += $duration;
                                }
                            } else {

                                $duration = ["duration" => null];

                                $data['video_category'][$l]['video'][$i] += $duration;
                            }



                            if (isset($data['owned']) && $i != 0) {

                                $data['video_category'][$l]['video'][$i]['video'] = null;
                            }
                        }
                    } else {

                        for ($i = 0; $i < count($data['video_category'][$l]['video']); $i++) {

                            $this->path = 'upload/course-video/';

                            $filename = $data['video_category'][$l]['video'][$i]['video'];



                            $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                            if (!$checkIfVideoIsLink) {

                                $file = $this->getID3->analyze($this->path . $filename);

                                $checkFileIsExist = stristr($file['error'][0], '!file_exists') ? false : true;



                                if ($checkFileIsExist) {

                                    $duration = ["duration" => $file['playtime_string']];

                                    $data['video_category'][$l]['video'][$i] += $duration;

                                    $data['video_category'][$l]['video'][$i]['video'] = $this->pathVideo . $data['video_category'][$l]['video'][$i]['video'];
                                } else {

                                    $duration = ["duration" => null];

                                    $data['video_category'][$l]['video'][$i] += $duration;
                                }
                            } else {

                                $duration = ["duration" => null];

                                $data['video_category'][$l]['video'][$i] += $duration;
                            }



                            if ($i != 0) {

                                $data['video_category'][$l]['video'][$i]['video'] = null;
                            }
                        }
                    }
                }
            }



            if (isset($type)) {

                $typeTag = $this->modelTypeTag

                    ->where('course_type.course_id', $id)

                    ->where('type.type_id', $type['type_id'])

                    ->join('type', 'type.type_id = type_tag.type_id')

                    ->join('tag', 'tag.tag_id = type_tag.tag_id')

                    ->join('course_type', 'course_type.type_id = type.type_id')

                    ->orderBy('course_type.course_id', 'DESC')

                    ->select('tag.*')

                    ->findAll();



                $data['type'] = $type["name"];



                // for ($i = 0; $i < count($typeTag); $i++) {

                //     $data['tag'][$i] = $typeTag[$i];

                // }

            } else {

                $data['type'] = null;
            }

            if ($tag) {

                $data['tag'] = $tag;
            } else {

                $data['tag'] = null;
            }

            $price = (empty($data['new_price'])) ? $data['old_price'] : $data['new_price'];



            $data['tax'] = $price * 11 / 100;

            $data['category'] = $category ? $category['name'] : "Basic";



            $review = $this->modelReview->where('course_id', $id)

                ->select('user_review_id, user_id, feedback, score')

                ->orderBy('user_review_id', 'DESC')

                ->groupBy('user_id')

                ->findAll();



            for ($i = 0; $i < count($review); $i++) {

                $user = $this->modelUser

                    ->where('id', $review[$i]['user_id'])

                    ->select('fullname, email, job_id, profile_picture')

                    ->first();

                $job = $this->modelJob

                    ->where('job_id', $user['job_id'])

                    ->select('job_name')

                    ->first();

                $review[$i] += $user;



                if ($user['job_id'] != null) {

                    $review[$i] += $job;
                } else {

                    $review[$i]['job_name'] = "Professional Independent";
                }
            }

            if (isset($data['video'])) {

                $data['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . count($data['video']);
            } else {
                $data['mengerjakan_video'] = "0 / 0";
            }

            $data['review'] = $review;



            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function show($id = null)

    {

        $header = $this->request->getServer('HTTP_AUTHORIZATION');



        // Jika belum login

        if (!$header) {

            return $this->getCourseById($id);
        } else {


            return $this->getCourseById($id, true);
            // try {

            //     return $this->getCourseById($id, true);
            // } catch (\Throwable $th) {

            //     return $this->fail($th->getMessage());
            // }
        }
    }


    //
    public function detailTraining($filter = null, $id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, users.fullname as author_name')

            ->orderBy('course_id', 'DESC')

            ->where('service', $filter)

            ->where('course_id', $id)

            ->join('users', 'users.id = course.author_id')

            ->findAll();



        $tag = [];







        for ($i = 0; $i < count($data); $i++) {

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            $data[$i]['category'] = $category;



            $price = (empty($data[$i]['new_price'])) ? $data[$i]['old_price'] : $data[$i]['new_price'];



            $data[$i]['tax'] = $price * 11 / 100;
        }



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    //
    public function trainingByAuthor($filter = null, $author_id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, users.fullname as author_name')

            ->orderBy('course_id', 'DESC')

            ->where('service', $filter)

            ->where('author_id', $author_id)

            ->join('users', 'users.id = course.author_id')

            ->findAll();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            $data[$i]['category'] = $category;
        }



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function training()

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, users.fullname as author_name')

            ->orderBy('course_id', 'DESC')

            ->where('service', 'training')

            ->join('users', 'users.id = course.author_id')

            ->findAll();



        $tag = [];







        for ($i = 0; $i < count($data); $i++) {

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            $data[$i]['category'] = $category;



            $price = (empty($data[$i]['new_price'])) ? $data[$i]['old_price'] : $data[$i]['new_price'];



            $data[$i]['tax'] = $price * 11 / 100;
        }



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function latestTraining($total = 3)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();

        $modelUserCourse = new UserCourse();

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);
        }



        $data = $model->select('course.*, users.fullname as author_name')

            ->orderBy('course_id', 'DESC')

            ->where('service', 'training')

            ->join('users', 'users.id = course.author_id')

            ->limit($total)

            ->find();



        $tag = [];







        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['owned'] = false;

            if ($header) {

                $owned = $modelUserCourse->where('user_id', $decoded->uid)->where('course_id', $data[$i]['course_id'])->first();

                if ($owned) $data[$i]['owned'] = true;
            }

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            $data[$i]['category'] = $category;



            $price = (empty($data[$i]['new_price'])) ? $data[$i]['old_price'] : $data[$i]['new_price'];



            $data[$i]['tax'] = $price * 11 / 100;
        }



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail_2'] = $data[$i]['thumbnail'];

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function trainingDetail($id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, users.fullname as author_name')

            ->orderBy('course_id', 'DESC')

            ->where('service', 'training')

            ->where('course_id', $id)

            ->join('users', 'users.id = course.author_id')

            ->first();



        $tag = [];


        $category = $modelCourseCategory

            ->where('course_id', $data['course_id'])

            ->join('category', 'category.category_id = course_category.category_id')

            ->orderBy('course_category.course_category_id', 'DESC')

            ->findAll();

        $type = $modelCourseType

            ->where('course_id', $data['course_id'])

            ->join('type', 'type.type_id = course_type.type_id')

            ->orderBy('course_type.course_type_id', 'DESC')

            ->findAll();

        $tag = $modelCourseTag

            ->select('course_tag_id, course_tag.tag_id, name')

            ->where('course_id', $data['course_id'])

            ->join('tag', 'tag.tag_id = course_tag.tag_id')

            ->orderBy('course_tag.course_tag_id', 'DESC')

            ->findAll();

        if ($type) {

            $data['type'] = $type;



            for ($k = 0; $k < count($type); $k++) {

                $typeTag = $modelTypeTag

                    ->where('course_type.course_id', $data['course_id'])

                    ->where('type.type_id', $type[$k]['type_id'])

                    ->join('type', 'type.type_id = type_tag.type_id')

                    ->join('tag', 'tag.tag_id = type_tag.tag_id')

                    ->join('course_type', 'course_type.type_id = type.type_id')

                    ->orderBy('course_type.course_id', 'DESC')

                    ->select('tag.*')

                    ->findAll();



                for ($o = 0; $o < count($typeTag); $o++) {

                    $data['tag'][$o] = $typeTag[$o];
                }
            }
        } else {

            $data['type'] = null;
        }

        if ($tag) {

            $data['tag'] = $tag;
        } else {

            $data['tag'] = null;
        }

        $data['category'] = $category;



        $price = (empty($data['new_price'])) ? $data['old_price'] : $data['new_price'];



        $data['tax'] = $price * 11 / 100;


        $data['thumbnail'] = $this->path . $data['thumbnail'];



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function filter($filter = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->orderBy('course_id', 'DESC')->where('service', $filter)->findAll();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function author($id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->orderBy('course_id', 'DESC')->where('author_id', $id)->where('service', 'course')->find();



        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];



            $user = $modelUser->select('fullname')->where('id', $id)->first();

            $data[$i]['author_name'] = $user['fullname'];



            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function create()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $modelCourse = new Course();

            $modelCourseCategory = new CourseCategory();

            $modelCourseType = new CourseType();

            $modelCourseTag = new CourseTag();



            $rules = [

                'title' => 'required|min_length[8]',

                'service' => 'required',

                'description' => 'required|min_length[8]',

                'key_takeaways' => 'max_length[255]',

                'suitable_for' => 'max_length[255]',

                'old_price' => 'required|numeric',

                'new_price' => 'permit_empty|numeric',

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]',

                'category_id' => 'required|numeric',

                'type_id' => 'required|numeric',

            ];



            $messages = [

                "title" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "service" => [

                    "required" => "{field} tidak boleh kosong",

                ],

                "description" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "key_takeaways" => [

                    'max_length' => '{field} maksimal 255 karakter',

                ],

                "suitable_for" => [

                    'max_length' => '{field} maksimal 255 karakter',

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "new_price" => [

                    "numeric" => "{field} harus berisi nomor",

                ],

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

                "category_id" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "type_id" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

            ];



            if ($this->validate($rules, $messages)) {

                $dataThumbnail = $this->request->getFile('thumbnail');

                $fileName = $dataThumbnail->getRandomName();

                $dataCourse = [

                    'title' => $this->request->getVar('title'),

                    'service' => $this->request->getVar('service'),

                    'description' => $this->request->getVar('description'),

                    'key_takeaways' => $this->request->getVar('key_takeaways'),

                    'suitable_for' => $this->request->getVar('suitable_for'),

                    'old_price' => $this->request->getVar('old_price'),

                    'new_price' => $this->request->getVar('new_price'),

                    'author_id' => $this->request->getVar('author_id'),

                    'thumbnail' => $fileName,

                    'author_id' => $decoded->uid,

                ];

                $dataThumbnail->move('upload/course/thumbnail/', $fileName);

                $modelCourse->insert($dataCourse);



                $dataCourseCategory = [

                    'course_id' => $modelCourse->insertID(),

                    'category_id' => $this->request->getVar('category_id')

                ];

                $dataCourseType = [

                    'course_id' => $modelCourse->insertID(),

                    'type_id' => $this->request->getVar('type_id')

                ];



                if ($this->request->getVar('tag') !== null) {

                    $dataCourseTag = [];

                    $tag = json_decode($this->request->getVar('tag'));

                    for ($i = 0; $i < count($tag); $i++) {

                        $dataCourseTag[$i] = [

                            'course_id' => $modelCourse->insertID(),

                            'tag_id' => $tag[$i]

                        ];
                    }

                    $modelCourseTag->insertBatch($dataCourseTag);
                }



                $modelCourseCategory->insert($dataCourseCategory);

                $modelCourseType->insert($dataCourseType);



                $response = [

                    'status'   => 201,

                    'success'    => 201,

                    'messages' => [

                        'success' => 'Course berhasil dibuat'

                    ]

                ];
            } else {

                $response = [

                    'status'   => 400,

                    'error'    => 400,

                    'messages' => $this->validator->getErrors(),

                ];
            }





            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function update($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $modelCourse = new Course();

            $modelCourseCategory = new CourseCategory();

            $modelCourseType = new CourseType();

            $modelCourseTag = new CourseTag();



            $rules_a = [

                'title' => 'required',

                'description' => 'required|min_length[8]',

                'key_takeaways' => 'max_length[255]',

                'suitable_for' => 'max_length[255]',

                'old_price' => 'required|numeric',

                'new_price' => 'required|numeric',

                'category_id' => 'numeric',

                'type_id' => 'numeric',

            ];



            $rules_b = [

                'thumbnail' => 'uploaded[thumbnail]'

                    . '|is_image[thumbnail]'

                    . '|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]'

                    . '|max_size[thumbnail,4000]'

            ];



            $messages_a = [

                "title" => [

                    "required" => "{field}  tidak boleh kosong",

                ],

                "description" => [

                    "required" => "{field}  tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

                "key_takeaways" => [

                    'max_length' => '{field} maksimal 255 karakter',

                ],

                "suitable_for" => [

                    'max_length' => '{field} maksimal 255 karakter',

                ],

                "old_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "new_price" => [

                    "required" => "{field} tidak boleh kosong",

                    "numeric" => "{field} harus berisi nomor",

                ],

                "category_id" => [

                    "numeric" => "{field} harus berisi nomor",

                ],

                "type_id" => [

                    "numeric" => "{field} harus berisi nomor",

                ],

            ];



            $messages_b = [

                "thumbnail" => [

                    'uploaded' => '{field} tidak boleh kosong',

                    'mime_in' => 'File Extention Harus Berupa png, jpg, atau jpeg',

                    'max_size' => 'Ukuran File Maksimal 4 MB'

                ],

            ];



            $findCourse = $this->model->where('course_id', $id)->first();

            if ($findCourse) {

                if ($this->validate($rules_a, $messages_a)) {

                    if ($this->validate($rules_b, $messages_b)) {

                        $oldThumbnail = $findCourse['thumbnail'];

                        $dataThumbnail = $this->request->getFile('thumbnail');



                        if ($dataThumbnail->isValid() && !$dataThumbnail->hasMoved()) {

                            if (file_exists("upload/course/thumbnail/" . $oldThumbnail)) {

                                unlink("upload/course/thumbnail/" . $oldThumbnail);
                            }

                            $fileName = $dataThumbnail->getRandomName();

                            $dataThumbnail->move('upload/course/thumbnail/', $fileName);
                        } else {

                            $fileName = $oldThumbnail['thumbnail'];
                        }



                        $data = [

                            'title' => $this->request->getVar('title'),

                            'service' => $this->request->getVar('service'),

                            'description' => $this->request->getVar('description'),

                            'key_takeaways' => $this->request->getVar('key_takeaways'),

                            'suitable_for' => $this->request->getVar('suitable_for'),

                            'old_price' => $this->request->getVar('old_price'),

                            'new_price' => $this->request->getVar('new_price'),

                            'author_id' => $this->request->getVar('author_id'),

                            'thumbnail' => $fileName,

                            'author_id' => $decoded->uid,

                        ];



                        $modelCourse->update($id, $data);



                        if ($this->request->getVar('category_id') !== null) {

                            $dataCourseCategory = [

                                'category_id' => $this->request->getVar('category_id')

                            ];

                            $modelCourseCategory->where('course_id', $id)->set($dataCourseCategory)->update();
                        };



                        if ($this->request->getVar('type_id') !== null) {

                            $dataCourseType = [

                                'type_id' => $this->request->getVar('type_id')

                            ];

                            $modelCourseType->where('course_id', $id)->set($dataCourseType)->update();
                        };



                        if ($this->request->getVar('tag') !== null) {

                            $dataCourseTag = [];

                            $tag = json_decode($this->request->getVar('tag'));

                            for ($i = 0; $i < count($tag); $i++) {

                                $dataCourseTag[$i] = [

                                    'course_id' => $id,

                                    'tag_id' => $tag[$i]

                                ];
                            }

                            $modelCourseTag->where('course_id', $id)->delete();

                            $modelCourseTag->insertBatch($dataCourseTag);
                        }



                        $response = [

                            'status'   => 201,

                            'success'    => 201,

                            'messages' => [

                                'success' => 'Data Course berhasil diupdate'

                            ]

                        ];
                    } else {

                        // $response = [

                        //     'status'   => 400,

                        //     'error'    => 400,

                        //     'messages' => $this->validator->getErrors(),

                        // ];

                        $dataCourse = [

                            'title' => $this->request->getVar('title'),

                            'service' => $this->request->getVar('service'),

                            'description' => $this->request->getVar('description'),

                            'key_takeaways' => $this->request->getVar('key_takeaways'),

                            'suitable_for' => $this->request->getVar('suitable_for'),

                            'old_price' => $this->request->getVar('old_price'),

                            'new_price' => $this->request->getVar('new_price'),

                            'author_id' => $decoded->uid,

                        ];



                        $modelCourse->update($id, $dataCourse);



                        if ($this->request->getVar('category_id') !== null) {

                            $dataCourseCategory = [

                                'category_id' => $this->request->getVar('category_id')

                            ];

                            $modelCourseCategory->where('course_id', $id)->set($dataCourseCategory)->update();
                        };



                        if ($this->request->getVar('type_id') !== null) {

                            $dataCourseType = [

                                'type_id' => $this->request->getVar('type_id')

                            ];

                            $modelCourseType->where('course_id', $id)->set($dataCourseType)->update();
                        };



                        if ($this->request->getVar('tag') !== null) {

                            $dataCourseTag = [];

                            $tag = json_decode($this->request->getVar('tag'));

                            for ($i = 0; $i < count($tag); $i++) {

                                $dataCourseTag[$i] = [

                                    'course_id' => $id,

                                    'tag_id' => $tag[$i]

                                ];
                            }

                            $modelCourseTag->where('course_id', $id)->delete();

                            $modelCourseTag->insertBatch($dataCourseTag);
                        }



                        $response = [

                            'status'   => 201,

                            'success'    => 201,

                            'messages' => [

                                'success' => 'Data Course berhasil diupdate'

                            ]

                        ];
                    }

                    // Kalau disini ada update, maka update 2 kali

                } else {

                    $response = [

                        'status'   => 400,

                        'error'    => 400,

                        'messages' => $this->validator->getErrors(),

                    ];
                }
            }



            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data Course tidak ditemukan');
    }



    public function delete($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member' || $data['role'] == 'mentor') {

                return $this->fail('Tidak dapat di akses selain admin, partner & author', 400);
            }



            $modelCourse = new Course();

            $modelCourseCategory = new CourseCategory();

            $modelCart = new Cart;

            $modelUserCourse = new UserCourse;

            $modelCourseTag = new CourseTag;

            $modelUserReview = new Review;

            $modelCourseType = new CourseType;

            $modelCourseBundling = new CourseBundling;

            $modelVideoCategory = new VideoCategory;





            if ($modelCourse->find($id)) {

                $modelCourseCategory->where('course_id', $id)->delete();

                $modelUserCourse->where('course_id', $id)->delete();

                $modelCourseTag->where('course_id', $id)->delete();

                $modelUserReview->where('course_id', $id)->delete();

                $modelCourseType->where('course_id', $id)->delete();

                $modelCourseBundling->where('course_id', $id)->delete();

                $modelVideoCategory->where('course_id', $id)->delete();

                $modelCart->where('course_id', $id)->delete();

                $modelCourse->delete($id);



                $response = [

                    'status'   => 200,

                    'success'    => 200,

                    'messages' => [

                        'success' => 'Course berhasil di hapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->failNotFound('Data tidak di temukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }


    public function getAll()

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();



        $data = $model->orderBy('course_id', 'DESC')->where('service', 'course')->find();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author_fullname'] = $author['fullname'];

            $data[$i]['author_company'] = $author['company'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }



                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $waktu = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = ["total" => $result];
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function latest($total = 20)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();



        $data = $model->limit($total)->orderBy('course_id', 'DESC')->where('service', 'course')->find();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author'] = $author['fullname'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail_2'] = $data[$i]['thumbnail'];

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }


            if ($videoCat) {
                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = ['total' => $result];
                }
            } else {

                $data[$i]['video'] = [];

                $data[$i]['total_video_duration'] = ['total' => "0 Jam : 0 Menit : 0 Detik"];
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function latest_2($total = 3)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $modelUserCourse = new UserCourse();

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);
        }

        $data = $model->limit($total)->orderBy('course_id', 'DESC')->where('service', 'course')->find();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author'] = $author['fullname'];

            unset($data[$i]['author_id']);


            $data[$i]['owned'] = false;

            if ($header) {

                $owned = $modelUserCourse->where('user_id', $decoded->uid)->where('course_id', $data[$i]['course_id'])->first();

                if ($owned) $data[$i]['owned'] = true;
            }



            $data[$i]['thumbnail_2'] = $data[$i]['thumbnail'];

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }


            if ($videoCat) {
                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = ['total' => $result];
                }
            } else {

                $data[$i]['video'] = [];

                $data[$i]['total_video_duration'] = ['total' => "0 Jam : 0 Menit : 0 Detik"];
            }



            $data[$i]['category'] = $category ? $category['name'] : "Basic";
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function find($key = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->orderBy('course_id', 'DESC')->like('title', $key)->find();





        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author'] = $author['fullname'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Data tidak ditemukan');
        }
    }



    public function filterByTitle($filter = null, $id = null)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        if (isset($_GET['title'])) {

            $key = $_GET['title'];

            $data = $model->select('course.*, users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', $filter)

                ->like('course.title', $key)

                ->orderBy('course.course_id', 'DESC')->find();
        } else {

            $key = null;

            $data = $model->select('course.*, users.fullname as author_name, category.name as category')

                ->join('users', 'users.id = course.author_id')

                ->join('course_category', 'course_category.course_category_id = course.course_id')

                ->join('category', 'category.category_id = course_category.category_id')

                ->where('users.id', $id)

                ->where('service', $filter)

                ->orderBy('course.course_id', 'DESC')->find();
        }



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author'] = $author['fullname'];

            unset($data[$i]['author_id']);



            $data[$i]['thumbnail'] = $this->path . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->findAll();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type;



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category;
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function userProgress($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);



            $user = new Users;

            $courseModel = new Course;

            $userCourseModel = new UserCourse;

            $userVideoModel = new UserVideo;

            $videoCategoryModel = new VideoCategory;

            $videoModel = new Video;

            $orderCourseModel = new OrderCourse;

            $orderModel = new Order;





            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            $author = $courseModel->where('author_id', $decoded->uid)->first();



            $course = $courseModel->where('course_id', $id)->first();



            if ($data['role'] != 'admin' && !$author) {

                return $this->fail('Tidak dapat di akses selain pemilik course atau admin', 400);
            }



            if ($course['author_id'] != $decoded->uid) {

                return $this->fail('Tidak dapat di akses selain pemilik course', 400);
            }



            // cek semua user yang mempunyai course berkaitan

            $userCourseData = $userCourseModel->where('course_id', $id)->findAll();



            foreach ($userCourseData as $data) {

                // mengambil data user yang berkaitan dengan course berdasarkan id user

                $userData = $user->where('id', $data['user_id'])->first();



                // mengambil data persentase progress user dari course yang berkaitan

                $videoCategory = $videoCategoryModel->where('course_id', $id)->first();

                $video = $videoModel->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                $completed = [];



                for ($j = 0; $j < count($video); $j++) {

                    $userVideo = $userVideoModel->where('user_id', $data['user_id'])->where('video_id', $video[$j]['video_id'])->first();

                    if (isset($userVideo)) {

                        $completed[$j] = $userVideo;
                    } else {

                        continue;
                    }
                }



                $percentage = (count($completed) / count($video)) * 100;



                $orderCourse = $orderCourseModel->select('order_id')->where('course_id', $id)->findAll();

                foreach ($orderCourse as $dataOrder) {

                    $order = $orderModel->select('transaction_time')->where('user_id', $data['user_id'])->where('order_id', $dataOrder['order_id'])->first();
                }



                $response[] = [

                    'username' => $userData['fullname'],

                    'progress' => $percentage,

                    'transaction_at' => isset($order) ? $order : [],

                ];
            }



            if (!isset($response)) $response = [];



            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function filterByCat($filter)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, category.name as category')

            // ->join('users', 'users.id = course.author_id')

            ->join('course_category', 'course_category.course_id = course.course_id')

            ->join('category', 'category.category_id = course_category.category_id')

            // ->where('users.id', $id)

            ->where('service', 'course')

            ->where('course_category.category_id', $filter)

            ->orderBy('course.course_id', 'DESC')

            ->find();



        $tag = [];

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category["name"];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function filterByTag($filter)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, tag.name as tag')

            // ->join('users', 'users.id = course.author_id')

            ->join('course_tag', 'course_tag.course_id = course.course_id')

            ->join('tag', 'tag.tag_id = course_tag.tag_id')

            // ->where('users.id', $id)

            ->where('service', 'course')

            ->where('course_tag.tag_id', $filter)

            ->orderBy('course.course_id', 'DESC')

            ->find();



        $tag = [];

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category["name"];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }



    public function filterByType($filter)

    {

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelUser = new Users();



        $data = $model->select('course.*, type.name as type')

            // ->join('users', 'users.id = course.author_id')

            ->join('course_type', 'course_type.course_id = course.course_id')

            ->join('type', 'type.type_id = course_type.type_id')

            // ->where('users.id', $id)

            ->where('service', 'course')

            ->where('course_type.type_id', $filter)

            ->orderBy('course.course_id', 'DESC')

            ->find();



        $tag = [];

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            if ($type) {

                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    // for ($o = 0; $o < count($typeTag); $o++) {

                    //     $data[$i]['tag'][$o] = $typeTag[$o];

                    // }

                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }



            $data[$i]['category'] = $category["name"];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function search($keyword = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($header) {

            $token = explode(' ', $header)[1];

            $decoded = JWT::decode($token, $key, ['HS256']);
        }

        $model = new Course();

        $modelCourseCategory = new CourseCategory();

        $modelCourseType = new CourseType();

        $modelCourseTag = new CourseTag();

        $modelTypeTag = new TypeTag();

        $modelTag = new Tag();

        $modelUser = new Users();

        $modelVidCat = new VideoCategory();

        $modelVideo = new Video();

        $modelUserReview = new Review();

        $modelUserVideo = new UserVideo();



        $data = $model->orderBy('course_id', 'DESC')->where('service', 'course')->like('title', $keyword)->findAll();



        $tag = [];



        for ($i = 0; $i < count($data); $i++) {

            $author = $modelUser->where('id', $data[$i]['author_id'])->first();

            $data[$i]['author_fullname'] = $author['fullname'];

            $data[$i]['author_company'] = $author['company'];

            unset($data[$i]['author_id']);





            $data[$i]['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $data[$i]['thumbnail'];

            $category = $modelCourseCategory

                ->where('course_id', $data[$i]['course_id'])

                ->join('category', 'category.category_id = course_category.category_id')

                ->orderBy('course_category.course_category_id', 'DESC')

                ->first();

            $type = $modelCourseType

                ->where('course_id', $data[$i]['course_id'])

                ->join('type', 'type.type_id = course_type.type_id')

                ->orderBy('course_type.course_type_id', 'DESC')

                ->findAll();

            $tag = $modelCourseTag

                ->select('course_tag_id, course_tag.tag_id, name')

                ->where('course_id', $data[$i]['course_id'])

                ->join('tag', 'tag.tag_id = course_tag.tag_id')

                ->orderBy('course_tag.course_tag_id', 'DESC')

                ->findAll();

            $videoCat = $modelVidCat

                ->where('course_id', $data[$i]['course_id'])

                ->orderBy('video_category.video_category_id', 'DESC')

                ->findAll();

            if ($type) {



                $data[$i]['type'] = $type[0]["name"];



                for ($k = 0; $k < count($type); $k++) {

                    $typeTag = $modelTypeTag

                        ->where('course_type.course_id', $data[$i]['course_id'])

                        // ->where('course_tag.course_id', $data[$i]['course_id'])

                        ->where('type.type_id', $type[$k]['type_id'])

                        ->join('type', 'type.type_id = type_tag.type_id')

                        ->join('tag', 'tag.tag_id = type_tag.tag_id')

                        ->join('course_type', 'course_type.type_id = type.type_id')

                        ->orderBy('course_type.course_id', 'DESC')

                        ->select('tag.*')

                        ->findAll();



                    for ($o = 0; $o < count($typeTag); $o++) {

                        $data[$i]['tag'][$o] = $typeTag[$o];
                    }
                }
            } else {

                $data[$i]['type'] = null;
            }

            if ($tag) {

                $data[$i]['tag'] = $tag;
            } else {

                $data[$i]['tag'] = null;
            }

            if ($videoCat) {
                for ($x = 0; $x < count($videoCat); $x++) {

                    $video = $modelVideo

                        ->select('video_id, video, thumbnail')

                        ->where('video_category_id', $videoCat[$x]['video_category_id'])

                        ->orderBy('order', 'ASC')

                        ->findAll();


                    $data[$i]['is_review'] = false;

                    $data[$i]['score'] = "0";

                    $data[$i]['mengerjakan_video'] = '0 / ' . count($video);

                    $data[$i]['lolos'] = false;


                    if ($header) {

                        $userReview = $modelUserReview

                            ->where('user_id', $decoded->uid)

                            ->where('course_id',  $data[$i]['course_id'])

                            ->first();



                        if ($userReview) {

                            $data[$i]['is_review'] = true;
                        }

                        $scoreCourseRaw = 0;



                        $videoCategory = $modelVidCat->where('course_id', $data[$i]['course_id'])->first();

                        $video = $modelVideo->where('video_category_id', $videoCategory['video_category_id'])->findAll();



                        $total_video_yang_dikerjakan = 0;

                        $dari = count($video);

                        foreach ($video as $key => $video_) {

                            $userVideo = $modelUserVideo->where('user_id', $decoded->uid)->where('video_id', $video_['video_id'])->first();



                            if ($userVideo) {

                                $total_video_yang_dikerjakan++;

                                $scoreCourseRaw += $userVideo['score'];
                            }
                        }

                        $data[$i]['mengerjakan_video'] = $total_video_yang_dikerjakan . ' / ' . $dari;



                        if ($total_video_yang_dikerjakan == count($video)) {

                            $data[$i]['lolos'] = true;
                        }



                        $scoreCourse = $scoreCourseRaw / count($video);

                        $data[$i]['score'] = $scoreCourse;
                    }



                    for ($z = 0; $z < count($video); $z++) {

                        $this->path = 'upload/course-video/';



                        $filename = $video[$z]['video'];

                        $video[$z]['thumbnail'] = $this->pathVideoThumbnail . $video[$z]['thumbnail'];



                        $checkIfVideoIsLink = stristr($filename, 'http://') ?: stristr($filename, 'https://');



                        if (!$checkIfVideoIsLink) {

                            $file = $this->getID3->analyze($this->path . $filename);



                            if (isset($file['error'][0])) {

                                $checkFileIsExist = false;
                            } else {

                                $checkFileIsExist = true;
                            }



                            if ($checkFileIsExist) {

                                if (isset($file['playtime_string'])) {

                                    $duration = ["duration" => $file['playtime_string']];
                                } else {

                                    $duration = ["duration" => '00:00:00'];
                                }



                                $data[$i]['video'][$z] = $duration;



                                $video[$z] += $duration;

                                $video[$z]['video'] = $this->pathVideo . $video[$z]['video'];
                            } else {

                                $duration = ["duration" => '00:00:00'];

                                $video[$z] += $duration;

                                $data[$i]['video'][$z] = $duration;
                            }
                        } else {

                            $duration = ["duration" => '00:00:00'];

                            $video[$z] += $duration;
                        }
                    }



                    $sum = strtotime('00:00:00');

                    $totalTime = 0;

                    $dataTime = $data[$i]['video'];



                    foreach ($dataTime as $element) {

                        $time = implode($element);

                        if (substr_count($time, ':') == 1) {

                            $time = '00:' . $time;
                        }

                        $strTime = date("H:i:s", strtotime($time));



                        $timeInSec = strtotime($strTime) - $sum;



                        $totalTime = $totalTime + $timeInSec;
                    }



                    $hours = intval($totalTime / 3600);



                    $totalTime = $totalTime - ($hours * 3600);



                    $minutes = intval($totalTime / 60);



                    $second = $totalTime - ($minutes * 60);



                    $result = ($hours . " Jam : " . $minutes . " Menit : " . $second . " Detik");



                    $data[$i]['total_video_duration'] = $result;
                }
            } else {
                $data[$i]['is_review'] = false;

                $data[$i]['score'] = "0";

                $data[$i]['mengerjakan_video'] = '0 / 0';

                $data[$i]['lolos'] = false;

                $data[$i]['video'] = [];

                $data[$i]['total_video_duration'] = "0 Jam : 0 Menit : 0 Detik";
            }



            $cek_course = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



            if ($cek_course != null) {

                $reviewcourse = $this->modelReview->where('course_id', $data[$i]['course_id'])->findAll();



                $rating_raw = 0;

                $rating_final = 0;



                for ($n = 0; $n < count($reviewcourse); $n++) {

                    $rating_raw += $reviewcourse[$n]['score'];

                    $rating_final = $rating_raw / count($reviewcourse);



                    $data[$i]['rating_course'] = $rating_final;
                }
            } else {

                $data[$i]['rating_course'] = 0;
            }



            // $rating_course = $controllerreview->ratingcourse($data[$i]['course_id']);

            // $data[$i]['rating_course'] = $rating_course;



            $data[$i]['category'] = $category['name'];
        }



        if (count($data) > 0) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }
}
