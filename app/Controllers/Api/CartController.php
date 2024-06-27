<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Models\Cart;

use App\Models\Course;

use App\Models\Bundling;

use App\Models\CourseCategory;

use App\Models\Users;

use App\Models\UserCourse;

use App\Models\Voucher;

use App\Models\Referral;

use App\Models\ReferralUser;

use App\Models\Tag;

use App\Models\Webinar;

use App\Models\UserWebinar;
use CodeIgniter\HTTP\Response;
use Firebase\JWT\JWT;



class CartController extends ResourceController

{

    use ResponseTrait;



    public function index()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $cart = new Cart;

            $course = new Course;

            $bundling = new Bundling;

            $user = new Users;

            $tag = new Tag;

            $webinar = new Webinar;

            $course_cat = new CourseCategory();



            $cart_data = $cart->where('user_id', $decoded->uid)->findAll();

            $user_data = $user->select('id, email, date_birth, address, phone_number')->where('id', $decoded->uid)->first();



            if (count($cart_data) > 0) {

                $temp = 0;



                foreach ($cart_data as $value) {

                    $course_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'course')->first();

                    $training_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'training')->first();

                    $bundling_data = $bundling->select('title, old_price, new_price, thumbnail')->where('bundling_id', $value['bundling_id'])->first();

                    $webinar_data = $webinar->select('title, old_price, new_price, thumbnail, tag_id')->where('webinar_id', $value['webinar_id'])->first();

                    if ($course_data) {

                        $category = $course_cat

                            ->where('course_id', $value['course_id'])

                            ->join('category', 'category.category_id = course_category.category_id')

                            ->orderBy('course_category.course_category_id', 'DESC')

                            ->first();

                        $video = $course

                            ->where('course.course_id', $value['course_id'])

                            ->join('video_category', 'video_category.course_id = course.course_id')

                            ->join('video', 'video_category.video_category_id = video.video_category_id')

                            ->findAll();

                        $course_data['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $course_data['thumbnail'];

                        $course_data['category'] = $category['name'];

                        $course_data['total_video'] = count($video);

                        $price = (empty($course_data['new_price'])) ? $course_data['old_price'] : $course_data['new_price'];
                    }



                    if ($training_data) {

                        $category = $course_cat

                            ->where('course_id', $value['course_id'])

                            ->join('category', 'category.category_id = course_category.category_id')

                            ->orderBy('course_category.course_category_id', 'DESC')

                            ->first();

                        $training_data['thumbnail'] = site_url() . 'upload/course/thumbnail/' . $training_data['thumbnail'];

                        $training_data['category'] = $category ? $category['name'] : 'Basic';

                        $price = (empty($training_data['new_price'])) ? $training_data['old_price'] : $training_data['new_price'];
                    }



                    if ($bundling_data) {

                        $courses = $bundling

                            ->where('bundling.bundling_id', $value['bundling_id'])

                            ->join('course_bundling', 'course_bundling.bundling_id = bundling.bundling_id')

                            ->join('category_bundling', 'category_bundling.category_bundling_id = bundling.category_bundling_id')

                            ->findAll();

                        $bundling_data['total_course'] = count($courses);

                        $bundling_data['category'] = $courses[0]['name'];

                        $bundling_data['thumbnail'] = site_url() . 'upload/bundling/' . $bundling_data['thumbnail'];

                        $price = (empty($bundling_data['new_price'])) ? $bundling_data['old_price'] : $bundling_data['new_price'];
                    }



                    if ($webinar_data) {

                        $tag = $tag

                            ->where('tag_id', $webinar_data['tag_id'])

                            ->first();

                        $webinar_data['tag'] = $tag['name'];

                        $webinar_data['thumbnail'] = site_url() . 'upload/webinar/' . $webinar_data['thumbnail'];

                        $price = (empty($webinar_data['new_price'])) ? $webinar_data['old_price'] : $webinar_data['new_price'];
                    }



                    $items[] = [

                        'cart_id' => $value['cart_id'],

                        'course' => $course_data,

                        'training' => $training_data,

                        'bundling' => $bundling_data,

                        'webinar' => $webinar_data,

                        'sub_total' => $price

                    ];



                    $subtotal = (int)$price;

                    $temp += $subtotal;
                }



                $tax = $temp * 11 / 100;

                if (isset($_GET['c'])) {

                    $data = $_GET['c'];
                } else {

                    $data = null;
                }



                $reedem = $this->reedem($data, $decoded->uid);



                if ($reedem > 0 && $reedem <= 100) {

                    $discount = ($reedem / 100) * ($temp + $tax);

                    $total = $temp + $tax - $discount;
                } else {

                    if ($reedem == 400) {

                        $reedem = 'Kuota kode telah habis';
                    } elseif ($reedem == 401) {

                        $reedem = 'Voucher kadaluarsa';
                    } elseif ($reedem == 402) {

                        $reedem = 'Kode tidak dapat digunakan';
                    }

                    $total = $temp + $tax;
                }



                $response = [

                    'user' => $user_data,

                    'item' => $items,

                    'coupon' => $reedem,

                    'sub_total' => $temp,

                    'total' => $total,

                    'tax' => $tax

                ];



                return $this->respond($response);
            } else {

                // return $this->failNotFound('Tidak ada data');

                $response = [

                    'user' => $user_data,

                    'item' => [],

                    'coupon' => 0,

                    'sub_total' => 0,

                    'total' => 0

                ];



                return $this->respond($response);
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function all()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $cart = new Cart;

            $course = new Course;

            $bundling = new Bundling;

            $user = new Users;

            $tag = new Tag;

            $webinar = new Webinar;

            $course_cat = new CourseCategory();



            $cart_data = $cart->where('user_id', $decoded->uid)->findAll();

            $user_data = $user->select('id, email, date_birth, address, phone_number')->where('id', $decoded->uid)->first();



            if (count($cart_data) > 0) {

                $temp = 0;



                foreach ($cart_data as $value) {

                    $course_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'course')->first();

                    $training_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'training')->first();

                    $bundling_data = $bundling->select('title, old_price, new_price, thumbnail')->where('bundling_id', $value['bundling_id'])->first();

                    $webinar_data = $webinar->select('title, old_price, new_price, thumbnail, tag_id')->where('webinar_id', $value['webinar_id'])->first();

                    if ($course_data) {

                        $video = $course

                            ->where('course.course_id', $value['course_id'])

                            ->join('video_category', 'video_category.course_id = course.course_id')

                            ->join('video', 'video_category.video_category_id = video.video_category_id')

                            ->findAll();

                        $type = 'course';

                        $title = $course_data['title'];

                        $old_price = $course_data['old_price'];

                        $new_price = $course_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $course_data['thumbnail'];

                        $total_item = count($video);

                        $price = (empty($course_data['new_price'])) ? $course_data['old_price'] : $course_data['new_price'];
                    }


                    if ($training_data) {

                        $type = 'training';

                        $title = $training_data['title'];

                        $old_price = $training_data['old_price'];

                        $new_price = $training_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $training_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($training_data['new_price'])) ? $training_data['old_price'] : $training_data['new_price'];
                    }


                    if ($bundling_data) {

                        $courses = $bundling

                            ->where('bundling.bundling_id', $value['bundling_id'])

                            ->join('course_bundling', 'course_bundling.bundling_id = bundling.bundling_id')

                            ->findAll();

                        $type = 'bundling';

                        $title = $bundling_data['title'];

                        $old_price = $bundling_data['old_price'];

                        $new_price = $bundling_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/bundling/' . $bundling_data['thumbnail'];

                        $total_item = count($courses);

                        $price = (empty($bundling_data['new_price'])) ? $bundling_data['old_price'] : $bundling_data['new_price'];
                    }



                    if ($webinar_data) {

                        $tag = $tag

                            ->where('tag_id', $webinar_data['tag_id'])

                            ->first();

                        $type = 'webinar';

                        $title = $webinar_data['title'];

                        $old_price = $webinar_data['old_price'];

                        $new_price = $webinar_data['new_price'];

                        $webinar_tag = $tag['name'];

                        $thumbnail = site_url() . 'upload/webinar/' . $webinar_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($webinar_data['new_price'])) ? $webinar_data['old_price'] : $webinar_data['new_price'];
                    }



                    $items[] = [

                        'cart_id' => $value['cart_id'],

                        'type' => $type,

                        'title' => $title,

                        'old_price' => $old_price,

                        'new_price' => $new_price,

                        'thumbnail' => $thumbnail,

                        'total_item' => $total_item,

                        'webinar_tag' => $webinar_tag,

                        'sub_total' => $price

                    ];



                    $subtotal = (int)$price;

                    $temp += $subtotal;
                }



                $tax = $temp * 11 / 100;

                if (isset($_GET['c'])) {

                    $data = $_GET['c'];
                } else {

                    $data = null;
                }



                $reedem = $this->reedem($data, $decoded->uid);



                if ($reedem > 0 && $reedem <= 100) {

                    $discount = ($reedem / 100) * ($temp + $tax);

                    $total = $temp + $tax - $discount;
                } else {

                    if ($reedem == 400) {

                        $reedem = 'Kuota kode telah habis';
                    } elseif ($reedem == 401) {

                        $reedem = 'Voucher kadaluarsa';
                    } elseif ($reedem == 402) {

                        $reedem = 'Kode tidak dapat digunakan';
                    }

                    $total = $temp + $tax;
                }



                $response = [

                    'user' => $user_data,

                    'item' => $items,

                    'coupon' => $reedem,

                    'sub_total' => $temp,

                    'total' => $total,

                    'tax' => $tax

                ];



                return $this->respond($response);
            } else {

                // return $this->failNotFound('Tidak ada data');

                $response = [

                    'user' => $user_data,

                    'item' => [],

                    'coupon' => 0,

                    'sub_total' => 0,

                    'total' => 0

                ];



                return $this->respond($response);
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function create($type = null, $id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $cart = new Cart;

            $userCourse = new UserCourse;

            $userWebinar = new UserWebinar;

            $webinar = new Webinar;

            $course = new Course;

            $bundling = new Bundling;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'member') {

                return $this->fail('Tidak dapat di akses selain member', 400);
            }



            $check2 = false;

            $check3 = false;

            if ($type == 'course') {

                $check = $cart->where('course_id', $id)->where('user_id ', $decoded->uid)->first();

                $check2 = $userCourse->where('user_id', $decoded->uid)->where('course_id', $id)->first();

                $check3 = $course->select('deleted_at')->where('course_id', $id)->first();

                $messages = 'Kursus';
            }



            if ($type == 'bundling') {

                $check = $cart->where('bundling_id', $id)->where('user_id', $decoded->uid)->first();

                $check2 = $userCourse->where('user_id', $decoded->uid)->where('bundling_id', $id)->first();

                $check3 = $bundling->select('deleted_at')->where('bundling_id', $id)->first();

                $messages = 'Bundling';
            }



            if ($type == 'webinar') {

                //cek apakah webinar sudah ada di cart

                $check = $cart->where('webinar_id', $id)->where('user_id', $decoded->uid)->first();

                //cek apakah webinar sudah pernah dibeli

                $check2 = $userWebinar->where('user_id', $decoded->uid)->where('webinar_id', $id)->first();

                //cek apakah webinar tersedia

                $check3 = $webinar->select('deleted_at')->where('webinar_id', $id)->first();

                $messages = 'Webinar';
            }



            if (!$check3) {

                return $this->failNotFound($messages . ' tidak ditemukan');
            }



            if ($check2) {

                return $this->fail($messages . ' sudah dibeli', 400);
            }



            if (!$check) {

                $data = [

                    'user_id' => $decoded->uid,

                    'course_id' => ($type == 'course') ? $id : null,

                    'bundling_id' => ($type == 'bundling') ? $id : null,

                    'webinar_id' => ($type == 'webinar') ? $id : null,

                ];

                $cart->save($data);

                $response = [

                    'status' => 200,

                    'success' => 200,

                    'message' => $messages . ' berhasil ditambahkan ke keranjang',

                    'data' => []

                ];
            } else {

                return $this->fail('Item sudah dimasukkan ke dalam keranjang', 400);
            }



            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
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

            if ($data['role'] != 'member') {

                return $this->fail('Tidak dapat di akses selain member', 400);
            }



            $model = new Cart();

            $cek = $model->find($id);

            if ($cek) {

                if ($cek['user_id'] != $decoded->uid) {
                    $response = [

                        'status'   => 400,

                        'success'    => 400,

                        'messages' => [

                            'success' => 'Anda tidak memiliki hak akses terhadap item ini.'

                        ]

                    ];

                    return $this->fail($response);
                }

                $model->delete($id);

                $response = [

                    'status'   => 200,

                    'success'    => 200,

                    'messages' => [

                        'success' => 'Item berhasil di hapus dari keranjang'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {
                $response = [

                    'status'   => 400,

                    'success'    => 400,

                    'messages' => [

                        'success' => 'Data tidak ditemukan.'

                    ]

                ];

                return $this->fail($response);
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function reedem($code = null, $id = null)

    {

        $voucher = new Voucher();

        $referral = new Referral();

        $ref_user = new ReferralUser;



        $check_voucher = $voucher->select('discount_price')->where('code', $code)->first();

        $check_referral = $referral->select('discount_price')->where('referral_code', $code)->first();

        $check_ref_user = $ref_user->select('referral_user_id, discount_price, is_active')->where('referral_code', $code)->first();



        if ($check_voucher) {

            $voucher_data = $voucher->where('code', $code)->first();



            if ($voucher_data['quota'] == 0) {

                return 400;
            } else {

                if ($voucher_data['start_date'] <= date("Y-m-d") && $voucher_data['due_date'] >= date("Y-m-d")) {

                    return $check_voucher['discount_price'];
                } else {

                    return 401;
                }
            }
        }



        if ($check_referral) {



            $ref_data = $referral->select('referral_id, user_id, referral_user')->where('referral_code', $code)->first();

            $check = $ref_user->where('user_id', $id)->where('referral_id', $ref_data['referral_id'])->first();



            // tidak bisa menggunakan kode referral milik diri sendiri

            // tidak bisa memakai kode referral orang lain lebih dari 1 kali

            if (($ref_data['user_id'] == $id) || $check) {

                return 402;
            }



            // batas kode referral dapat dipakai orang lain 5 kali

            if ($ref_data['referral_user'] < 5) {

                return $check_referral['discount_price'];
            } else {

                return 400;
            }
        }



        if ($check_ref_user) {



            $check = $ref_user->where('user_id', $id)->first();



            if ($check_ref_user['is_active'] == 0 && !$check) {

                return $check_ref_user['discount_price'];
            } else {

                return 402;
            }
        }
    }


    public function process()
    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $cart = new Cart;

            $course = new Course;

            $bundling = new Bundling;

            $user = new Users;

            $tag = new Tag;

            $webinar = new Webinar;

            $temp = 0;

            $items = [];

            $list_item = array_unique($this->request->getJSON()->item_id);

            $type = $this->request->getJSON()->type;


            if ($type == 'cart') {

                foreach ($list_item as $input) {

                    $value = $cart->where('cart_id', $input)->first();

                    $course_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'course')->first();

                    $training_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $value['course_id'])->where('service', 'training')->first();

                    $bundling_data = $bundling->select('title, old_price, new_price, thumbnail')->where('bundling_id', $value['bundling_id'])->first();

                    $webinar_data = $webinar->select('title, old_price, new_price, thumbnail, tag_id')->where('webinar_id', $value['webinar_id'])->first();

                    if ($course_data) {

                        $video = $course

                            ->where('course.course_id', $value['course_id'])

                            ->join('video_category', 'video_category.course_id = course.course_id')

                            ->join('video', 'video_category.video_category_id = video.video_category_id')

                            ->findAll();

                        $type = 'course';

                        $title = $course_data['title'];

                        $old_price = $course_data['old_price'];

                        $new_price = $course_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $course_data['thumbnail'];

                        $total_item = count($video);

                        $price = (empty($course_data['new_price'])) ? $course_data['old_price'] : $course_data['new_price'];
                    }



                    if ($training_data) {

                        $type = 'training';

                        $title = $training_data['title'];

                        $old_price = $training_data['old_price'];

                        $new_price = $training_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $training_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($training_data['new_price'])) ? $training_data['old_price'] : $training_data['new_price'];
                    }



                    if ($bundling_data) {

                        $courses = $bundling

                            ->where('bundling.bundling_id', $value['bundling_id'])

                            ->join('course_bundling', 'course_bundling.bundling_id = bundling.bundling_id')

                            ->findAll();

                        $type = 'bundling';

                        $title = $bundling_data['title'];

                        $old_price = $bundling_data['old_price'];

                        $new_price = $bundling_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/bundling/' . $bundling_data['thumbnail'];

                        $total_item = count($courses);

                        $price = (empty($bundling_data['new_price'])) ? $bundling_data['old_price'] : $bundling_data['new_price'];
                    }



                    if ($webinar_data) {

                        $tag = $tag

                            ->where('tag_id', $webinar_data['tag_id'])

                            ->first();

                        $type = 'webinar';

                        $title = $webinar_data['title'];

                        $old_price = $webinar_data['old_price'];

                        $new_price = $webinar_data['new_price'];

                        $webinar_tag = $tag['name'];

                        $thumbnail = site_url() . 'upload/webinar/' . $webinar_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($webinar_data['new_price'])) ? $webinar_data['old_price'] : $webinar_data['new_price'];
                    }



                    $items[] = [

                        'item_id' => $value['cart_id'],

                        'type' => $type,

                        'title' => $title,

                        'old_price' => $old_price,

                        'new_price' => $new_price,

                        'thumbnail' => $thumbnail,

                        'total_item' => $total_item,

                        'webinar_tag' => $webinar_tag,

                        'sub_total' => $price

                    ];



                    $subtotal = (int)$price;

                    $temp += $subtotal;
                }
            } else {

                if ($type == "course") {

                    $course_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $list_item[0])->where('service', 'course')->first();

                    if ($course_data) {

                        $video = $course

                            ->where('course.course_id', $list_item[0])

                            ->join('video_category', 'video_category.course_id = course.course_id')

                            ->join('video', 'video_category.video_category_id = video.video_category_id')

                            ->findAll();

                        $type = 'course';

                        $title = $course_data['title'];

                        $old_price = $course_data['old_price'];

                        $new_price = $course_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $course_data['thumbnail'];

                        $total_item = count($video);

                        $price = (empty($course_data['new_price'])) ? $course_data['old_price'] : $course_data['new_price'];
                    }
                } else if ($type == "training") {

                    $training_data = $course->select('title, old_price, new_price, thumbnail')->where('course_id', $list_item[0])->where('service', 'training')->first();

                    if ($training_data) {

                        $type = 'training';

                        $title = $training_data['title'];

                        $old_price = $training_data['old_price'];

                        $new_price = $training_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/course/thumbnail/' . $training_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($training_data['new_price'])) ? $training_data['old_price'] : $training_data['new_price'];
                    }
                } else if ($type == "bundling") {

                    $bundling_data = $bundling->select('title, old_price, new_price, thumbnail')->where('bundling_id', $list_item[0])->first();

                    if ($bundling_data) {

                        $courses = $bundling

                            ->where('bundling.bundling_id', $list_item[0])

                            ->join('course_bundling', 'course_bundling.bundling_id = bundling.bundling_id')

                            ->findAll();

                        $type = 'bundling';

                        $title = $bundling_data['title'];

                        $old_price = $bundling_data['old_price'];

                        $new_price = $bundling_data['new_price'];

                        $webinar_tag = '';

                        $thumbnail = site_url() . 'upload/bundling/' . $bundling_data['thumbnail'];

                        $total_item = count($courses);

                        $price = (empty($bundling_data['new_price'])) ? $bundling_data['old_price'] : $bundling_data['new_price'];
                    }
                } else if ($type == "webinar") {

                    $webinar_data = $webinar->select('title, old_price, new_price, thumbnail, tag_id')->where('webinar_id', $list_item[0])->first();

                    if ($webinar_data) {

                        $tag = $tag

                            ->where('tag_id', $webinar_data['tag_id'])

                            ->first();

                        $type = 'webinar';

                        $title = $webinar_data['title'];

                        $old_price = $webinar_data['old_price'];

                        $new_price = $webinar_data['new_price'];

                        $webinar_tag = $tag['name'];

                        $thumbnail = site_url() . 'upload/webinar/' . $webinar_data['thumbnail'];

                        $total_item = 0;

                        $price = (empty($webinar_data['new_price'])) ? $webinar_data['old_price'] : $webinar_data['new_price'];
                    }
                }

                $items[] = [

                    'item_id' => $list_item[0],

                    'type' => $type,

                    'title' => $title,

                    'old_price' => $old_price,

                    'new_price' => $new_price,

                    'thumbnail' => $thumbnail,

                    'total_item' => $total_item,

                    'webinar_tag' => $webinar_tag,

                    'sub_total' => $price

                ];



                $subtotal = (int)$price;

                $temp += $subtotal;
            }




            $tax = $temp * 11 / 100;

            $total = $temp + $tax;

            // if (isset($_GET['c'])) {

            //     $data = $_GET['c'];
            // } else {

            //     $data = null;
            // }



            // $reedem = $this->reedem($data, $decoded->uid);



            // if ($reedem > 0 && $reedem <= 100) {

            //     $discount = ($reedem / 100) * ($temp + $tax);

            //     $total = $temp + $tax - $discount;
            // } else {

            //     if ($reedem == 400) {

            //         $reedem = 'Kuota kode telah habis';
            //     } elseif ($reedem == 401) {

            //         $reedem = 'Voucher kadaluarsa';
            //     } elseif ($reedem == 402) {

            //         $reedem = 'Kode tidak dapat digunakan';
            //     }

            //     $total = $temp + $tax;
            // }



            $response = [

                'item' => $items,

                // 'coupon' => $reedem,

                'sub_total' => $temp,

                'total' => $total,

                'tax' => $tax

            ];



            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }
}
