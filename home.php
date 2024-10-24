
<?php
$file = '';
$response = ['success' => false, 'file' => '', 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $final_folder = "image/";
    $target_file = $final_folder . basename($_FILES["fileupload"]["name"]);
    $upload = true;

    // Delete all existing files in the image folder
    $files = glob($final_folder . '*'); 
    foreach ($files as $existingFile) {
        if (is_file($existingFile)) {
            if (!unlink($existingFile)) {
                $response['error'] = "Error deleting file: $existingFile";
                echo json_encode($response);
                exit();
            }
        }
    }


    // Move uploaded file to target directory
    if ($upload && move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file)) {
        $response['success'] = true;
        $response['file'] = basename($_FILES["fileupload"]["name"]);
    } else {
        $response['error'] = "File not uploaded";
    }
    
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>File Upload</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <!-- # @imp This is a special comment in HTML -->
    
    <div class="container ">
        <form>
            <h1><span>UPLOAD</span> FILES</h1>
            <input type="file" name="fileupload" id="fileupload">
        </form>
        <div class="profile-img" id="profileImg" style="display: none;">
            <img id="uploadedImage" src="" alt="Profile Image" style="max-width: 100%; cursor: pointer;">
        </div>
            
            
                <label class="camera-icon" for="fileupload" id="camericon" style="display: none;">
                    <i class="fas fa-camera"></i>
                    
                </label>



        <!-- Modal -->
        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="img01">
        </div>
    </div>

    <script>
        document.getElementById('fileupload').addEventListener('change', function(event) {
            var fileInput = event.target;
            var file = fileInput.files[0];

            if (file) {
                var formData = new FormData();
                formData.append('fileupload', file);

                fetch('home.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var fileupload = document.getElementById('fileupload');
                        var profileImg = document.getElementById('profileImg');
                        var uploadedImage = document.getElementById('uploadedImage');
                        var camericon = document.getElementById('camericon');
                        uploadedImage.src = 'image/' + data.file;
                        profileImg.style.display = 'block';
                        camericon.style.display = 'block';
                        fileupload.style.display = 'none';
                    } else {
                        console.error('Error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });

        // Get modal element
        var modal = document.getElementById("myModal");
        var img = document.getElementById("uploadedImage");
        var modalImg = document.getElementById("img01");
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the profile image, open the modal
        img.onclick = function() {
            modal.style.display = "flex";
            modalImg.src = this.src;
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


        // smoke

    </script>
</body>
</html>
