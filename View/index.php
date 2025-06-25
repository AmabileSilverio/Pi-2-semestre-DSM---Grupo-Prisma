<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <style>

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }

        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

     
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #800b0b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #800b0b;
        }

        
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: #333;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 5px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            
        }

        .radio-container {
            margin-bottom: 10px;
            text-align: left;
        }
        
        .radio-container input[type="radio"] {
            margin-right: 5px; 
            vertical-align: middle; /
        }

        .radio-label {
            display: inline-block;
            margin-left: 1px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($erro)) : ?>
      <p style="color:red; text-align:center;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form action="/Pi2/controller/login.php" method="post">

      <label for="email">Nome de Usuário:</label>
      <input type="text" id="email" name="email" required placeholder="Digite seu usuário" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">

      <div class="checkbox-container">
          
      </div>
      <input type="submit" value="Entrar"> 

      <div style="text-align: center; margin-top: 20px;">
          <img src="http://www.fatecsp.br/img/logos_cps_gov.jpg" alt="Imagem final do formulário" style="max-width: 50%; height: auto;">
      </div>
    </form>
  </div>

        <div vw class="enabled">
  <div vw-access-button class="active"></div>
  <div vw-plugin-wrapper>
    <div class="vw-plugin-top-wrapper"></div>
  </div>
</div>

<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
  new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>


</body>
</html>
