<?php
require_once 'degiskenler.php';
require_once 'fonksiyonlarim.php';
require_once 'db.php';

header("Access-Control-Allow-Origin: *");


if (isset($_GET['page_slug'])) {

  $result = fetchPage($DB, $_GET['page_slug']); // Sadece bir bilgi sayfasını getirelim

} else {

  $result = fetchMenus($DB); // Menülerin hepsini getirelim

}


// JSON formatında cevap döndür
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


################################################
################################################ Kullanılan Fonksiyonlar
################################################
function fetchPage($DB, $page_slug)
{
  $sql = "SELECT * FROM web_pages WHERE page_slug = :page_slug LIMIT 1";
  $stmt = $DB->prepare($sql);
  $stmt->bindParam(':page_slug', $page_slug);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC); // Sadece 1 kayıt çekelim (fetch)
  return $result;
}


function fetchMenus($DB)
{
  $sql = "SELECT id, page_name, page_slug, parent_id FROM web_pages ORDER BY parent_id, sort_order";
  $stmt = $DB->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Tüm sonuçlar gelsin (fetchAll)
  return $result;
}
