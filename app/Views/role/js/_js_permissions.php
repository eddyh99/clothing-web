<script nonce="<?= esc($nonce) ?>">
    document.addEventListener('DOMContentLoaded', function () {
        const menuCheckboxes = document.querySelectorAll('.menu-checkbox');

        menuCheckboxes.forEach(menu => {
            menu.addEventListener('change', function () {
                const menuId = this.id.replace('menu_', '');
                const permGroup = document.getElementById('permissions_' + menuId);
                const permCheckboxes = permGroup.querySelectorAll('.perm-checkbox');

                if (this.checked) {
                    permGroup.style.display = 'block';
                    permCheckboxes.forEach(cb => cb.checked = true);
                } else {
                    permGroup.style.display = 'none';
                    permCheckboxes.forEach(cb => cb.checked = false);
                }
            });
        });
    });
</script>
