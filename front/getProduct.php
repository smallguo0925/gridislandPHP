<?php
//要請問老師，上線後是否把該句註解
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
try {
    //連線
    require_once("../connectGridIsland.php");

    //準備sql指令
    $sql = "select p.*,t.tag_name
    from prod p 
    left join prod_tag pt on (p.prod_id = pt.prod_id)
    left join tag t on (pt.tag_id = t.tag_id)
    where p.prod_state = 1";

    // 建立PDO Statement
    $prods = $pdo->query($sql);
    $result = ["error" => false, "msg" => "成功取得商品資料", "products" => []];
    while($row = $prods->fetch(PDO::FETCH_ASSOC)){
        $productId = $row['prod_id'];
        $productName = $row['prod_name'];
        $productPrice = $row['prod_price'];
        $productDiscountPrice = $row['prod_discount_price'];
        $productIntro = $row['prod_intro'];
        $productDesc = $row['prod_desc'];
        $prodDate = $row['prod_date'];
        $prodState = $row['prod_state'];
        $prodImg1 = $row['prod_img1'];
        $prodImg2 = $row['prod_img2'];
        $prodImg3 = $row['prod_img3'];
        $prodBrief = $row['prod_brief'];

        if (!isset($result['products'][$productId])) {
            $result['products'][$productId] = [
                'prod_id' => $productId,
                'prod_name' => $productName,
                'prod_price' => $productPrice,
                'prod_discount_price' => $productDiscountPrice,
                'prod_intro' => $productIntro,
                'prod_desc' => $productDesc,
                'prod_date' => $prodDate,
                'prod_state' => $prodState,
                'prod_img1' => $prodImg1,
                'prod_img2' => $prodImg2,
                'prod_img3' => $prodImg3,
                'prod_brief' => $prodBrief,
                'tags' => []  // 初始化標籤陣列
            ];
        }

        $tag = $row['tag_name'];
        if (!empty($tag)) {
            $result['products'][$productId]['tags'][] = $tag;
        }
    }
    $result['products'] = array_values($result['products']);
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);
?>
