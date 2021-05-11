<?php
session_start();
include("config.php");
//Get user information
$searchedUserID = -1;
if (!empty($_GET['searchedUserID'])){
    //This means we access this php file via search user tab and looking at other people's profiles
    $searchedUserID = $_GET['searchedUserID'];
}
else{
    //This means user looks at his/her own  profile
    $searchedUserID = $_SESSION['userID'];
}
//Get the user from users table
$getUserQuery = "SELECT * FROM users WHERE user_id = '$searchedUserID'";
$getUserQueryResult = $mysqli->query($getUserQuery);
$getUserQueryRow = $getUserQueryResult->fetch_assoc();
$userName = $getUserQueryRow['name'];
$userGender = $getUserQueryRow['gender'];
$userBirthday = $getUserQueryRow['birthday'];
$userCreatedAt = $getUserQueryRow['created_at'];

$userProfilePic = "";
if($userGender == 'male'){
    $userProfilePic = 'maleProfile.png';
}
else if($userGender == 'female'){
    $userProfilePic = 'femaleProfile.png';
}
else{
    $userProfilePic = 'otherProfile.png';
}
//Add post to post table
if( isset($_POST['postSubmit'])) {
    $postContent = $_POST['postContent'];
    $insertNewPostQuery = "INSERT INTO post(text, date, like_count, dislike_count) 
                            VALUES ('$postContent', NOW(),0, 0)";
    $insertNewPostQueryPrep = $mysqli->prepare($insertNewPostQuery);
    $insertNewPostQueryResult = $insertNewPostQueryPrep->execute();
    $insertNewPostQueryPrep->close();
    if ($insertNewPostQueryResult) {
        echo 'Successfully inserted new post.';
    }
    //add post-user relation
    $getLastAddedPostIDQuery = "SELECT * FROM post ORDER BY post_id DESC LIMIT 1";
    $getLastAddedPostIDQueryResult = $mysqli->query($getLastAddedPostIDQuery);
    $getLastAddedPostIDQueryRow = $getLastAddedPostIDQueryResult->fetch_assoc();
    $lastAddedPostID = $getLastAddedPostIDQueryRow['post_id'];

    $insertNewPostsRelationQuery = "INSERT INTO posts(post_id, user_id) 
                            VALUES ('$lastAddedPostID', '$searchedUserID')";
    $insertNewPostsRelationQueryPrep = $mysqli->prepare($insertNewPostsRelationQuery);
    $insertNewPostsRelationQueryResult = $insertNewPostsRelationQueryPrep->execute();
    $insertNewPostsRelationQueryPrep->close();
    if ($insertNewPostsRelationQueryResult) {
        echo 'Added new posts Relation';
    }
}
if( isset($_POST['like'])) {
    $likedPostID = $_POST['like'];
    //search if rates relation already exists
    $getRatesInfoQuery = "SELECT * FROM rates_post WHERE rates_post.post_id = '$likedPostID' AND rates_post.user_id = '$searchedUserID'";
    $getRatesInfoQueryResult = $mysqli->query($getRatesInfoQuery . " AND rates_post.is_like = true");
    if($getRatesInfoQueryResult->num_rows==1) {
        echo "You have already liked this post.";
    }
    else{
        //Check if user previously disliked book
        $getRatesInfoDislikeQueryResult = $mysqli->query($getRatesInfoQuery . " AND rates_post.is_like = false");
        if($getRatesInfoDislikeQueryResult->num_rows == 1) {
            echo " You have disliked this book previously";
            //Update previously disliked rates relation as like
            $updateRatesQuery = "UPDATE rates_post SET is_like = TRUE WHERE rates_post.post_id = '$likedPostID' AND rates_post.user_id = '$searchedUserID'";
            $updateRatesQueryPrep = $mysqli->prepare($updateRatesQuery);
            $updateRatesQueryResult = $updateRatesQueryPrep->execute();
            $updateRatesQueryPrep->close();
            //Decrease post dislike count
            $decreasePostDislikeQuery = "UPDATE post SET dislike_count = dislike_count - 1 WHERE post.post_id = '$likedPostID'";
            $decreasePostDislikeQueryPrep = $mysqli->prepare($decreasePostDislikeQuery);
            $decreasePostDislikeQueryResult = $decreasePostDislikeQueryPrep->execute();
            $decreasePostDislikeQueryPrep->close();
        }
        else {
            //add new rates relation
            $insertRatesQuery = "INSERT INTO rates_post(post_id, user_id, is_like) 
                                VALUES ('$likedPostID', '$searchedUserID', TRUE)";
            $insertRatesQueryPrep = $mysqli->prepare($insertRatesQuery);
            $insertRatesQueryResult = $insertRatesQueryPrep->execute();
            $insertRatesQueryPrep->close();
        }
        //increase book like count by 1
        $increasePostLikeQuery = "UPDATE post SET like_count = like_count + 1 WHERE post.post_id = '$likedPostID'";
        $increasePostLikeQueryPrep = $mysqli->prepare($increasePostLikeQuery);
        $increasePostLikeQueryResult = $increasePostLikeQueryPrep->execute();
        $increasePostLikeQueryPrep->close();
    }
}
if( isset($_POST['dislike'])) {
    $dislikedPostID = $_POST['dislike'];
    //search if rates relation already exists
    $getRatesInfoQuery = "SELECT * FROM rates_post WHERE rates_post.post_id = '$dislikedPostID' AND rates_post.user_id = '$searchedUserID'";
    $getRatesInfoLikeQueryResult = $mysqli->query($getRatesInfoQuery . " AND rates_post.is_like = false");
    if($getRatesInfoLikeQueryResult->num_rows==1) {
        echo "You have already disliked this post.";
    }
    else{
        //Check if user previously liked book
        $getRatesInfoDislikeQueryResult = $mysqli->query($getRatesInfoQuery . " AND rates_post.is_like = true");
        if($getRatesInfoDislikeQueryResult->num_rows==1) {
            echo " You have liked this post previously";
            //Update previously liked rates relation as dislike
            $updateRatesQuery = "UPDATE rates_post SET is_like = FALSE WHERE rates_post.post_id = '$dislikedPostID' AND rates_post.user_id = '$searchedUserID'";
            $updateRatesQueryPrep = $mysqli->prepare($updateRatesQuery);
            $updateRatesQueryResult = $updateRatesQueryPrep->execute();
            $updateRatesQueryPrep->close();
            //Decrease book like count
            $decreasePostDislikeQuery = "UPDATE post SET like_count = like_count - 1 WHERE post.post_id = '$dislikedPostID'";
            $decreasePostDislikeQueryPrep = $mysqli->prepare($decreasePostDislikeQuery);
            $decreasePostDislikeQueryResult = $decreasePostDislikeQueryPrep->execute();
            $decreasePostDislikeQueryPrep->close();
        }
        else {
            //add new rates relation
            $insertRatesQuery = "INSERT INTO rates_post(post_id, user_id, is_like) 
                                VALUES ('$dislikedPostID', '$searchedUserID', FALSE)";
            $insertRatesQueryPrep = $mysqli->prepare($insertRatesQuery);
            $insertRatesQueryResult = $insertRatesQueryPrep->execute();
            $insertRatesQueryPrep->close();
        }
        //increase book dislike count by 1
        $increasePostDislikeQuery = "UPDATE post SET dislike_count = dislike_count + 1 WHERE post.post_id = '$dislikedPostID'";
        $increasePostDislikeQueryPrep = $mysqli->prepare($increasePostDislikeQuery);
        $increasePostDislikeQueryResult = $increasePostDislikeQueryPrep->execute();
        $increasePostDislikeQueryPrep->close();
    }
}
//Display user posts
$getUserPostsQuery = "SELECT * FROM post NATURAL JOIN posts WHERE user_id = '$searchedUserID'";
$getUserPostsQueryResult = $mysqli->query($getUserPostsQuery);
$getUserPostsQueryRowNum = mysqli_num_rows( $getUserPostsQueryResult);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        UserProfile
    </title>
</head>
<body>
<div style="width: 49%; position: absolute; top: 0px; left: 150px;">
    <img  style="position: relative; height: 180px; width: 280px;" src="img/<?php echo"$userProfilePic";?>">
    <h1><?php echo"$userName"; ?></h1>
    <td>Gender : <?php echo"$userGender"; ?></td>
    <br></br>
    <td>Born On : <?php echo"$userBirthday"; ?></td>
    <br></br>
    <td>Joined at : <?php echo"$userCreatedAt"; ?></td>
    <br></br>
    <?php
        if($searchedUserID == $_SESSION['userID']) {
            //User is in his/her profile can create posts
            echo "<form class=\"button\" method=\"post\">
                    <label>Create new Post:</label><br></br>
                    <textarea type=\"text\" id=\"postContent\" name=\"postContent\" rows='8' cols='50' placeholder='Share with your friends...'></textarea><br></br>
                    <input type=\"submit\" name=\"postSubmit\" value=\"Submit\"/>
                  </form>";
        }
    ?>
</div>
<div style="width: 49%; position: absolute; top: 0px; right: 150px; width: 500px; height: 600px; border: 1px solid black; border-collapse: collapse; overflow:scroll;">
    <h1>Posts: </h1>
    <?php
        if( $getUserPostsQueryRowNum > 0) {
            while ($getUserPostsQueryRow = mysqli_fetch_assoc($getUserPostsQueryResult)) {
                echo "<div style='overflow:scroll; border: 1px solid black; border-collapse: collapse;'>
                        <img style='position: relative; height: 50px; width: 50px;' src='img/".$userProfilePic."'><span><b>".$userName."</b><br><b>Created at: ".$getUserPostsQueryRow['date']."</b></span>
                        <p>" . $getUserPostsQueryRow['text'] . "</p>
                        <b>Like Count: </b>".$getUserPostsQueryRow['like_count']."<br><b>Dislike Count: </b>".$getUserPostsQueryRow['dislike_count']."
                        <form style='margin-top: 10px; margin-bottom: 5px' method='post'>
                            <button name='like' value='".$getUserPostsQueryRow['post_id']."'><img src='img/like.png' alt='Like' style='position: relative; height: 20px; width: 20px;'></button>
                            <button name='dislike' value='".$getUserPostsQueryRow['post_id']."'><img src='img/dislike.png' alt='Dislike' style='position: relative; height: 20px; width: 20px;'></button>
                        </form>
                      </div>";
            }
        }
    ?>
</div>
</body>
</html>

