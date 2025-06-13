<?php    include"include/ustbar.php"; ?>

<center>
<div class="container">
    <video id="video" src="video/namik_kemal_uni.mp4" autoplay muted width="80%">
        Your browser does not support the video tag.
    </video>
</div>
</center>
<script>
    const video = document.getElementById('video');
    
    // 2.5 saniye sonra videoyu durdur
    setTimeout(function() {
        video.pause();
    }, 2500); // 2.5 saniye sonra durdurulacak
</script>


</div>
</body>
</html>




