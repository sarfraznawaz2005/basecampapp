<style>
    #overlay__ {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        font-size: 20px;
        text-align: center;
        z-index: 5;
        width: 100%;
        height: 100%;
        display: none;
    }

    #overlay__ span {
        display: table-cell;
        vertical-align: middle;
    }
</style>

<div id="overlay__" align="center">
    <span>Please wait...</span>
</div>

@push('scripts')
    <script type="text/javascript">

        $(document).ready(hideOverlay);

        function showOverlay() {
            $('#overlay__').show();
        }

        function hideOverlay() {
            $('#overlay__').hide();
        }
    </script>
@endpush