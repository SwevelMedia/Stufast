<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php

                use Firebase\JWT\JWT;

                $key = getenv('TOKEN_SECRET');

                if (get_cookie("access_token")) {
                    $token = get_cookie("access_token");
                    $decoded = JWT::decode($token, $key, ['HS256']);
                }
                ?>
                <form action="<?= base_url('/api/users/update/' . $decoded->uid); ?>" id="edit" class=" form d-flex flex-column">
                </form>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            </div>
        </div>
    </div>
</div>
