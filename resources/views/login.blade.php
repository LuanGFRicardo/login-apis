@extends('layouts.app')

@section('content')
    <div class="login-container">
        <h2 class="mb-4">Entrar com:</h2>

        <a href="{{ route('login.google') }}" class="btn-social btn-google">
            <i class="fab fa-google"></i> Continuar com Google
        </a>

        <a href="{{ route('login.microsoft') }}" class="btn-social btn-microsoft">
            <i class="fab fa-microsoft"></i> Continuar com Microsoft
        </a>

        <a href="{{ route('login.meta') }}" class="btn-social btn-meta">
            <i class="fab fa-facebook-f"></i> Continuar com Facebook
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>

    <script>
        (async () => {
            const fp = await FingerprintJS.load();
            const result = await fp.get();
            const visitorId = result.visitorId;

            const data = {
                fingerprint: visitorId,
                language: navigator.language,
                screen_resolution: [window.screen.width, window.screen.height],
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                platform: navigator.platform,
                webgl_vendor: null,
                webgl_renderer: null,
                device_memory: navigator.deviceMemory || null,
                hardware_concurrency: navigator.hardwareConcurrency || null,
                ad_block: false
            };

            // WebGL Info
            try {
                const canvas = document.createElement('canvas');
                const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
                const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
                data.webgl_vendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
                data.webgl_renderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
            } catch (e) {
                console.warn('WebGL info not available:', e);
            }

            fetch('/save-fingerprint', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
        })();
    </script>
@endpush