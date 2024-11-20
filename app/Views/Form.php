<?php  


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
</head>

<body>
    <form action="/postdata" method="post">

        Enter Prouduct Name: <input type="text" name="name" id="" required><br>
        Enter Product Category: <input list="category" name="category" required>
        <datalist id="category">
            <option value="Electronics "></option>
            <option value="Fashion "></option>
            <option value="Home and Garden "></option>
            <option value="Beauty and Personal Care "></option>
            <option value="Health and Wellness "></option>
            <option value="Sports and Outdoors "></option>
            <option value="Toys and Games "></option>
            <option value="Books and Stationery "></option>
            <option value="Pet Supplies "></option>
            <option value="Music "></option>
            <option value="Automotive Accessories  "></option>
            <option value="Jewelry and Watches "></option>
            <option value="Baby Products "></option>
            <option value="Arts and Crafts "></option>
            <option value="Office "></option>

        </datalist><br>
        Enter Product Price: <input type="number" name="price" id="" required><br>
        <input type="submit" name="" id="">
        
    </form>
</body>

</html>