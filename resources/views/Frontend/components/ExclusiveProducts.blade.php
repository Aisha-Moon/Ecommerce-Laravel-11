<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-6">
             <div class="heading_s1 text-center">
                 <h2>Exclusive Products</h2>
             </div>
         </div>
     </div>
     <div class="row">
         <div class="col-12">
             <div class="tab-style1">
                 <ul class="nav nav-tabs justify-content-center" role="tablist">
                     <li class="nav-item">
                         <a class="nav-link active" id="popular-tab" data-bs-toggle="tab" href="#Popular" role="tab" aria-controls="popular" aria-selected="true">Popular</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" id="new-tab" data-bs-toggle="tab" href="#New" role="tab" aria-controls="new" aria-selected="false">New</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" id="top-tab" data-bs-toggle="tab" href="#Top" role="tab" aria-controls="top" aria-selected="false">Top</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" id="special-tab" data-bs-toggle="tab" href="#Special" role="tab" aria-controls="special" aria-selected="false">Special</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" id="trending-tab" data-bs-toggle="tab" href="#Trending" role="tab" aria-controls="trending" aria-selected="false">Trending</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" id="regular-tab" data-bs-toggle="tab" href="#Regular" role="tab" aria-controls="regular" aria-selected="false">Regular</a>
                     </li>
                 </ul>
             </div>
             <div class="tab-content">
                 <div class="tab-pane fade show active" id="Popular" role="tabpanel" aria-labelledby="popular-tab">
                     <div id="PopularItem" class="row shop_container"></div>
                 </div>
                 <div class="tab-pane fade" id="New" role="tabpanel" aria-labelledby="new-tab">
                     <div id="NewItem" class="row shop_container"></div>
                 </div>
                 <div class="tab-pane fade" id="Top" role="tabpanel" aria-labelledby="top-tab">
                     <div id="TopItem" class="row shop_container"></div>
                 </div>
                 <div class="tab-pane fade" id="Special" role="tabpanel" aria-labelledby="special-tab">
                     <div id="SpecialItem" class="row shop_container"></div>
                 </div>
                 <div class="tab-pane fade" id="Trending" role="tabpanel" aria-labelledby="trending-tab">
                     <div id="TrendingItem" class="row shop_container"></div>
                 </div>
                 <div class="tab-pane fade" id="Regular" role="tabpanel" aria-labelledby="regular-tab">
                     <div id="RegularItem" class="row shop_container"></div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <script>
 async function loadProductsByCategory(categoryName, containerId) {
    try {
        let res = await axios.get(`/ListProductByRemark/${categoryName}`);
        const container = document.getElementById(containerId);
        container.innerHTML = ''; // Clear container
        let products = res.data.data;
        
        products.forEach(product => {
            let productHtml = `
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="product">
                        <div class="product_img">
                            <a href="/details?id=${product.id}">
                                <img src="${product.image}" alt="product_img">
                            </a>
                        </div>
                        <div class="product_info">
    
    
                            <h6 class="product_title"> <a href="/details?id=${product.id}">  ${product.title} </a></h6>
                            <div class="product_price">$ ${product.price}</div>
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', productHtml);
        });
    } catch (error) {
        console.error(`Error loading ${categoryName} products:`, error);
    }
}

</script>