<!DOCTYPE html>
<!-- TODO page ugly -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #preview {
            margin-top: 20px;
            max-width: 500px; /* Adjust as needed */
        }
    </style>
</head>
<body>
    <h1>Upload menuitem preview</h1>
    <input type="file" id="fileInput" accept="image/*">
    <img id="preview" src="" alt="Image Preview" style="display: none;">

    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('preview');
                    img.src = e.target.result;
                    img.style.display = 'block'; // Show the image
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
