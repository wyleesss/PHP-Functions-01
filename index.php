<?php
session_cache_expire(0);
session_start();
require_once("word-number-const`s.php");
require_once("all-css-colors-const`s.php");


// завдання 1:
echo "1.<br>";
function defineNegative(array $a) : bool
{
    try {
        foreach ($a as $num) {
            echo "<b>".($num < 0 ? "<span style='color: red'>$num</span> " : "$num ")."</b>";
        }
        return true;
    } catch(Throwable $e) {
        echo "Function cannot be performed: {$e->getMessage()}";
        return false;
    }
}

$arr = [9, 8, 7, 6, 5, -5, -6, -7, -8, -9];
echo "<br>".(defineNegative($arr) ? "success" : "failure")."<hr>".PHP_EOL;


// завдання 2:
echo "2.<br>";
function convertToText(int $num) : string
{
    if ($num > 999999999 || $num < -999999999) {
        throw new OutOfBoundsException("number out of bounds");
    }
    if ($num == 0) {
        return "zero";
    }

    $res = "";

    if ($num < 0) {
        $res .= "minus ";
        $num *= -1;
    }

    if ($num >= 1000000) {
        $res .= convertToText(floor($num / 1000000))." million ";
        $num %= 1000000;
    }

    if ($num >= 1000) {
        $res .= convertToText(floor($num / 1000))." thousand ";
        $num %= 1000;
    }

    if ($num >= 100) {
        $res .= ONES[floor($num / 100)]." hundred ";
        $num %= 100;
    }

    if ($num == 10 || $num >= 20) {
        $res .= TENS[floor($num / 10) * 10]." ";
        $num %= 10;
    }

    elseif ($num >= 10 && $num <= 19) {
        $res .= TEENS[$num];
        return preg_replace("/\s+/", " ", $res);
    }

    if ($num > 0) {
        $res .= ONES[$num];
    }

    return preg_replace("/\s+/", " ", $res);
}

$num = "-999999999";
echo "Number = $num<br>";

try {
    echo "<b>".convertToText($num)."</b>";
} catch (Throwable $e) {
    echo "Function cannot be performed: {$e->getMessage()}";
} finally {
    echo "<hr>".PHP_EOL;
}


// завдання 3:
echo "3.<br>";
function drawRandDiv(int $times = 10) : bool
{
    if ($times > 0) {
        if (!drawRandDiv(--$times)) {
            return false;
        }
    }

    try {
        $x_pos = random_int(0, 1000);
        $y_pos = random_int(0, 1000);
        $height = random_int(100, 300);
        $width = random_int(100, 300);
        $color = COLORS[random_int(0, 147)];
    } catch (Throwable $e) {
        echo "Function cannot be performed: {$e->getMessage()}";
        return false;
    }

    echo "<div style=\"position: absolute;".
         "left: $x_pos;".
         "top: $y_pos;".
         "height: $height;".
         "width: $width;".
         "background-color: $color;".
         "opacity: 0.2\"></div>";

    return true;
}

echo (drawRandDiv() ? "success" : "<br>failure")."<hr>".PHP_EOL;


// завдання 4, 5:
echo "4, 5.<br><h1>Catalog:</h1><br>(зображення видні тільки при запуску через phpstorm)<br>";

function displayCatalogProduct(string $name, string $image, int|float $price): void
{
    echo "<div class='product' style='display: inline-block; background: darkgray; width: 250px; height: 385px; border-radius: 20px; margin: 20px' align='center'>".
         "<form method='post'>".
         "<div style='overflow: hidden;'>".
         "<img src='$image' alt='$name' style='object-fit: cover; margin: 25px; height: 200px; width: 200px; border-radius: 20px'>".
         "</div>".
         "<h3 style='width: 200px; text-align: center'>$name</h3>".
         "<p style='width: 200px; text-align: center'>$price$</p>".
         "<input type='submit' name='add' value='Add To Cart' style='border-radius: 20px'>".
         "<input type='hidden' name='catalog_name' value='$name'>".
         "<input type='hidden' name='image' value='$image'>".
         "<input type='hidden' name='price' value='$price'>".
         "</form>".
         "</div>";
}
function displayCartProduct(string $name, string $image, int|float $price, int $amount): void
{
    echo "<div class='product' style='display: inline-block; background: darkgray; width: 250px; height: 455px; border-radius: 20px; margin: 20px' align='center'>".
         "<form method='post'>".
         "<div style='overflow: hidden;'>".
         "<img src='$image' alt='$name' style='object-fit: cover; margin: 25px; height: 200px; width: 200px; border-radius: 20px'>".
         "</div>".
         "<h3 style='width: 200px; text-align: center'>$name</h3>".
         "<p style='width: 200px; text-align: center'>$price$</p>".
         "<h3 style='width: 200px; text-align: center'>Amount: $amount</h3>".
         "<input type='submit' name='add_one' value='+' style='border-radius: 20px; font-size: 20px'>".
         "<input type='submit' name='delete_one' value='-' style='border-radius: 20px; font-size: 20px'><br>".
         "<input type='submit' name='delete_all' value='Delete All' style='border-radius: 20px'>".
         "<input type='hidden' name='cart_name' value='$name'>".
         "</form>".
         "</div>";
}
function displayCart() : void
{
    echo "<hr><h1>Cart:</h1>".
         "<h2>Total Price: {$_SESSION['totalPrice']}$</h2>".
         "<h2>Total Amount: {$_SESSION['totalAmount']}</h2>".
         "<form method='post'>".
         "<input style='width: 200px; height: 50px; font-size: 20px' type='submit' name='clear' value='Clear Cart'>".
         "</form>";

    if (isset($_SESSION['cartProducts'])) {
        foreach ($_SESSION['cartProducts'] as $name => $product) {
            displayCartProduct($name, $product[0], $product[1], $product[2]);
        }
    }
}
function addCartProduct(string $name, string $image, int|float $price) : void
{
    if (!isset($_SESSION['cartProducts'])) {
        $_SESSION['totalPrice'] = 0;
        $_SESSION['totalAmount'] = 0;
        $_SESSION['cartProducts'] = [];
    }
    if (array_key_exists($name, $_SESSION['cartProducts'])) {
        $_SESSION['cartProducts'][$name][2]++;
    }
    else {
        $_SESSION['cartProducts'][$name] = [$image, $price, 1];
    }

    $_SESSION['totalPrice'] += $price;
    $_SESSION['totalAmount']++;
}
function deleteCartProduct(string $name, $deleteAll = false) : void
{
    if (!array_key_exists($name, $_SESSION['cartProducts'])) {
        return;
    }
    if ($deleteAll) {
        $_SESSION['totalPrice'] -= $_SESSION['cartProducts'][$name][1] * $_SESSION['cartProducts'][$name][2];
        $_SESSION['totalAmount'] -= $_SESSION['cartProducts'][$name][2];
        unset($_SESSION['cartProducts'][$name]);
    }
    else {
        $_SESSION['cartProducts'][$name][2]--;
        $_SESSION['totalPrice'] -= $_SESSION['cartProducts'][$name][1];
        $_SESSION['totalAmount']--;
        if ($_SESSION['cartProducts'][$name][2] == 0) {
            unset($_SESSION['cartProducts'][$name]);
        }
    }
}

displayCatalogProduct("Lego McLaren Sabre", "/ProductImages/LegoMcLarenSabre.png", 45);
displayCatalogProduct("Lego Mercedes CLK", "/ProductImages/LegoMercedesCLK.png", 50);
displayCatalogProduct("Lego Ferrari Enzo", "/ProductImages/LegoFerrariEnzo.png", 40);
displayCatalogProduct("Lego Pagani Zonda", "/ProductImages/LegoPaganiZonda.png", 45);
displayCatalogProduct("Lego Volvo XC60", "/ProductImages/LegoVolvoXC60.png", 55);
displayCatalogProduct("Lego RAM Limited Ed.", "/ProductImages/LegoRAMLimitedEdition.png", 55);

displayCart();

if (isset($_POST['add'])) {
    $name = $_POST['catalog_name'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    addCartProduct($name, $image, $price);

    echo "<script>window.location = window.location.href;</script>";
    exit();
}

if (isset($_POST['delete_one'])) {
    $name = $_POST['cart_name'];

    deleteCartProduct($name);

    echo "<script>window.location = window.location.href;</script>";
    exit();
}

if (isset($_POST['add_one'])) {
    $name = $_POST['cart_name'];

    addCartProduct($name, $_SESSION['cartProducts'][$name][0], $_SESSION['cartProducts'][$name][1]);

    echo "<script>window.location = window.location.href;</script>";
    exit();
}

if (isset($_POST['delete_all'])) {
    $name = $_POST['cart_name'];

    deleteCartProduct($name, true);

    echo "<script>window.location = window.location.href;</script>";
    exit();
}

if (isset($_POST['clear'])) {
    $_SESSION['totalPrice'] = 0;
    $_SESSION['totalAmount'] = 0;
    $_SESSION['cartProducts'] = [];

    echo "<script>window.location = window.location.href;</script>";
    exit();
}