<script nonce="<?= esc($nonce) ?>">
// === Show/hide password (umum, untuk semua halaman) ===
$(document).on("click", ".password-addon", function () {
    let input = $(this).siblings("input");
    let icon  = $(this).find("i");

    if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("ri-eye-fill").addClass("ri-eye-off-fill");
    } else {
        input.attr("type", "password");
        icon.removeClass("ri-eye-off-fill").addClass("ri-eye-fill");
    }
});

$(document).ready(function () {
    let passwordInput = $("#password-input");
    let confirmInput  = $("#cpass-input");

    // Hanya aktifkan validasi kalau ada confirm password (signup.php)
    if (confirmInput.length > 0) {
        let submitBtn = $("form button[type='submit']");

        function checkPasswordMatch() {
            let passVal  = passwordInput.val();
            let cpassVal = confirmInput.val();

            if (passVal === "" || cpassVal === "" || passVal !== cpassVal) {
                submitBtn.prop('disabled', true);
            } else {
                submitBtn.prop('disabled', false);
            }
        }

        passwordInput.on('keyup change', checkPasswordMatch);
        confirmInput.on('keyup change', checkPasswordMatch);

        $("form").on('submit', function (e) {
            if (passwordInput.val() !== confirmInput.val()) {
                e.preventDefault();
                alert("Password dan Confirm Password harus sama!");
            }
        });

        // Set kondisi awal
        checkPasswordMatch();
    }
});
</script>
