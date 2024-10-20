<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
     <div class="container">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <div class="page-title">
                     <h1>Policy : <span id="policyName"></span></h1> 
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
         <div id="policy" class="row">
         </div>
     </div>
 </div>
 <script>
 async function Policy() {
    let searchParams = new URLSearchParams(window.location.search);
    let type = searchParams.get('type');

    // Set the policy title based on type
    if (type === "about") {
        $("#policyName").text("About Us");
    } else if (type === "refund") {
        $("#policyName").text("Refund Policy");
    } else if (type === "terms") {
        $("#policyName").text("Terms & Condition");
    } else if (type === "how to buy") {
        $("#policyName").text("How to Buy");
    } else if (type === "contact") {
        $("#policyName").text("Our Contact Details");
    } else if (type === "complain") {
        $("#policyName").text("How to put complain");
    }

    try {
        // Fetch policy details by type
        let res = await axios.get("/PolicyByType/" + type);
        
        if (res.data.des) {
            // Inject policy description into the HTML
            $("#policy").html(res.data.des);
        } else {
            $("#policy").html("<p>Policy description not available</p>");
        }
    } catch (error) {
        console.error(error);
        $("#policy").html("<p>Error fetching policy details.</p>");
    }
}

</script>