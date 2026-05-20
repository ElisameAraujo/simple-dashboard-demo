<script>
    (() => {
        try {
            const defaultTheme = document.documentElement.dataset.theme || 'light';
            const theme = localStorage.getItem('theme') || defaultTheme;

            document.documentElement.setAttribute('data-theme', theme);
        } catch {
            //
        }
    })();
</script>
