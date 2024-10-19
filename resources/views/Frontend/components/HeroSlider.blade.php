<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div class="carousel slide carousel-fade light_arrow" id="carouselExampleControls" data-bs-ride="carousel">
        <div class="carousel-inner" id="carouselSection">
            <!-- Carousel items will be added here dynamically -->
        </div>
        <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleControls" role="button">
            <i class="ion-chevron-left"></i>
        </a>
        <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleControls" role="button">
            <i class="ion-chevron-right"></i>
        </a>
    </div>
</div>

<script>
    async function Slider() {
        try {
            let res = await axios.get('api/productSliders');
            $("#carouselSection").empty();
            let sliders = res.data.data;

            sliders.forEach((element, i) => {
                let activeClass = (i === 0) ? ' active' : ''; 
                console.log(element.image); // Check the output in the console

                
                let SliderItem = `
        <div class="carousel-item${activeClass} background_bg" style="background-image: url('/uploads/product_sliders/${element.image}');"
;">
            <div class="banner_slide_content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-9">
                            <div class="banner_content overflow-hidden">
                                <h5 class="mb-3 staggered-animation font-weight-light" data-animation="slideInLeft" data-animation-delay="0.5s">${element.price}</h5>
                                <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="1s">${element.title}</h2>
                                <a class="btn btn-fill-out rounded-0 staggered-animation text-uppercase" href="" data-animation="slideInLeft" data-animation-delay="1.5s">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

                $("#carouselSection").append(SliderItem);
            });

        } catch (error) {
            console.error('Error fetching sliders:', error);
        }
    }

    // Call the Slider function to populate the carousel
    Slider();
</script>
