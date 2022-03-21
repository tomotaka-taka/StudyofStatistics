<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Study of Statistics</title>
</head>
<body>
    <div id="main">
        <h1>Study of Statistics</h1>
    
        <h2>Chapter</h2>
        <ol>
            <?php foreach($chapters as $chapter): ?>
            <li><a href="./chapter.php?id=<?php echo $chapter['id']; ?>">
            <?php echo $chapter['chapter']; ?></a></li>
            <?php endforeach; ?>
        </ol>
    </div>
</body>
</html>