<!DOCTYPE html>
<html>
<title>Price Compare Site</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>

<header class="w3-container w3-teal">
  <h1>Price Compare Site</h1>
</header>
<div class="w3-container">

<form class="w3-container w3-card-4" action="main.php" method="get">
 <p>
 <input class="w3-input" name="searchdata" type="text" required>
 <label class="w3-label w3-validate">Search Product</label></p>
 <p>
 <input id="search" class="w3-input" type="submit" value="Search">
</form>
<div class="w3-row-padding w3-margin-top">

<?php
error_reporting(E_ALL & ~E_NOTICE);
if(isset($_GET['searchdata']))
{
$search = $_GET['searchdata'];
$search = strtolower($search);
$search = str_replace(" ","+",$search);
  $web_page_data = file_get_contents("http://www.pricetree.com/search.aspx?q=".$search);

  $item_list = explode('<div class="items-wrap">', $web_page_data);
  $i=1;
  if(sizeof($item_list)<2){ 
    echo '<p><b>No results, enter proper product name Ex: Moto G</b></p>';
    $i=5;
  }
$count = 4;
  for($i;$i<5;$i++){

    $url_link1 = explode('href="',$item_list[$i]);
    $url_link2 = explode('"', $url_link1[1]);


    $image_link1 = explode('data-original="',$item_list[$i]);
    $image_link2 = explode('"', $image_link1[1]);
    $title1 = explode('title="', $item_list[$i]);
    $title2 = explode('"', $title1[1]);

    $avaliavle1 = explode('avail-stores">', $item_list[$i]);
    $avaliable = explode('</div>', $avaliavle1[1]);
    if(strcmp($avaliable[0],"Not available") == 0) {
    
      $count = $count-1;
      continue;
    
    }

    $item_title = $title2[0];
    if(strlen($item_title)<2){
      continue;
    }
    $item_link = $url_link2[0];
    $item_image_link = $image_link2[0];
    $item_id1 = explode("-", $item_link);
    $item_id = end($item_id1); 
    echo '
    <br>
    <div class="w3-row">
    <div class="w3-col l2 w3-row-padding">
    <div class="w3-card-2" style="background-color:teal;color:white;">
    <img src="'.$item_image_link.'" style="width:100%">
    <div class="w3-container">
    <h5>'.$item_title.'</h5>
    </div>
    </div>
    </div>
  ';


    $request = "http://www.pricetree.com/dev/api.ashx?pricetreeId=".$item_id."&apikey=7770AD31-382F-4D32-8C36-3743C0271699";
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE);

    echo '
    <div class="w3-col l8">
    <div class="w3-card-2">
      <table class="w3-table w3-striped w3-bordered w3-card-4">
      <thead>
      <tr class="w3-blue">
        <th>Seller_Name</th>
        <th>Price</th>
        <th>Buy Here</th>
      </tr>
      </thead>
    ';
    foreach ($results['data'] as $itemdata) {
      $seller = $itemdata['Seller_Name'];
      $price = $itemdata['Best_Price'];
      $product_link = $itemdata['Uri'];
  echo '
      <tr>
        <td>'.$seller.'</td>
        <td>'.$price.'</td>
        <td><a href="'.$product_link.'">Buy</a></td>
      </tr>
      ';
    }
    echo '
      </table>
      </div>
      </div>
      </div>
    ';
  }
  if($count == 0){
    echo '<p><b>No Products avaliable, Enter Proper Product Ex: Moto G</b></p>';
  }
}
else {
  echo '<p>Use this to get Best Price from all Sites. <b>Search Product to Know Price from All Online Shops</b></p>';
}
?>

</div>
</div>
</div>

<footer class="w3-container w3-teal w3-opacity">
<p>Copyright @ Jordan</p>
</footer>
</body>
</html>