<?php



namespace App\Controllers\Api;



use CodeIgniter\RESTful\ResourceController;

use App\Models\Notification;

use App\Models\UserNotification;

use App\Models\Users;

use App\Models\Order;

use App\Models\OrderBundling;

use App\Models\OrderCourse;

use App\Models\UserCourse;

use CodeIgniter\HTTP\RequestInterface;

use Firebase\JWT\JWT;

use CodeIgniter\API\ResponseTrait;

use CodeIgniter\HTTP\IncomingRequest;



class NotificationController extends ResourceController

{

    use ResponseTrait;

    /**

     * Return an array of resource objects, themselves in array format

     *

     * @return mixed

     */

    public function index()

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;

            $modelnotification = new Notification();

            $modelusernotification = new UserNotification();

            $data = [];

            $data['unread'] = 0;


            $private_notification = $modelusernotification

                ->select('user_notification.*, notification.message, notification.public, notification.notification_category_id, notification_category.thumbnail, notification_category.link')

                ->join('notification', 'notification.notification_id = user_notification.notification_id')

                ->join('notification_category', 'notification.notification_category_id = notification_category.notification_category_id', 'left')

                ->where('user_notification.user_id', $decoded->uid)

                ->orderBy('user_notification.created_at', 'DESC')

                ->limit(30)

                ->find();



            $data['notification'] = [];

            if (count($private_notification) != 0) {

                for ($i = 0; $i < count($private_notification); $i++) {

                    if ($private_notification[$i]['read'] == 0) {

                        $data['unread'] += 1;
                    }

                    if ($private_notification[$i]['notification_category_id'] == null) {

                        $private_notification[$i]['link'] = site_url();

                        $private_notification[$i]['thumbnail'] = site_url() . 'image/notif/default.png';
                    } else {

                        $private_notification[$i]['link'] = site_url() . $private_notification[$i]['link'];

                        $private_notification[$i]['thumbnail'] = site_url() . 'image/notif/' . $private_notification[$i]['thumbnail'];
                    }

                    array_push($data['notification'], $private_notification[$i]);
                }
            }

            return $this->respond($data);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function read($id)
    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];

        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $modelusernotification = new UserNotification();

            if ($id == 'all') {

                $user_notif = $modelusernotification->where('user_id', $decoded->uid)->where('read', 0)->findAll();

                if ($user_notif) {

                    foreach ($user_notif as $notif) {

                        $modelusernotification->update($notif['user_notification_id'], ['read' => 1]);
                    }
                }
            } else {

                $cek = $modelusernotification->where('user_id', $decoded->uid)->where('user_notification_id', $id)->first();

                if (!$cek) return $this->failForbidden('User tidak memiliki akses terhadap data ini');

                $modelusernotification->update($id, ['read' => 1]);
            }

            $response = [

                'status'   => 200,

                'success'    => 200,

                'messages' => [

                    "success" => 'Notifikasi berhasil dibaca'

                ]

            ];

            return $this->respond($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }
    }



    /**

     * Return the properties of a resource object

     *

     * @return mixed

     */

    public function show($id = null)

    {

        //

    }



    /**

     * Return a new resource object, with default properties

     *

     * @return mixed

     */

    public function new()

    {

        //

    }



    /**

     * Create a new resource object, from "posted" parameters

     *

     * @return mixed

     */

    public function create()

    {

        include 'SendNotification.php';



        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $rules = [

                'public' => 'required|numeric',

                'notification_category_id' => 'required|numeric',

                'message' => 'required|min_length[8]',

            ];



            $messages = [

                "public" => [

                    "required" => "{field} tidak boleh kosong",

                    'numeric' => '{field} harus bernilaikan 0 (private) atau 1 (publik)'

                ],

                "notification_category_id" => [

                    "required" => "{field} tidak boleh kosong",

                    'numeric' => '{field} harus bernilaikan id notification category'

                ],

                "message" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

            ];



            if ($this->validate($rules, $messages)) {



                $message = $this->request->getVar('message');

                $category = $this->request->getVar('notification_category_id');

                if ($this->request->getVar('public') == 0) {



                    $user_id = $this->request->getVar('user_id');



                    $sendNotification = SendNotification(0, $user_id, $message, $category);



                    return $this->respond($sendNotification);
                } else {



                    $sendNotification = SendNotification(1, null, $message, $category);



                    return $this->respond($sendNotification);
                }
            } else {

                $response = [

                    'status'   => 400,

                    'error'    => 400,

                    'messages' => $this->validator->getErrors(),

                ];



                return $this->respond($response, 400);
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    /**

     * Return the editable properties of a resource object

     *

     * @return mixed

     */

    public function edit($id = null)

    {

        //

    }



    /**

     * Add or update a model resource, from "posted" properties

     *

     * @return mixed

     */

    public function update($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];





        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $model = new Notification();



            $rules = [

                'message' => 'required|min_length[8]',

            ];



            $messages = [

                "message" => [

                    "required" => "{field} tidak boleh kosong",

                    'min_length' => '{field} minimal 8 karakter'

                ],

            ];



            if ($model->find($id)) {

                if ($this->validate($rules, $messages)) {

                    if ($this->request->getRawInput('user_id')) {

                        $data = [

                            'user_id' => $this->request->getRawInput('user_id'),

                            'message' => $this->request->getRawInput('message'),

                        ];
                    } else {

                        $data = [

                            'message' => $this->request->getRawInput('message'),

                        ];
                    }

                    $model->update($id, $data['user_id']);



                    $response = [

                        'status'   => 201,

                        'success'    => 201,

                        'messages' => [

                            'success' => 'Notification berhasil di perbarui'

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

                    'messages' => 'Data tidak ditemukan',

                ];
            }



            return $this->respondCreated($response);
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    /**

     * Delete the designated resource object from the model

     *

     * @return mixed

     */

    public function delete($id = null)

    {

        $key = getenv('TOKEN_SECRET');

        $header = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$header) return $this->failUnauthorized('Akses token diperlukan');

        $token = explode(' ', $header)[1];



        try {

            $decoded = JWT::decode($token, $key, ['HS256']);

            $user = new Users;



            $data = $user->select('role')->where('id', $decoded->uid)->first();

            if ($data['role'] == 'member') {

                return $this->fail('Tidak dapat di akses oleh member', 400);
            }



            $model = new Notification();



            if ($model->find($id)) {

                $model->delete($id);



                $response = [

                    'status'   => 200,

                    'success'    => 200,

                    'messages' => [

                        'success' => 'Notification berhasil di hapus'

                    ]

                ];

                return $this->respondDeleted($response);
            } else {

                return $this->failNotFound('Data tidak di temukan');
            }
        } catch (\Throwable $th) {

            return $this->fail($th->getMessage());
        }

        return $this->failNotFound('Data user tidak ditemukan');
    }



    public function sendnotification($user_id = null, $message = null)

    {

        $modelnotification = new Notification();

        $modelusernotification = new UserNotification();



        if ($user_id == null) {

            $data = [

                'public' => 1,

                'message' => $message

            ];

            $modelnotification->insert($data);
        } else {

            $data = [

                'public' => 0,

                'message' => ''

            ];

            $modelnotification->insert($data);



            $datauser = [

                'user_id' => $user_id,

                'message' => $message

            ];

            $modelusernotification->insert($datauser);
        }
    }



    public function midtrans()

    {

        // Mengambil data mentah dari permintaan

        $data = json_decode(file_get_contents('php://input'), true);

        // $request = service('request');



        // Mengubahnya menjadi objek PHP

        // $data = json_decode($json);



        $orderBundling = new OrderBundling();

        $orderCourse = new OrderCourse();

        $order = new Order();

        $userCourse = new UserCourse();



        $getOrder = $order->where('order_id', $data['order_id'])->first();



        if (isset($getOrder)) {



            if ($data['status_code'] == "200") {

                $getOrderBundling = $orderBundling->where('order_id', $data['order_id'])->findAll();

                $getOrderCourse = $orderCourse->where('order_id', $data['order_id'])->findAll();



                if ($getOrderBundling != NULL) {

                    foreach ($getOrderBundling as $value) {

                        $dataOrderBundling = [

                            'user_id' => $getOrder["user_id"],

                            'bundling_id' => $value['bundling_id'],

                            'is_access' => "1",

                        ];
                    }

                    if (count($getOrderBundling) > 1) {

                        $userCourse->insertBatch($dataOrderBundling);
                    } else {

                        $userCourse->insert($dataOrderBundling);
                    }
                }



                if ($getOrderCourse != NULL) {

                    foreach ($getOrderCourse as $value) {

                        $dataOrderCourse = [

                            'user_id' => $getOrder["user_id"],

                            'course_id' => $value['course_id'],

                            'is_access' => "1",

                        ];
                    }

                    if (count($getOrderBundling) > 1) {

                        $userCourse->insertBatch($dataOrderCourse);
                    } else {

                        $userCourse->insert($dataOrderCourse);
                    }
                }

                $dataUpdate = [

                    "transaction_status" => $data['transaction_status'],

                ];

                $order->update($data['order_id'], $dataUpdate);
            } else {

                $dataUpdate = [

                    "transaction_status" => $data['transaction_status'],

                ];

                $order->update($data['order_id'], $dataUpdate);
            }
        }
    }



    public function sendPush()

    {

        $to = $this->request->getVar("to");

        $title = $this->request->getVar("title");

        $body = $this->request->getVar("body");

        $icon = $this->request->getVar("icon");

        $url = $this->request->getVar("url");

        define('FCM_AUTH_KEY', 'AAAAplDlCPE:APA91bHiGzVg5xI5QLrmlSwgGAsotDjka5j6JYS6KjcnnKu-8dJ9hh4nWiVKQQmBbyMWLxJ6xA0itmUJc68pH2v0zH7G7qYnIlW4ElZOMBo1RIh8fjXXAjSrJtg-f4YZBM6xWiw1fWPr');

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

                    . 'Authorization: key=' . FCM_AUTH_KEY . "\r\n",

                'content' => $postdata

            )

        );

        $context  = stream_context_create($opts);

        $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
    }
}
