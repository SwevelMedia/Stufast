<?php



namespace App\Controllers\Api;



use CodeIgniter\API\ResponseTrait;

use App\Models\Video;

use App\Models\Course;

use App\Models\Users;

use App\Models\Quiz;

use App\Models\Quiz_2;

use App\Models\UserVideo;

use App\Models\UserView;

use App\Models\VideoCategory;

use App\Models\UserTask;

use App\Models\UserScore;

use App\Models\UserCourse;

use CodeIgniter\RESTful\ResourceController;

use Firebase\JWT\JWT;



class VideoController extends ResourceController

{

	use ResponseTrait;

	private $videoModel = NULL;

	private $courseModel = NULL;





	function __construct()

	{

		$this->videoModel = new Video();

		$this->courseModel = new Course();

		$this->userVideoModel = new UserVideo();

		$this->videoCategory = new VideoCategory();

		$this->userView = new UserView();
	}



	public function index($id = null)

	{

		$data = $this->videoModel->where('video_id', $id)->first();



		$path_thumbnail = site_url() . 'upload/course-video/thumbnail/';

		$path_video = site_url() . 'upload/course-video/';



		if (!$data) {

			return $this->failNotFound('Data Video tidak ditemukan');
		}



		$data = [

			"video_id" => $data['video_id'],

			"video_category_id" => $data['video_category_id'],

			"title" =>  $data['title'],

			"thumbnail" => $path_thumbnail . $data['thumbnail'],

			"video" => $path_video . $data['video'],

			"order" =>  $data['order'],

			"created_at" => $data['created_at'],

			"updated_at" => $data['updated_at'],

		];



		$modelQuiz = new Quiz;

		$dataQuiz = $modelQuiz->where('video_id', $id)->first();

		// var_dump($dataQuiz);



		$quiz = [];

		// for($i = 0; $i < count($dataQuiz); $i++){

		// 	array_push($quiz, $dataQuiz[$i]);

		// 	// $quizRaw = [];



		// 	$question = json_decode($dataQuiz[$i]['question']);

		// 	for($l = 0; $l < count($question); $l++){

		// 		unset($question[$l]->is_valid);

		// 	}

		// 	// array_push($quizRaw, $question);

		// 	unset($dataQuiz[$i]['question']);

		// 	$dataQuiz[$i]['soal'] = $question;

		// }

		if ($dataQuiz != null) {

			$question = json_decode($dataQuiz['question']);

			for ($l = 0; $l < count($question); $l++) {

				unset($question[$l]->valid_answer);
			}

			// array_push($quizRaw, $question);

			unset($dataQuiz['question']);

			shuffle($question);



			$dataQuiz['soal'] = [];



			if (count($question) >= 5) {

				$rand_keys = array_rand($question, 5);

				for ($i = 0; $i < count($rand_keys); $i++) {

					array_push($dataQuiz['soal'], $question[$rand_keys[$i]]);
				}
			} else {

				$rand_keys = array_rand($question, count($question));

				for ($i = 0; $i < count($rand_keys); $i++) {

					array_push($dataQuiz['soal'], $question[$rand_keys[$i]]);
				}
			}
		} else {

			$dataQuiz['soal'] = null;
		}

		$data['quiz'] = $dataQuiz;

		shuffle($data['quiz']);



		return $this->respond($data);
	}

	public function getQuiz($id = null)

	{

		$data = $this->videoModel->where('video_id', $id)->first();



		$path_thumbnail = site_url() . 'upload/course-video/thumbnail/';

		$path_video = site_url() . 'upload/course-video/';



		if (!$data) {

			return $this->failNotFound('Data Video tidak ditemukan');
		}



		$data = [

			"video_id" => $data['video_id'],

			"video_category_id" => $data['video_category_id'],

			"title" =>  $data['title'],

			"thumbnail" => $path_thumbnail . $data['thumbnail'],

			"video" => $path_video . $data['video'],

			"order" =>  $data['order'],

			"created_at" => $data['created_at'],

			"updated_at" => $data['updated_at'],

		];



		$modelQuiz = new Quiz_2;

		$dataQuiz = $modelQuiz->where('video_id', $id)->findAll();

		$quiz = [];



		// if ($dataQuiz != null) {

		// 	$question = json_decode($dataQuiz['question']);

		// 	for ($l = 0; $l < count($question); $l++) {

		// 		// unset($question[$l]->is_valid);

		// 	}

		// 	// array_push($quizRaw, $question);

		// 	unset($dataQuiz['question']);

		// 	shuffle($question);



		// 	$dataQuiz['soal'] = [];



		// 	if (count($question) >= 5) {

		// 		$rand_keys = array_rand($question, 5);

		// 		for ($i = 0; $i < count($rand_keys); $i++) {

		// 			array_push($dataQuiz['soal'], $question[$rand_keys[$i]]);

		// 		}

		// 	} else {

		// 		$rand_keys = array_rand($question, count($question));

		// 		for ($i = 0; $i < count($rand_keys); $i++) {

		// 			array_push($dataQuiz['soal'], $question[$rand_keys[$i]]);

		// 		}

		// 	}

		// } else {

		//     $dataQuiz['soal'] = null;

		// }

		// $data['quiz'] = $dataQuiz;

		shuffle($dataQuiz);

		$random_keys = array_rand($dataQuiz, 5); // select 3 random keys from the original array

		$random_data = array();

		foreach ($random_keys as $key) {

			$random_data[] = $dataQuiz[$key]; // use the random keys to select elements from the original array

		}

		$data['quiz'] = $random_data;



		return $this->respond($data);
	}



	// get jawaban valid by soal yang tampil 



	public function answer($id = null)

	{

		$key = getenv('TOKEN_SECRET');

		$header = $this->request->getServer('HTTP_AUTHORIZATION');

		if (!$header) return $this->failUnauthorized('Akses token diperlukan');

		$token = explode(' ', $header)[1];



		try {

			$decoded = JWT::decode($token, $key, ['HS256']);

			$video = new Video();

			$userCourse = new UserCourse();

			$model = new UserScore;

			$modelUserVideo = new UserVideo();



			$rules = [

				"answer" => "required",

			];

			$messages = [

				"answer" => [

					"required" => "{field} tidak boleh kosong"

				],

			];

			if (!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());



			// $modelQuiz = new Quiz;

			// $dataQuiz = $modelQuiz->where('video_id', $id)->first();

			// $question = json_decode($dataQuiz['question']);



			$jawaban_valid = $this->request->getVar('jawaban_valid');

			$answer = $this->request->getVar('answer');



			if (count($answer) != count($jawaban_valid)) {

				return $this->fail('Mohon jawab semua soal yang ada', 400);
			}



			$salah = 0;

			for ($i = 0; $i < count($jawaban_valid); $i++) {

				if ($answer[$i]->answer != $jawaban_valid[$i]) {

					// for debugging

					// return $this->fail('Jawaban ke ' . $i+1 .' salah', 400);

					$salah++;
				}
			}



			$scoreRaw = round((count($answer) - $salah) / count($answer), 6);

			$score = intval($scoreRaw * 100);

			if ($score == 1) {

				$score = 100;
			}



			$userVideo = $this->userVideoModel->where('user_id', $decoded->uid)->where('video_id', $id)->first();

			$userView = $this->userView->where('id_user', $decoded->uid)->where('id_video', $id)->first();



			if (!$userView) {

				$this->userView->insert([

					'id_user' => $decoded->uid,

					'id_video' => $id,

				]);
			}



			// return $this->fail($userVideo, 400);

			if (!$userVideo && $score >= 50) {

				$this->userVideoModel->insert([

					'user_id' => $decoded->uid,

					'video_id' => $id,

					'score' => $score

				]);
			} elseif ($userVideo && $score >= 50) {

				$this->userVideoModel->where('user_id', $decoded->uid)->where('video_id', $id)

					->set('score', $score)

					->update();
			}



			$pass = false;

			if ($score >= 50) {

				$pass = true;

				$courseIds = $userCourse

					->select('course_id')

					->where('user_id', $decoded->uid)

					->orderBy('user_id')

					->findAll();

				$courseIds = array_merge(

					$courseIds,

					$userCourse

						->select('course_bundling.course_id')

						->join('course_bundling', 'user_course.bundling_id = course_bundling.bundling_id')

						->where('user_course.user_id', $decoded->uid)

						->orderBy('user_course.user_id')

						->findAll()

				);

				$courses = 0;

				$average = 0;

				$courseIds = array_unique(array_column($courseIds, 'course_id'));

				foreach ($courseIds as $courseId) {

					$videoScores = [];

					$videoUser = $modelUserVideo

						->select('video.title, user_video.score, video_category.video_category_id')

						->join('video', 'user_video.video_id = video.video_id')

						->join('video_category', 'video.video_category_id = video_category.video_category_id')

						->where('user_video.user_id', $decoded->uid)

						->where('video_category.course_id', $courseId)

						->find();

					if (count($videoUser) != 0) {

						$totalVideo = $video

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

					'user_id' => $decoded->uid,

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

				'status' => 200,

				'success' => 200,

				'pass' => $pass,

				'score' => $score

			];

			return $this->respondCreated($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
	}


	// buat mobile 

	public function answer_2($id = null)

	{

		$key = getenv('TOKEN_SECRET');

		$header = $this->request->getServer('HTTP_AUTHORIZATION');

		if (!$header) return $this->failUnauthorized('Akses token diperlukan');

		$token = explode(' ', $header)[1];



		try {

			$decoded = JWT::decode($token, $key, ['HS256']);



			$rules = [

				"answer" => "required",

			];

			$messages = [

				"answer" => [

					"required" => "{field} tidak boleh kosong"

				],

			];

			if (!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());



			// $modelQuiz = new Quiz;

			// $dataQuiz = $modelQuiz->where('video_id', $id)->first();

			// $question = json_decode($dataQuiz['question']);



			$question = $this->request->getVar('question');

			$answer = $this->request->getVar('answer');


			$modelQuiz = new Quiz_2;

			// $dataQuiz = $modelQuiz->whereIn('quiz_id', $question)->findAll();

			// $jawaban_valid = [];

			// return json_encode($dataQuiz);



			if (count($answer) != count($question)) {

				return $this->fail('Mohon jawab semua soal yang ada', 400);
			}



			$salah = 0;

			for ($i = 0; $i < count($question); $i++) {

				$soal = $modelQuiz->where('quiz_id', $question[$i])->first();

				if ($answer[$i] != strtoupper($soal['valid_answer'])) {

					// for debugging

					// return $this->fail('Jawaban ke ' . $i + 1 . ' salah', 400);

					$salah++;
				}
			}



			$scoreRaw = round((count($answer) - $salah) / count($answer), 6);

			$score = intval($scoreRaw * 100);

			if ($score == 1) {

				$score = 100;
			}



			$userVideo = $this->userVideoModel->where('user_id', $decoded->uid)->where('video_id', $id)->first();

			$userView = $this->userView->where('id_user', $decoded->uid)->where('id_video', $id)->first();



			if (!$userView) {

				$this->userView->insert([

					'id_user' => $decoded->uid,

					'id_video' => $id,

				]);
			}



			// return $this->fail($userVideo, 400);

			if (!$userVideo && $score >= 50) {

				$this->userVideoModel->insert([

					'user_id' => $decoded->uid,

					'video_id' => $id,

					'score' => $score

				]);
			} elseif ($userVideo && $score >= 50) {

				$this->userVideoModel->where('user_id', $decoded->uid)->where('video_id', $id)

					->set('score', $score)

					->update();
			}



			$pass = false;

			if ($score >= 50) {

				$pass = true;
			}

			$response = [

				'status' => 200,

				'success' => 200,

				'pass' => $pass,

				'score' => $score

			];

			return $this->respondCreated($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
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



			$rules = [

				"video_category_id" => "required",

				"title" => "required",

				"thumbnail" => "uploaded[thumbnail]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]|max_size[thumbnail,4000]",

				"video" => "mime_in[video,video/mp4,video/3gp,video/flv]|max_size[video,262144]",

				// "video" => "max_size[video,262144]",

				"order" => "required",

			];

			$messages = [

				"video_category_id" => [

					"required" => "{field} tidak boleh kosong"

				],

				"title" => [

					"required" => "{field} tidak boleh kosong"

				],

				"thumbnail" => [

					'uploaded' => '{field} tidak boleh kosong',

					'mime_in' => '{field} Harus Berupa jpg, jpeg, png atau webp',

					'max_size' => 'Ukuran {field} Maksimal 4 MB'

				],

				"video" => [

					// 'uploaded' => '{field} tidak boleh kosong',

					'mime_in' => '{field} Harus Berupa mp4, 3gp, atau flv',

					'max_size' => 'Ukuran {field} Maksimal 256 MB'

				],

				"order" => [

					"required" => "{field} tidak boleh kosong"

				],

			];



			if (!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());



			$verifyCourse = $this->videoCategory->where("video_category_id", $this->request->getVar('video_category_id'))->first();

			if (!$verifyCourse) {

				return $this->failNotFound('Course tidak ditemukan');
			} else {

				$dataVideo = $this->request->getVar('video_url');



				$dataThumbnail = $this->request->getFile('thumbnail');

				$thumbnailFileName = $dataThumbnail->getRandomName();

				$dataThumbnail->move('upload/course-video/thumbnail/', $thumbnailFileName);



				if (empty($dataVideo)) {

					$dataVideo = $this->request->getFile('video');

					$fileName = $dataVideo->getRandomName();

					$dataVideo->move('upload/course-video/', $fileName);
				} else {

					$fileName = $dataVideo;
				}



				$this->videoModel->insert([

					'video_category_id' => $this->request->getVar("video_category_id"),

					'title' => $this->request->getVar("title"),

					'thumbnail' => $thumbnailFileName,

					'order' => $this->request->getVar("order"),

					'video' => $fileName

				]);



				$response = [

					'status' => 200,

					'success' => 200,

					'message' => 'Video berhasil diupload',

					'data' => []

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



			$rules_a = [

				"video_category_id" => "required",

				"title" => "required",

				"order" => "required",

			];



			$rules_b = [

				"thumbnail" => "uploaded[thumbnail]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]|max_size[thumbnail,4000]",

			];



			$rules_c = [

				"video" => "uploaded[video]|mime_in[video,video/mp4,video/3gp,video/flv]|max_size[video,262144]",

			];



			$messages_a = [

				"video_category_id" => [

					"required" => "{field} tidak boleh kosong"

				],

				"title" => [

					"required" => "{field} tidak boleh kosong"

				],

				"order" => [

					"required" => "{field} tidak boleh kosong"

				],

			];



			$messages_b = [

				"thumbnail" => [

					'uploaded' => '{field} tidak boleh kosong',

					'mime_in' => 'File Extention Harus Berupa jpg, jpeg, png atau webp',

					'max_size' => 'Ukuran File Maksimal 4 MB'

				],

			];



			$messages_c = [

				"video" => [

					'uploaded' => '{field} tidak boleh kosong',

					'mime_in' => 'File Extention Harus Berupa mp4, 3gp, atau flv',

					'max_size' => 'Ukuran File Maksimal 256 MB'

				],

			];



			$verifyCourse = $this->videoCategory->where("video_category_id", $this->request->getVar('video_category_id'))->first();



			if (!$verifyCourse) {

				return $this->failNotFound('Course tidak ditemukan');
			}



			$findvideo = $this->videoModel->where('video_id', $id)->first();

			if ($findvideo) {

				if ($this->validate($rules_a, $messages_a)) {

					if ($this->validate($rules_b, $messages_b)) {

						$oldThumbnail = $findvideo['thumbnail'];

						$dataThumbnail = $this->request->getFile('thumbnail');



						if ($dataThumbnail->isValid() && !$dataThumbnail->hasMoved()) {

							if (file_exists("upload/course-video/thumbnail/" . $oldThumbnail)) {

								unlink("upload/course-video/thumbnail/" . $oldThumbnail);
							}

							$thumbnailFileName = $dataThumbnail->getRandomName();

							$dataThumbnail->move('upload/course-video/thumbnail/', $thumbnailFileName);
						} else {

							$thumbnailFileName = $oldThumbnail['thumbnail'];
						}



						$dataVideo = $this->request->getVar('video_url');



						if ($this->validate($rules_c, $messages_c) || $dataVideo != null) {

							$oldVideo = $findvideo['video'];

							$dataVideo = $this->request->getVar('video_url');



							if (empty($dataVideo)) {

								$dataVideo = $this->request->getFile('video');

								if ($dataVideo->isValid() && !$dataVideo->hasMoved()) {

									if (file_exists("upload/course-video/" . $oldVideo)) {

										unlink("upload/course-video/" . $oldVideo);
									}

									$fileName = $dataVideo->getRandomName();

									$dataVideo->move('upload/course-video/', $fileName);
								} else {

									$fileName = $oldVideo['video'];
								}
							} else {

								$fileName = $dataVideo;
							}



							$data = [

								'video_category_id' => $this->request->getVar("video_category_id"),

								'title' => $this->request->getVar("title"),

								'thumbnail' => $thumbnailFileName,

								'order' => $this->request->getVar("order"),

								'video' => $fileName

							];



							$this->videoModel->update($id, $data);



							$response = [

								'status'   => 201,

								'success'    => 201,

								'messages' => [

									'success' => 'Video berhasil diperbarui'

								]

							];
						}



						$data = [

							'video_category_id' => $this->request->getVar("video_category_id"),

							'title' => $this->request->getVar("title"),

							'thumbnail' => $thumbnailFileName,

							'order' => $this->request->getVar("order"),

						];



						$this->videoModel->update($id, $data);



						$response = [

							'status'   => 201,

							'success'    => 201,

							'messages' => [

								'success' => 'Video berhasil diperbarui'

							]

						];
					}



					$dataVideo = $this->request->getVar('video_url');



					if ($this->validate($rules_c, $messages_c) || $dataVideo != null) {

						$oldVideo = $findvideo['video'];

						$dataVideo = $this->request->getVar('video_url');



						if (empty($dataVideo)) {

							$dataVideo = $this->request->getFile('video');

							if ($dataVideo->isValid() && !$dataVideo->hasMoved()) {

								if (file_exists("upload/course-video/" . $oldVideo)) {

									unlink("upload/course-video/" . $oldVideo);
								}

								$fileName = $dataVideo->getRandomName();

								$dataVideo->move('upload/course-video/', $fileName);
							} else {

								$fileName = $oldVideo['video'];
							}
						} else {

							$fileName = $dataVideo;
						}



						$data = [

							'video_category_id' => $this->request->getVar("video_category_id"),

							'title' => $this->request->getVar("title"),

							'order' => $this->request->getVar("order"),

							'video' => $fileName

						];



						$this->videoModel->update($id, $data);



						$response = [

							'status'   => 201,

							'success'    => 201,

							'messages' => [

								'success' => 'Video berhasil diperbarui'

							]

						];
					}



					$data = [

						'video_category_id' => $this->request->getVar("video_category_id"),

						'title' => $this->request->getVar("title"),

						'order' => $this->request->getVar("order"),

					];



					$this->videoModel->update($id, $data);



					$response = [

						'status'   => 201,

						'success'    => 201,

						'messages' => [

							'success' => 'Video berhasil diperbarui'

						]

					];
				} else {

					$response = [

						'status'   => 400,

						'error'    => 400,

						'messages' => $this->validator->getErrors(),

					];
				}
			} else {

				$response = [

					'status'   => 400,

					'error'    => 400,

					'messages' => 'Data Video tidak ditemukan',

				];
			}

			return $this->respondCreated($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
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

			if ($data['role'] == 'member') {

				return $this->fail('Tidak dapat di akses oleh member', 400);
			}



			$data = $this->videoModel->find($id);

			if ($data) {

				$videoName = $data['video'];

				unlink("upload/course-video/" . $videoName);

				$this->videoModel->delete($id);

				$response = [

					'status'   => 200,

					'success'    => 200,

					'messages' => [

						'success' => 'Video berhasil dihapus'

					]

				];
			}

			return $this->respondDeleted($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}

		return $this->failNotFound('Data Video tidak ditemukan');
	}



	public function order()

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

				return $this->fail('Tidak dapat di akses selain admin & author', 400);
			}



			$orderReq = $this->request->getVar();



			for ($i = 0; $i < count($orderReq); $i++) {

				$video = $this->videoModel->find($orderReq[$i]->video_id);

				if ($video) {

					$data = [

						'order' => $orderReq[$i]->order

					];

					$this->videoModel->update($orderReq[$i]->video_id, $data);



					$response = [

						'status'   => 200,

						'success'    => 200,

						'messages' => [

							'success' => 'Video berhasil diupdate'

						]

					];
				} else {

					return $this->failNotFound('Data Video tidak ditemukan');
				}
			}



			return $this->respond($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
	}



	public function submitTask()
	{

		$key = getenv('TOKEN_SECRET');

		$header = $this->request->getServer('HTTP_AUTHORIZATION');

		if (!$header) return $this->failUnauthorized('Akses token diperlukan');

		$token = explode(' ', $header)[1];

		$task = new UserTask;

		try {

			$decoded = JWT::decode($token, $key, ['HS256']);

			$input = [

				'user_id' => $decoded->uid,

				'video_id' => $this->request->getVar('video_id')
			];

			$rules = [

				'video_id' => 'required|numeric',

				'task_file' => 'uploaded[task_file]'

					. '|mime_in[task_file,application/pdf]'

					. '|max_size[task_file,5000]'

			];

			$messages = [

				'video_id' => [

					'required' => 'video_id tidak boleh kosong',

					'numeric' => 'video_id harus berisi numerik'

				],

				'task_file' => [

					'uploaded' => 'File tugas tidak boleh kosong',

					'mime_in' => 'Ekstensi file harus berupa pdf',

					'max_size' => 'Ukuran file maksimal 5 MB'

				],

			];

			if ($this->validate($rules, $messages)) {

				$data = $task->where('video_id', $input['video_id'])->where('user_id', $input['user_id'])->first();

				$file = $this->request->getFile('task_file');

				if ($file->isValid() && !$file->hasMoved()) {

					$fileName = $file->getRandomName();

					$file->move('upload/task/', $fileName);

					$input['task_file'] = $fileName;

					if ($data) {

						if (file_exists("upload/task/" . $data['task_file'])) {

							unlink("upload/task/" . $data['task_file']);
						}

						$task->update($data['user_task_id'], $input);
					} else {

						$task->save($input);
					}

					$response = [

						'status'   => 200,

						'error'    => 200,

						'messages' => "Tugas anda berhasil di unggah"

					];

					return $this->respond($response);
				}

				$response = [

					'status'   => 400,

					'error'    => 400,

					'messages' => "File tidak valid",

				];

				return $this->fail($response);
			} else {

				return $this->fail($this->validator->getErrors());
			}
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
	}



	public function submitTaskMobile($video_id = null)
	{

		$key = getenv('TOKEN_SECRET');

		$header = $this->request->getServer('HTTP_AUTHORIZATION');

		if (!$header) return $this->failUnauthorized('Akses token diperlukan');

		$token = explode(' ', $header)[1];

		$task = new UserTask;

		try {

			$decoded = JWT::decode($token, $key, ['HS256']);

			$input = [

				'user_id' => $decoded->uid,

				'video_id' => $video_id
			];

			$data = $task->where('video_id', $input['video_id'])->where('user_id', $input['user_id'])->first();

			$binaryData = file_get_contents('php://input');

			$allowedExtensions = ['pdf'];

			$maxFileSize = 5000 * 1024;

			$filepath = 'upload/task/';

			$fileSize = strlen($binaryData);


			if (empty($binaryData)) {

				return $this->fail(['error' => 'File tugas tidak boleh kosong'], 400);
			}

			if (strpos($binaryData, '%PDF') !== 0) {

				return $this->fail(['error' => 'Ekstensi file harus berupa PDF'], 400);
			}

			if ($fileSize > $maxFileSize) {

				return $this->fail(['error' => 'Ukuran file maksimal 5 MB'], 400);
			}


			$filename = uniqid() . '.pdf';

			file_put_contents($filepath . $filename, $binaryData);

			$input['task_file'] = $filename;

			if ($data) {

				if (file_exists("upload/task/" . $data['task_file'])) {

					unlink("upload/task/" . $data['task_file']);
				}

				$task->update($data['user_task_id'], $input);
			} else {

				$task->save($input);
			}

			$response = [

				'status'   => 200,

				'error'    => 200,

				'messages' => "Tugas anda berhasil di unggah"

			];

			return $this->respond($response);
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
	}



	public function taskHistory($course_id)
	{

		$key = getenv('TOKEN_SECRET');

		$header = $this->request->getServer('HTTP_AUTHORIZATION');

		if (!$header) return $this->failUnauthorized('Akses token diperlukan');

		$token = explode(' ', $header)[1];

		$task = new UserTask;

		$course = new Course;

		$user_video = new UserVideo;

		$days = array(

			'Sunday' => 'Minggu',

			'Monday' => 'Senin',

			'Tuesday' => 'Selasa',

			'Wednesday' => 'Rabu',

			'Thursday' => 'Kamis',

			'Friday' => 'Jumat',

			'Saturday' => 'Sabtu'

		);

		$months = array(

			'January' => 'Januari',

			'February' => 'Februari',

			'March' => 'Maret',

			'April' => 'April',

			'May' => 'Mei',

			'June' => 'Juni',

			'July' => 'Juli',

			'August' => 'Agustus',

			'September' => 'September',

			'October' => 'Oktober',

			'November' => 'November',

			'December' => 'Desember'

		);

		try {

			$decoded = JWT::decode($token, $key, ['HS256']);

			$history = $course

				->select('video.video_id, video.title, video.task')

				->join('video_category', 'course.course_id=video_category.course_id')

				->join('video', 'video.video_category_id=video_category.video_category_id')

				->where('course.course_id', $course_id)

				->where('video.task IS NOT NULL')

				->orderBy('video.order', 'ASC')

				->findAll();

			if ($history) {

				foreach ($history as $key => $item) {

					$quiz = $user_video

						->where('video_id', $item['video_id'])

						->where('user_id', $decoded->uid)

						->first();

					if ($quiz) {

						$history[$key]['quiz_score'] = $quiz['score'];
					} else {

						$history[$key]['quiz_score'] = 0;
					}

					$user_task = $task

						->where('video_id', $item['video_id'])

						->where('user_id', $decoded->uid)

						->first();

					if ($user_task) {

						$history[$key]['task_file'] = site_url() . 'upload/task/' . $user_task['task_file'];

						$timestamp = strtotime($user_task['updated_at']);

						$date = date('l, d F Y', $timestamp);

						$date = strtr($date, $days);

						$date = strtr($date, $months);

						$history[$key]['date'] = $date;

						$history[$key]['status'] = 'Dikumpulkan';
					} else {

						$history[$key]['task_file'] = '-';

						$history[$key]['date'] = '-';

						$history[$key]['status'] = 'Belum dikumpulkan';
					}
				}

				return $this->respond($history);
			} else {
				return $this->failNotFound('tidak ada data');
			}
		} catch (\Throwable $th) {

			return $this->fail($th->getMessage());
		}
	}
}
