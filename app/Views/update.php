<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-container {
            background-color: #fff;
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px; /* Set a maximum width for the form */
        }

        form {
            display: flex;
            flex-direction: column;
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
            width: 100%; /* Full width */
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px; /* Add space above the button */
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Update Product</h2>
        <form action="/updatedetails/<?= htmlspecialchars($item->_id) ?>" method="POST">
            <div class="form-group">
                <label for="updatename">Enter updated product name:</label>
                <input type="text" name="updatename" id="updatename" value='<?= htmlspecialchars($item->productname) ?>' required>
            </div>
            <div class="form-group">
                <label for="updatecat">Enter updated category:</label>
                <input type="text" name="updatecat" id="updatecat" value='<?= htmlspecialchars($item->productcategory) ?>' required>
            </div>
            <div class="form-group">
                <label for="updateprice">Enter updated price:</label>
                <input type="number" name="updateprice" id="updateprice" value='<?= htmlspecialchars($item->productprice) ?>' required>
            </div>
            <input type="submit" value="Update">
        </form>
    </div>

</body>
</html>