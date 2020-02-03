<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Run create XML file</title>

</head>

<body>

<?php

header('Content-Type: text/html; charset=utf-8');

global $con;

$con = mysqli_connect('localhost', 'root','2VLnlyd1x','dbluxyart');//this is the unique connection for the selection

//echo "Host information: " . mysqli_get_host_info($con) . PHP_EOL;

mysqli_set_charset( $con, 'utf8');

$slct_stmnt = 'SELECT * FROM luxyart_tb_sitemap WHERE sitemap_id in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18) and status = 1 ORDER BY FIELD(sitemap_id,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18)';

$resultMain = mysqli_query($con, $slct_stmnt);

// khai bao xml

$xml = new DomDocument("1.0","UTF-8");

$content = $xml->createElement("urlset");

$content = $xml->appendChild($content);



$content->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");

$content->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");

$content->setAttribute("xsi:schemaLocation", "http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd");

if ($resultMain->num_rows > 0) {

	

    // output data of each row

    while($rowMain = $resultMain->fetch_assoc()) {

		

		$is_params = $rowMain["is_params"];

		

		$loc = $rowMain["loc"];

		$changefreq = $rowMain["changefreq"];

		$priority = $rowMain["priority"];

		

		$sitemap_id = $rowMain["sitemap_id"];

		

		$lastmod = "2018-05-22";

		

		if($is_params == 0){

			

			$item = $xml->createElement("url");

			

			$id_loc = $xml->createElement("loc", $loc);

			$id_loc = $item->appendChild($id_loc);

			

			$id_lastmod = $xml->createElement("lastmod", $lastmod);

			$id_lastmod = $item->appendChild($id_lastmod);

			

			$id_changefreq = $xml->createElement("changefreq", $changefreq);

			$id_changefreq = $item->appendChild($id_changefreq);

			

			$id_priority = $xml->createElement("priority", $priority);

			$id_priority = $item->appendChild($id_priority);

			

			if($item != ""){

				$item = $content->appendChild($item);

			}

			

			echo "sitemap_id = ".$sitemap_id." ok<br>";

			

		}

		else{

			

			//$item = $xml->createElement("url");

			

			//echo "sitemap_id = ".$sitemap_id."<br>";

			

			if($sitemap_id == 17){

				$link = $rowMain["loc"];
				
				$sql = "SELECT category_id, group_id FROM luxyart_tb_qa_category WHERE lang_id = 1 and status = 1 ORDER BY category_id DESC";
				$result = mysqli_query($con, $sql);

				if($result->num_rows > 0){

					while($row = $result->fetch_assoc()){

						$category_id = $row['category_id'];
						$group_id = $row['group_id'];

						$link1 = str_replace("{qa_category_id}", $group_id, $link);

						$item = $xml->createElement("url");
						
						$id_loc = $xml->createElement("loc", $link1);
						$id_loc = $item->appendChild($id_loc);

						$id_lastmod = $xml->createElement("lastmod", $lastmod);
						$id_lastmod = $item->appendChild($id_lastmod);

						$id_changefreq = $xml->createElement("changefreq", $changefreq);
						$id_changefreq = $item->appendChild($id_changefreq);

						$id_priority = $xml->createElement("priority", $priority);
						$id_priority = $item->appendChild($id_priority);

						if($item != ""){
							$item = $content->appendChild($item);
						}

					}	

				}	

				echo "sitemap_id = ".$sitemap_id." ok<br>";

			}

			if($sitemap_id == 18){

				$link = $rowMain["loc"];
				
				$sql = "SELECT qa_id, group_id FROM luxyart_tb_qa WHERE lang_id = 1 and status = 1 ORDER BY qa_id DESC";
				$result = mysqli_query($con, $sql);

				if($result->num_rows > 0){

					while($row = $result->fetch_assoc()){

						$category_id = $row['category_id'];
						$group_id = $row['group_id'];

						$link1 = str_replace("{qa_id}", $group_id, $link);

						$item = $xml->createElement("url");
						
						$id_loc = $xml->createElement("loc", $link1);
						$id_loc = $item->appendChild($id_loc);

						$id_lastmod = $xml->createElement("lastmod", $lastmod);
						$id_lastmod = $item->appendChild($id_lastmod);

						$id_changefreq = $xml->createElement("changefreq", $changefreq);
						$id_changefreq = $item->appendChild($id_changefreq);

						$id_priority = $xml->createElement("priority", $priority);
						$id_priority = $item->appendChild($id_priority);

						if($item != ""){
							$item = $content->appendChild($item);
						}

					}	

				}

				echo "sitemap_id = ".$sitemap_id." ok<br>";

			}

		}

    }

}

$xml->FormatOutput = true;
$output = $xml->saveXML();
$xml->save("anotherpage.xml");
$con->close();

echo "Create anotherpage.xml ok!";

/*$to      = 'chanchin39@gmail.com';
$subject = 'Create xml: company.xml';
$message = 'Create xml: company.xml';
$headers = 'From: info@viecoi.vn' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);*/

?>

</body>
</html>

