<?php
session_start();

// Jika konfirmasi diterima, lakukan logout
if (isset($_POST['confirm_logout']) && $_POST['confirm_logout'] == 'yes') {
    session_destroy();
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Konfirmasi Logout</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade show" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="false" style="display: block;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar?
      </div>
      <div class="modal-footer">
        <form method="post" action="logout.php">
          <button type="submit" name="confirm_logout" value="yes" class="btn btn-danger">Ya, Keluar</button>
          <a href="javascript:history.back()" class="btn btn-secondary">Tidak</a>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Menampilkan modal secara otomatis saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('logoutModal'), {
        backdrop: 'static',
        keyboard: false
    });
    myModal.show();
});
</script>

</body>
</html>