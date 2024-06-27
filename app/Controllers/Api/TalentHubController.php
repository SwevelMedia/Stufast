<?php



namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Users;

use App\Models\Jobs;

use App\Models\UserCourse;

use App\Models\Course;

use App\Models\CourseTag;

use App\Models\VideoCategory;

use App\Models\Video;

use App\Models\UserVideo;

use App\Models\UserCv;

use App\Models\UserScore;

use Firebase\JWT\JWT;

use CodeIgniter\Files\File;

use CodeIgniter\HTTP\IncomingRequest;

use CodeIgniter\HTTP\RequestInterface;
use PhpParser\Node\Stmt\Continue_;

class TalentHubController extends ResourceController

{

    use ResponseTrait;

    private $user = NULL;

    private $userCourse = NULL;

    private $courseTag = NULL;

    private $modelCourse = NULL;

    private $modelUserVideo = NULL;

    private $video = NULL;

    private $userCv = NULL;


    function __construct()
    {

        $this->user = new Users();

        $this->userCourse = new UserCourse();

        $this->courseTag = new CourseTag();

        $this->modelCourse = new Course();

        $this->modelUserVideo = new UserVideo();

        $this->video = new Video();

        $this->userCv = new UserCv();
    }



    public function page($page = 1)

    {

        $raw_data = $this->user

            ->select('fullname, id, profile_picture')

            ->where('role', 'member')

            ->orderBy('fullname', 'DESC')

            ->findAll();

        $perPage = 10;

        $offset = ($page - 1) * $perPage;

        $data = array_slice($raw_data, $offset, $perPage);

        $result = [];

        foreach ($data as $userData) {

            $cv = $this->userCv

                ->where('user_id', $userData['id'])

                ->first();

            $userData['status'] = $cv ? $cv['status'] : 'Pegawai Tetap atau Freelance';

            $userData['method'] = $cv ? $cv['method'] : 'Remote atau WFO';

            $userData['min_salary'] = $cv ? (int)$cv['min_salary'] : 0;

            $userData['max_salary'] = $cv ? (int)$cv['max_salary'] : 0;


            $courses = [];

            $average = 0;

            $courseIds = $this->getUserCourseIds($userData['id']);

            foreach ($courseIds as $courseId) {

                $courseInfo = $this->getCourseInfo($courseId);

                if ($courseInfo) {

                    $videoScores = $this->getVideoScores($userData['id'], $courseId);

                    if (!empty($videoScores)) {

                        $sum = array_sum(array_column($videoScores, 'score'));

                        $finalScore = $sum / count($videoScores);

                        $courses[] = [

                            'title' => $courseInfo['title'],

                            'course_id' => $courseInfo['course_id'],
                            // 'final_score' => $finalScore,
                            // 'score' => $videoScores
                        ];

                        $average += $finalScore;
                    }
                }
            }

            if (count($courses) > 0) {

                $average /= count($courses);

                $average = number_format($average, 0);
            }

            $userData['fullname'] = ucwords(strtolower($userData['fullname']));

            $userData['average_score'] = $average;

            $userData['total_course'] = count($courses);

            $userData['courses'] = $courses;

            $result[] = $userData;
        }

        if (count($result) > 0) {

            usort($result, function ($a, $b) {

                return $b['average_score'] - $a['average_score'];
            });

            return $this->respond($result);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    public function pagination()

    {

        $result = [];

        $data = [];

        $filter = $this->request->getJSON();

        $raw_data = $this->user

            ->select('fullname, id, profile_picture, total_course, average_score')

            ->where('role', 'member')

            ->where('total_course >', 1)

            ->join('user_score', 'users.id=user_score.user_id')

            ->orderBy($filter->sort->value, $filter->sort->order)

            ->findAll();

        foreach ($raw_data as $userData) {

            $cv = $this->userCv

                ->where('user_id', $userData['id'])

                ->first();

            $userData['fullname'] = $userData['fullname'] == null ? '-' : ucwords(strtolower($userData['fullname']));

            $userData['profile_picture_2'] = site_url() . 'upload/users/' . $userData['profile_picture'];

            $userData['status'] = $cv ? $cv['status'] : 'Pegawai Tetap atau Freelance';

            $userData['method'] = $cv ? $cv['method'] : 'WFO atau Remote';

            $userData['min_salary'] = $cv ? (int)$cv['min_salary'] : 0;

            $userData['max_salary'] = $cv ? (int)$cv['max_salary'] : 0;

            if (count($filter->status) > 0) {

                $pass = false;

                foreach ($filter->status as $item) {

                    if (stripos($userData['status'], $item) !== false) {

                        $pass = true;
                    }
                }

                if (!$pass) continue;
            }

            if (count($filter->method) > 0) {

                $pass = false;

                foreach ($filter->method as $item) {

                    if (stripos($userData['method'], $item) !== false) {

                        $pass = true;
                    }
                }

                if (!$pass) continue;
            }

            if ($filter->max_salary > 0) {

                if ($userData['min_salary'] > $filter->max_salary) {

                    continue;
                }
            }

            if ($filter->search) {

                if (stripos($userData['fullname'], $filter->search) === false) {

                    continue;
                }
            }

            $data[] = $userData;
        }

        $total_item = count($data);

        $offset = ($filter->page - 1) * 12;

        $result = array_slice($data, $offset, 12);

        if (count($result) > 0) {

            $response = [

                'current_page' => $filter->page,

                'total_page' => ceil($total_item / 12),

                'total_item' => $total_item,

                'talent' => $result
            ];

            return $this->respond($response);
        } else {

            $response = [

                'current_page' => $filter->page,

                'total_page' => 0,

                "total_item" => 0,

                'talent' => []
            ];

            return $this->respond($response);
        }
    }


    public function topTalent($total = 4)

    {

        $data = $this->user

            ->select('fullname, id, profile_picture, total_course, average_score')

            ->where('role', 'member')

            ->where('total_course >', 1)

            ->join('user_score', 'users.id=user_score.user_id')

            ->orderBy('average_score', 'DESC')

            ->limit($total)

            ->find();

        foreach ($data as &$userData) {

            $userData['fullname'] = $userData['fullname'] == null ? '-' : ucwords(strtolower($userData['fullname']));

            $userData['profile_picture_2'] = site_url() . 'upload/users/' . $userData['profile_picture'];
        }

        return $this->respond($data);
    }


    public function index()

    {

        $data = $this->user

            ->select('fullname, id, profile_picture')

            ->where('role', 'member')

            ->orderBy('fullname', 'ASC')

            ->findAll();

        $result = [];

        foreach ($data as $userData) {

            $cv = $this->userCv

                ->where('user_id', $userData['id'])

                ->first();

            $userData['status'] = $cv ? $cv['status'] : 'Pegawai Tetap atau Freelance';

            $userData['method'] = $cv ? $cv['method'] : 'WFO atau Remote';

            $userData['min_salary'] = $cv ? (int)$cv['min_salary'] : 0;

            $userData['max_salary'] = $cv ? (int)$cv['max_salary'] : 0;


            $courses = [];

            $average = 0;

            $courseIds = $this->getUserCourseIds($userData['id']);

            foreach ($courseIds as $courseId) {

                $courseInfo = $this->getCourseInfo($courseId);

                if ($courseInfo) {

                    $videoScores = $this->getVideoScores($userData['id'], $courseId);

                    if (!empty($videoScores)) {

                        $sum = array_sum(array_column($videoScores, 'score'));

                        $finalScore = $sum / count($videoScores);

                        $courses[] = [

                            'title' => $courseInfo['title'],

                            'course_id' => $courseInfo['course_id'],
                            // 'final_score' => $finalScore,
                            // 'score' => $videoScores
                        ];

                        $average += $finalScore;
                    }
                }
            }

            if (count($courses) > 1) {

                $average /= count($courses);

                $average = number_format($average, 0);

                $userData['fullname'] = ucwords(strtolower($userData['fullname']));

                $userData['average_score'] = $average;

                $userData['total_course'] = count($courses);

                $userData['courses'] = $courses;

                $result[] = $userData;
            }
        }

        if (count($result) > 0) {

            usort($result, function ($a, $b) {

                return $b['average_score'] - $a['average_score'];
            });

            return $this->respond($result);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }


    private function getUserCourseIds($userId)

    {

        $courseIds = $this->userCourse

            ->select('course_id')

            ->where('user_id', $userId)

            ->orderBy('user_id')

            ->findAll();

        $courseIds = array_merge(

            $courseIds,

            $this->userCourse

                ->select('course_bundling.course_id')

                ->join('course_bundling', 'user_course.bundling_id = course_bundling.bundling_id')

                ->where('user_course.user_id', $userId)

                ->orderBy('user_course.user_id')

                ->findAll()

        );

        return array_unique(array_column($courseIds, 'course_id'));
    }


    private function getCourseInfo($courseId)

    {

        return $this->modelCourse

            ->select('title, course_id')

            ->where('course_id', $courseId)

            ->first();
    }


    private function getVideoScores($userId, $courseId)

    {

        $videoUser = $this->modelUserVideo

            ->select('video.title, user_video.score, video_category.video_category_id')

            ->join('video', 'user_video.video_id = video.video_id')

            ->join('video_category', 'video.video_category_id = video_category.video_category_id')

            ->where('user_video.user_id', $userId)

            ->where('video_category.course_id', $courseId)

            ->find();

        if (count($videoUser) != 0) {

            $totalVideo = $this->video

                ->where('video_category_id', $videoUser[0]['video_category_id'])

                ->find();

            if (count($videoUser) == count($totalVideo)) {

                return $videoUser;
            }
        }
    }


    public function show($id = null)

    {

        $modelVideo = new Video;

        $modelVideoCategory = new VideoCategory;

        $modelUserVideo = new UserVideo;

        $modelCourse = new Course;

        $user = $this->user->select('id, fullname, address, profile_picture')->where('id', $id)->first();

        $user['fullname'] = ucwords(strtolower($user['fullname']));

        $user['profile_picture_2'] = base_url() . "/upload/users/" . $user['profile_picture'];

        $cv = $this->userCv

            ->where('user_id', $user['id'])

            ->first();

        $user['status'] = $cv && $cv['status'] != null ? $cv['status'] : 'Pegawai Tetap atau Freelance';

        $user['method'] = $cv && $cv['method'] != null ? $cv['method'] : 'WFO atau Remote';

        if ($cv) {

            if (($cv['min_salary'] != null && $cv['min_salary'] != 0) && ($cv['max_salary'] != null && $cv['max_salary'] != 0)) {

                $user['range'] = 'Rp. ' . number_format($cv['min_salary'], 0, ',', '.') . ' - Rp. ' . number_format($cv['max_salary'], 0, ',', '.');
            } else if (($cv['min_salary'] == null || $cv['min_salary'] == 0) && ($cv['max_salary'] != null || $cv['max_salary'] != 0)) {

                $user['range'] = '< Rp. ' . number_format($cv['max_salary'], 0, ',', '.');
            } else if (($cv['min_salary'] != null || $cv['min_salary'] != 0) && ($cv['max_salary'] == null || $cv['max_salary'] == 0)) {

                $user['range'] = '> Rp. ' . number_format($cv['min_salary'], 0, ',', '.');
            } else {

                $user['range'] = 'Menyesuaikan';
            }
        } else {

            $user['range'] = "Menyesuaikan";
        }

        $user['about'] = $cv && $cv['about'] != null ? $cv['about'] : '-';

        $user['instagram'] = $cv && $cv['instagram'] != null ? $cv['instagram'] : '-';

        $user['facebook'] = $cv && $cv['facebook'] != null ? $cv['facebook'] : '-';

        $user['linkedin'] = $cv && $cv['linkedin'] != null ? $cv['linkedin'] : '-';

        $user['portofolio'] = $cv && $cv['portofolio'] != null ? site_url() . 'upload/portofolio/' . $cv['portofolio'] : '-';

        $course = [];

        $lolos = [];

        $ach = [];

        $query = $this->userCourse

            ->select('course_bundling.course_id')

            ->join('course_bundling', 'user_course.bundling_id = course_bundling.bundling_id')

            ->where('user_course.user_id', $id)

            ->orderBy('user_course.user_id')

            ->findAll();

        foreach ($query as $row) {

            $course[] = $row['course_id'];
        }

        $query = $this->userCourse

            ->select('course.course_id')

            ->join('course', 'user_course.course_id = course.course_id')

            ->where('user_course.user_id', $id)

            ->orderBy('user_course.user_id')

            ->findAll();

        foreach ($query as $row) {

            $course[] = $row['course_id'];
        }

        $course = array_unique($course);

        $course = array_values($course);

        for ($i = 0; $i < count($course); $i++) {

            $videoCat_ = $modelVideoCategory->where('course_id', $course[$i])->first();

            if ($videoCat_) {

                $video_ = $modelVideo->where('video_category_id', $videoCat_['video_category_id'])->findAll();

                $userVideo = 0;

                $total_video_yang_dikerjakan = 0;

                for ($l = 0; $l < count($video_); $l++) {

                    $userVideo_ = $modelUserVideo->where('user_id', $id)->where('video_id', $video_[$l]['video_id'])->first();

                    if ($userVideo_) {

                        $userVideo++;

                        $total_video_yang_dikerjakan++;
                    }
                }

                if ($total_video_yang_dikerjakan == count($video_)) {

                    $lolos[] = $course[$i];
                }
            }
        }

        foreach ($lolos as $value) {


            $judul = $modelCourse

                ->select('title, course_id')

                ->where('course_id', $value)

                ->first();

            $nilai = $modelUserVideo

                ->select('video.title, user_video.score')

                ->join('video', 'user_video.video_id = video.video_id ')

                ->join('video_category', 'video.video_category_id = video_category.video_category_id ')

                ->where('user_video.user_id', $id)

                ->where('video_category.course_id', $judul['course_id'])

                ->find();

            if ($nilai) {

                $sum = 0;

                foreach ($nilai as $n) {

                    $sum += $n['score'];
                }

                $final_score = $sum / count($nilai);

                $final_score = number_format($final_score, 0);
            }

            $ach[] = [

                'course_title' => $judul['title'],

                'final_score' => $final_score,

                'score' => $nilai

            ];
        }

        $average = 0;

        foreach ($ach as $nilai) {

            $average += $nilai['final_score'];
        }

        if (count($ach) != 0) {
            $average = $average / count($ach);

            if (floor($average) == $average) {
                // Tidak ada desimal
                $average = number_format($average, 0); // Format tanpa desimal
            } else {
                // Ada desimal
                $average = number_format($average, 0); // Format dengan 2 desimal
            }
        }

        $data = [

            'user' => $user,

            'total_course' => count($ach),

            'average_score' => $average,

            'ach' => $ach,

        ];

        // dd($data);

        if ($data) {

            return $this->respond($data);
        } else {

            return $this->failNotFound('Data tidak ditemukan');
        }
    }

    public function populate()
    {

        try {

            $model = new UserScore;

            $data = $this->user

                ->select('fullname, id, profile_picture')

                ->where('role', 'member')

                ->orderBy('fullname', 'ASC')

                ->findAll();

            foreach ($data as $userData) {

                $courseIds = $this->userCourse

                    ->select('course_id')

                    ->where('user_id', $userData['id'])

                    ->orderBy('user_id')

                    ->findAll();

                $courseIds = array_merge(

                    $courseIds,

                    $this->userCourse

                        ->select('course_bundling.course_id')

                        ->join('course_bundling', 'user_course.bundling_id = course_bundling.bundling_id')

                        ->where('user_course.user_id', $userData['id'])

                        ->orderBy('user_course.user_id')

                        ->findAll()

                );

                $courses = 0;

                $average = 0;

                $courseIds = array_unique(array_column($courseIds, 'course_id'));

                foreach ($courseIds as $courseId) {

                    $videoScores = [];

                    $videoUser = $this->modelUserVideo

                        ->select('video.title, user_video.score, video_category.video_category_id')

                        ->join('video', 'user_video.video_id = video.video_id')

                        ->join('video_category', 'video.video_category_id = video_category.video_category_id')

                        ->where('user_video.user_id', $userData['id'])

                        ->where('video_category.course_id', $courseId)

                        ->find();

                    if (count($videoUser) != 0) {

                        $totalVideo = $this->video

                            ->where('video_category_id', $videoUser[0]['video_category_id'])

                            ->find();

                        if (count($videoUser) == count($totalVideo)) {

                            $videoScores = $videoUser;
                        }
                    }

                    if (!empty($videoScores)) {

                        $sum = array_sum(array_column($videoScores, 'score'));

                        $finalScore = $sum / count($videoScores);

                        $courses++;

                        $average += $finalScore;
                    }
                }

                if ($average > 0) {

                    $average /= $courses;

                    $average = number_format($average, 0);
                }

                $input = [

                    'user_id' => $userData['id'],

                    'total_course' => $courses,

                    'average_score' => $average

                ];

                // return $this->respond($input);

                $cek = $model->where('user_id', $input['user_id'])->first();

                if ($cek) {

                    $model->update($cek['user_score_id'], $input);
                } else {

                    $model->save($input);
                }
            }

            $response = [

                'total_user' => count($data),

                'total_nilai' => count($model->findAll())
            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }
}
