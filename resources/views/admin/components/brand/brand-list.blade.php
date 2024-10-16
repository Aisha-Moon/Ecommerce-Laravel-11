<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between">
                    <div class="align-items-center col">
                        <h4>Brand</h4>
                    </div>
                    <div class="align-items-center col">
                        <button class="float-end btn bg-gradient-primary m-0" data-bs-toggle="modal" data-bs-target="#create-modal">Create</button>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="table-responsive">
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th>No</th>
                                <th>Image</th>
                                <th>Brand</th>
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
</div>

<script>
    getList();


    async function getList() {


        showLoader();
        let res = await axios.get("/api/brands");




        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        tableData.DataTable().destroy();
        tableList.empty();



        let dataArray = res.data.data;


        dataArray.forEach(function(item, index) {
            // Ensure the image URL is correct by adding the base URL if necessary
            let imageUrl = item['image'] ? `{{ config('app.url') }}/${item['image']}` : 'path-to-your-default-image.png';

            // Generate the table row with the image
            let row = `<tr>
        <td>${index + 1}</td>
        <td><img src="${imageUrl}" width="50" height="50" alt="Brand Image"></td>
        <td>${item['name']}</td>
        <td>
            <button data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
            <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
        </td>
     </tr>`;

            // Append the row to the table
            tableList.append(row);
        });




        $('.editBtn').on('click', async function() {
            let id = $(this).data('id');
            await FillUpUpdateForm(id)
            $("#update-modal").modal('show');
        })

        $('.deleteBtn').on('click', function() {
            let id = $(this).data('id');
            $("#delete-modal").modal('show');
            $("#deleteID").val(id);
        })

        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            lengthMenu: [5, 10, 15, 20, 30]
        });

    }
</script>
