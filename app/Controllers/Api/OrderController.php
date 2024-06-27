<?php



namespace App\Controllers\Api;



use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

use Firebase\JWT\JWT;

use App\Models\Cart;

use App\Models\Order;

use App\Models\OrderCourse;

use App\Models\OrderBundling;

use App\Models\OrderWebinar;

use App\Models\UserCourse;

use App\Models\Users;

use App\Models\Referral;

use App\Models\ReferralUser;

use App\Models\Voucher;

use App\Models\Course;

use App\Models\Bundling;

use App\Models\UserWebinar;

use App\Models\Webinar;

use CodeIgniter\HTTP\Response;

class OrderController extends BaseController

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

            $user = new Users;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;



            // cek role user

            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] != 'admin') {

                return $this->fail('Tidak dapat di akses selain admin', 400);
            }



            $dataOrder = $order->select('users.email, users.fullname, order.order_id, order.transaction_status, order.gross_amount as total_price, order.created_at as order_date')->join('users', 'users.id = order.user_id')->findAll();

            $response['order'] = $dataOrder;



            for ($i = 0; $i < count($dataOrder); $i++) {

                $itemCourse = $orderCourse->select('course.title, course.new_price')

                    ->join('course', 'order_course.course_id = course.course_id')

                    ->where('order_id', $dataOrder[$i]['order_id'])

                    ->findAll();

                $response['order'][$i]['course-item'] = $itemCourse;



                $itemBundling = $orderBundling->select('bundling.title, bundling.new_price')

                    ->join('bundling', 'order_bundling.bundling_id = bundling.bundling_id')

                    ->where('order_id', $dataOrder[$i]['order_id'])

                    ->findAll();

                $response['order'][$i]['bundling-item'] = $itemBundling;
            }



            return $this->respond($response);



            if (count($data) > 0) {

                return $this->respond($data);
            } else {

                return $this->failNotFound('Tidak ada data');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getOrderByAuthor()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member' || $data['role'] == 'admin') {

                return $this->fail('Hanya dapat di akses oleh author', 400);
            }



            $orderCourse = $orderCourse->select('order.order_id,users.fullname, order.transaction_status, course.title, order.created_at as order_time')

                ->join('order', 'order_course.order_id = order.order_id')

                ->join('users', 'order.user_id = users.id')

                ->join('course', 'order_course.course_id = course.course_id')

                ->where('course.author_id', $decoded->uid)

                ->findAll();



            $orderBundling = $orderBundling->select('order.order_id,users.fullname, order.transaction_status, bundling.title, order.created_at as order_time')

                ->join('order', 'order_bundling.order_id = order.order_id')

                ->join('users', 'order.user_id = users.id')

                ->join('bundling', 'order_bundling.bundling_id = bundling.bundling_id')

                ->where('bundling.author_id', $decoded->uid)

                ->findAll();



            $response = [

                'course' => $orderCourse,

                'bundling' => $orderBundling

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function generateSnap()

    {

        include 'SendNotification.php';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $data = [];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            // Set your Merchant Server Key

            \Midtrans\Config::$serverKey = getenv('mid_server_key');

            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).

            \Midtrans\Config::$isProduction = false;

            // Set sanitization on (default)

            \Midtrans\Config::$isSanitized = true;

            // Set 3DS transaction for credit card to true

            \Midtrans\Config::$is3ds = true;



            $cart = new Cart;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;

            $orderWebinar = new OrderWebinar;

            $user = new Users;

            $referral = new Referral;

            $referralUser = new ReferralUser;

            $voucher = new Voucher;

            $course = new Course;

            $bundling = new Bundling;

            $webinar = new Webinar;

            $temp = 0;

            $userId = $decoded->uid;

            //$userId = 17;

            $getUser = $user->where('id', $userId)->first();

            if (!isset($_GET['cr']) && !isset($_GET['tr']) && !isset($_GET['bd']) && !isset($_GET['wb'])) {

                $cartData = $cart->where('user_id', $userId)->findAll();

                foreach ($cartData as $value) {

                    $course_data = $course->select('old_price, new_price')->where('course_id', $value['course_id'])->first();

                    $bundling_data = $bundling->select('old_price, new_price')->where('bundling_id', $value['bundling_id'])->first();

                    $webinar_data = $webinar->select('old_price, new_price')->where('webinar_id', $value['webinar_id'])->first();



                    if ($course_data) {

                        $price = (isset($course_data['new_price'])) ? $course_data['new_price'] : $course_data['old_price'];
                    }



                    if ($bundling_data) {

                        $price = (isset($bundling_data['new_price'])) ? $bundling_data['new_price'] : $bundling_data['old_price'];
                    }



                    if ($webinar_data) {

                        $price = (isset($webinar_data['new_price'])) ? $webinar_data['new_price'] : $webinar_data['old_price'];
                    }



                    $subTotal = (int)$price + ((int)$price * 11 / 100);

                    $temp += $subTotal;
                }



                $getDiscount = 0;

                if (isset($_GET['c'])) {

                    $getCode = $_GET['c'];

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending'

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;



                $dataOrderCourse = [];

                $getCourseCart = $cart->select('course_id')->where('user_id', $userId)->where('course_id !=', NULL)->findAll();

                if ($getCourseCart != null) {

                    foreach ($getCourseCart as $value) {

                        $dataOrderCourse[] = [

                            'order_id' => $orderId,

                            'course_id' => $value['course_id'],

                        ];
                    }

                    $orderCourse->insertBatch($dataOrderCourse);

                    $data['type'] = 'coursebatch';

                    $data['data_order_course'] = $dataOrderCourse;
                }



                $dataOrderBundling = [];

                $getBundlingCart = $cart->select('bundling_id')->where('user_id', $userId)->where('bundling_id !=', NULL)->findAll();

                if ($getBundlingCart != null) {

                    foreach ($getBundlingCart as $value) {

                        $dataOrderBundling[] = [

                            'order_id' => $orderId,

                            'bundling_id' => $value['bundling_id'],

                        ];
                    }

                    $orderBundling->insertBatch($dataOrderBundling);

                    $data['type'] = 'bundlingbatch';

                    $data['data_order_bundling'] = $dataOrderBundling;
                }



                $dataOrderWebinar = [];

                $getWebinarCart = $cart->select('webinar_id')->where('user_id', $userId)->where('webinar_id !=', NULL)->findAll();

                if ($getWebinarCart != null) {

                    foreach ($getWebinarCart as $value) {

                        $dataOrderWebinar[] = [

                            'order_id' => $orderId,

                            'webinar_id' => $value['webinar_id'],

                        ];
                    }

                    $orderWebinar->insertBatch($dataOrderWebinar);

                    $data['type'] = 'webinarbatch';

                    $data['data_order_webinar'] = $dataOrderWebinar;
                }
            }



            if (isset($_GET['cr']) || isset($_GET['tr'])) {

                $idcourse = (empty($_GET['cr'])) ? $_GET['tr'] : $_GET['cr'];

                $verifyCourse = $course->select('old_price, new_price')->where("course_id", $idcourse)->first();

                if (!$verifyCourse) return 'data course tidak ditemukan';

                $price = (empty($verifyCourse['new_price'])) ? $verifyCourse['old_price'] : $verifyCourse['new_price'];

                $temp = $price + ($price * 11 / 100);



                $getDiscount = 0;

                if (isset($_GET['c'])) {

                    $getCode = $_GET['c'];

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();

                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending',

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;



                $dataOrderCourse = [

                    'order_id' => $orderId,

                    'course_id' => $idcourse

                ];

                $orderCourse->insert($dataOrderCourse);

                $data['type'] = 'course';

                $data['data_order_course'] = $dataOrderCourse;
            }



            if (isset($_GET['bd'])) {

                $verifyBundling = $bundling->select('old_price, new_price')->where("bundling_id", $_GET['bd'])->first();

                if (!$verifyBundling) return 'data bundling tidak ditemukan';

                $price = (empty($verifyBundling['new_price'])) ? $verifyBundling['old_price'] : $verifyBundling['new_price'];

                $temp = $price + ($price * 11 / 100);



                $getDiscount = 0;

                if (isset($_GET['c'])) {

                    $getCode = $_GET['c'];

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();

                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending',

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;



                $dataOrderBundling = [

                    'order_id' => $orderId,

                    'bundling_id' => $_GET['bd']

                ];

                $orderBundling->insert($dataOrderBundling);

                $data['type'] = 'bundling';

                $data['data_order_bundling'] = $dataOrderBundling;
            }


            if (isset($_GET['wb'])) {

                $verifyWebinar = $webinar->select('old_price, new_price')->where("webinar_id", $_GET['wb'])->first();

                if (!$verifyWebinar) return 'data webinar tidak ditemukan';

                $price = (empty($verifyWebinar['new_price'])) ? $verifyWebinar['old_price'] : $verifyWebinar['new_price'];

                $temp = $price + ($price * 11 / 100);



                $getDiscount = 0;

                if (isset($_GET['c'])) {

                    $getCode = $_GET['c'];

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();

                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending',

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;



                $dataOrderWebinar = [

                    'order_id' => $orderId,

                    'webinar_id' => $_GET['wb']

                ];

                $orderWebinar->insert($dataOrderWebinar);

                $data['type'] = 'webinar';

                $data['data_order_webinar'] = $dataOrderWebinar;
            }







            // $getCourse = $orderCourse->getData($orderId)->getResultArray();

            // $dataCourse = [];

            // if ($getCourse != null) {

            //     foreach ($getCourse as $value) {

            //         $dataCourse[] = [

            //             'id' => "c" . $value['order_course_id'],

            //             'name' => $value['title'],

            //             //'price' => $value['new_price'],

            //             'quantity' => 1

            //         ];

            //     }

            // }



            // $getBundling = $orderBundling->getData($orderId)->getResultArray();

            // $dataBundling = [];

            // if ($getBundling != null) {

            //     foreach ($getBundling as $value) {

            //         $dataBundling[] = [

            //             'id' => "b" . $value['order_bundling_id'],

            //             'name' => $value['title'],

            //             //'price' => (isset($value['new_price'])) ? $value['new_price'] : $value['price'],

            //             'quantity' => 1

            //         ];

            //     }

            // }



            // $cart->where('user_id', $userId)->delete();



            $transaction = [

                'order_id' => $orderId,

                'gross_amount' => $total

            ];



            $cust_detail = [

                'email' => $getUser['email'],

                'phone' => $getUser['phone_number']

            ];



            //$item = array_merge($dataBundling, $dataCourse);

            $params = [

                'transaction_details' => $transaction,

                'customer_details' => $cust_detail,

                //'item_details' => $item

            ];

            $token = \Midtrans\Snap::getSnapToken($params);



            // notifikasi

            $message = "Selamat pesanan anda berhasil dibuat";

            SendNotification(0, $userId, $message, 1);



            $data['token'] = $token;

            return $this->respond($data);

            //return view ('pages/transaction/snap-pay', ['token' => $token]);

        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function generateSnap2()

    {

        include 'SendNotification.php';

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        $data = [];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            // Set your Merchant Server Key

            \Midtrans\Config::$serverKey = getenv('mid_server_key');

            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).

            \Midtrans\Config::$isProduction = false;

            // Set sanitization on (default)

            \Midtrans\Config::$isSanitized = true;

            // Set 3DS transaction for credit card to true

            \Midtrans\Config::$is3ds = true;



            $cart = new Cart;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;

            $orderWebinar = new OrderWebinar;

            $user = new Users;

            $referral = new Referral;

            $referralUser = new ReferralUser;

            $voucher = new Voucher;

            $course = new Course;

            $bundling = new Bundling;

            $webinar = new Webinar;

            $temp = 0;

            $userId = $decoded->uid;

            //$userId = 17;

            $getUser = $user->where('id', $userId)->first();

            $type = $this->request->getJSON()->type;

            if ($type == 'cart') {

                $carts = $this->request->getJSON()->item_id;

                $cartData = $cart->whereIn('cart_id', $carts)->where('user_id', $userId)->findAll();

                // return $this->respond($cartData);

                foreach ($cartData as $value) {

                    $course_data = $course->select('old_price, new_price')->where('course_id', $value['course_id'])->first();

                    $bundling_data = $bundling->select('old_price, new_price')->where('bundling_id', $value['bundling_id'])->first();

                    $webinar_data = $webinar->select('old_price, new_price')->where('webinar_id', $value['webinar_id'])->first();


                    if ($course_data) {

                        $price = (isset($course_data['new_price'])) ? $course_data['new_price'] : $course_data['old_price'];
                    }



                    if ($bundling_data) {

                        $price = (isset($bundling_data['new_price'])) ? $bundling_data['new_price'] : $bundling_data['old_price'];
                    }



                    if ($webinar_data) {

                        $price = (isset($webinar_data['new_price'])) ? $webinar_data['new_price'] : $webinar_data['old_price'];
                    }



                    $subTotal = (int)$price + ((int)$price * 11 / 100);

                    $temp += $subTotal;
                }



                $getDiscount = 0;

                if ($this->request->getJSON()->code) {

                    $getCode = $this->request->getJSON()->code;

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending'

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;


                $data['item'] = [];



                $dataOrderCourse = [];

                $getCourseCart = $cart->select('course_id')->whereIn('cart_id', $carts)->where('user_id', $userId)->where('course_id !=', NULL)->findAll();

                if ($getCourseCart != null) {

                    foreach ($getCourseCart as $value) {

                        $order_item = $course->where('course_id', $value['course_id'])->first();

                        $dataOrderCourse[] = [

                            'order_id' => $orderId,

                            'course_id' => $value['course_id'],

                        ];

                        $data_course = [

                            'order_id' => $orderId,

                            'item_id' => $value['course_id'],

                            'type' => $order_item['service']

                        ];

                        $data['item'][] = $data_course;
                    }

                    $orderCourse->insertBatch($dataOrderCourse);

                    $data['type'] = 'coursebatch';

                    // $data['data_order_course'] = $dataOrderCourse;
                }



                $dataOrderBundling = [];

                $getBundlingCart = $cart->select('bundling_id')->whereIn('cart_id', $carts)->where('user_id', $userId)->where('bundling_id !=', NULL)->findAll();

                //var_dump($getBundlingCart);

                if ($getBundlingCart != null) {

                    foreach ($getBundlingCart as $value) {

                        $dataOrderBundling[] = [

                            'order_id' => $orderId,

                            'bundling_id' => $value['bundling_id'],

                        ];

                        $data_bundling = [

                            'order_id' => $orderId,

                            'item_id' => $value['bundling_id'],

                            'type' => 'bundling'

                        ];

                        $data['item'][] = $data_bundling;
                    }

                    $orderBundling->insertBatch($dataOrderBundling);

                    $data['type'] = 'bundlingbatch';

                    // $data['data_order_bundling'] = $dataOrderBundling;
                }



                $dataOrderWebinar = [];

                $getWebinarCart = $cart->select('webinar_id')->where('user_id', $userId)->where('webinar_id !=', NULL)->findAll();

                if ($getWebinarCart != null) {

                    foreach ($getWebinarCart as $value) {

                        $dataOrderWebinar[] = [

                            'order_id' => $orderId,

                            'webinar_id' => $value['webinar_id'],

                        ];

                        $data_webinar = [

                            'order_id' => $orderId,

                            'item_id' => $value['webinar_id'],

                            'type' => 'webinar'

                        ];

                        $data['item'][] = $data_webinar;
                    }

                    $orderWebinar->insertBatch($dataOrderWebinar);

                    $data['type'] = 'webinarbatch';

                    // $data['data_order_webinar'] = $dataOrderWebinar;
                }
            }


            if ($type == 'course' || $type == 'training') {

                $course_data = $course->select('old_price, new_price')->where('course_id', $this->request->getJSON()->item_id[0])->first();

                $price = (isset($course_data['new_price'])) ? $course_data['new_price'] : $course_data['old_price'];

                $subTotal = (int)$price + ((int)$price * 11 / 100);

                $temp += $subTotal;


                $getDiscount = 0;

                if ($this->request->getJSON()->code) {

                    $getCode = $this->request->getJSON()->code;

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending'

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;

                $data['item'] = [];

                $dataOrderCourse = [];

                $dataOrderCourse[] = [

                    'order_id' => $orderId,

                    'course_id' => $this->request->getJSON()->item_id[0],

                ];

                $data_course = [

                    'order_id' => $orderId,

                    'item_id' => $this->request->getJSON()->item_id[0],

                    'type' => $type

                ];

                $data['item'][] = $data_course;

                $orderCourse->insertBatch($dataOrderCourse);

                $data['type'] = 'coursebatch';
            }


            if ($type == 'bundling') {

                $bundling_data = $bundling->select('old_price, new_price')->where('bundling_id', $this->request->getJSON()->item_id[0])->first();

                $price = (isset($bundling_data['new_price'])) ? $bundling_data['new_price'] : $bundling_data['old_price'];

                $subTotal = (int)$price + ((int)$price * 11 / 100);

                $temp += $subTotal;


                $getDiscount = 0;

                if ($this->request->getJSON()->code) {

                    $getCode = $this->request->getJSON()->code;

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending'

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;

                $data['item'] = [];

                $dataOrderBundling = [];

                $dataOrderBundling[] = [

                    'order_id' => $orderId,

                    'bundling_id' => $this->request->getJSON()->item_id[0],

                ];

                $data_bundling = [

                    'order_id' => $orderId,

                    'item_id' => $this->request->getJSON()->item_id[0],

                    'type' => 'bundling'

                ];

                $data['item'][] = $data_bundling;

                $orderBundling->insertBatch($dataOrderBundling);

                $data['type'] = 'bundlingbatch';
            }


            if ($type == 'webinar') {

                $webinar_data = $webinar->select('old_price, new_price')->where('webinar_id', $this->request->getJSON()->item_id[0])->first();

                $price = (isset($webinar_data['new_price'])) ? $webinar_data['new_price'] : $webinar_data['old_price'];

                $subTotal = (int)$price + ((int)$price * 11 / 100);

                $temp += $subTotal;


                $getDiscount = 0;

                if ($this->request->getJSON()->code) {

                    $getCode = $this->request->getJSON()->code;

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $code = $verifyReferral['referral_code'];

                        $getDiscount = $verifyReferral['discount_price'];
                    } else if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $code = $verifyRefUser['referral_code'];

                            $getDiscount = $verifyRefUser['discount_price'];
                        } else {

                            $code = null;

                            $getDiscount = 0;
                        }
                    } else if ($verifyVoucher != NULL) {

                        $code = $verifyVoucher['code'];

                        $getDiscount = $verifyVoucher['discount_price'];
                    } else {

                        $code = null;
                    }
                } else {

                    $code = null;
                }



                if ($getDiscount > 0) {

                    $discount = ($getDiscount / 100) * $temp;

                    $total = ceil($temp - $discount);
                } else if ($getDiscount == 0) {

                    $total = $temp;
                }



                $orderId = rand();

                $dataOrder = [

                    'order_id'  => $orderId,

                    'user_id' => $userId,

                    'coupon_code' => $code,

                    'discount_price' => $getDiscount,

                    'sub_total' => $temp,

                    'gross_amount' => $total,

                    'transaction_status' => 'pending'

                ];

                // $order->insert($dataOrder);

                $data['data_order'] = $dataOrder;

                $data['item'] = [];

                $dataOrderWebinar = [];

                $dataOrderWebinar[] = [

                    'order_id' => $orderId,

                    'webinar_id' => $this->request->getJSON()->item_id[0],

                ];

                $data_webinar = [

                    'order_id' => $orderId,

                    'item_id' => $this->request->getJSON()->item_id[0],

                    'type' => 'webinar'

                ];

                $data['item'][] = $data_webinar;

                $orderWebinar->insertBatch($dataOrderWebinar);

                $data['type'] = 'webinarbatch';
            }

            // $getCourse = $orderCourse->getData($orderId)->getResultArray();

            // $dataCourse = [];

            // if ($getCourse != null) {

            //     foreach ($getCourse as $value) {

            //         $dataCourse[] = [

            //             'id' => "c" . $value['order_course_id'],

            //             'name' => $value['title'],

            //             //'price' => $value['new_price'],

            //             'quantity' => 1

            //         ];

            //     }

            // }



            // $getBundling = $orderBundling->getData($orderId)->getResultArray();

            // $dataBundling = [];

            // if ($getBundling != null) {

            //     foreach ($getBundling as $value) {

            //         $dataBundling[] = [

            //             'id' => "b" . $value['order_bundling_id'],

            //             'name' => $value['title'],

            //             //'price' => (isset($value['new_price'])) ? $value['new_price'] : $value['price'],

            //             'quantity' => 1

            //         ];

            //     }

            // }



            // $cart->where('user_id', $userId)->delete();



            $transaction = [

                'order_id' => $orderId,

                'gross_amount' => $total

            ];



            $cust_detail = [

                'email' => $getUser['email'],

                'phone' => $getUser['phone_number']

            ];



            //$item = array_merge($dataBundling, $dataCourse);

            $params = [

                'transaction_details' => $transaction,

                'customer_details' => $cust_detail,

                //'item_details' => $item

            ];

            $token = \Midtrans\Snap::getSnapToken($params);



            // notifikasi

            $message = "Selamat pesanan anda berhasil dibuat";

            SendNotification(0, $userId, $message, 1);



            $data['token'] = $token;


            // menyimpan data order ke database

            $orderModel = new Order;

            $primaryKey = 'order_id';



            $data_order = [

                'order_id' => $data['data_order']['order_id'],

                'snap_token' => $data['token'],

                'user_id'    => $data['data_order']['user_id'],

                'coupon_code' => $data['data_order']['coupon_code'],

                'discount_price'    => $data['data_order']['discount_price'],

                'sub_total' => $data['data_order']['sub_total'],

                'gross_amount' => $data['data_order']['gross_amount'],

                'transaction_status' => 'pending'

            ];

            $orderModel->insert($data_order);

            return $this->respond($data);

            //return view ('pages/transaction/snap-pay', ['token' => $token]);

        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function sendPush($to, $title, $body, $icon, $url)

    {

        $postdata = json_encode(

            [

                'notification' =>

                [

                    'title' => $title,

                    'body' => $body,

                    'icon' => $icon,

                    'click_action' => $url

                ],

                'to' => $to

            ]

        );


        $opts = array(

            'http' =>

            array(

                'method'  => 'POST',

                'header'  => 'Content-type: application/json' . "\r\n"

                    . 'Authorization: key=' . getenv('fcm_auth_key') . "\r\n",

                'content' => $postdata

            )

        );

        $context  = stream_context_create($opts);

        $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
    }



    public function notifHandler()

    {

        // Set your Merchant Server Key

        \Midtrans\Config::$serverKey = getenv('mid_server_key');

        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).

        \Midtrans\Config::$isProduction = false;

        // Set sanitization on (default)

        \Midtrans\Config::$isSanitized = true;

        // Set 3DS transaction for credit card to true

        \Midtrans\Config::$is3ds = true;


        $order = new Order;

        $order_course = new OrderCourse;

        $order_bundling = new OrderBundling;

        $order_webinar = new OrderWebinar;

        $user_course = new UserCourse;

        $user_webinar = new UserWebinar;

        $referral = new Referral;

        $referralUser = new ReferralUser;

        $voucher = new Voucher;

        $cart = new Cart;

        $user = new Users;



        $notif = new \Midtrans\Notification();

        //var_dump($notif);

        $notif = $notif->getResponse();

        $transaction = $notif->transaction_status;

        $fraud = $notif->fraud_status;

        include 'SendNotification.php';



        error_log("Order ID $notif->order_id: " . "transaction status = $transaction, fraud status = $fraud");


        $dataUpdate['payment_type'] = $this->request->getVar("payment_type");

        $dataUpdate['transaction_id'] = $this->request->getVar("transaction_id");



        $id_user = $order->where('order_id', $this->request->getVar("order_id"))->first();

        $data_user = $user->where('id', $id_user['user_id'])->first();


        if ($transaction == 'capture') {

            // For credit card transaction, we need to check whether transaction is challenge by FDS or not

            if ($type == 'credit_card') {

                if ($fraud == 'challenge') {

                    // TODO set payment status in merchant's database to 'Challenge by FDS'

                    // TODO merchant should decide whether this transaction is authorized or not in MAP

                    // echo "Transaction order_id: " . $notif->order_id . " is challenged by FDS";
                } else {

                    // TODO set payment status in merchant's database to 'Success'

                    // echo "Transaction order_id: " . $notif->order_id . " successfully captured using " . $notif->payment_type;
                }
            }
        } else if ($transaction == 'settlement') {

            $status = 'paid';

            // TODO set payment status in merchant's database to 'Settlement'

            // echo "Transaction order_id: " . $notif->order_id . " successfully transfered using " . $notif->payment_type;

            $id = $this->request->getVar("order_id");

            //$id = 916110939;



            //update order status

            // $dataUpdate = [

            //     "order_id" => $this->request->getVar("order_id"),

            //     "transaction_status" => $status,

            //     "transaction_id" => $this->request->getVar("transaction_id"),

            // ];

            $dataUpdate['order_id'] = $this->request->getVar("order_id");

            $dataUpdate['transaction_status'] = $status;

            $dataUpdate['transaction_id'] = $this->request->getVar("transaction_id");


            $order_data = $order->where('order_id', $this->request->getVar("order_id"))->first();

            if ($order_data['transaction_status'] != 'paid') {

                $order->update($id, $dataUpdate);



                //update referral information

                $coupon = $order->select('user_id, coupon_code')->where("order_id", $id)->first();

                if (isset($coupon)) {

                    $getCode = $coupon['coupon_code'];

                    $verifyReferral = $referral->where("referral_code", $getCode)->first();

                    $verifyRefUser = $referralUser->where("referral_code", $getCode)->first();

                    $verifyVoucher = $voucher->where("code", $getCode)->first();



                    if ($verifyReferral != NULL) {

                        $verifyReferral['referral_user'] += 1;

                        do {

                            $ref_code = strtoupper(bin2hex(random_bytes(4)));

                            $check_code = $referral->where('referral_code', $ref_code)->first();

                            $check_code2 = $referralUser->where('referral_code', $ref_code)->first();
                        } while ($check_code || $check_code2);



                        $data = [

                            'referral_user' => $verifyReferral['referral_user'],

                        ];



                        $ref_used = [

                            'referral_id' => $verifyReferral['referral_id'],

                            'user_id' => $coupon['user_id'],

                            'referral_code' => $ref_code,

                            'discount_price' => 15

                        ];



                        $referral->update($verifyReferral['referral_id'], $data);

                        $referralUser->save($ref_used);
                    }



                    if ($verifyRefUser != NULL) {

                        if ($verifyRefUser['is_active'] == 0) {

                            $used = [

                                'is_active' => 1

                            ];

                            $referralUser->update($verifyRefUser['referral_user_id'], $used);
                        }
                    }



                    if ($verifyVoucher != NULL) {

                        $verifyVoucher['quota'] -= 1;



                        $data = [

                            'quota' => $verifyVoucher['quota'],

                        ];



                        $voucher->update($verifyVoucher['voucher_id'], $data);
                    }
                }



                $getOrderData = $order

                    ->select('*')

                    ->where('order_id', $id)

                    ->findAll();



                //add usercourse

                $userCourse = [];

                $course = $order_course->select('course_id')->where('order_id', $id)->findAll();

                if ($course != null) {

                    foreach ($course as $value) {

                        $userCourse[] = [

                            'user_id' => $getOrderData[0]["user_id"],

                            'course_id' => $value['course_id'],

                            'is_access' => '0'

                        ];

                        $cart->where('user_id', $getOrderData[0]["user_id"])->where('course_id', $value['course_id'])->delete();
                    }

                    $user_course->insertBatch($userCourse);
                }



                $userBundling = [];

                $bundling = $order_bundling

                    ->select('bundling_id')

                    ->where('order_id', $id)

                    ->findAll();

                if ($bundling != null) {

                    foreach ($bundling as $value) {

                        $userBundling[] = [

                            'user_id' => $getOrderData[0]["user_id"],

                            'bundling_id' => $value['bundling_id'],

                            'is_access' => '0'

                        ];

                        $cart->where('user_id', $getOrderData[0]["user_id"])->where('bundling_id', $value['bundling_id'])->delete();
                    }

                    $user_course->insertBatch($userBundling);
                }



                $userWebinar = [];

                $webinar = $order_webinar

                    ->select('webinar_id')

                    ->where('order_id', $id)

                    ->findAll();

                if ($webinar != null) {

                    foreach ($webinar as $value) {

                        $userWebinar[] = [

                            'user_id' => $getOrderData[0]["user_id"],

                            'webinar_id' => $value['webinar_id']

                        ];

                        $cart->where('user_id', $getOrderData[0]["user_id"])->where('webinar_id', $value['webinar_id'])->delete();
                    }

                    $user_webinar->insertBatch($userWebinar);
                }

                // kirim notifikasi

                $message = "Pembayaran berhasil. Terima kasih telah menggunakan layanan kami. Selamat belajar.";

                SendNotification(0, $data_user['id'], $message, 1);

                if ($data_user['device_token']) {

                    $this->sendPush($data_user['device_token'], 'Pembayaran berhasil!', $message, site_url() . 'image/logo.svg', site_url());
                }
                //return 0;

                $getOrder = [

                    "order_id" => $getOrderData[0]["order_id"],

                    "checkout_date" => $getOrderData[0]["transaction_time"],

                    "sub_total" => $getOrderData[0]["sub_total"],

                    "total" => $getOrderData[0]["gross_amount"],

                    "discount_price" => $getOrderData[0]["discount_price"],

                    "payment_type" => $this->request->getVar("payment_type"),

                    "transaction_time" => $this->request->getVar("transaction_time")

                ];



                $getCourse = $order_course

                    ->select('course.title, course.new_price')

                    ->where('order_course.order_id', $id)

                    ->join('course', 'order_course.course_id=course.course_id')

                    ->findAll();



                $getCourse['getCourse'] = $getCourse;



                $getBundling = $order_bundling

                    ->select('bundling.title, bundling.new_price')

                    ->where('order_bundling.order_id', $id)

                    ->join('bundling', 'order_bundling.bundling_id=bundling.bundling_id')

                    ->findAll();



                $getBundling['getBundling'] = $getBundling;



                $getWebinar = $order_webinar

                    ->select('webinar.title, webinar.new_price')

                    ->where('order_webinar.order_id', $id)

                    ->join('webinar', 'order_webinar.webinar_id=webinar.webinar_id')

                    ->findAll();



                $getWebinar['getWebinar'] = $getWebinar;



                $data = array_merge($getCourse, $getBundling, $getWebinar, $getOrder);



                //send receipt payment

                $getEmail = $order

                    ->select('users.email')

                    ->where('order.order_id', $id)

                    ->join('users', 'order.user_id=users.id')

                    ->findAll();





                $subject = 'Pembelian Selesai';

                $getEmail = $getEmail[0]["email"];

                //return view('/html_email/payment_success.html',$data);



                $message = view('/html_email/payment_success.html', $data);

                $email = \Config\Services::email();

                $email->setTo($getEmail);

                $email->setFrom('stufastlearningcenter@gmail.com', 'Pembelian berhasil');

                $email->setSubject($subject);

                $email->setMessage($message);



                $email->send();
            }
        } else if ($transaction == 'pending') {

            $status = 'pending';

            // TODO set payment status in merchant's database to 'Pending'

            // echo "Waiting customer to finish transaction order_id: " . $notif->order_id . " using " . $notif->payment_type;
        } else if ($transaction == 'deny') {

            $order = new Order;

            $status = 'cancel';

            $order_data = $order->where('order_id', $this->request->getVar("order_id"))->first();

            $dataUpdate = [

                "transaction_status" => $status

            ];

            if ($order_data['transaction_status'] != 'cancel') {

                $order->update($this->request->getVar("order_id"), $dataUpdate);


                // kirim notifikasi

                $message = "Pembayaran ditolak, silahkan lakukan pemesanan ulang";

                SendNotification(0, $data_user['id'], $message, 2);

                if ($data_user['device_token']) {

                    $this->sendPush($data_user['device_token'], 'Pembayaran ditolak', $message, site_url() . 'image/logo.svg', site_url());
                }
            }

            // TODO set payment status in merchant's database to 'Denied'

            // echo "Payment using " . $type . " for transaction order_id: " . $notif->order_id . " is denied.";
        } else if ($transaction == 'expire') {

            $order = new Order;

            $status = 'cancel';

            $order_data = $order->where('order_id', $this->request->getVar("order_id"))->first();

            $dataUpdate = [

                "transaction_status" => $status

            ];

            if ($order_data['transaction_status'] != 'cancel') {

                $order->update($this->request->getVar("order_id"), $dataUpdate);


                // kirim notifikasi

                $message = "Pesanan anda melewati batas waktu pembayaran";

                SendNotification(0, $data_user['id'], $message, 2);

                if ($data_user['device_token']) {

                    $this->sendPush($data_user['device_token'], 'Pesanan kadaluarsa', $message, site_url() . 'image/logo.svg', site_url());
                }
            }

            // TODO set payment status in merchant's database to 'expire'

            // echo "Payment using " . $type . " for transaction order_id: " . $notif->order_id . " is expired.";
        } else if ($transaction == 'cancel') {

            $order = new Order;

            $status = 'cancel';

            $order_data = $order->where('order_id', $this->request->getVar("order_id"))->first();

            $dataUpdate = [

                "transaction_status" => $status

            ];

            if ($order_data['transaction_status'] != 'cancel') {

                $order->update($this->request->getVar("order_id"), $dataUpdate);


                $message = "Pesanan anda berhasil dibatalkan. Pilih course lainya di stufast.id";

                SendNotification(0, $data_user['id'], $message, 2);

                if ($data_user['device_token']) {

                    $this->sendPush($data_user['device_token'], 'Pesanan dibatalkan', $message, site_url() . 'image/logo.svg', site_url());
                }
            }

            // TODO set payment status in merchant's database to 'Denied'

            // echo "Payment using " . $type . " for transaction order_id: " . $notif->order_id . " is canceled.";
        }
    }



    public function create()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $result = $this->request->getVar('result');

            $checkout_detail = $this->request->getVar('checkout_detail');



            // post to db 

            $orderModel = new Order;

            $primaryKey = 'order_id';



            $data = [

                // 'result' => $result,

                // 'checkout_detail' => $checkout_detail,



                // 'order_id' => $result['order_id'],

                'snap_token' => $checkout_detail['token'],

                'user_id'    => $checkout_detail['data_order']['user_id'],

                'coupon_code' => $checkout_detail['data_order']['coupon_code'],

                'discount_price'    => $checkout_detail['data_order']['discount_price'],

                'sub_total' => $checkout_detail['data_order']['sub_total'],

                'gross_amount' => $checkout_detail['data_order']['gross_amount'],



                // 'payment_type' => $result['payment_type'],




                // 'transaction_status'    => $result['transaction_status'],

                // 'transaction_id' => $result['transaction_id'],

                // 'transaction_time' => $result['transaction_time'],

            ];



            if (isset($result['order_id'])) {

                $data['order_id'] = $result['order_id'];
            }

            if (isset($result['pdf_url'])) {

                $data['url_slip'] = $result['pdf_url'];
            }

            if (isset($result['payment_type'])) {

                $data['payment_type'] = $result['payment_type'];
            }

            if (isset($result['transaction_status'])) {

                $data['transaction_status'] = $result['transaction_status'];
            }

            if (isset($result['transaction_id'])) {

                $data['transaction_id'] = $result['transaction_id'];
            }

            if (isset($result['transaction_time'])) {

                $data['transaction_time'] = $result['transaction_time'];
            }



            if ($result['payment_type'] == 'bank_transfer') {

                // bca, bni, bri

                if (isset($result['va_numbers'])) {

                    $data += [

                        'bank' => $result['va_numbers'][0]['bank'],

                        'va_number' => $result['va_numbers'][0]['va_number'],

                    ];
                } else {

                    // permata 

                    $data += [

                        'bank' => 'permata',

                        'va_number' => $result['permata_va_number'],

                    ];
                }
            } else if ($result['payment_type'] == 'echannel') {

                // mandiri 

                $data += [

                    'bank' => 'mandiri',

                    'bill_key' => $result['bill_key'],

                    'biller_code' => $result['biller_code'],

                ];
            }



            // Inserts data and returns inserted row's primary key

            // $orderModel->insert($data);



            // Inserts data and returns true on success and false on failure

            $orderModel->insert($data);



            // Returns inserted row's primary key

            // $orderModel->getInsertID();



            $response = [

                'status' => '200',

                'message' => 'Success',

            ];





            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function cancel()
    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        include 'SendNotification.php';



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $order = new Order;

            $user = new Users;

            $data_user = $user->where('id', $decoded->uid)->first();

            $data = $order->where('user_id', $decoded->uid)->where('order_id', $this->request->getVar('order_id'))->first();

            if ($data) {

                $input = [

                    'transaction_status' => 'cancel'

                ];

                $order->update($data['order_id'], $input);

                $message = "Pesanan anda berhasil dibatalkan. Pilih course lainya di stufast.id";

                SendNotification(0, $data_user['id'], $message, 2);

                if ($data_user['device_token']) {

                    $this->sendPush($data_user['device_token'], 'Pesanan dibatalkan', $message, site_url() . 'image/logo.svg', site_url());
                }

                $response = [

                    'status' => '200',

                    'success' => '200',

                    'messages' => 'Data berhasil dibatalkan',

                ];

                return $this->respond($response);
            } else {

                return $this->fail("Pesanan tidak ditemukan.");
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    public function getOrderByMember($status)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;

            $orderWebinar = new OrderWebinar;

            $course = new Course;

            $bundling = new Bundling;

            $webinar = new Webinar;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'author' || $data['role'] == 'admin') {

                return $this->fail('Hanya dapat di akses oleh member', 400);
            }


            $dataOrder = $order

                ->where('user_id', $decoded->uid)

                ->where('transaction_status', $status)

                ->orderBy('created_at', 'desc')

                ->findAll();


            foreach ($dataOrder as &$value) {

                $dataOrderCourse = $orderCourse->where('order_id', $value['order_id'])->findAll();

                $dataOrderBundling = $orderBundling->where('order_id', $value['order_id'])->findAll();

                $dataOrderWebinar = $orderWebinar->where('order_id', $value['order_id'])->findAll();

                $itemList = [];

                foreach ($dataOrderCourse as $data) {

                    $include = $course->where('course_id', $data['course_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'thumbnail' => site_url() . 'upload/course/thumbnail/' . $include['thumbnail'],

                        'type' => ucfirst($include['service'])
                    ];
                }

                foreach ($dataOrderBundling as $data) {

                    $include = $bundling->where('bundling_id', $data['bundling_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'thumbnail' => site_url() . 'upload/bundling/' . $include['thumbnail'],

                        'type' => 'Bundling'
                    ];
                }

                foreach ($dataOrderWebinar as $data) {

                    $include = $webinar->where('webinar_id', $data['webinar_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'thumbnail' => site_url() . 'upload/webinar/' . $include['thumbnail'],

                        'type' => 'Webinar'
                    ];
                }

                $amount = count($itemList);

                $value['total_item'] = $amount;

                $value['item'] = $itemList;
            }

            $response = $dataOrder;

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }

    public function getOrderById($order_id)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;

            $orderWebinar = new OrderWebinar;

            $course = new Course;

            $bundling = new Bundling;

            $webinar = new Webinar;

            $raw_price = 0;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'author' || $data['role'] == 'admin') {

                return $this->fail('Hanya dapat di akses oleh member', 400);
            }

            $dataOrder = $order

                ->select('users.fullname, users.email, users.phone_number, order.*')

                ->join('users', 'order.user_id = users.id')

                ->where('user_id', $decoded->uid)

                ->where('order_id', $order_id)

                ->first();


            if ($dataOrder) {

                $dataOrderCourse = $orderCourse->where('order_id', $dataOrder['order_id'])->findAll();

                $dataOrderBundling = $orderBundling->where('order_id', $dataOrder['order_id'])->findAll();

                $dataOrderWebinar = $orderWebinar->where('order_id', $dataOrder['order_id'])->findAll();

                $itemList = [];

                foreach ($dataOrderCourse as $data) {

                    $include = $course->where('course_id', $data['course_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => ucfirst($include['service'])
                    ];

                    $raw_price += $include['new_price'];
                }

                foreach ($dataOrderBundling as $data) {

                    $include = $bundling->where('bundling_id', $data['bundling_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => 'Bundling'
                    ];

                    $raw_price += $include['new_price'];
                }

                foreach ($dataOrderWebinar as $data) {

                    $include = $webinar->where('webinar_id', $data['webinar_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => 'Webinar'
                    ];

                    $raw_price += $include['new_price'];
                }

                $amount = count($itemList);

                $dataOrder['raw_price'] = $raw_price;

                $dataOrder['tax'] = $raw_price * 0.11;

                $dataOrder['discount_amount'] = $dataOrder['sub_total'] - $dataOrder['gross_amount'];

                $dataOrder['total_item'] = $amount;

                $dataOrder['item'] = $itemList;
            }

            $response = $dataOrder;

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }


    public function invoiceData($order_id, $user_id)

    {

        try {

            $user = new Users;

            $order = new Order;

            $orderCourse = new OrderCourse;

            $orderBundling = new OrderBundling;

            $orderWebinar = new OrderWebinar;

            $course = new Course;

            $bundling = new Bundling;

            $webinar = new Webinar;

            $raw_price = 0;

            $dataOrder = $order

                ->select('users.fullname, users.email, users.phone_number, order.*')

                ->join('users', 'order.user_id = users.id')

                ->where('user_id', $user_id)

                ->where('order_id', $order_id)

                ->first();


            if ($dataOrder) {

                $dataOrderCourse = $orderCourse->where('order_id', $dataOrder['order_id'])->findAll();

                $dataOrderBundling = $orderBundling->where('order_id', $dataOrder['order_id'])->findAll();

                $dataOrderWebinar = $orderWebinar->where('order_id', $dataOrder['order_id'])->findAll();

                $itemList = [];

                foreach ($dataOrderCourse as $data) {

                    $include = $course->where('course_id', $data['course_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => ucfirst($include['service'])
                    ];

                    $raw_price += $include['new_price'];
                }

                foreach ($dataOrderBundling as $data) {

                    $include = $bundling->where('bundling_id', $data['bundling_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => 'Bundling'
                    ];

                    $raw_price += $include['new_price'];
                }

                foreach ($dataOrderWebinar as $data) {

                    $include = $webinar->where('webinar_id', $data['webinar_id'])->first();

                    $itemList[] = [

                        'title' => $include['title'],

                        'price' => $include['new_price'],

                        'type' => 'Webinar'
                    ];

                    $raw_price += $include['new_price'];
                }

                $amount = count($itemList);

                $dataOrder['raw_price'] = $raw_price;

                $dataOrder['tax'] = $raw_price * 0.11;

                $dataOrder['discount_amount'] = $dataOrder['sub_total'] - $dataOrder['gross_amount'];

                $dataOrder['total_item'] = $amount;

                $dataOrder['item'] = $itemList;

                $data = [

                    'status' => 'success',

                    'message' => 'Data ditemukan',

                    'data' => $dataOrder

                ];
            } else {

                $data = [

                    'status' => 'error',

                    'message' => 'Data tidak ditemukan'

                ];
            }

            return $data;
        } catch (\Throwable $th) {

            return $th;
        }
    }


    public function webView()
    {
        $data = [

            'token' => 'kosong'

        ];

        if (isset($_GET['token'])) {

            $data['token'] = $_GET['token'];
        }

        return view('pages/transaction/web-view-payment', $data);
    }
}
