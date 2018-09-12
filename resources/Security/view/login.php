<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
</head>
<body>
    <h1>Sign in</h1>
    <?php foreach(Helper::flash('error') as $msg):?>
        <div class="error"><?= $msg ?></div>
    <?php endforeach;?>
    <?= $form ?>
</body>
</html>