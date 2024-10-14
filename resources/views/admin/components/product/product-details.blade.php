<!-- Add Details Modal -->
<div class="modal fade" id="addDetailsModal" aria-labelledby="addDetailsLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDetailsLabel">Add Product Details</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailsForm">
                    <input id="productIdField" name="product_id" type="hidden">
                    <input id="productDetailsIdField" name="product_details_id" type="hidden">
                    <input id="removeImg1" name="remove_img1" type="hidden" value="0">
                    <input id="removeImg2" name="remove_img2" type="hidden" value="0">
                    <input id="removeImg3" name="remove_img3" type="hidden" value="0">
                    <input id="removeImg4" name="remove_img4" type="hidden" value="0">

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="des" rows="3" required></textarea>
                    </div>

                    <!-- Color Field -->
                    <div class="mb-3">
                        <label class="form-label" for="color">Color</label>
                        <input class="form-control" id="color" name="color" type="text" maxlength="200" required>
                    </div>

                    <!-- Size Field -->
                    <div class="mb-3">
                        <label class="form-label" for="size">Size</label>
                        <input class="form-control" id="size" name="size" type="text" maxlength="200" required>
                    </div>

                    <!-- Image Fields -->
                    @foreach (range(1, 4) as $index)
                        <div class="mb-3">
                            <img id="existingImage{{ $index }}" src="{{ asset('admin/images/default.jpg') }}" alt="Existing Image {{ $index }}" style="max-width: 100px; display: none;" />
                            <label class="form-label" for="img{{ $index }}">Image {{ $index }} ({{ $index === 1 ? 'Required' : 'Optional' }})</label>
                            <input class="form-control" id="img{{ $index }}" name="img{{ $index }}" type="file" accept="image/*" onchange="previewImage(event, 'existingImage{{ $index }}')" {{ $index === 1 ? 'required' : '' }}>
                            <button class="btn btn-danger btn-sm mt-2" type="button" onclick="removeImage('img{{ $index }}', 'existingImage{{ $index }}', 'removeImg{{ $index }}')">Remove Image {{ $index }}</button>
                        </div>
                    @endforeach

                    <button class="btn btn-primary" onclick="saveDetails()">Save Details</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event, imgElementId) {
        const imgElement = document.getElementById(imgElementId);
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imgElement.src = e.target.result;
                imgElement.style.display = 'block'; // Make the image visible
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            imgElement.style.display = 'none'; // Hide the image if no file is selected
        }
    }

    // Updated remove image function
    function removeImage(inputId, imgElementId, removeImgFieldId) {
        document.getElementById(inputId).value = ""; // Clear the file input
        const imgElement = document.getElementById(imgElementId);
        imgElement.style.display = 'none'; // Hide the image
        imgElement.src = "{{ asset('admin/images/default.jpg') }}"; // Reset to default image
        document.getElementById(removeImgFieldId).value = "1"; // Set the corresponding hidden field to mark the image for deletion
    }
    async function FillUpDetailsForm(details) {
        $("#description").val(details.des || '');
        $("#color").val(details.color || '');
        $("#size").val(details.size || '');
        $("#productDetailsIdField").val(details.id || ''); // Set the hidden input for product details ID

        // Show existing images if they exist
        if (details.img1) {
            $("#existingImage1").attr("src", '{{ config('app.url') }}/' + details.img1).show();
        } else {
            $("#existingImage1").hide();
        }

        if (details.img2) {
            $("#existingImage2").attr("src", '{{ config('app.url') }}/' + details.img2).show();
        } else {
            $("#existingImage2").hide();
        }

        if (details.img3) {
            $("#existingImage3").attr("src", '{{ config('app.url') }}/' + details.img3).show();
        } else {
            $("#existingImage3").hide();
        }

        if (details.img4) {
            $("#existingImage4").attr("src", '{{ config('app.url') }}/' + details.img4).show();
        } else {
            $("#existingImage4").hide();
        }
    }
    // Update form submission
    async function saveDetails() {
        const productDetailsId = document.getElementById('productDetailsIdField').value;
        // const formData = new FormData(document.getElementById('detailsForm'));
        
        const des = document.getElementById('description').value;
        const color = document.getElementById('color').value;
        const size = document.getElementById('size').value;
        const product_id = document.getElementById('productIdField').value;

        const removeImg1 = document.getElementById('removeImg1').value;
        const removeImg2 = document.getElementById('removeImg2').value;
        const removeImg3 = document.getElementById('removeImg3').value;
        const removeImg4 = document.getElementById('removeImg4').value;

        const img1 = document.getElementById('img1').files[0];
        const img2 = document.getElementById('img2').files[0];
        const img3 = document.getElementById('img3').files[0];
        const img4 = document.getElementById('img4').files[0];

        const formData = new FormData();

        const images = [img1, img2, img3, img4];
        images.forEach(function(img, index) {
           if(img){
            formData.append(`img${index+1}`, img);
           }
        });

        formData.append('removeimg1', removeImg1);
        formData.append('removeimg2', removeImg2);
        formData.append('removeimg3', removeImg3);
        formData.append('removeimg4', removeImg4);
        formData.append('des', des);
        formData.append('color', color);
        formData.append('size', size);
        formData.append('product_id', product_id);

        // Form Validation
        if (!formData.get('des')) {
            errorToast("Description is required!");
            return;
        }
        if (!formData.get('color')) {
            errorToast("Color is required!");
            return;
        }
        if (!formData.get('size')) {
            errorToast("Size is required!");
            return;
        }

        for(let [key, value] of formData){
            console.log(key, value);
        }

        try {
            let response;
            if (productDetailsId) {
                formData.append('_method', 'PUT'); // Indicate update
                response = await axios.post(`/api/productDetails/${productDetailsId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                console.log(response)
            } else {
                response = await axios.post('/api/productDetails', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                console.log(response);
            }

            if (response.status === 200 || response.status === 201) {
                successToast('Product details saved successfully');zzzzaz
                $('#addDetailsModal').modal('hide');
            } else {
                errorToast('Failed to save product details');
            }
        } catch (error) {
            console.error('Error saving product details:', error.response ? error.response.data : error);
            errorToast('An error occurred while saving the product details');
        }
    }
</script>
