<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
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
                <li class="breadcrumb-item active" aria-current="page">Course</li>
            </ol>
        </nav>

        <?php
        session_start();
        
        // Kiểm tra xem thông tin kết nối có trong session hay không
        if (isset($_SESSION['server']) && isset($_SESSION['database']) && isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $server = $_SESSION['server'];
            $database = $_SESSION['database'];
            $username = $_SESSION['username'];
            $password = $_SESSION['password'];

            // Kết nối tới database
            try {
                $conn = new mysqli($server, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }

                // Lấy danh sách khóa học từ database
                $sql = "SELECT title, description, imageUrl FROM Course"; // Make sure the SQL query is correct
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Hiển thị danh sách khóa học
                    echo '<h3>Danh sách khóa học:</h3>';
                    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
                    while ($row = $result->fetch_assoc()) {
                        $imageUrl = htmlspecialchars($row["imageUrl"]);
                        $title = htmlspecialchars($row["title"]);
                        $description = htmlspecialchars($row["description"]);

                        echo '
                        <div class="col">
                            <div class="card h-100">
                                <img src="' . $imageUrl . '" class="card-img-top" alt="' . $title . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $title . '</h5>
                                    <p class="card-text">' . $description . '</p>
                                </div>
                            </div>
                        </div>';
                    }
                    echo '</div>'; // Close the row div
                } else {
                    echo '<div class="alert alert-warning">Không có khóa học nào.</div>';
                }

            } catch (Exception $e) {
                echo 'Lỗi: ' . $e->getMessage();
            }
        } else {
            echo 'Chưa kết nối tới cơ sở dữ liệu. Vui lòng kết nối tại trang <a href="connect.php">Connect MySQL</a>';
        }

        // Ghi file
        if (isset($_POST['submit'])) {
            $filename = $_POST['filename'];

            // Kiểm tra và tạo file
            if (empty($filename)) {
                echo "Tên file không được để trống.";
            } else {
                $filename .= ".txt"; // Add .txt extension
                $file = fopen($filename, "w");
                if ($file) {
                    echo "Đang ghi file...";
                    // Ghi nội dung vào file
                    $content = "Danh sách khóa học:\n";
                    $sql = "SELECT title, description FROM Course"; // Ensure this is the correct SQL query
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $content .= "Tên khóa học: " . htmlspecialchars($row['title']) . "\nMô tả: " . htmlspecialchars($row['description']) . "\n\n";
                        }
                    }
                    fwrite($file, $content);
                    fclose($file);
                    echo '<div class="alert alert-success">Đã ghi file thành công: ' . $filename . '</div>';
                } else {
                    echo "Không thể mở file để ghi.";
                }
            }
        }
        ?>

        <hr>
        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="filename" placeholder="File name" name="filename" required>
                    <label for="filename">File name</label>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Write file</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
