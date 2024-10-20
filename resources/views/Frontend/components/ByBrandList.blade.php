<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
     <div class="container">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <div class="page-title">
                     <h1>Brand: <span id="BrandName"></span></h1> 
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
         <div id="byBrandList" class="row">
             <!-- Products will be dynamically added here -->
         </div>
     </div>
 </div>
 
 <script>
     async function fetchProductsByBrand() {
         let searchParams = new URLSearchParams(window.location.search);
         let brandId = searchParams.get('id'); 
         
         try {
             let res = await axios.get(`/ListProductByBrand/${brandId}`);
             let products = res.data.data;
 
             let BrandList = document.getElementById('byBrandList');
             let BrandName = document.getElementById('BrandName');
             
             BrandList.innerHTML = '';  // Clear any existing content
             
             BrandName.textContent = products[0]['brand']['name'];
             
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
                 
                     BrandList.insertAdjacentHTML('beforeend', productHtml);
             });
 
         } catch (error) {
             console.error('Failed to fetch products:', error);
         }
     }
 
     // Call the function when the page loads
     document.addEventListener('DOMContentLoaded', fetchProductsByBrand);
 </script>
 