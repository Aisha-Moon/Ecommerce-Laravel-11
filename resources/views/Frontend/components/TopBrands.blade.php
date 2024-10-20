<div class="section">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-md-6">
                 <div class="heading_s4 text-center">
                     <h2>Top Brands</h2>
                 </div>
                 <p class="text-center leads">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim Nullam nunc varius.</p>
             </div>
         </div>
         <div id="TopBrandItem" class="row align-items-center">
             
 
            
 
             <!-- Add more brands as needed -->
         </div>
     </div>
 </div>
 
 <script>
 

     async function TopBrand() {
         let res = await axios.get('api/brands');
 
         $('#TopBrandItem').empty();
         let brands = res.data.data;
         brands.forEach((element) => {
             let eachItem = ` <div class="p-2 col-2">
                 <div class="item">
                     <div class="categories_box">
                         <a href="by-brands?id=${element.id}">
                             <img src="${element.image}" alt="cat_img1"/>
                             <span>${element.name}</span>
                         </a>
                     </div>
                 </div>
             </div>`;
             $("#TopBrandItem").append(eachItem);
         });
 
     }
 </script>
 