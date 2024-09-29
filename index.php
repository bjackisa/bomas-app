<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Capture System</title>

    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- jQuery via CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }
        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #007bff;
        }
        #loading {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Face Capture System</h3>
                    <p class="card-text text-center">Enter your name and press Start to begin capturing face images.</p>

                    <!-- Form to get user name -->
                    <form id="faceCaptureForm" method="POST" action="">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter your name" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Start</button>
                        </div>
                    </form>

                    <!-- Loading indicator -->
                    <div id="loading" class="text-center mt-3">
                        <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="50">
                        <p>Capturing face images. Please wait...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and AJAX to handle form submission -->
<script>
    $(document).ready(function() {
        $('#faceCaptureForm').submit(function(event) {
            event.preventDefault();
            $('#loading').show(); // Show loading animation

            let userName = $('#userName').val();

            // Send the user name to the backend PHP to trigger the Python script
            $.post('', { userName: userName }, function(response) {
                $('#loading').hide(); // Hide loading animation
                alert(response.message);
            }, 'json').fail(function() {
                $('#loading').hide();
                alert("Error starting the face capture.");
            });
        });
    });
</script>

<?php
// PHP part to handle the Python script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName'] ?? '';

    // Sanitize user input
    $userName = escapeshellarg($userName);

    // NOTE: This will only work locally when run on a local machine with camera access.
    // Run the Python script to capture faces (locally)
    $command = escapeshellcmd("python3 face_taker.py $userName");
    $output = shell_exec($command);

    // Send JSON response back to the client
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Face capture started for ' . $userName]);
    exit;
}
?>

</body>
</html>
