<div class="section">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-md-6">
                 <div class="heading_s4 text-center">
                     <h2>Top Categories</h2>
                 </div>
                 <p class="text-center leads">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim Nullam nunc varius.</p>
             </div>
         </div>
         <div id="TopCategoryItem" class="row align-items-center">
            
 
         </div>
     </div>
 </div>
 
 <script>
     
 
     async function TopCategory() {
         let res = await axios.get('api/categories');
 
         $('#TopCategoryItem').empty();
         let categories = res.data.data;
         categories.forEach((element) => {
             let eachItem = ` <div class="p-2 col-2">
                 <div class="item">
                     <div class="categories_box">
                         <a href="by-category?id=${element.id}">
                             <img src="${element.image}" alt="cat_img1"/>
                             <span>${element.name}</span>
                         </a>
                     </div>
                 </div>
             </div>`;
             $("#TopCategoryItem").append(eachItem);
         });
 
     }
 </script>