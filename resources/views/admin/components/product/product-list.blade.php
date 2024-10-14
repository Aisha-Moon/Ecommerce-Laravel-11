<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between">
                    <div class="align-items-center col">
                        <h4>Product</h4>
                    </div>
                    <div class="align-items-center col">
                        <button class="float-end btn bg-gradient-primary m-0" data-bs-toggle="modal" data-bs-target="#create-modal">Create</button>
                    </div>
                </div>
                <hr class="bg-dark" />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>Image</th>
                            <th>Title</th>
                            <th>Discount ? Price</th>
                            <th>Normal Price</th>
                            <th>Stock</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    getList();


    async function getList() {


        showLoader();
        let res = await axios.get("/api/products");

        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        tableData.DataTable().destroy();
        tableList.empty();
        let dataArray = res.data.data;
        dataArray.forEach(function(item, index) {
            let row = `<tr>
            <td><img class="width="50" height="50"  src="{{ config('app.url') }}/${item['image']}"></td>
            <td>${item['title']}</td>
            <td>${item['discount'] == 1 ? 'Yes - $' + item['discount_price'] : 'No'}</td>
            <td>${item['price']}</td>
            <td>${item['stock'] == 1 ? 'Available' : "Unavailable"}</td>
            <td>${item['brand']['name']}</td>
            <td>${item['category']['name']}</td>
            <td>${item['remark']}</td>
            <td>
                <button data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                <button data-id="${item['id']}" class="btn addDetailsBtn btn-sm btn-outline-primary">Add Details</button>
            </td>
         </tr>`
            tableList.append(row)
        })


        $('.editBtn').on('click', async function() {
            let id = $(this).data('id');
            // let detail = $(this).data('detail');
            await FillUpUpdateForm(id);
            $("#update-modal").modal('show');
        })

        $('.deleteBtn').on('click', function() {
            let id = $(this).data('id');
            let detail = $(this).data('detail');
            $("#delete-modal").modal('show');
            $("#deleteProductId").val(id);
            $("#deleteDetailId").val(detail);
        })
     // Event listener for the Add Details button
$('.addDetailsBtn').on('click', async function() {
    let productId = $(this).data('id'); // Get product ID from data attribute
    $("#productIdField").val(productId); // Set the product ID in the hidden field

    try {
        // Fetch existing product details
        let response = await axios.get(`/api/productDetails/${productId}`);

        if (response.data && response.data.data) {
            // Call FillUpDetailsForm to populate the form with existing data
            await FillUpDetailsForm(response.data.data);
            $("#addDetailsLabel").text('Edit Product Details');
        } else {
            // Prepare the modal for new entry
            $("#description").val('');
            $("#color").val('');
            $("#size").val('');
            $("#productDetailsIdField").val(''); 

            // Hide existing images
            $("#existingImage1").hide();
            $("#existingImage2").hide();
            $("#existingImage3").hide();
            $("#existingImage4").hide();

            $("#addDetailsLabel").text('Add Product Details');
        }

        // Show the modal
        $("#addDetailsModal").modal('show');
    } catch (error) {
        // Handle error when fetching product details
        console.error('Error fetching product details:', error);
        // Prepare the modal for new entry
        $("#description").val('');
        $("#color").val('');
        $("#size").val('');
        $("#productDetailsIdField").val(''); // Ensure this is cleared for new entry
        $("#addDetailsLabel").text('Add Product Details');

        // Show the modal
        $("#addDetailsModal").modal('show');
    }
});


        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            lengthMenu: [5, 10, 15, 20, 30]
        });

    }
</script>
