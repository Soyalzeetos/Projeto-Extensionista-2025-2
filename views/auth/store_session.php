<?php
if (!isset($userDataJson)) {
    header('Location: /');
    exit;
}
?>
<script>
    localStorage.setItem('user_data', '<?= $userDataJson ?>');

    if (window.location.pathname === '/login') {
        window.location.href = '/';
    } else {
        window.location.reload();
    }
</script>

<?php
?>
<noscript>
    <meta http-equiv="refresh" content="0;url=/">
</noscript>
