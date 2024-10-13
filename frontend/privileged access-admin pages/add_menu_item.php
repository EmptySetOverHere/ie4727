<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Menu Item Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #preview {
            margin-top: 20px;
            max-width: 500px; /* Adjust as needed */
        }
        label {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Upload Menu Item Preview</h1>

    <form id="menuItemForm" action="../../backend/admin_add_menu_items/api_add_menu_item.php" method="post" enctype="multipart/form-data">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="is_in_stock">In Stock:</label>
        <input type="checkbox" id="is_in_stock" name="is_in_stock">

        <label for="is_vegetarian">Vegetarian:</label>
        <input type="checkbox" id="is_vegetarian" name="is_vegetarian">

        <label for="is_halal">Halal:</label>
        <input type="checkbox" id="is_halal" name="is_halal">

        <label for="fileInput">Upload Image:</label>
        <input type="file" id="fileInput" name="image" accept="image/*"> 

        <img id="preview" src="" alt="Image Preview" style="display: none;">
        
        <button type="submit">Submit</button>
    </form>

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

        // Allow form submission
        document.getElementById('menuItemForm').addEventListener('submit', function(event) {
            // Remove the preventDefault line to allow form submission
            // event.preventDefault(); // Prevent actual submission for now
            // You can add any additional validation or actions here if needed
        });
    </script>
</body>
</html>