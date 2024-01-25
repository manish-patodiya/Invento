<div class="card-body p-5 text-center" id="card-otp-verification" style="display:none">
    <a class="fs-4 text-dark" href="<?=base_url()?>"><img src="<?=base_url('public/img/Vishwakarma.jpg')?>" width="40"
            height="40"> Jangid Samaj</a>
    <h3 class="mt-2 mb-4 text-uppercase">OTP</h3>
    <span class="mobile-text">Enter the code we just send on
        your mobile phone <br><b id="otp-phone" class="text-danger"></b></span>
    <form method="post" id="frm-otp-verification">
        <div class="mx-5 mt-5">
            <input type="number" name="otp" class="form-control" id="otp" autofocus="">
            <span id="otp-err" class="text-danger" style="display:none"></span>
            <input type="hidden" name="id" id="user-id">
        </div>
        <div class="text-center mt-2">
            <button type="submit" class="btn  btn-success" id="btn-verify">
                Verify
            </button>
        </div>
    </form>
    <div class="text-center mt-3">
        <span class="d-block mobile-text">Don't receive the code?</span>
        <a href="javascript:void(0)" id="resend-otp" class="font-weight-bold text-danger cursor"
            style="display:none">Resend</a>
        <span class="text-secondary" id="regenerate-timer">resend in 59 seconds</span>
    </div>
</div>

<script>
document.getElementById('otp').addEventListener('input', function(e) {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})/);
    e.target.value = x[1] + x[2]
});
</script>