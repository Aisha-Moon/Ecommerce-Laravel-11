<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
     <div class="container">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <div class="page-title">
                     <h1>Category: <span id="CatName"></span></h1> <!-- The category name will be updated dynamically -->
                 </div>
             </div>
             <div class="col-md-6">
                 <ol class="breadcrumb justify-content-md-end">
                     <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">This Page</a></li>
                 </ol>
             </div>
         </div>
     </div>
 </div>
 
 <div class="mt-5">
     <div class="container my-5">
         <div id="byCategoryList" class="row">
             <!-- Products will be dynamically added here -->
         </div>
     </div>
 </div>
 
 <script>
     async function fetchProductsByCategory() {
         let searchParams = new URLSearchParams(window.location.search);
         let categoryId = searchParams.get('id');  // Assuming the category ID is passed as a query param 'id'
         
         try {
             let res = await axios.get(`/ListProductByCategory/${categoryId}`);
             let products = res.data.data;
 
             let categoryList = document.getElementById('byCategoryList');
             let catName = document.getElementById('CatName');
             
             categoryList.innerHTML = '';  // Clear any existing content
             
             // Assuming the category name is included in the response
             catName.textContent = products[0]['category']['name'];
             
             // Loop through products and add them to the page
             products.forEach(product => {
                 let productHtml = `
                     <div class="col-lg-3 col-md-4 col-6">
                         <div class="product">
                             <div class="product_img">
                                 <a href="/details?id=${product.id}">
                                     <img src="${product.image}" alt="product_img">
                                 </a>
                                 <div class="product_action_box">
                                     <ul class="list_none pr_action_btn">
                                         <li><a href="/details?id=${product.id}" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                                     </ul>
                                 </div>
                             </div>
                             <div class="product_info">
                                 <h6 class="product_title"><a href="/details?id=${product.id}">${product.title}</a></h6>
                                 <div class="product_price">
                                     <span class="price">$${product.price}</span>
                                 </div>
                                 <div class="rating_wrap">
                                     <div class="rating">
                                         <div class="product_rate" style="width:${product.star}%"></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>`;
                 
                 categoryList.insertAdjacentHTML('beforeend', productHtml);
             });
 
         } catch (error) {
             console.error('Failed to fetch products:', error);
         }
     }
 
     // Call the function when the page loads
     document.addEventListener('DOMContentLoaded', fetchProductsByCategory);
 </script>
 