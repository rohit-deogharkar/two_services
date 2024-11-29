<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <!-- jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('/css/list.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>

    <div class="headers">
        <button id="openModal">Add Product</button>

        <div class="header1">
            <a href="/logout" id="black"><strong><?= session('user_id') ?></strong></a>
        </div>
    </div>
    
    <form action="/postfilter" id="filter-form" method="post" class="w">
        <select name="filter-name" class="filter-select" id="filter-select" style="width:100px">
        
            <?php foreach ($documents_all as $item): ?>
                <option value=""></option>
                <option value="<?= $item['productname'] ?>"><?= $item['productname'] ?></option>
            <?php endforeach ?>
        </select>

        <select name="filter-cat" class="filter-select" style="width:100px">
        
            <?php foreach ($documents_all as $item): ?>
                <option value=""></option>
                <option value="<?= $item['productcategory'] ?>"><?= $item['productcategory'] ?></option>
            <?php endforeach ?>
        </select>

        <select name="filter-price" class="filter-select" style="width:100px">
       
            <?php foreach ($documents_all as $item): ?>
                <option value=""></option>
                <option value="<?= $item['productprice'] ?>"><?= $item['productprice'] ?></option>
            <?php endforeach ?>
        </select>
        <input type="submit" name="" id="">
        <a href="/download">Download List</a>
        
    </form>
    <form action="/upload-file" method="post" enctype="multipart/form-data" class="w">
            <input type="file" name="myfile" id="" value="upload file">
            <input type="submit" name="" id="">
        </form>

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

    <div id="filterModal" class="modal">
        <div class="modal-content">
            <span class="close-filter">&times;</span>
            <div class="form-container">
                <h2>filter</h2>
                <form id="updateForm" action="/filterdetails" method="POST">
                    <div class="form-group">
                        <label for="updatename">Enter filter</label>
                        <input type="text" name="filtername" id="filtername" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="updatecat">Enter updated category:</label>
                        <input type="text" name="updatecat" id="updatecat" required>
                    </div>
                    <div class="form-group">
                        <label for="updateprice">Enter updated price:</label>
                        <input type="number" name="updateprice" id="updateprice" required>
                    </div> -->

                    <input type="submit" value="add filter">
                </form>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(() => {
        $('#mytable').DataTable()
        $('.filter-select').select2({});
    })

</script>
<script src="<?= base_url('/js/list.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>