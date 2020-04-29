<?php
error_reporting(E_ALL);
require './require/database.php';

if (!isset($_GET["cat"])) 
	{
		die();
	} 

function getCat() 
	{
		$connection = openCon();
		$category = $_GET["cat"];
		$category = $connection->real_escape_string($category);
		$_category = mb_convert_encoding($category, 'UTF-8', 'UTF-8');
		$_category = htmlentities($_category, ENT_QUOTES, 'UTF-8');
		$id = 0; $page = 0; $slug = '';
		
		$query = "SELECT id, page, slug FROM category WHERE slug = '$_category'";
		$result = $connection->query($query);
		
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$id = $row["id"];
				$page = $row["page"];
				$slug = $row["slug"];
				return array($id, $page, $slug, $_category);
			}
		}
	}

    function getAPI() 
	{
		$cat = getCat();
		$apiLink = "https://api.stackexchange.com/2.2/questions?page=" . $cat[1] . "&pagesize=1&order=desc&sort=votes&tagged=" . $cat[2] . "&site=stackoverflow&filter=!17vk3if(*2Lg0Rorn1TZ-*uUbEK(_2m7B6q9mvDAosEBeP&access_token=8uARb5H4OZe(W8WIoAdHTQ))&key=i*w0QrsPQVOnFEGdUTCU2Q((";
		$apiData = gzdecode(file_get_contents($apiLink));
        $json = json_decode($apiData, true);
		return $json;
	}


    $apiJson = getAPI();

function quotaCheck() {
        global $apiJson;
		$data = $apiJson;
		$quota = $data["quota_remaining"];
        if($quota == 0) {
            echo "Remaining quota has run dry: " . $data["quota_remaining"] . "<br><br>";
            die();
        } else {
            echo "Remaining quota is: " . $data["quota_remaining"] . "<br><br>";
        }
    }

    quotaCheck();

	
function updatePage($page, $category) {
		$connection = openCon();
		$newPage = $page+1;
		$query = "UPDATE category SET page = $newPage WHERE slug = '$category'";
		$result = $connection->query($query);
		return $result;
	}
	
function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
		return 'n-a';
	  }

	  return $text;
	}	

function questionData() 
	{
		$connection = openCon();
        global $apiJson;
		$data = $apiJson;
		
		$slug = slugify($data['items'][0]['title']);
		
		$isAnswered = $connection->real_escape_string($data['items'][0]['is_answered']);
		$_isAnswered = mb_convert_encoding($isAnswered, 'UTF-8', 'UTF-8');
		$_isAnswered = htmlentities($_isAnswered, ENT_QUOTES, 'UTF-8');
		
		$title = $connection->real_escape_string($data['items'][0]['title']);
		$_title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
		$_title = htmlentities($_title, ENT_QUOTES, 'UTF-8');
		
		$body = $connection->real_escape_string($data['items'][0]['body']);
		$_body = mb_convert_encoding($body, 'UTF-8', 'UTF-8');
		$_body = htmlentities($_body, ENT_QUOTES, 'UTF-8');
		
		$score = $connection->real_escape_string($data['items'][0]['score']);
		$_score = mb_convert_encoding($score, 'UTF-8', 'UTF-8');
		$_score = htmlentities($_score, ENT_QUOTES, 'UTF-8');
		
		$owner = $connection->real_escape_string($data['items'][0]['owner']['display_name']);
		$_owner = mb_convert_encoding($owner, 'UTF-8', 'UTF-8');
		$_owner = htmlentities($_owner, ENT_QUOTES, 'UTF-8');
		
		$creationDate = $connection->real_escape_string($data['items'][0]['creation_date']);
		$_creationDate = mb_convert_encoding($creationDate, 'UTF-8', 'UTF-8');
		$_creationDate = htmlentities($_creationDate, ENT_QUOTES, 'UTF-8');
		
		$current_timestamp = time();
		$modifiedDate = $current_timestamp;
		
		return array($slug, $_isAnswered, $_title, $_body, $_score, $_owner, $creationDate, $modifiedDate);
	}
	
function tagData() 
	{
		$connection = openCon();
        global $apiJson;
		$data = $apiJson;
		$dataArray = array();
		
		foreach($data['items'][0]['tags'] as $t) {
			$t = $connection->real_escape_string($t);
			$_t = mb_convert_encoding($t, 'UTF-8', 'UTF-8');
			$_t = htmlentities($_t, ENT_QUOTES, 'UTF-8');
			$dataArray[] = $_t;
		}
		return $dataArray;
	}

function answerData() 
	{
		$connection = openCon();
        global $apiJson;
		$data = $apiJson;
		$ownerArray = array();
		$acceptedArray = array();
		$scoreArray = array();
		$creationArray = array();
		$bodyArray = array();
		
		foreach($data['items'][0]['answers'] as $answers) {
			if(!isset($answers['owner']['display_name'])) {
				$answerOwner = 'Dawood Khan Masood';
			} else {
				$answerOwner = $answers['owner']['display_name'];		
			}
			$answerOwner = $connection->real_escape_string($answerOwner);
			$_answerOwner = mb_convert_encoding($answerOwner, 'UTF-8', 'UTF-8');
			$_answerOwner = htmlentities($_answerOwner, ENT_QUOTES, 'UTF-8');
			$ownerArray[] = $_answerOwner;
			
			$is_accepted = $answers['is_accepted'];
			$is_accepted = $connection->real_escape_string($is_accepted);
			$_is_accepted = mb_convert_encoding($is_accepted, 'UTF-8', 'UTF-8');
			$_is_accepted = htmlentities($_is_accepted, ENT_QUOTES, 'UTF-8');
			$acceptedArray[] = $_is_accepted;
			
			$score = $answers['score'];
			$score = $connection->real_escape_string($score);
			$_score = mb_convert_encoding($score, 'UTF-8', 'UTF-8');
			$_score = htmlentities($_score, ENT_QUOTES, 'UTF-8');
			$scoreArray[] = $_score;
			
			$creation = $answers['creation_date'];
			$creation = $connection->real_escape_string($creation);
			$_creation = mb_convert_encoding($creation, 'UTF-8', 'UTF-8');
			$_creation = htmlentities($_creation, ENT_QUOTES, 'UTF-8');
			$creationArray[] = $_creation;
			
			$body = $answers['body'];
			$body = $connection->real_escape_string($body);
			$_body = mb_convert_encoding($body, 'UTF-8', 'UTF-8');
			$_body = htmlentities($_body, ENT_QUOTES, 'UTF-8');
			$bodyArray[] = $_body;
		}
		
		return array ($ownerArray, $acceptedArray, $scoreArray, $creationArray, $bodyArray);
	}

function addQuestion() 
	{
		$connection = openCon();
		$data = questionData();
		$catSlug = getCat();
		
		$query = "INSERT INTO question (slug, isAnswered, title, body, score, owner, catSlug, creationDate, modifiedDate) VALUES ('$data[0]-$data[7]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$catSlug[2]', '$data[6]', '$data[7]')";
		$result = $connection->query($query);
		if ($result === TRUE) {
			$lastId = mysqli_insert_id($connection);
			echo "<b>[#1]:</b> <span style=\"color:green;\">Question added (ID: $lastId) in \"$catSlug[2]\".</span><br/><br/>";
			return array($lastId);
		} else {
			echo "<b>[#1]:</b> <span style=\"color:red;\">Error adding question. Details: " . $connection->error . "</span><br/>";
		}
	}

function initialize() 
	{
		$page = getCat();
		$tData = tagData();
		$qId = addQuestion();			
		$answer = answerData();
		$answerOwner = $answer[0];
		$answerAccepted = $answer[1];
		$answerScore = $answer[2];
		$answerCreation = $answer[3];
		$answerBody = $answer[4];
		echo '{"status":"Initializing..."}<br/><br/>';
		echo updatePage($page[1], $page[2]);		
		foreach ($tData as $t) {
			$connection = openCon();
			
			$query = "INSERT INTO tag (title) VALUES ('$t')";
			$result = $connection->query($query);
			$tagLastId = mysqli_insert_id($connection);
			
			if ($result === TRUE) {
			} else {
				$query = "SELECT id FROM tag WHERE title = '$t'";
				$result = $connection->query($query);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$tagLastId = $row["id"];
					}
				}
			}
			
			$query = "INSERT INTO qtrelation (questionId, tagId) VALUES ('$qId[0]', '$tagLastId')";
			$result = $connection->query($query);
		}

		$nonempty = array_values($answerOwner);
		for ($i = 0; $i<100; $i++) {
			$owner = $answerOwner[$i];
			if($answerAccepted[$i] == 1) {
				$isAccepted = 1;	
			} else {
				$isAccepted = 0;
			}
            		$score = $answerScore[$i];
            		$creationDate = $answerCreation[$i];
            		$body = $answerBody[$i];             
            		$query = "INSERT INTO answer (isAccepted, body, score, owner, creationDate, questionId) VALUES ($isAccepted, '$body', '$score', '$owner', '$creationDate', '$qId[0]')";
           		if (!isset($nonempty[$i])) break;
			if ($score > 0 && $score != 0) {
				$result = $connection->query($query);
				if ($result) {
					echo '<b>-</b> Adding answer with score: ' . $score . '<br>';
		    		} else {
					echo '<b>-</b> There was an error!<br>';
				}
			}
            	}
	}
	
initialize();
		
