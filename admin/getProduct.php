<?php
//要請問老師，上線後是否把該句註解
header("Access-Control-Allow-Origin: *");
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
    ";

    // 建立PDO Statement
    $prods = $pdo->query($sql);
    $sql = "select count(*) from prod";
    $allProd = $pdo->query($sql);
    $allProdCount = $allProd->fetch(PDO::FETCH_ASSOC);
    $sql = "select count(*) from prod where prod_state = 1";
    $pProd = $pdo->query($sql);
    $pProdCount = $pProd->fetch(PDO::FETCH_ASSOC);
    $sql = "select count(*) from prod where prod_state = 0";
    $upProd = $pdo->query($sql);
    $upProdCount = $upProd->fetch(PDO::FETCH_ASSOC);
    $sql = "select count(*) from prod where prod_discount_price is not null";
    $disProd = $pdo->query($sql);
    $disProdCount = $disProd->fetch(PDO::FETCH_ASSOC);
    $result = ["error" => false, "msg" => "成功取得商品資料", "products" => [],"allProd"=>$allProdCount,"pProd"=>$pProdCount,"upProd"=>$upProdCount,"disProd"=>$disProdCount];
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
        $prodBreif = $row['prod_breif'];

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
                'prod_breif' => $prodBreif,
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
