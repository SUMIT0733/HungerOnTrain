<?php

    include_once("dbConnect.php");
    $id = 1;
    $i = 0;
    $sql = "SELECT menu.cuisine_id,cuisines.cuisine_name FROM menu JOIN cuisines 
            ON menu.cuisine_id = cuisines.cuisine_id WHERE restaurant_id = {$id} 
            GROUP By menu.cuisine_id";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    //echo mysqli_num_rows($res);
    if(mysqli_num_rows($res) > 0){
        $val['responce'] = "successs";
        while($a = mysqli_fetch_assoc($res)){
    
            $val['data'][$i]['category'] = $a['cuisine_name'];
            $val['data'][$i]['id'] = $a['cuisine_id'];
            $cuisine_id = $a['cuisine_id'];

            $sql1 = "SELECT menu.food_item_id,menu.food_item_name,menu.food_item_price,
                    cuisines.cuisine_name,menu.ingredients,menu.Veg 
                    from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id 
        WHERE menu.cuisine_id = $cuisine_id AND menu.restaurant_id = {$id}";
            $res1 = mysqli_query($con,$sql1) or die("1");
            while($b = mysqli_fetch_assoc($res1)){
                $val['data'][$i]['menu'][] = $b;
            }
        
            $i++;
        }
    }
    echo json_encode($val);
    mysqli_close($con);
?>