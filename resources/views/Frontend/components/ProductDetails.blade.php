<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
                <div class="product-image">
                    <div class="product_img_box">
                        <img class="w-100" id="product_img1" src='frontend/images/product_img1.jpg' />
                    </div>
                    <div class="row p-2">
                        <a class="col-3 product_img_box p-1" href="#">
                            <img id="img1" src="frontend/images/product_small_img3.jpg" />
                        </a>
                        <a class="col-3 product_img_box p-1" href="#">
                            <img id="img2" src="frontend/images/product_small_img3.jpg" />
                        </a>
                        <a class="col-3 product_img_box p-1" href="#">
                            <img id="img3" src="frontend/images/product_small_img3.jpg" alt="product_small_img3" />
                        </a>
                        <a class="col-3 product_img_box p-1" href="#">
                            <img id="img4" src="frontend/images/product_small_img3.jpg" alt="product_small_img3" />
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="pr_detail">
                    <div class="product_description">
                        <h4 class="product_title" id="p_title"></h4>
                        <h1 class="price" id="p_price"></h1>
                    </div>
                    <div>
                        <p id="p_des"></p>
                    </div>
                    <div>
                        <p id="p_details"></p>
                    </div>
                </div>

                <label class="form-label">Size</label>
                <select class="form-select" id="p_size"></select>

                <label class="form-label">Color</label>
                <select class="form-select" id="p_color"></select>

                <hr />
                <div class="cart_extra">
                    <div class="cart-product-quantity">
                        <div class="quantity">
                            <input class="minus" type="button" value="-">
                            <input class="qty" id="p_qty" name="quantity" type="text" value="1" title="Qty" size="4">
                            <input class="plus" type="button" value="+">
                        </div>
                    </div>
                    <div class="cart_btn">
                        <button class="btn btn-fill-out btn-addtocart" type="button" onclick="AddToCart()"><i class="icon-basket-loaded"></i> Add to cart</button>
                        <a class="add_wishlist" href="#" onclick="AddToWishList()"><i class="icon-heart"></i></a>
                    </div>
                </div>

                <hr />

                <!-- Customer Reviews Section -->
                <!-- Customer Reviews Section -->
                <div class="section review-section">
                    <h4>Customer Reviews</h4>
                    <ul class="list-group" id="reviewList">
                        <!-- Reviews will be populated here by the JavaScript -->
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $('.plus').on('click', function() {
        if ($(this).prev().val()) {
            $(this).prev().val(+$(this).prev().val() + 1);
        }
    });

    $('.minus').on('click', function() {
        if ($(this).next().val() > 1) {
            $(this).next().val(+$(this).next().val() - 1);
        }
    });

    let searchParams = new URLSearchParams(window.location.search);
    let id = searchParams.get('id');

    async function productDetails() {
        try {
          $(".preloader").delay(90).fadeIn(100).removeClass('loaded');

            let res = await axios.get(`api/products/${id}`);
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');

            let Details = res.data.data;

            if (Details.details) {
                document.getElementById('product_img1').src = Details.details['img1'] || '';
                document.getElementById('img1').src = Details.details['img1'] || '';
                document.getElementById('img2').src = Details.details['img2'] || '';
                document.getElementById('img3').src = Details.details['img3'] || '';
                document.getElementById('img4').src = Details.details['img4'] || '';
                document.getElementById('p_details').innerHTML = Details.details['des'] || 'No description available';

                let size = Details.details['size'] ? Details.details['size'].split(',') : [];
                let color = Details.details['color'] ? Details.details['color'].split(',') : [];

                let SizeOption = `<option value=''>Choose Size</option>`;
                $("#p_size").append(SizeOption);
                size.forEach((item) => {
                    let option = `<option value='${item}'>${item}</option>`;
                    $("#p_size").append(option);
                });

                let ColorOption = `<option value=''>Choose Color</option>`;
                $("#p_color").append(ColorOption);
                color.forEach((item) => {
                    let option = `<option value='${item}'>${item}</option>`;
                    $("#p_color").append(option);
                });

                $('#img1').on('click', function() {
                    $('#product_img1').attr('src', Details.details['img1'] || '');
                });
                $('#img2').on('click', function() {
                    $('#product_img1').attr('src', Details.details['img2'] || '');
                });
                $('#img3').on('click', function() {
                    $('#product_img1').attr('src', Details.details['img3'] || '');
                });
                $('#img4').on('click', function() {
                    $('#product_img1').attr('src', Details.details['img4'] || '');
                });
            } else {
                document.getElementById('product_img1').src = '';
                document.getElementById('p_details').innerHTML = 'No details available for this product.';
            }

            document.getElementById('p_title').innerText = Details['title'] || 'No title available';
            document.getElementById('p_price').innerText = `$${Details['price'] || '0.00'}`;
            document.getElementById('p_des').innerText = Details['short_desc'] || 'No short description available';
        } catch (error) {
            console.error("Error fetching product details:", error);
        }
    }

    async function productReview() {
        let res = await axios.get("/ListReviewByProduct/" + id);
        let Details = await res.data['data'];

        // Check if there are any reviews
        if (Details.length === 0) {
            // Hide the review section if no reviews are found
            document.querySelector('.review-section').style.display = 'none';
        } else {
            // Show the review section and populate it with reviews
            document.querySelector('.review-section').style.display = 'block';

            $("#reviewList").empty();

            Details.forEach((item, i) => {
                let each = `<li class="list-group-item">
                <h6>${item['customer_profile']['cus_name']}</h6>
                <p class="m-0 p-0">${item['description']}</p>
                <div class="rating_wrap">
                    <div class="rating">
                        <div class="product_rate" style="width:${parseFloat(item['rating']) * 20}%"></div>
                    </div>
                </div>
            </li>`;
                $("#reviewList").append(each);
            });
        }
    }


    productReview();

    async function AddToCart() {
        try {
            let p_size = document.getElementById('p_size').value;
            let p_color = document.getElementById('p_color').value;
            let p_qty = document.getElementById('p_qty').value;

            if (p_size.length === 0) {
                alert("Product Size Required !");
            } else if (p_color.length === 0) {
                alert("Product Color Required !");
            } else if (p_qty === 0) {
                alert("Product Qty Required !");
            } else {
                $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
                let res = await axios.post("/api/CreateCartList", {
                    "product_id": id,
                    "color": p_color,
                    "size": p_size,
                    "quantity": p_qty
                });
                $(".preloader").delay(90).fadeOut(100).addClass('loaded');
                if (res.status === 200) {
                    alert("Request Successful")
                }
            }

        } catch (e) {
            if (e.response.status === 401) {
                sessionStorage.setItem("last_location", window.location.href)
                window.location.href = "/login"
            }
        }
    }


    async function AddToWishList() {
        try {
            $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
            let res = await axios.get("/api/CreateWishList/" + id);
            console.log(res.data);
            
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
            if (res.status === 200) {
                alert("Request Successful")
            }
        } catch (e) {
            if (e.response.status === 401) {
                sessionStorage.setItem("last_location", window.location.href)
                window.location.href = "/login"
            }
        }
    }


    async function AddReview() {
        let reviewText = document.getElementById('reviewTextID').value;
        let reviewScore = document.getElementById('reviewScore').value;
        if (reviewScore.length === 0) {
            alert("Score Required !")
        } else if (reviewText.length === 0) {
            alert("Review Required !")
        } else {
            $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
            let postBody = {
                description: reviewText,
                rating: reviewScore,
                product_id: id
            }
            let res = await axios.post("/CreateProductReview", postBody);
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
            await productReview();
        }


    }

   
</script>
