<footer class="main-footer">
    <div class="footer-left">
        Copyright &copy; {{ now()->year }} <div class="bullet"></div> Develop By <a href="#">Kelompok 13 TA</a>
    </div>
</footer>

<style>
    .main-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ffffff;
        border-top: 1px solid rgba(0,0,0,0.08);
        z-index: 9999;
        padding: 12px 20px;
        box-shadow: 0 -4px 12px rgba(16,24,40,0.03);
    }

    /* keep footer content layout consistent */
    .main-footer .footer-left { color: #374151; font-size: 0.95rem; }
</style>

<script>
    // ensure page content is not covered by the fixed footer
    document.addEventListener('DOMContentLoaded', function(){
        try {
            var footer = document.querySelector('.main-footer');
            if(footer){
                var h = footer.offsetHeight || 56;
                document.body.style.paddingBottom = h + 'px';
                // adjust on resize
                window.addEventListener('resize', function(){
                    var h2 = footer.offsetHeight || 56;
                    document.body.style.paddingBottom = h2 + 'px';
                });
            }
        } catch(e) { console && console.warn && console.warn('footer padding adjust failed', e); }
    });
</script>
