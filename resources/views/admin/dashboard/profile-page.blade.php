@extends('admin.layout.sidenav-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Admin Profile</h1>

    <div class="row">
        <div class="col-md-4 p-2">
            <label class="form-label">Admin Email</label>
            <input type="email" id="admin_email" class="form-control form-control-sm" readonly/>
        </div>
    </div>

    <hr/>
</div>

<script>
    async function loadAdminProfile() {
        let res = await axios.get("/admin_profile");
        console.log(res);
        
        if (res.data.data !== null) {
            document.getElementById('admin_email').value = res.data.data.admin_email;
        }
    }

    // Call loadAdminProfile on page load
    document.addEventListener("DOMContentLoaded", loadAdminProfile);
</script>
@endsection
