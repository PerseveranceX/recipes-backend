<?php
    include("config/db_connect.php");
 if($db_connection){
   $sql = 'SELECT * FROM food';

   $result = mysqli_query($db_connection, $sql);

   $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

   mysqli_free_result($result);

   mysqli_close($db_connection);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Food Recipes</title>
        <link rel="stylesheet" href="templates/main-style.css">
        <style>
             section {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
                width: 80vw;
                margin: 0 auto;
            }
            .card {
                background-color: white;
                display: flex;
                flex-direction: column;
                width: 200px;
                height: 300px;
                align-items: center;
                padding-bottom: 10px;
                box-sizing: content-box;
            }

            .card img {
                height: 150px;
                width: 200px;
                background-size: cover;
            }

            .card .title {
                margin: 5px auto;
                padding: 10px;
                font-weight: bold;
                font-size: 20px;
                font-family: arial;
                border-bottom: 2px gray solid;
            }

            li {
                margin-left: 5px;
                list-style-position: inside;
                font-size: 17px;
            }

            .view-recipe {
                width: fit-content;
                height: fit-content;
                background: #976100;
                color: white;
                padding: 5px 7.5px;
                border-radius: 3px;
                font-size: 18px;
                font-family: cursive;
            }

            .img {
                position: relative;
            }

            .overlay::after {
                content: "Broken image";
                position: absolute;
                left:50%;
                top: 50%;
                transform: translate(-50%,-50%);
                font-size: 18px;
                font-weight: bold;
                width: fit-content;
            }
        </style>
    </head>
    <body>
        <?php include("templates/header.php"); ?>

        <section>
            <?php foreach($recipes as $recipe): ?>
                <div class="card">
                    <div class="img"><img src="<?php echo $recipe['image'] ?>" alt="Recipe image" ></div>
                    <div class="title"><?php echo $recipe['title'] ?></div>
                    <ul>
                    <?php $ingredients_arr=explode(",",$recipe["ingredients"]);
                    foreach($ingredients_arr as $ingredient): ?>
                        <li><?php echo $ingredient ?></li>
                    <?php endforeach; ?>
                    </ul>
                    <a class="view-recipe" href="recipe.php?id=<?php echo $recipe['id'] ?>">View Recipe</a>
                </div>
            <?php endforeach; ?>
        </section>

        <?php include("templates/footer.php"); ?>
        <script>
            let img=document.querySelectorAll("img");
            let div=document.querySelectorAll(".img");
            let tries= new Array(img.length);
            tries.fill(0);
            img.forEach((image, index) => {
                
                image.addEventListener("error",function(e){
                    if(tries[index]<1){
                    tries[index]++;
                    image.src="https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg";
                    div[index].classList.add("overlay");
                    image.style.opacity=.6;
                }
            });
            });
        </script>
    </body>
</html>

