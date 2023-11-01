<?php
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Gabriel Dzuman">
    <title>LegatumProject - Login</title>

<link href="./dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <link href="style.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form>
    <a href="./index.php" class="icon">
    <img src="./img/lp.png" width="75x" height="75px"><br><br></a>
    <h1 class="h3 mb-3 fw-normal">Faça seu Login</h1><br>
    <div class="form-floating">
      <input type="email" class="form-control" id="floatingInput" placeholder="nome@exemplo.com">
      <label for="floatingInput">E-mail</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="sua senha aqui">
      <label for="floatingPassword">Senha</label>
    </div>
    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="lembre-me"> Lembre-me</label>
        <a href="#"> Esqueci a senha</a>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted">&copy; LegatumProject - 2023</p>
    
  </form>
</main> 
  </body>
</html>
