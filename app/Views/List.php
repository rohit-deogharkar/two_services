<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>

    <link rel="stylesheet" href="<?= base_url('/assets/css/bootstrap.min.css'); ?>">

    <script src="<?= base_url('/assets/js/bootstrap.min.js'); ?>"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Parkinsans", sans-serif;
        }
    </style>

</head>

<body>

    <nav class="navbar bg-light items-center">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 pb-2 ">
                <h3 style="font-weight:700">Product Bazaar</h3>
            </span>

            <a href="/logout">
                <div class="text-center mr">
                    <i class="fa-solid fa-user" style="color:black; font-size:25px"></i>
                </div>
                <div style="color:black; margin-right:10px">
                    <?= session('user_id') ?>
                </div>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="headers my-3">
            <button type="button" class="btn btn-success " data-bs-toggle="modal" data-bs-target="#addproduct_modal">
                <i class="fa-solid fa-plus"></i> Add
            </button>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterproduct_modal">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadfile_modal">
                <i class="fa-solid fa-upload"></i> Upload
            </button>

            <button type="button" class="btn btn-warning">
                <a href="/download" style="color:white; text-decoration:none"><i class="fa-solid fa-download" style="color:white"></i> Download</a>
            </button>

        </div>

        <!-- filter bs done modal -->
        <div class="modal fade" id="filterproduct_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Filter Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/postfilter" id="filter-form" method="post">

                            <select name="filter-name" class="filter-select" style="width:100px">
                                <option value=""></option>
                                <?php foreach ($documents_all as $item): ?>
                                    <option value="<?= $item['productname'] ?>"><?= $item['productname'] ?></option>
                                <?php endforeach ?>
                            </select>

                            <select name="filter-cat" class="filter-select" style="width:100px">
                                <option value=""></option>
                                <?php foreach ($documents_all as $item): ?>
                                    <option value="<?= $item['productcategory'] ?>"><?= $item['productcategory'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <select name="filter-price" class="filter-select" style="width:100px">
                                <option value=""></option>
                                <?php foreach ($documents_all as $item): ?>
                                    <option value="<?= $item['productprice'] ?>"><?= $item['productprice'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" name="" id="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- upload modal bs done-->
        <div class="modal fade" id="uploadfile_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/upload-file" method="post" enctype="multipart/form-data" class="w"
                            id="uploadfileform">
                            <input type="file" name="myfile" id="" value="upload file">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" name="" id="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- add modal bs done -->
        <div class="modal fade" id="addproduct_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/postdata" method="post">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Prodcut Name:</label>
                                <input type="text" name="name" required class="form-control" id="recipient-name">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Product Category:</label>
                                <input class="form-control" name="category" id="message-text" name="category"
                                    required></input>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Product Price:</label>
                                <input class="form-control" name="price" id="message-text" required></input>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" name="" id="">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- udpate modal bs done -->
        <div class="modal fade" id="updateproduct_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/updatedetails" method="post" id="updateProductModal">
                            <input type="hidden" id="updateId" name="updateId">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Prodcut Name:</label>
                                <input type="text" class="form-control" name="updatename" id="old_name">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Product Category:</label>
                                <input class="form-control" name="updatecat" id="old_category"></input>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Product Price:</label>
                                <input class="form-control" name="updateprice" id="old_price"></input>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" name="" id="">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- table bs done -->
        <table class="table table-striped text-center border border-dark" id="mainTable">
            <thead class="table-primary">
                <tr>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Product Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->productname); ?></td>
                        <td><?php echo htmlspecialchars($item->productcategory); ?></td>
                        <td><strong>Rs.</strong> <?php echo htmlspecialchars($item->productprice); ?><strong>/-</strong>
                        </td>
                        <td>
                            <button data-id="<?= $item->_id ?>" class="btn btn-primary udpateButton" data-bs-toggle="modal"
                                data-bs-target="#updateproduct_modal" style="padding:2px;width:30px">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" style="padding:2px;width:30px"><a
                                    href="/delete/<?= $item->_id ?>" style="color:white"><i
                                        class="fa-solid fa-trash"></i></a></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        $(document).ready(() => {
            $('#mainTable').DataTable();

            $('.filter-select').select2({
                dropdownParent: $('#filterproduct_modal')
            });

            $('.udpateButton').click(function () {
                id = this.getAttribute('data-id')
                url = `update/${id}`
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        newData = JSON.parse(data)
                        console.log(newData._id.$oid)

                        $('#old_price').val(newData.productprice)
                        $('#old_name').val(newData.productname)
                        $('#old_category').val(newData.productcategory)
                        $('#updateId').val(newData._id.$oid)
                    }
                })
            })

        });

    </script>
    <script src="<?= base_url('/js/list.js') ?>"></script>
</body>

</html>