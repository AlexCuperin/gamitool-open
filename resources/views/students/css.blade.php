<style>

    #snackbar {
        visibility: hidden;
        max-width: 400px;
        margin-left: -125px;
        background-color: lightgrey;
        color: black;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 43%;
        bottom: 50%;
        font-size: 17px;
    }

    #snackbar.show {
        visibility: visible;
        -webkit-animation: fadein 2s;
        animation: fadein 2s;
    }

    @-webkit-keyframes fadein {
        from {bottom: 0; opacity: 0;}
        to {bottom: 50%; opacity: 1;}
    }

    @keyframes fadein {
        from {bottom: 0; opacity: 0;}
        to {bottom: 50%; opacity: 1;}
    }

    @-webkit-keyframes fadeout {
        from {bottom: 50%; opacity: 1;}
        to {bottom: 0; opacity: 0;}
    }

    @keyframes fadeout {
        from {bottom: 50%; opacity: 1;}
        to {bottom: 0; opacity: 0;}
    }

</style>
