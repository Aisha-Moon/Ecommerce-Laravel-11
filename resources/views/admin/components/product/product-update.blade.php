<div class="modal animated zoomIn" id="update-modal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategoryUpdate" type="text">
                                </select>

                                <label class="form-label">Brand</label>
                                <select class="form-control form-select" id="productBrandUpdate" type="text">
                                </select>

                                <label class="form-label">Remark</label>
                                <select class="form-control form-select" id="productRemarkUpdate" type="text">
                                    <option value="">Select Remark</option>
                                    <option value="popular">Popular</option>
                                    <option value="new">New</option>
                                    <option value="top">Top</option>
                                    <option value="special">Special</option>
                                    <option value="trending">Trending</option>
                                    <option value="regular">Regular</option>
                                </select>

                                <label class="form-label mt-2">Title</label>
                                <input class="form-control" id="productTitleUpdate" type="text">

                                <label class="form-label mt-2">Price</label>
                                <input class="form-control" id="productPriceUpdate" type="text">

                                <br />
                                <label class="form-label">Discount </label>
                                <input id="isDiscountCbUpdate" type="checkbox" onclick="toggleDiscountPriceTab()">
                                <br />

                                <div class="d-none" id="discountPriceTabUpdate">
                                    <label class="form-label mt-2">Discount Price</label>
                                    <input class="form-control" id="productDiscountPriceUpdate" type="text">
                                </div>

                                <label class="form-label mt-2">Stock</label>
                                <select class="form-select" id="productStockUpdate">
                                    <option value="1">Available</option>
                                    <option value="0">Out Of Stock</option>
                                </select>

                                <label class="form-label mt-2">Star</label>
                                <input class="form-control" id="productStarUpdate" type="text">

                                <label class="form-label mt-2">Short Description</label>
                                <textarea class="form-control" id="productShortDescriptionUpdate" name="" rows="3"></textarea>

                                <br />
                                <img class="w-15" id="oldImg" src="{{ asset('admin/images/default.jpg') }}" />
                                <br />

                                <label class="form-label">Image</label>
                                <input class="form-control" id="productImgUpdate" type="file" oninput="oldImg.src=window.URL.createObjectURL(this.files[0])">

                                <input class="d-none" id="updateID" type="text">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn bg-gradient-primary mx-2" id="update-modal-close" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button class="btn bg-gradient-success" id="save-btn" onclick="update()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('update-modal-close').addEventListener('click', () => {
        resetImageFields();
    });

    const resetImageFields = () => {
        document.getElementById('oldImg').src = "{{ asset('admin/images/default.jpg') }}";
        document.getElementById('update-form').reset();
    };

    toggleDiscountPriceTab = () => {
        let isDiscountCb = document.getElementById('isDiscountCbUpdate');
        let discountPriceTab = document.getElementById('discountPriceTabUpdate');
        if (isDiscountCb.checked) {
            discountPriceTab.classList.remove('d-none');
        } else {
            discountPriceTab.classList.add('d-none');
        }
    };

    async function FillCategoryDropDown() {
        $('#productCategoryUpdate').empty();
        $("#productCategoryUpdate").append(`<option value="">Select Category</option>`);
        let res = await axios.get("/api/categories");
        res = res.data.data;

        res.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`;
            $("#productCategoryUpdate").append(option);
        });
    }

    async function FillBrandDropDown() {
        $('#productBrandUpdate').empty();
        $("#productBrandUpdate").append(`<option value="">Select Brand</option>`);
        let res = await axios.get("/api/brands");
        res = res.data.data;

        res.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`;
            $("#productBrandUpdate").append(option);
        });
    }

    async function FillUpUpdateForm(id) {
        document.getElementById('updateID').value = id;
        showLoader();

        FillCategoryDropDown();
        FillBrandDropDown();

        let res = await axios.get(`/api/products/${id}`);
        res = res.data.data;
        hideLoader();
        document.getElementById('productCategoryUpdate').value = res['category_id'];
        document.getElementById('productBrandUpdate').value = res['brand_id'];
        document.getElementById('productRemarkUpdate').value = res['remark'];
        document.getElementById('productTitleUpdate').value = res['title'];
        document.getElementById('productPriceUpdate').value = res['price'];
        document.getElementById('productStockUpdate').value = res['stock'];
        const stockValue = res['stock'];

        // Set the selected value based on stock status
        const stockDropdown = document.getElementById('productStockUpdate');
        stockDropdown.value = stockValue ? "1" : "0"; // Update dropdown selection

        document.getElementById('productStarUpdate').value = res['star'];
        document.getElementById('productShortDescriptionUpdate').value = res['short_desc'];
        document.getElementById('oldImg').src = '{{ config('app.url') }}/' + res['image'];
        if (res['discount']) {
            document.getElementById('isDiscountCbUpdate').checked = true;
            document.getElementById('discountPriceTabUpdate').classList.remove('d-none');
            document.getElementById('productDiscountPriceUpdate').value = res['discount_price'];
        } else {
            document.getElementById('isDiscountCbUpdate').checked = false;
            document.getElementById('discountPriceTabUpdate').classList.add('d-none');
            document.getElementById('productDiscountPriceUpdate').value = '';
        }
    }

    async function update() {
        let category_id = document.getElementById('productCategoryUpdate').value;
        let brand_id = document.getElementById('productBrandUpdate').value;
        let remark = document.getElementById('productRemarkUpdate').value;
        let title = document.getElementById('productTitleUpdate').value;
        let is_discount = document.getElementById('isDiscountCbUpdate');
        let discount_price = document.getElementById('productDiscountPriceUpdate').value;
        let price = document.getElementById('productPriceUpdate').value;
        let stock = document.getElementById('productStockUpdate').value;
        let star = document.getElementById('productStarUpdate').value;
        let short_des = document.getElementById('productShortDescriptionUpdate').value;
        let image = document.getElementById('productImgUpdate').files[0];

        let product_id = document.getElementById('updateID').value;

        // Form Validation
        if (category_id.length === 0) {
            errorToast("Product Category Required!");
        } else if (brand_id.length === 0) {
            errorToast("Product Brand Required!");
        } else if (remark.length === 0) {
            errorToast("Product Remark Required!");
        } else if (title.length === 0) {
            errorToast("Product Title Required!");
        } else if (price.length === 0) {
            errorToast("Product Price Required!");
        } else if (short_des.length === 0) {
            errorToast("Product Short Description Required!");
        } else if (is_discount.checked && discount_price.length === 0) {
            errorToast("Discount Price Required!");
        } else {
            let formData = new FormData();

            // Append only fields that are present or changed
            if (category_id) formData.append('category_id', category_id);
            if (brand_id) formData.append('brand_id', brand_id);
            if (remark) formData.append('remark', remark);
            if (title) formData.append('title', title);
            if (price) formData.append('price', price);
            if (stock) formData.append('stock', stock);
            if (star) formData.append('star', star);
            if (short_des) formData.append('short_desc', short_des);
            if (is_discount.checked) {
                formData.append('discount', 1);
                formData.append('discount_price', discount_price);
            } else {
                formData.append('discount', 0);
            }

            // Handle image if changed
            if (image) {
                formData.append('image', image);
            }

            formData.append('_method', 'PUT'); // Indicate the method is PUT for update

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            };

            showLoader();
            try {
                let res = await axios.post(`/api/products/${product_id}`, formData, config);
                hideLoader();

                if (res.status === 200) {
                    successToast(res.data['msg']);
                    await getList(); // Refresh product list
                    $("#update-modal").modal('hide');
                    document.getElementById("update-form").reset();
                    document.getElementById('update-modal-close').click();
                } else {
                    errorToast(res.data['msg']);
                }
            } catch (error) {
                hideLoader();
                errorToast('Failed to update product. Please try again.');
            }
        }
    }
</script>
