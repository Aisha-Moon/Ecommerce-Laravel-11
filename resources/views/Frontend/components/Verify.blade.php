<div class="login_register_wrap section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-10">
                <div class="login_wrap">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1">
                            <h3>Verification</h3>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" id="code" name="email" type="text" required="" placeholder="Verification Code">
                        </div>
                        <div class="form-group mb-3">
                            <button class="btn btn-fill-out btn-block" name="login" type="submit" onclick="verify()">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
 async function verify() {
    let code = document.getElementById('code').value;
    let email = sessionStorage.getItem('email');
    if (code.length === 0) {
        alert("Code Required!");
    } else {
        $(".preloader").delay(90).fadeIn(100).removeClass('loaded');
        try {
            let res = await axios.get("api/VerifyLogin/" + email + "/" + code);
            if (res.status === 200) {
                // Check if a redirect URL is provided in the response
                if (res.data.redirect) {
                    window.location.href = res.data.redirect; // Redirect based on the response
                }
            } else {
                $(".preloader").delay(90).fadeOut(100).addClass('loaded');
                alert("Request Failed");
            }
        } catch (error) {
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
            alert("Request Failed");
        }
    }
}


</script>
