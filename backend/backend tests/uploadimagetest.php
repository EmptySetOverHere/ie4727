<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic POST Request Tester with File Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>Dynamic POST Request Tester with File Upload</h2>

<div class="form-group">
    <label for="postUrl">POST URL:</label>
    <input type="text" id="postUrl" placeholder="Enter the URL (e.g., upload.php)">
</div>

<div id="inputFieldsContainer">
    <h3>Input Fields</h3>
    <div class="input-field-group">
        <input type="text" class="inputFieldName" placeholder="Enter field name">
        <input type="text" class="inputFieldValue" placeholder="Enter field value">
        <button type="button" onclick="removeField(this)">Remove</button>
    </div>
</div>

<h3>File Upload</h3>
<div class="form-group">
    <label for="fileFieldName">File Input Field Name:</label>
    <input type="text" id="fileFieldName" placeholder="Enter file field name">
</div>
<div class="form-group">
    <label for="fileUpload">Select a file:</label>
    <input type="file" id="fileUpload">
</div>

<button type="button" onclick="addInputField()">Add Another Input Field</button>
<button onclick="submitForm()">Submit</button>

<script>
    function addInputField() {
        const container = document.getElementById('inputFieldsContainer');
        
        const newFieldGroup = document.createElement('div');
        newFieldGroup.className = 'input-field-group';

        newFieldGroup.innerHTML = `
            <input type="text" class="inputFieldName" placeholder="Enter field name">
            <input type="text" class="inputFieldValue" placeholder="Enter field value">
            <button type="button" onclick="removeField(this)">Remove</button>
        `;

        container.appendChild(newFieldGroup);
    }

    function removeField(button) {
        const fieldGroup = button.parentNode;
        fieldGroup.parentNode.removeChild(fieldGroup);
    }

    function submitForm() {
        const postUrl = document.getElementById('postUrl').value;
        const fileFieldName = document.getElementById('fileFieldName').value;
        const fileUpload = document.getElementById('fileUpload').files[0];

        // Create a form dynamically
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = postUrl;
        form.enctype = 'multipart/form-data';

        const inputFields = document.querySelectorAll('.input-field-group');
        
        inputFields.forEach(fieldGroup => {
            const name = fieldGroup.querySelector('.inputFieldName').value;
            const value = fieldGroup.querySelector('.inputFieldValue').value;

            // Create input field for the dynamic name
            const input = document.createElement('input');
            input.type = 'hidden';  // Use hidden input field to send data
            input.name = name;
            input.value = value;

            // Append the input to the form
            form.appendChild(input);
        });

        // Append file input dynamically if a file is selected
        if (fileUpload && fileFieldName) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = fileFieldName;
            fileInput.files = document.getElementById('fileUpload').files;

            form.appendChild(fileInput);
        }

        // Append the form to the body
        document.body.appendChild(form);

        // Submit the form
        form.submit();
    }
</script>

</body>
</html>