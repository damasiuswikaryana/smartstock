<!-- Required Js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/i18next.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/i18nextHttpBackend.min.js') }}"></script>
<script src="{{ asset('assets/js/icon/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>
<script src="{{ asset('assets/js/multi-lang.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/number-sperator.js') }}"></script>
<script src="{{ asset('assets/js/gallery.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    change_box_container('false');
    layout_caption_change('true');
    layout_rtl_change('false');
    preset_change('preset-2');

    const loading = document.querySelector('.loader');
    let fadeInInterval, fadeOutInterval;

    function showLoader(timing = 3) {
        if (!loading.classList.contains('is-active')) {
            clearInterval(fadeInInterval);
            clearInterval(fadeOutInterval);
            let newValue = 0;
            loading.classList.add('is-active');
            loading.style.display = 'flex';
            loading.style.opacity = 0;
            fadeInInterval = setInterval(() => {
                if (newValue < 1) {
                    newValue += 0.01;
                    loading.style.opacity = newValue;
                } else {
                    clearInterval(fadeInInterval);
                }
            }, timing);
        }
    }

    function hideLoader(timing = 3) {
        clearInterval(fadeInInterval);
        clearInterval(fadeOutInterval);
        let newValue = 1;
        fadeOutInterval = setInterval(() => {
            if (newValue > 0) {
                newValue -= 0.01;
                loading.style.opacity = newValue;
            } else {
                loading.style.opacity = 0;
                loading.style.display = 'none';
                loading.classList.remove('is-active');
                clearInterval(fadeOutInterval);
            }
        }, timing);
    }

    function showToastSuccess(message) {
        Toastify({
            text: `<i class='fa fa-check-circle text-success me-2'></i> ${message}`,
            escapeMarkup: false,
            duration: 5000,
            gravity: "bottom",
            position: "center",
            style: {
                color: "#fff",
                background: "#333",
            },
        }).showToast();
    }

    function showToastError(message) {
        Toastify({
            text: `<i class='fa fa-times-circle text-danger me-2'></i> ${message}`,
            escapeMarkup: false,
            duration: 5000,
            gravity: "bottom",
            position: "center",
            style: {
                color: "#fff",
                background: "#333",
            },
        }).showToast();
    }

    @if (Session::has('success'))
        showToastSuccess("{{ \Session::get('success') }}");
    @endif
    @if (Session::has('error'))
        showToastError("{{ \Session::get('error') }}");
    @endif

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
</script>
