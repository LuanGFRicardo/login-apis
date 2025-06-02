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
        

        <p class="mt-4 text-muted">
            Isso é apenas um exemplo visual. A lógica de autenticação real será tratada no backend.
        </p>
    </div>
@endsection