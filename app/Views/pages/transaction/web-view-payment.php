<html>

<head>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->

  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo getenv('mid_client_key'); ?>"></script>

  <!-- <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-qsGN92Rh94gid13G"></script> -->

  <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->

</head>



<body>

  <!-- jquery -->

  <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>



  <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

  <script type="text/javascript">
    let token = '<?php echo $token; ?>';

    $(document).ready(() => {

      const sendPush = async (to, title, body, icon, url) => {

        try {

          let option = {

            type: 'POST',

            url: `/api/notification/push`,

            dataType: 'json',

            data: {

              'to': to,

              'title': title,

              'body': body,

              'icon': icon,

              'url': url

            },

            success: function(res) {

              data = console.log(res);

            },

          };

          let data;

          await $.ajax(option);

          return data;

        } catch (error) {

          console.log(error);

        }

      };

      window.snap.pay(token, {

        onSuccess: function(result) {

          window.location.href = "/snap/sukses";

          // sendPush('fAsSJ5_2RpGTtwtIzqmARZ:APA91bEIzFAR2TGZ5NStYPcPSLZ9CeaPis1WFWeqdseLR3qE5ft74EpNACzETSW7AZM9f3PotH0igJ7qk9ct5sytduo3JBKXpA9svixwLBmcyimRoZ9LTETUeljBEvBeD_0EEwy7TLEK', 'Pembayaran berhasil!', 'Terima kasih telah menggunakan layanan kami. Selamat belajar.', 'https://dev.stufast.id/image/logo.svg', 'https://dev.stufast.id')


          // alert("payment success!"); console.log(result);

          // console.log(checkout_detail);

        },

        onPending: function(result) {

          window.location.href = "/snap/batal";

          // alert("wating your payment!"); console.log(result);

          // console.log(checkout_detail);

        },

        onError: function(result) {

          window.location.href = "/snap/batal";

          // alert("payment failed!"); console.log(result);

          // console.log(checkout_detail);

        },

        onClose: function() {

          window.location.href = "/snap/batal";

          // console.log(checkout_detail);

        }

      });


    });
  </script>

</body>

</html>