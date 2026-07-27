<!-- Web Application Manifest -->
<link rel="manifest" href="{{ route('laravelpwa.manifest') }}">
<!-- Chrome for Android theme color -->
<meta name="theme-color" content="{{ config('laravelpwa.manifest.theme_color') }}">
<meta name="theme-color" content="{{ config('laravelpwa.manifest.theme_color') }}" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="{{ config('laravelpwa.manifest.theme_color') }}" media="(prefers-color-scheme: dark)">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable"
    content="{{ config('laravelpwa.manifest.display') == 'standalone' ? 'yes' : 'no' }}">
<meta name="application-name" content="{{ config('laravelpwa.manifest.short_name') }}">
<link rel="icon" sizes="" href="{{ config('laravelpwa.manifest.icons["72x72"]["path"]') }}">

<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable"
    content="{{ config('laravelpwa.manifest.display') == 'standalone' ? 'yes' : 'no' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="{{ config('laravelpwa.manifest.status_bar') }}">
<meta name="apple-mobile-web-app-title" content="{{ config('laravelpwa.manifest.short_name') }}">
<link rel="apple-touch-icon" href="{{ config('laravelpwa.manifest.icons["72x72"]["path"]') }}">
<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="{{ config('laravelpwa.manifest.background_color') }}">
<meta name="msapplication-TileImage" content="{{ config('laravelpwa.manifest.icons["72x72"]["path"]') }}">

<script type="text/javascript">
    // Initialize the service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/serviceworker.js?v=4', {
            scope: '.'
        }).then(function(registration) {
            // Registration was successful
            console.log('Smartwarehouse PWA: ServiceWorker registration successful with scope: ', registration
                .scope);
        }, function(err) {
            // registration failed :(
            console.log('Smartwarehouse PWA: ServiceWorker registration failed: ', err);
        });

        navigator.serviceWorker.getRegistrations()
            .then(regs => {
                console.log('jumlah:', regs.length);

                regs.forEach(r => {
                    console.log(r.scope);
                    console.log(r.active?.scriptURL);
                });
            });
    }
</script>

<!--start firebase-->
<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-analytics.js";
    import {
        getMessaging,
        getToken,
        onMessage
    } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-messaging.js";

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyC5zBWI5LhffiGH5hEVCC4av0AjDl_u3-s",
        authDomain: "smartwarehouse-45e10.firebaseapp.com",
        projectId: "smartwarehouse-45e10",
        storageBucket: "smartwarehouse-45e10.firebasestorage.app",
        messagingSenderId: "104983308896",
        appId: "1:104983308896:web:5ba2d5c0dee0f062167105",
        measurementId: "G-RS1PD30M3S"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    const messaging = getMessaging(app);

    async function initFirebaseNotification() {
        showLoader();
        try {
            // CHECK SUPPORT
            if (!window.Notification) {
                hideLoader();
                alert(
                    'Notification belum didukung.\n\n' +
                    'Gunakan Safari dan install aplikasi ke Home Screen.');
                return;
            }

            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                hideLoader();
                showToastError("Notification permission ditolak");
                return;
            }

            const registration = await navigator.serviceWorker.ready;
            const token = await getToken(messaging, {
                vapidKey: "BJwKaCkUEbb85__DtUhaeKlgimuxMdwyDS8OC-xTVtTYFtpr1S8fqoJGnzXc-1yzh_T5kZI8-98IbsIvxonf_Qs",
                serviceWorkerRegistration: registration
            });

            console.log("FCM TOKEN:", token);
            $.ajax({
                url: '/save-fcm-token',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    token: token
                },
                success: function(response) {
                    hideLoader();
                    showToastSuccess("Berhasil aktifkan notifikasi");
                }
            });
        } catch (err) {
            hideLoader();
            alert(err);
        }
    }
    window.initFirebaseNotification = initFirebaseNotification;
    // FOREGROUND MESSAGE
</script>
<!--end firebase-->
