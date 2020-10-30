<?php
//подключаем файл с подключением к БД
include $_SERVER['DOCUMENT_ROOT']."/configs/db.php";

//если был отправлен POST-запрос 'btnOptions' (нажата кнопка "ОК")
if(isset($_POST['btnOptions'])) {
    //проверяем чтобы отправленые поля были выбраны
    if($_POST['color'] != "NCS" && $_POST['product'] != "Products") {
        //делаем запрос к БД на выбор цвета соответствующего из запроса
        $sql = "SELECT * FROM `ncs` WHERE `color_name` ='" . $_POST["color"] . "'";
        //выполняем запрос
        $result = mysqli_query($connect, $sql);
        //преобразовываем его в массив
        $color = mysqli_fetch_assoc($result);

        //делаем запрос к БД на выбор id продукта соответствующего запросу
        $sql = "SELECT * FROM `product` WHERE `product_name`='" . $_POST['product'] . "'";
        //выполняем запрос
        $result = mysqli_query($connect, $sql);
        //преобразовываем его в массив
        $id_product = mysqli_fetch_assoc($result);

        //делаем запрос к БД на выбор id базы соответствующей цвету и продукту с запроса
        $sql = "SELECT `id_base` FROM `ncs_recept` WHERE `color_name` ='" . $_POST["color"] . "' AND `id_product`='" . $id_product['id_product'] . "'";
        //выполняем запрос
        $result = mysqli_query($connect, $sql);
        //преобразовываем его в массив
        $id_base = mysqli_fetch_assoc($result);

        //делаем запрос к БД на определение названия базы соответствующей цвету и продукту с запроса
        $sql = "SELECT `name_base` FROM `base` WHERE `id_base`=" . $id_base['id_base'];
        //выполняем запрос
        $result = mysqli_query($connect, $sql);
        //преобразовываем его в массив
        $name_base = mysqli_fetch_assoc($result);
        ?>


        <div class="alert alert-primary" > 
            Цвет: <?php echo $color['color_name']; ?>
            <div class="w-25 position-absolute p-3" 
                style="background-color: <?php echo $color['color_html']; ?>; top: 7px; left: 200px;"></div>
            
        </div>
    <div class="alert alert-primary">
        Краска: <?php echo $_POST['product']; ?>
    </div>
    <div class="alert alert-primary">
        База: <?php echo $name_base['name_base']; ?>
    </div>
    <div class="alert alert-primary">
    Рецептура:
    <?php
    //делаем запрос к БД на выбор рецептуры соответствующей цвету и продукту с запроса
    $sql = "SELECT `id_colorant`, `colorant_value` FROM `ncs_recept_item` WHERE `color_name` ='" . $_POST["color"] . "' AND `id_product`='" . $id_product['id_product'] . "' AND `id_base`='" . $id_base['id_base'] . "'";
    //выполняем запрос
    $result = mysqli_query($connect, $sql);
    //определяем количество колорантов
    $count_colorants = mysqli_num_rows($result);
    for ($i=0; $i < $count_colorants; $i++) { 
        $price = 0;
        //преобразовываем его в массив
        $recipe = mysqli_fetch_assoc($result);
        //делаем запрос к БД на определение названия колоранта соответствующей цвету и продукту с запроса
        $sql1 = "SELECT * FROM `colorant` WHERE `id_colorant`=" . $recipe['id_colorant'];
        //выполняем запрос
        $result1 = mysqli_query($connect, $sql1);
        //преобразовываем его в массив
        $colorant = mysqli_fetch_assoc($result1);
        $colorant_ml = round(($recipe['colorant_value']/6.5), 1);
        //$price = $price + ($colorant['price']*$colorant['colorant_value']);
        echo "<div class=\"alert alert-secondary\">";
        echo $colorant['name_colorant'] . ": " . $colorant_ml . "ml";
        echo"</div>";
    }
    ?>
        
    </div>
    <div class="alert alert-primary">
    Выбор фасовки
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
        <label class="form-check-label" for="inlineRadio1">1</label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
        <label class="form-check-label" for="inlineRadio2">5</label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
        <label class="form-check-label" for="inlineRadio2">10</label>
        </div>
        </div>
        </div>
        </div>
    <div class="alert alert-primary" role="alert">
    Цена
    </div>
    <?php
    } else {
        echo "Выбирете все параметры для подсчета!";
    }
}