<?php



use App\Models\Notification;

use App\Models\UserNotification;

use App\Models\Users;



function SendNotification($public, $userId, $message, $category)

{

    $notificationModel = new Notification();

    $userNotif = new UserNotification();

    $users = new Users();



    if ($public == 1) {

        $notificationModel->insert([

            'public' => $public,

            'notification_category_id' => $category,

            'message' => $message,

        ]);

        $user = $users->findAll();

        foreach ($user as $value) {

            $userNotif->insert([

                'user_id' => $value['id'],

                'notification_id' => $notificationModel->getInsertID(),

                'read' => 0,

            ]);
        }

        $response = [

            'status'   => 201,

            'success'    => 201,

            'messages' => [

                'success' => 'Notification public berhasil dibuat'

            ]

        ];
    } else {

        $notificationModel->insert([

            'public' => $public,

            'notification_category_id' => $category,

            'message' => $message,

        ]);

        $userNotif->insert([

            'user_id' => $userId,

            'notification_id' => $notificationModel->getInsertID(),

            'read' => 0,

        ]);

        $response = [

            'status'   => 201,

            'success'    => 201,

            'messages' => [

                'success' => 'Notification berhasil dibuat'

            ]

        ];
    }

    return $response;
};
