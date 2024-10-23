<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Nối Cơ Sở Dữ Liệu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Ví dụ PHP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="connect.php">Connect MySQL</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Connect MySQL</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col"></div>
            <div class="col">
                <?php
                if (isset($_POST['submit'])) {
                    $server = $_POST['server'];
                    $database = $_POST['database'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    session_start();
                    $_SESSION['server'] = $server;
                    $_SESSION['database'] = $database;
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                
                    try {
                        // Tạo kết nối
                        $conn = new mysqli($server, $username, $password, $database);
                        
                        // Kiểm tra kết nối
                        if ($conn->connect_error) {
                            throw new Exception("Kết nối thất bại: " . $conn->connect_error);
                        }
                        // Kết nối tới MySQL server
                    $conn = new mysqli($server, $username, $password);

                    if ($conn->connect_error) {
                        echo '<div class="alert alert-danger" role="alert">Kết nối thất bại: ' . $conn->connect_error . '</div>';
                    } else {
                        // Tạo cơ sở dữ liệu nếu chưa tồn tại
                        $sql = "CREATE DATABASE IF NOT EXISTS `db_dam_phuong_anh`";
                        if ($conn->query($sql) === TRUE) {
                            echo '  <div class="alert alert-success d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            Database connected and created successfully.
                                        </div>
                                    </div>';
                            $_SESSION['database'] = "db_dam_phuong_anh";
                        } else {
                            echo '  <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            Error creating database: ' . $conn->error . '
                                        </div>
                                    </div>';
                                 }
                        // Kết nối tới cơ sở dữ liệu đã tạo
                        $conn->select_db($database);
                    }
                        echo '  <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>
                                        Kết nối thành công, đã lưu vào session
                                    </div>
                                </div>';
                    } catch (Exception $e) {
                        echo '  <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        ' . $e->getMessage() . '
                                    </div>
                                </div>';
                    }
                }
                ?>
            </div>
        </div>

        <form class="row" method="POST">
            <div class="col">
                <div class="form-floating mb-3">
                    <input value="localhost" type="text" class="form-control" id="server" placeholder="Server" name="server" required>
                    <label for="server">Server</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="database" placeholder="Cơ sở dữ liệu" name="database" required>
                    <label for="database">Database</label>
                </div>
                <div class="form-floating mb-3">
                    <input value="root" type="text" class="form-control" id="username" placeholder="Tên đăng nhập" name="username" required>
                    <label for="username">username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Mật khẩu" name="password">
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Kiểm tra kết nối</button>
            </div>
            <div class="col">
                <img class="w-100 h-100 rounded" style="object-fit: cover;" src="https://cdn.fpt-is.com/vi/Database-2-scaled-1-scaled-1715587890.webp" alt="DBMS">
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
