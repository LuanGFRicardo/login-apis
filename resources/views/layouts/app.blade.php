<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBn5+syVGaTYRBOyHh8mI61ZGYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            text-decoration: none; /* Garante que o link não tenha sublinhado */
            color: #fff; /* Cor do texto padrão */
        }
        .btn-google {
            background-color: #db4437; /* Google Red */
        }
        .btn-microsoft {
            background-color: #0078d4; /* Microsoft Blue */
        }
        .btn-meta {
            background-color: #1877f2; /* Facebook Blue */
        }
        .btn-social i {
            margin-right: 10px;
            font-size: 1.3em;
        }
    </style>
</head>
<body>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Você pode adicionar JavaScript para lidar com os cliques dos botões aqui,
        // por exemplo, redirecionando para as rotas de API.
        // Por enquanto, os links HREF já farão o trabalho de redirecionar.
    </script>
</body>
</html>