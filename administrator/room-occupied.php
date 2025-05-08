<?php
session_start();
include '../inc/queries.php';

// Check if an admin exists in the database
$checkAdminQuery = "SELECT COUNT(*) as count FROM `admin`";
$result = $conn->query($checkAdminQuery);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // No admin found, redirect to index.php
    header('Location: index.php');
    exit();
}

// Proceed if an admin is found
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>List of Room Occupied</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
               
                <div class="card-body">


                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      <th style="text-align: center;" >#</th>
                        <th>Room(#)</th>
                        <th>Maximum</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Image</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                   
                      $no = 1;
                      $query = mysqli_query($conn, "SELECT room.room_id,room.roomnumber,room.roomtype,room.roomimage,room.maximum,tenants.status,
                      COUNT(DISTINCT rent.tenants_id) AS distinct_rent_count
                      FROM room 
                      INNER JOIN rent ON room.room_id = rent.room_id 
                      INNER JOIN tenants ON rent.tenants_id = tenants.tenants_id 
                      WHERE tenants.status = 1 
                      GROUP BY room.room_id  HAVING 
                      room.maximum = distinct_rent_count
                  ");

                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                        $bacante = $maximum - $distinct_rent_count;


                      ?>

                        <tr>
                        <td style="text-align: center;" ><?php echo $no  ?></td>
                          <td>Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                          <td><?php echo $maximum ?> Bed</td>
                          <td><?php echo $distinct_rent_count ?> Bed</td>
                          <td><?php echo $bacante ?> Bed</td>
                          <td style="width: 15%;padding:5px;"><a class="image-popup-vertical-fit" href="../image/<?php echo $roomimage ?>"><img style="width: 100% !important;max-height: 100px;object-fit: cover;" src="../image/<?php echo $roomimage ?>" alt=""></a></td>
                          
                          <td><a class="btn btn-primary btn-sm" href="room-available-view.php">
                              <i class="fas fa-folder">
                              </i>
                              View
                            </a>
                          </td>
                        </tr>
                      <?php
                      $no ++;
                      }
                      ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="float-right d-none d-sm-inline">
        <b>Version</b> 3.1.0
      </div>
      <strong>&copy; 2024 Your Boarding House.</strong> All rights reserved.
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
  </div>



  <?php include 'includes/script.php' ?>

  <script>
    $(document).ready(function() {

      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false

      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          verticalFit: true
        }

      });
      $('table').removeClass('dataTable ');

    });
  </script>
</body>

</html>