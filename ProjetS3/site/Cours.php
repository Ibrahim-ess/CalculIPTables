<?php
$title="Cours";
require("ressources/header.php");?>

<!-- CORPS -->
<script>
function actu_iframe(){
    if(navigator.appName=="Microsoft Internet Explorer" ){
        if(document.all) document.all.id_iframe.style.height = document.frames("id_iframe" ).document.body.scrollHeight;
        else document.getElementById("id_iframe" ).style.height = document.getElementById("id_iframe" ).contentDocument.body.scrollHeight;
    }
    else{
        document.getElementById("id_iframe" ).style.height = document.getElementById("id_iframe" ).contentDocument.body.offsetHeight+"px";
    }
}
</script>
<div class="container">
        <iframe onLoad="actu_iframe();" src="/~butelle/Polys/" name="name_iframe" id="id_iframe" width=100% height=700px frameborder=no SCROLLING=auto>
        </iframe>
</div>
<?php require("ressources/footer.php");?>
