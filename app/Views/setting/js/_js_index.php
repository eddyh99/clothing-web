<script nonce="<?= esc($nonce) ?>">
    $(document).ready(function () {

        // === Tab 1 : Ganti Password ===

        // Fungsi toggle password
        $('.toggle-password').on('click', function () {
            let input = $(this).closest('.input-group').find('input');
            let icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            } else {
                input.attr('type', 'password');
                icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            }
        });

        // Tombol submit
        let submitBtn = $("#profileTab form button[type='submit']");
        let passwordInput = $("#new_password");
        let confirmInput  = $("#confirm_password");

        function checkPasswordMatch() {
            let passVal = passwordInput.val();
            let cpassVal = confirmInput.val();

            if (passVal === "" || cpassVal === "" || passVal !== cpassVal) {
                submitBtn.prop('disabled', true);
            } else {
                submitBtn.prop('disabled', false);
            }
        }

        // Event input password
        passwordInput.on('keyup change', function () {
            checkPasswordMatch();
        });

        confirmInput.on('keyup change', function () {
            checkPasswordMatch();
        });

        // Alert saat submit jika password dan confirm password tidak sama
        $("#profileTab form").on('submit', function (e) {
            if (passwordInput.val() !== confirmInput.val()) {
                e.preventDefault();
                alert("Password dan Confirm Password harus sama!");
            }
        });

        // Inisialisasi cek pertama kali
        checkPasswordMatch();

        // === Tab 2 : Ganti Subdomain ===
        let domainInput = $("#domainName");
        let domainSubmit = $("#appSettingTab form button[type='submit']");

        function checkDomainInput() {
            if (domainInput.val().trim() === "") {
                domainSubmit.prop('disabled', true);
            } else {
                domainSubmit.prop('disabled', false);
            }
        }

        // Cek pertama kali
        checkDomainInput();

        // Event input
        domainInput.on('keyup change', function() {
            checkDomainInput();
        });
    });
</script>
