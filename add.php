<?php
 $errors=["name" => "", "title" => "", "ingredients" => "", "image" => ""];
 $name = $title = $ingredients = $image = "";
 if(isset($_POST["submit"])){
     $name = htmlspecialchars($_POST["name"]);
     $title = htmlspecialchars($_POST["title"]);
     $ingredients = htmlspecialchars($_POST["ingredients"]);
     $image = htmlspecialchars($_POST["image"]);

     if (empty($name)) {
         $errors["name"] = "Name is required";
     }

     if (empty($title)) {
        $errors["title"] = "Title is required";
    }

    if (empty($ingredients)) {
        $errors["ingredients"] = "Ingredients is required";
    }

    if (empty($image)) {
        $errors["image"] = "Image link is required";
    }


    if(!array_filter($errors)){
        include("config/db_connect.php");

        $header = ["X-Api-Key: OgtRJeSKvH4rQYFBBbMOcA==vm2zazDhSAW1NT6C"];

        $curl_session_handle = curl_init();
        $url = "https://api.calorieninjas.com/v1/nutrition?query=" . urlencode($ingredients);

        curl_setopt($curl_session_handle, CURLOPT_URL, $url);
        curl_setopt($curl_session_handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl_session_handle, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl_session_handle);

        if($err = curl_error($curl_session_handle)){
            echo("Error with database");
        }
        else {
            $formatted = json_decode($result,true);
            $calories=$fatTotal=$fatSaturated=$cholesterol=$potassium=$sodium=$carbsTotal=$fiber=$sugar=$protein=0;
           echo "<pre>";
            print_r($formatted);
            echo "</pre>";
            for($i=0;$i<count($formatted);$i++){
                $calories+=$formatted["items"][$i]["calories"];
                $fatTotal+=$formatted["items"][$i]["fat_total_g"];
                $fatSaturated+=$formatted["items"][$i]["fat_saturated_g"];
                $cholesterol+=$formatted["items"][$i]["cholesterol_mg"];
                $potassium+=$formatted["items"][$i]["potassium_mg"];
                $sodium+=$formatted["items"][$i]["sodium_mg"];
                $fiber+=$formatted["items"][$i]["fiber_g"];
                $sugar+=$formatted["items"][$i]["sugar_g"];
                $protein+=$formatted["items"][$i]["protein_g"];
                $carbsTotal+=$formatted["items"][$i]["carbohydrates_total_g"];
            }
        }

        echo curl_getinfo($curl_session_handle, CURLINFO_HTTP_CODE);

        curl_close($curl_session_handle);

        $sql = "INSERT INTO food(title,ingredients,author,image,fatTotal,fatSaturated,cholesterol,
        potassium,sodium,carbsTotal,sugar,fiber,protein,calories) 
        VALUES('$title','$ingredients','$name','$image','$fatTotal','$fatSaturated','$cholesterol',
        '$potassium','$sodium','$fiber','$sugar','$protein','$carbsTotal','$calories')";
        if(mysqli_query($db_connection, $sql)){
            mysqli_close($db_connection);
            header("Location: index.php");
        }
    }
 }
?>

<html>
    <head>
        <title>Food Recipes</title>
        <link rel="stylesheet" href="templates/main-style.css">
        <style>
            section .add-header {
    text-align: center;
    font-size: 25px;
    font-weight: bold;
    font-family: cursive;
}

section form {
    background: white;
    padding: 20px;
    width: 40vw;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: 10px;
}

        section form input,
        section form textarea {
            padding: 10px 0;
            margin-top: 10px;
            border: 0;
            border-bottom: 2px solid gray;
            font-size: 18px;
            font-family: arial;
        }

        section form textarea {
            resize: none;
            overflow-y: hidden;
        }

        label {
            color: #464646;
        }

        section form input[type="submit"] {
            font-weight: bold;
        }

        .error {
            color: red;
        }
        </style>
    </head>
    <body>
    <?php include("templates/header.php"); ?>

            <section>
                <div class="add-header">Add a recipe</div>
                <form action="add.php" method="POST">
                    <label>Your name:</label>
                    <input type="text" name="name" value="<?php echo $name ?>" maxlength=255>
                    <div class="error"><?php echo $errors["name"]?></div>

                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo $title ?>" maxlength=255>
                    <div class="error"><?php echo $errors["title"]?></div>

                    <label>Ingredients (comma , seperated):</label>
                    <textarea name="ingredients" maxlength=500><?php echo $ingredients ?></textarea>
                    <div class="error"><?php echo $errors["ingredients"]?></div>

                    <label>Link to image:</label>
                    <input name="image" maxlength=500 value=<?php echo $image ?>>
                    <div class="error"><?php echo $errors["image"]?></div>

                    <input type="submit" name="submit">
                </form>
            </section>

        <?php include("templates/footer.php"); ?>
        <script>
            let area=document.querySelector("section form textarea");
            area.addEventListener("input", () => {
                     area.style.height="auto";
                     area.style.height=area.scrollHeight+2+"px";
            });
        </script>
    </body>
</html>