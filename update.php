<?php 

$pdo = new PDO('mysql:host=localhost; port=3306; dbname=monsters_db', 'root', '');
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;

if(!$id)
{
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM monsters where id = :id");
$statement->bindValue(':id', $id);
$statement->execute();
$monsters = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];
$name = $monsters['name'];
$description = $monsters['description'];
$weight = $monsters['weight'];
$height = $monsters['height'];

if ($_SERVER['REQUEST_METHOD']==='POST')
{
    $name = $_POST['name'];
    $description = $_POST['description'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    if (!$name || !$description)
    {
        $errors[] = "Name & Description of Monster Required";
    }

    if (!is_dir('images')){
        mkdir('images');
    }

    if (empty($errors)) 
    {
        $image = $_FILES['image'] ?? null;
        $imagePath = $monsters['image'];

        if ($image && $image['tmp_name']) { 

            if ($monsters['image']){
                unlink($monsters['image']);
            }

            $imagePath = 'images/'.randomString(8).'/'.$image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $pdo->prepare
        ('
        UPDATE monsters SET name = :name, image = :image, 
        description = :description, weight = :weight, height = :height 
        WHERE id = :id
        '); 
 
        $statement->bindValue(':name', $name);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':weight', $weight);
        $statement->bindValue(':height', $height);
        $statement->bindValue(':id', $id);
        $statement->execute();

        header('Location: index.php');
    }
}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for($i = 0; $i < $n; $i++)
    {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}

?>

<!doctype html>
<html lang="en"> 
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="stylesheet" href="mon.css">
        <title> 
            Edit Monster Details
        </title>
    </head>
    <body>
        <a href='index.php' class="btn btn-secondary">Back</a>
        <h1> Edit Monster </h1> 
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"> 
                <?php foreach ($errors as $error): ?>
                    <div><?php echo $error ?></div>
                <?php endforeach; ?>
            </div>  
        <?php endif; ?>

        <form action = "" method="post" enctype="multipart/form-data">
            
            <?php if ($monsters['image']): ?> 
                <img src="<?php echo $monsters['image'] ?>" class="update-image">
            <?php endif; ?>
                    
            <div class="mb-3">
                <label>Monster Image</label>
                <input type="file" name="image" class="form-control" value="<?php echo $image ?>">
            </div>
            <div class="mb-3">
                <label>Monster Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name ?>">
            </div>
            <div class="mb-3">
                <label>Monster Description</label>
                <textarea class="form-control" name="description"><?php echo $description ?></textarea>
            </div>
            <div class="mb-3">
                <label>Weight</label>
                <input type="weight" step="0.01" name="weight" class="form-control" value="<?php echo $weight ?>">
            </div>
            <div class="mb-3">
                <label>Height</label>
                <input type="height" step="0.01" name="height" class="form-control" value="<?php echo $height ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button> 
        </form>
    </body>
</html>