<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script nonce="<?= esc($nonce) ?>" src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script nonce="<?= esc($nonce) ?>" src="https://cdn.datatables.net/plug-ins/2.3.3/api/sum().js"></script>

<script nonce="<?= esc($nonce) ?>">
  $(function(){

    // Datepicker
    $("#date").datepicker({
      dateFormat: "dd/mm/yy"
    });

  });
</script>