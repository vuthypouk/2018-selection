<!DOCTYPE html>
<html>
<style>
body, html {
    height: 100%;
    margin: 0;
}

.bgimg {
    background-image: url('<?php echo base_url() ?>assets/images/background/1.jpg');
    height: 100%;
    background-position: center;
    background-size: cover;
    position: relative;
    color: white;
    font-family: "Courier New", Courier, monospace;
    font-size: 25px;
}

.topleft {
    position: absolute;
    top: 0;
    left: 16px;
}

.bottomleft {
    position: absolute;
    bottom: 0;
    left: 16px;
}

.middle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

hr {
    margin: auto;
    width: 40%;
}
</style>
<body>
<!-- page coming soon -->
<div class="bgimg">
  <div class="topleft">
    <p>MAP</p>
  </div>
  <div class="middle">
    <h1>COMING SOON</h1>
  </div>
  <div class="bottomleft">
    <p>Province disttribution will display when we complete it</p>
  </div>
</div>

</body>
</html>