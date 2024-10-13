<!-- Add Details Modal -->
<div class="modal fade" id="addDetailsModal" tabindex="-1" aria-labelledby="addDetailsLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="addDetailsLabel">Add Product Details</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form id="detailsForm" enctype="multipart/form-data">
                     <input type="hidden" id="productIdField" name="product_id">
                     <input type="hidden" id="productDetailsIdField" name="product_details_id">
 
                     <!-- Description Field -->
                     <div class="mb-3">
                         <label for="description" class="form-label">Description</label>
                         <textarea class="form-control" id="description" name="des" rows="3" required></textarea>
                     </div>
 
                     <!-- Color Field -->
                     <div class="mb-3">
                         <label for="color" class="form-label">Color</label>
                         <input type="text" class="form-control" id="color" name="color" maxlength="200" required>
                     </div>
 
                     <!-- Size Field -->
                     <div class="mb-3">
                         <label for="size" class="form-label">Size</label>
                         <input type="text" class="form-control" id="size" name="size" maxlength="200" required>
                     </div>
 
                     <!-- Image 1 Field with Existing Image Preview -->
                     <div class="mb-3">
                         <img id="existingImage1" src="{{ asset('admin/images/default.jpg') }}" alt="Existing Image 1" style="max-width: 100px; display: none;" />
                         <label for="img1" class="form-label">Image 1 (Required)</label>
                         <input type="file" class="form-control" id="img1" name="img1" accept="image/*" required onchange="previewImage(event, 'existingImage1')">
                     </div>
 
                     <!-- Image 2 Field with Existing Image Preview -->
                     <div class="mb-3">
                         <img id="existingImage2" src="{{ asset('admin/images/default.jpg') }}" alt="Existing Image 2" style="max-width: 100px; display: none;" />
                         <label for="img2" class="form-label">Image 2 (Optional)</label>
                         <input type="file" class="form-control" id="img2" name="img2" accept="image/*" onchange="previewImage(event, 'existingImage2')">
                     </div>
 
                     <!-- Image 3 Field with Existing Image Preview -->
                     <div class="mb-3">
                         <img id="existingImage3" src="{{ asset('admin/images/default.jpg') }}" alt="Existing Image 3" style="max-width: 100px; display: none;" />
                         <label for="img3" class="form-label">Image 3 (Optional)</label>
                         <input type="file" class="form-control" id="img3" name="img3" accept="image/*" onchange="previewImage(event, 'existingImage3')">
                     </div>
 
                     <!-- Image 4 Field with Existing Image Preview -->
                     <div class="mb-3">
                         <img id="existingImage4" src="{{ asset('admin/images/default.jpg') }}" alt="Existing Image 4" style="max-width: 100px; display: none;" />
                         <label for="img4" class="form-label">Image 4 (Optional)</label>
                         <input type="file" class="form-control" id="img4" name="img4" accept="image/*" onchange="previewImage(event, 'existingImage4')">
                     </div>
 
                     <!-- Submit Button -->
                     <button type="submit" class="btn btn-primary">Save Details</button>
                 </form>
             </div>
         </div>
     </div>
 </div>
 
 
<script>
 //preview image
 function previewImage(event, imgElementId) {
    const imgElement = document.getElementById(imgElementId);
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            imgElement.src = e.target.result; // Set the src of the image element to the file's data
            imgElement.style.display = 'block'; // Make the image visible
        };

        reader.readAsDataURL(file); // Read the file as a data URL
    } else {
        imgElement.style.display = 'none'; // Hide the image if no file is selected
    }
}
 //update 

 document.getElementById('detailsForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent default form submission

    // Initialize FormData
    let formData = new FormData();
    let productId = document.getElementById('productIdField').value;
    let productDetailsId = document.getElementById('productDetailsIdField').value; // Assume you have a hidden input for the product details ID

    formData.append('product_id', productId); // Append product ID

    // Append other form fields
    let description = document.getElementById('description').value;
    let color = document.getElementById('color').value;
    let size = document.getElementById('size').value;

    formData.append('des', description);  
    formData.append('color', color);     
    formData.append('size', size);       

    // Append each image if it exists
    let imgFields = ['img1', 'img2', 'img3', 'img4'];
    imgFields.forEach((imgField) => {
        let fileInput = document.getElementById(imgField).files[0];
        if (fileInput) {
            formData.append(imgField, fileInput);
        }
    });

    try {
        let response;
        if (productDetailsId) {
          formData.append('_method','PUT')
            // If product details ID exists, call the update endpoint
            response = await axios.post(`/api/productDetails/${productDetailsId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
        } else {
            // Otherwise, call the create endpoint
            response = await axios.post('/api/productDetails', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
        }

        if (response.status === 200 || response.status === 201) {
            successToast('Product details saved successfully');
            $('#addDetailsModal').modal('hide');
            // Optionally refresh the product list or update the UI
        } else {
            errorToast('Failed to save product details');
        }
    } catch (error) {
        console.error('Error saving product details:', error.response ? error.response.data : error);
        errorToast('An error occurred while saving the product details');
    }
});

     </script>
     