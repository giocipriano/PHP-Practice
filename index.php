<?php 

$pdo = new PDO('mysql:host=localhost; port=3306; dbname=monsters_db', 'root', '');
$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $pdo -> prepare('SELECT * FROM monsters ORDER BY date_created DESC');
$statement -> execute();
$monsters = $statement -> fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en"> 
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="stylesheet" href="mon.css">
        <title> 
            Monsters Database 
        </title>
    </head>
    <body>
        <h1> Monsters Database </h1> 
        <p> 
            <a href='add.php' class="btn btn-success">Create</a>
        </p> 

        <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Weight</th>
            <th scope="col">Height</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($monsters as $i => $monsters): ?> 
            <tr>
                <th scope="row"><?php echo $i++ ?></th>
                <td>
                    <img src="<?php echo $monsters['image'] ?>" class="thumb-image">
                </td>
                <td><?php echo $monsters['name'] ?></td> 
                <td><?php echo $monsters['description'] ?></td>
                <td><?php echo $monsters['weight'] ?></td>
                <td><?php echo $monsters['height'] ?></td>
                <td>
                    <a href="update.php?id=<?php echo $monsters['id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                    <form style="display: inline-block" method="post", action="delete.php">
                        <input type="hidden" name="id" value="<?php echo $monsters['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-dark">Delete</button>
                    </form>  
                </td>
            </tr>
        <?php endforeach; ?>  
        </tbody>
        </table>
    </body>
</html>