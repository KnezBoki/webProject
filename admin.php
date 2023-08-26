<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

if (!$_SESSION['logged_in']) {
    header("location: login.php");
    exit();
}
elseif($_SESSION['userRole'] != 'admin'){
    header("location: index.php");
    exit();
}
else {
    $email = $_SESSION['email'];

    $conn = connect();

    $stmt = $conn->prepare("SELECT first_name, last_name, gender, date_of_birth, phone FROM accounts WHERE email=?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $gender = $result['gender'];
    $rawDate = $result['date_of_birth'];
    $phone = $result['phone'];

    $dob = reverseFormatDate($rawDate);

    $stmt = $conn->prepare("SELECT account_id, role_id FROM user_role WHERE role_id IN (2, 3)");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $workerData = [];
    $userData = [];

    foreach ($results as $row) {
        $accountId = $row['account_id'];
        $roleId = $row['role_id'];

        $stmt = $conn->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$accountId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            switch ($roleId) {
                case 2:
                    $workerData[$accountId] = $result[0]; // Store the first row in the workerData array
                    break;
                case 3:
                    $userData[$accountId] = $result[0]; // Store the first row in the userData array
                    break;
            }
        }
    }

}
?>
<section class="my-5 pt-md-4 pt-5 pb-5">
    <div class="container-fluid py-5">
        <div id="successPopup" class="popup hidden">User successfully updated!</div>
        <div id="deletePopup" class="popup hidden">User successfully deleted!</div>
        <div class="row">
            <!-- Side Menu -->
            <nav class="main-nav col-md-2 col-12 d-md-block d-lg-block d-inline-block sidebar">
                <div>
                    <ul class="nav d-flex flex-column flex-md-custom">
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnProfile" data-target="profile">Admin Profile</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnWorkers" data-target="workers">Workers</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnUsers" data-target="users">Users</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnReservations" data-target="reservations">Reservations</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnTables" data-target="tables">Tables</a>
                        </li>
                    </ul>

                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 col-12 bg-light">
                <div class="container p-2 section" id="profile">
                    <!-- *** Current admins info *** -->
                    <div class="row ">
                        <div class="col-lg-12 text-center mb-4">
                            <h2>Admin Profile</h2>
                        </div>
                        <!-- *** Name *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Name:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $fname . ' ' . $lname; ?></h4>
                            </div>
                        </div>

                        <!-- *** Email *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Email:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $email; ?></h4>
                            </div>
                        </div>

                        <!-- *** Gender *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Gender:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $gender; ?></h4>
                            </div>
                        </div>

                        <!-- *** Date of birth *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Date of Birth:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $dob; ?></h4>
                            </div>
                        </div>

                        <!-- *** Phone *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Phone:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $phone; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container p-2 d-none section" id="workers">
                    <!-- *** List of workers *** -->
                    <div class="row d-flex justify-content-start">
                        <div class="col-md-4 mb-4">
                            <div class="border p-3 d-flex justify-content-center" style="word-wrap: break-word; white-space: normal;">
                                <a class="btnAddWorker"><h1>+</h1></a>
                                <form id="addWorkerForm" class="contact-form hidden">
                                    <input type="hidden" name="workerId" value="Worker">
                                    <input type="text" name="first_name" placeholder="First Name">
                                    <input type="text" name="last_name" placeholder="Last Name">
                                    <input type="email" name="email" placeholder="Email">
                                    <select name="gender">
                                        <option value="" selected disabled>Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="date" name="date_of_birth" placeholder="Date of Birth">
                                    <input type="tel" name="phone" placeholder="Phone">
                                    <input type="password" name="password" placeholder="Password">
                                    <button type="submit">Add Worker</button>
                                </form>
                            </div>
                        </div>

                        <?php
                        $counter = 1;

                        foreach ($workerData as $workerID => $data) {
                            echo '<div class="col-md-4 mb-4 d-flex justify-content-center">
                                    <div class="border p-3" style="word-wrap: break-word; white-space: normal;">
                                    <form name="editWorker'.$counter.'" onsubmit="return false;" method="post">
                                    <input type="hidden" name="worker_id" value="'.$workerID.'">';

                            foreach ($data as $key => $value) {
                                if($key === 'date_of_birth'){
                                    echo '<p><strong>' . $key . ':</strong></p><input type="date" name="'.$key.'" value="'.$value.'" disabled>';
                                }
                                else{
                                    echo '<p><strong>' . $key . ':</strong></p><input name="'.$key.'" value="'.$value.'" disabled>';
                                }
                            }
                            echo '<div class="text-center mt-3">
                                    <button class="btn btn-primary btnEditWorker">Edit</button>
                                    <button class="btn btn-primary btnSaveWorker" style="display: none;">Save</button>
                                    <button class="btn btn-danger btnDeleteWorker">Delete</button>
                                  </div>
                                  </form>
                                  </div>
                                </div>
                                ';
                            $counter++;
                        }?>
                    </div>
                </div>
                <div class="container p-2 d-none section" id="users">
                    <!-- *** List of users *** -->
                    <div class="row d-flex justify-content-start">
                        <div class="col-md-4 mb-4">
                            <div class="border p-3 d-flex justify-content-center" style="word-wrap: break-word; white-space: normal;">
                                <a class="btnAddUser"><h1>+</h1></a>
                                <form id="addUserForm" class="contact-form hidden">
                                    <input type="text" name="first_name" placeholder="First Name">
                                    <input type="text" name="last_name" placeholder="Last Name">
                                    <input type="email" name="email" placeholder="Email">
                                    <select name="gender">
                                        <option value="" selected disabled>Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="date" name="date_of_birth" placeholder="Date of Birth">
                                    <input type="tel" name="phone" placeholder="Phone">
                                    <input type="password" name="password" placeholder="Password">
                                    <button type="submit">Add User</button>
                                </form>
                            </div>
                        </div>
                        <?php
                        $counter = 1;

                        foreach ($userData as $userId => $data) {
                            echo '<div class="col-md-4 mb-4 d-flex justify-content-center">
                                    <div class="border p-3" style="word-wrap: break-word; white-space: normal;">
                                    <form name="editUser'.$counter.'" onsubmit="return false;" method="post">
                                    <input type="hidden" name="user_id" value="'.$userId.'">';

                            foreach ($data as $key => $value) {
                                if($key === 'date_of_birth'){
                                    echo '<p><strong>' . $key . ':</strong></p><input type="date" name="'.$key.'" value="'.$value.'" disabled>';
                                }
                                else{
                                    echo '<p><strong>' . $key . ':</strong></p><input name="'.$key.'" value="'.$value.'" disabled>';
                                }
                            }
                            echo '<div class="text-center mt-3">
                                    <button class="btn btn-primary btnEditUser">Edit</button>
                                    <button class="btn btn-primary btnSaveUser" style="display: none;">Save</button>
                                    <button class="btn btn-danger btnDeleteUser">Delete</button>
                                  </div>
                                  </form>
                                  </div>
                                </div>
                                ';
                            $counter++;
                        }?>
                    </div>
                </div>
                <div class="container p-2 d-none section" id="reservations">
                    <!-- *** List of reservation *** -->
                    <div class="row d-flex justify-content-center">
                        <div class="row d-inline-block justify-content-center">
                            <!-- Filter Controls -->
                            <div class="col-sm-12 mb-3">
                                <form onchange="updateReservationFilters()" onsubmit="return false;" name="filterForm" method="post" id="filterForm" class="d-flex flex-wrap">
                                    <!-- *** Date *** --->
                                    <div class="form-group mr-3">
                                        <input type="date" id="filterDate" name="filterDate" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="form-group mr-3">
                                        <button id="filterAll" name="filterAll" class="form-control btn btn-primary" autocomplete="off">Show all</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- *** Reservations *** -->
                        <ul id="reservationList" class="list-unstyled">

                        </ul>
                    </div>
                </div>
                <div class="container p-2 d-none section" id="tables">
                    <!-- *** List of tables and details about the tables *** -->
                    <div class="row d-flex justify-content-center">
                        <!-- *** Tables *** -->
                        <?php
                        $sql = "SELECT COUNT(*) as row_count FROM tables";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        $maxT = $result['row_count']; //NUMBER OF TABLES IN RESTAURANT FROM DB

                        for ($i = 1; $i <= $maxT; $i++) {
                            $stmt = $conn->prepare("SELECT num_seats, location, smoking FROM tables WHERE table_id = ?");
                            $stmt->execute([$i]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);

                            $seats = $result['num_seats'];
                            $location = $result['location'];
                            $smoking = $result['smoking'];

                            echo '
                        <div class="col-sm-4 border">
                            <form name="updateTable" method="post" onsubmit="return false;">
                                <div class="row text-center">
                                    <div class="row mb-1">
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-sm-start">
                                                <label class="font-weight-bold">Table:</label>
                                            </div>
                                        </div>                                          
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-md-start">
                                                <input type="text" value="' . $i . '" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-sm-start">
                                                <label class="font-weight-bold">Seats:</label>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-md-start">
                                                <input type="text" class="seats" value="' . $seats . '" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-sm-start">
                                                <label class="font-weight-bold">Location:</label>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-md-start">
                                                <select name="location" class="location" disabled>';
                                                   if($location === 'Outdoor'){
                                                       echo '
                                                          <option value="Outdoor" selected >Outdoor</option>
                                                          <option value="Balcony">Balcony</option>
                                                          <option value="Indoor">Indoor</option>
                                                        ';
                                                   }
                                                   elseif($location ===' Balcony'){
                                                       echo'
                                                       <option value="Balcony" selected >Balcony</option>
                                                       <option value="Outdoor">Outdoor</option>
                                                          <option value="Indoor">Indoor</option>
                                                       ';
                                                   }
                                                   else{
                                                       echo '
                                                        <option value="Indoor" selected >Indoor</option>
                                                        <option value="Outdoor">Outdoor</option>
                                                        <option value="Balcony">Balcony</option>
                                                       ';
                                                   }
                                                echo '</select>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-sm-start">
                                                <label class="font-weight-bold">Smoking:</label>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-3 text-md-start">
                                                <input type="text" class="smoking" value="' . $smoking . '" disabled>
                                            </div>
                                        </div>
                                        <button class="edit-btn btn btn-primary" data-table-id="' . $i . '">Edit</button>
                                        <button class="save-btn btn btn-primary" data-table-id="' . $i . '" style="display:none;">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>
    <script src="assets/js/admin_dash.js"></script>
    <script src="assets/js/table_update.js"></script>
    <script src="assets/js/reservation_filter.js"></script>
<?php
require "template/footer.php";