<?php
// this is the dashboard page
session_start();

if (!isset($_SESSION["user_id"]) && $_SESSION["user_id"] != true) {
    header("Location: index.php?err=illegal_access");
    exit;
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

require_once("db_connection.php");
$conn = connect_database();

require_once('header.php');
?>
<link rel="stylesheet" href="css/dashboard.css">

<body>
    <div class="container px-4 my-4">
        <h1 class="display-6 text-dark text-center my-4 shadow">
            Task 1 - Fetching Data and Updating and Deleting Records
        </h1>

        <div class="logout_button my-2" style="display: flex; justify-content: flex-end;">
            <a href="logout.php">
                <button type="button" class="btn btn-sm btn-danger">Logout</button>
            </a>
        </div>

        <div class="d-flex justify-content-center">
            <div class="table_card my-4 shadow">
                <div class="heading display-6 text-center my-2">users</div>

                <table class="table" id="users_table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Sl No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Created_at</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $serial = 1;

                        $sql = "SELECT * FROM `users`";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {
                                if ($rows["id"] == $user_id) {
                        ?>
                                    <tr style="background: greenyellow !important;">
                                        <th scope="row"><?= $serial ?></th>
                                        <td><?= $rows["name"] ?></td>
                                        <td><?= $rows["email"] ?></td>
                                        <td> <?php
                                                $date = new DateTime($rows["created_at"]);
                                                $formatDate = $date->format('d/m/Y');
                                                echo $formatDate;
                                                ?></td>
                                        <td class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-sm btn-danger mx-2 remove_btn" data-id=<?= $rows["id"] ?>>Remove</button>
                                            <button type="button" class="btn btn-sm btn-success mx-2" id="edit_btn" data-id=<?= $rows["id"] ?>>Edit</button>
                                        </td>
                                    </tr>
                                <?php
                                } else {
                                ?>
                                    <tr class="">
                                        <th scope="row"><?= $serial ?></th>
                                        <td><?= $rows["name"] ?></td>
                                        <td><?= $rows["email"] ?></td>
                                        <td>
                                            <?php
                                            $date = new DateTime($rows["created_at"]);
                                            $formatDate = $date->format('d/m/Y');
                                            echo $formatDate;
                                            ?>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-sm btn-danger remove_btn" data-id=<?= $rows["id"] ?>>Remove</button>
                                        </td>
                                    </tr>
                        <?php
                                }
                                $serial++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="form_modal">
            <div class="modal" tabindex="-1" id="edit_form_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Form</h5>
                            <button type="button" id="close-modal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit_form">
                                <input type="hidden" name="edit_user" value="edit_user">
                                <?php
                                $sql_edit = "SELECT * FROM `users` WHERE `id`=$user_id;";
                                $result_edit = $conn->query($sql_edit);
                                $row = $result_edit->fetch_assoc();
                                ?>
                                <input type="hidden" name="user_id" value="<?= $row["id"] ?>">
                                <div class="form-group my-4">
                                    <label for="email">Your Email</label>
                                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="eg: user@gmail.com" value="<?= $row["email"] ?>" required />
                                </div>

                                <div class="form-group my-4">
                                    <label for="username">Your Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="eg: your name" value="<?= $row["name"] ?>" required />
                                </div>

                                <div class="form-group my-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="leave the field and Submit if you don't wish to change your password" required />
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="edit_form_submit">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
    <script>
        let table = new DataTable('#users_table');

        $(document).ready(function() {
            $('#edit_btn').click(function() {
                $('#edit_form_modal').modal('show');
            });

            $('#close-modal').click(function() {
                $('#edit_form_modal').modal('hide');
            });
        });

        $(document).ready(function() {
            $(".remove_btn").on("click", function() {
                event.preventDefault();

                let id = $(this).data("id");

                $.ajax({
                    type: "POST",
                    url: "function.php",
                    data: {
                        user_id: id,
                        remove_user: true
                    },
                    success: function(data) {
                        if (data.includes("user_removed")) {
                            Swal.fire({
                                icon: "success",
                                title: "User Removed",
                                text: "User has been removed Successfully",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "unexpected Server Error",
                                text: data,
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            })
                        }
                    }
                });
            });

            $("#edit_form_submit").on("click", function() {
                event.preventDefault();

                let form = document.getElementById('edit_form');
                let formData = new FormData(form);

                if (formData.get("email") == null || formData.get("email") == "") {
                    Swal.fire({
                        position: "top-end",
                        icon: "warning",
                        title: "Email is required for Your account!",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                    return;
                } else if (formData.get("username") == null || formData.get("username") == "") {
                    Swal.fire({
                        position: "top-end",
                        icon: "warning",
                        title: "Enter Your Name",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                    return;
                }

                if (formData.get("password") != null && formData.get("password") != "") {
                    let pass = formData.get("password");
                    if (pass.length < 8) {
                        Swal.fire({
                            position: "top-end",
                            icon: "warning",
                            title: "Passwords Must be of 8 Characters",
                            showConfirmButton: true,
                            confirmButtonText: "OK",
                        });
                        return;
                    }
                }

                $.ajax({
                    type: "POST",
                    url: "function.php",
                    data: $('#edit_form').serialize(),
                    success: function(data) {
                        if (data.includes("user_edited")) {
                            Swal.fire({
                                icon: "success",
                                title: "Saved Changes",
                                text: "Your Credentials has been edited successfully.",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "unexpected Server Error",
                                text: data,
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            })
                        }
                    }
                })
            })
        });
    </script>
</body>