<?php 
    include("config/db_connect.php");

    if(isset($_POST['delete'])){
        $id = $_GET['id'];

        $sql = "DELETE FROM food WHERE id = $id";

        if(mysqli_query($db_connection, $sql)){
            mysqli_close($db_connection);
            header("Location: index.php");
        }
        else {
            echo "Problem while deleting the recipe";
        }
    }

    $recipe="";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM food WHERE id = $id";

        $result = mysqli_query($db_connection,$sql);

        $recipe = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

        mysqli_close($db_connection);
    }
?>

<html>
    <head>
    <title>Food Recipes</title>
        <link rel="stylesheet" href="templates/main-style.css">
        <style>
            section {
                flex-grow:1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            section img {
                width: 70vw;
                height: 300px;
            }

            section .title {
                margin-top: 10px;
                font-family: cursive;
                font-size: 25px;
                font-weight: bold;
            }

            section .author {
                font-size: 18px;
                font-weight: 600;
            }

            section .ingredients {
                font-size: 18px;
                font-weight: 600;
            }

            .dont-exist {
                margin-top: 25px;
                font-size: 30px;
                font-weight: bold;
            }

            input[name="delete"] {
                background: red;
                color: white;
                font-size: 18px;
                padding: 5px 7.5px;
                font-weight: bold;
                border-color: orangered;
                margin-bottom:10px;
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
                font-size: 25px;
                font-weight: bold;
                width: fit-content;
            }

            ul{
                list-style-position: inside;
            }

            .ingredients-container {
                display: flex;
                padding: 10px;
                background: white;
                flex-direction: column;
                align-items: center;
            }

            .ingredients {
                margin-bottom: 10px;
                border-bottom: 2px solid black;
            }

            .facts {
                background: white;
                padding: 3px;
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .facts * {
                font-size: 18px;
            }

            .facts-title {
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                border-bottom: 3px solid black;
            }

            .calories {
                font-weight: bold;
                border-bottom: 2px solid black;
            }

            .main {
                font-weight: 600;
            }

            .indent {
                margin-left: 20px;
            }

            .fat-total ~ div {
                border-top: 2px solid gray;
            }
        </style>
    </head>
    <body>
        <?php include("templates/header.php")?>
        <?php if($recipe): ?>
        <section>
        <div class="img"><img src="<?php echo $recipe["image"] ?>" alt="Recipe image"></div>
            <div class="title"><?php echo $recipe["title"]?></div>
            <div class="author">Created by: <?php echo $recipe["author"]?>, At: <?php echo $recipe["time"]?></div>
            <div class="ingredients-container">
            <div class="ingredients">Ingredients</div>
            <ul>
                <?php $ingredients_arr=explode(",",$recipe["ingredients"]);
                foreach($ingredients_arr as $ingredient): ?>
                    <li><?php echo $ingredient ?></li>
                <?php endforeach; ?>
            </ul>
            </div>
            <div class="facts">
                <div class="facts-title">Nutrition Facts</div>
                <div class="calories">Calories <?php echo $recipe["calories"] ?></div>
                <div class="fat-total main">Total Fat <?php echo $recipe["fatTotal"]?>g</div>
                <div class="fat-sat indent">Saturated Fat <?php echo $recipe["fatSaturated"]?>g</div>
                <div class="cholesterol main">Cholesterol <?php echo $recipe["cholesterol"]?>mg</div>
                <div class="potassium main">Potassium <?php echo $recipe["potassium"]?>mg</div>
                <div class="sodium main">Sodium <?php echo $recipe["sodium"]?>mg</div>
                <div class="carbs-total main">Total Carbohydrates <?php echo $recipe["carbsTotal"]?>g</div>
                <div class="fiber indent">Dietary Fiber <?php echo $recipe["fiber"]?>g</div>
                <div class="sugar indent">Sugars <?php echo $recipe["sugar"]?>g</div>
                <div class="protein main">Protein <?php echo $recipe["protein"]?>g</div>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $id ?>" method="POST">
                <input type="submit" name="delete" value="delete">
            </form>
        </section>
        <?php else: ?>
            <section>
            <div class="dont-exist">This recipe doesn't exist</div>
            </section>
        <?php endif; ?>
        <?php include("templates/footer.php")?>

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