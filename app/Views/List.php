<?php
// Assuming $documents is defined somewhere before this block
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .alert {
            margin: 20px 0;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
        }

        /* Button styles */
        #openModal {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #openModal:hover {
            background-color: #0056b3;
        }

        /* Modal styles */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.7);
            /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            /* Reduced width */
            max-width: 600px;
            /* Maximum width */
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.4s;
            /* Add fade-in effect */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #1e7e34;
        }

        /* Datalist styles */
        datalist {
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        option {
            padding: 10px;
        }

        /* Add to your existing CSS */
        .close-update {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-update:hover,
        .close-update:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-container {
            margin: 0 !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>
    <button id="openModal">Add Product</button>

    <table id="mytable">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Category</th>
                <th>Product Price</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item->productname); ?></td>
                    <td><?php echo htmlspecialchars($item->productcategory); ?></td>
                    <td><strong>Rs.</strong> <?php echo htmlspecialchars($item->productprice); ?><strong>/-</strong></td>
                    <!-- Change this line in your table -->
                    <td>
                        <a href="#" class="updateBtn" data-id="<?= htmlspecialchars((string) $item->_id) ?>"
                            data-name="<?= htmlspecialchars($item->productname) ?>"
                            data-category="<?= htmlspecialchars($item->productcategory) ?>"
                            data-price="<?= htmlspecialchars($item->productprice) ?>">Update</a>
                        <a href="delete/<?= htmlspecialchars((string) $item->_id) ?>" class="deletedBtn">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert">Data deleted Successfully</div>
    <?php endif; ?>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Product</h2>
            <form action="/postdata" method="post">


                Enter Product Name: <input type="text" name="name" required><br>
                Enter Product Category: <input type="text" list="category" name="category" required>
                <datalist id="category">
                    <option value="Electronics"></option>
                    <option value="Fashion"></option>
                    <option value="Home and Garden"></option>
                    <option value="Beauty and Personal Care"></option>
                    <option value="Health and Wellness"></option>
                    <option value="Sports and Outdoors"></option>
                    <option value="Toys and Games"></option>
                    <option value="Books and Stationery"></option>
                    <option value="Pet Supplies"></option>
                    <option value="Music"></option>
                    <option value="Automotive Accessories"></option>
                    <option value="Jewelry and Watches"></option>
                    <option value="Baby Products"></option>
                    <option value="Arts and Crafts"></option>
                    <option value="Office"></option>
                </datalist><br>
                Enter Product Price: <input type="number" name="price" required><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <!-- Add this after your existing modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close-update">&times;</span>
            <div class="form-container">
                <h2>Update Product</h2>
                <form id="updateForm" action="/updatedetails" method="POST">
                    <div class="form-group">
                        <label for="updatename">Enter updated product name:</label>
                        <input type="text" name="updatename" id="updatename" required>
                    </div>
                    <div class="form-group">
                        <label for="updatecat">Enter updated category:</label>
                        <input type="text" name="updatecat" id="updatecat" required>
                    </div>
                    <div class="form-group">
                        <label for="updateprice">Enter updated price:</label>
                        <input type="number" name="updateprice" id="updateprice" required>
                    </div>
                    <input type="hidden" id="updateId" name="id">
                    <input type="submit" value="Update">
                </form>
            </div>
        </div>
    </div>


</body>
<script src="cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(() => {
        $('#mytable').DataTable()
    })
    document.querySelectorAll('.deletedBtn').forEach(btn => {
        btn.addEventListener('click', (event) => {
            if (!confirm("Do you want to delete it?")) {
                event.preventDefault(); // Prevent the link from being followed
            }
        });
    });

    // Modal functionality
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function () {
        modal.style.display = "block";
    }

    span.onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Add this to your existing script section
    // Update Modal functionality
    var updateModal = document.getElementById("updateModal");
    var updateBtns = document.querySelectorAll('.updateBtn');
    var updateClose = document.getElementsByClassName("close-update")[0];
    var updateForm = document.getElementById("updateForm");

    // Add click event to all update buttons
    updateBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            updateModal.style.display = "block";

            // Get data from data attributes
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const category = this.getAttribute('data-category');
            const price = this.getAttribute('data-price');

            // Set form action
            updateForm.action = `/updatedetails/${id}`;

            // Fill form fields
            document.getElementById('updatename').value = name;
            document.getElementById('updatecat').value = category;
            document.getElementById('updateprice').value = price;
            document.getElementById('updateId').value = id;
        });
    });

    // Close button functionality
    updateClose.onclick = function () {
        updateModal.style.display = "none";
    }

    // Click outside modal to close
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == updateModal) {
            updateModal.style.display = "none";
        }
    }


</script>


</html>