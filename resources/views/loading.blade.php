<style>
    .sin_scroll{
        overflow-x: hidden;
        color:black;
        font-family:'Opens Sans',helvetica;
        height:100%;
        width:101%;
        margin: 0px;
        padding: 0px;
    }
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100vh;
        z-index: 9999;
        background-color: rgba(0, 0, 0, 0.51);
        opacity: .8;
    }
    .loading_message{
        position:absolute;
        top: 50%;
        left: 50%;
        width:10em;
        height:10em;
        margin-top: -5em;
        margin-left: -7rem;
        /* background-color: #0b2e13;*/
    }
    .loading_message > img{
        width: 100%;
        height: 100%;
    }
</style>
<div class="loader">
    <div class="loading_message">
        <img src="{{asset('/public/images/loading2.gif')}}"/>
    </div>
</div>
<script type="text/javascript">
        $("html").addClass("sin_scroll");
        $("body").addClass("sin_scroll");
        $( document ).ready(function() {
        $(".loader").fadeOut("slow");
        $("html").removeClass("sin_scroll");
        $("body").removeClass("sin_scroll");
    });
</script>
