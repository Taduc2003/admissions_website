<?php
session_start();

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "dbtest1";
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy student_id từ session
$student_id = $_SESSION['user_id'];

// Lấy danh sách nguyện vọng ngành học của sinh viên
$sql_majors = "SELECT Major_Student.major_id, Majors.major_name, Major_Student.major_level
               FROM Major_Student
               INNER JOIN Majors ON Major_Student.major_id = Majors.major_id
               WHERE Major_Student.student_id = '$student_id'
               ORDER BY Major_Student.major_level ASC";

$result_majors = mysqli_query($conn, $sql_majors);

// Kiểm tra nếu đã submit form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Lấy danh sách thứ tự ưu tiên mới từ form
    $major_level = $_POST["major_level"];
    
    // Chuyển đổi mảng thứ tự ưu tiên thành mảng không trùng lặp và sắp xếp từ 1 trở đi
    $uniquemajor_level = array_unique($major_level);
    //sort($uniquemajor_level);
    
    // Kiểm tra số lượng nguyện vọng có khớp với số lượng thứ tự ưu tiên
    $numMajors = mysqli_num_rows($result_majors);
    $numUniquemajor_level = count($uniquemajor_level);
    
    if ($numMajors != $numUniquemajor_level) {
        echo '<script>alert("Số lượng thứ tự ưu tiên không khớp với số lượng nguyện vọng.")</script>';
    } else {
        // Duyệt qua danh sách nguyện vọng và cập nhật thứ tự ưu tiên trong bảng Major_Student
        foreach ($major_level as $index => $major_level) {
            $major_id = $_POST["major_id"][$index];
            $newmajor_level = $uniquemajor_level[$index];
            
            // Cập nhật thứ tự ưu tiên mới
            $sql_update_major_level= "UPDATE Major_Student SET major_level = '$newmajor_level' WHERE student_id = '$student_id' AND major_id = '$major_id'";
            mysqli_query($conn, $sql_update_major_level);
        }
        
        echo '<script>alert("Cập nhật thứ tự ưu tiên thành công.")</script>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>XẾP NGUYỆN VỌNG - Chương trình quản lý tuyển sinh</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="./css/style.css" rel="stylesheet"></head>
<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

 
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="home.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa-solid fa-school me-3"></i>AMSYS</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="home.html" class="nav-item nav-link">Home</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                      VIEW                  
                    </a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="xemthongtinsvien.php" class="dropdown-item">View Information</a>
                        <a href="diemNamTrc.php" class="dropdown-item">View Last Year's Scores</a>
                        <a href="xemdiem.php" class="dropdown-item">View Student's Scores</a>
                        <a href="phanhoicuagiangvien.php" class="dropdown-item">View Feedback</a>
                    </div>
                </div>
                
                <a href="guiyeucau.php" class="nav-item nav-link">SEND REQUEST</a>
                <a href="./dangkykhoi.php" class="nav-item nav-link">Block Register</a>
                <a href="./luachonnganh.php" class="nav-item nav-link">Choose Major</a>
                <a href="./xoanganh.php" class="nav-item nav-link">Remove Major</a>
                <a href="./xepnguyenvong.php" class="nav-item nav-link active">Prioritize</a>


                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img
                        src="https://static.vecteezy.com/system/resources/thumbnails/000/511/962/small/57_Student.jpg"
                        class="rounded-circle"
                        height="20"
                        alt=""
                        loading="lazy"
                      />
                      STUDENT ACCOUNT                   
                    </a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="xemthongtinsvien.php" class="dropdown-item">View information</a>
                        <!-- <div class="dropdown-item" onclick="go()">Logout</div> -->
                        <a href="index.php" class="dropdown-item">Log out</a>

                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">XẾP NGUYỆN VỌNG</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="./home.html">HOME</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">PRIORITIZE</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="container">

        <div class="row height d-flex justify-content-center align-items-center">

            <div class="col-md-6">

                <div class="form">
                    <h2 style="text-align:center; color: white">Thứ tự ưu tiên nguyện vọng</h2>
                    <form style="text-align:center" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <?php
                        $major_levelCount = 1;
                        
                        while ($row = mysqli_fetch_assoc($result_majors)) {
                            echo "<label style='color: white' for='priority[]'>" . $row['major_name'] . ":</label>";
                            echo "<input  type='hidden' name='major_id[]' value='" . $row['major_id'] . "'>";
                            echo "&ensp;<input style='margin-top: 10px' type='number' name='major_level[]' value='" . $major_levelCount . "' min='1' required max='5'><br>";
                            
                            $major_levelCount++;
                        }
                        ?>

                        <input style='margin-top: 10px' type="submit" name="submit" value="Cập nhật">

                    </form>
                    </div>
            
            </div>
            
        </div>
          
    </div>

       <!-- Footer Start -->
       <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 col-md-6">
                    <h4 class="text-white mb-3">Quick Link</h4>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Our Team</a>
                </div>
                <div class="col-lg-6 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Số 1 Đại Cồ Việt, phường Bách Khoa, quận Hai Bà Trưng, Hà Nội, Việt Nam</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">AMSYS</a>, All Right Reserved.

                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        <span style="color: #181d38;"></a>Designed By HTML Codex</span><br><br>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://kit.fontawesome.com/533391d722.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
