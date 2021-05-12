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
$thisUserID = $_SESSION['userID'];
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
                            VALUES ('$lastAddedPostID', '$thisUserID')";
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
    $getRatesInfoQuery = "SELECT * FROM rates_post WHERE rates_post.post_id = '$likedPostID' AND rates_post.user_id = '$thisUserID'";
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
            $updateRatesQuery = "UPDATE rates_post SET is_like = TRUE WHERE rates_post.post_id = '$likedPostID' AND rates_post.user_id = '$thisUserID'";
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
                                VALUES ('$likedPostID', '$thisUserID', TRUE)";
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
    $getRatesInfoQuery = "SELECT * FROM rates_post WHERE rates_post.post_id = '$dislikedPostID' AND rates_post.user_id = '$thisUserID'";
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
            $updateRatesQuery = "UPDATE rates_post SET is_like = FALSE WHERE rates_post.post_id = '$dislikedPostID' AND rates_post.user_id = '$thisUserID'";
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
                                VALUES ('$dislikedPostID', '$thisUserID', FALSE)";
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
//send friend req
if( isset($_POST['addFriendRequest'])) {
    $insertAddAsFriendQuery = "INSERT INTO add_as_friend(user_id, friend_id) 
                                VALUES ('$thisUserID', '$searchedUserID')";
    $insertAddAsFriendQueryPrep = $mysqli->prepare($insertAddAsFriendQuery);
    $insertAddAsFriendQueryResult = $insertAddAsFriendQueryPrep->execute();
    $insertAddAsFriendQueryPrep->close();
}
if( isset($_POST['acceptFriendRequest'])) {
    $insertAddAsFriendQuery = "INSERT INTO add_as_friend(user_id, friend_id) 
                                VALUES ('$thisUserID', '$searchedUserID')";
    $insertAddAsFriendQueryPrep = $mysqli->prepare($insertAddAsFriendQuery);
    $insertAddAsFriendQueryResult = $insertAddAsFriendQueryPrep->execute();
    $insertAddAsFriendQueryPrep->close();
}
//is_friends?
$isFriendRequestSentQuery = "SELECT COUNT(*) AS row_count FROM add_as_friend WHERE user_id = $thisUserID AND friend_id = $searchedUserID";
$isFriendRequestSentQueryResult = $mysqli->query($isFriendRequestSentQuery);
$isFriendRequestSentQueryRow = $isFriendRequestSentQueryResult->fetch_assoc();

$isFriendRequestReceivedQuery = "SELECT COUNT(*) AS row_count FROM add_as_friend WHERE user_id = $searchedUserID AND friend_id = $thisUserID";
$isFriendRequestReceivedQueryResult = $mysqli->query($isFriendRequestReceivedQuery);
$isFriendRequestReceivedQueryRow = $isFriendRequestReceivedQueryResult->fetch_assoc();
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
            echo "<form method=\"post\">
                    <label>Create new Post:</label><br></br>
                    <textarea type=\"text\" id=\"postContent\" name=\"postContent\" rows='8' cols='50' placeholder='Share with your friends...'></textarea><br></br>
                    <input type=\"submit\" name=\"postSubmit\" value=\"Submit\"/>
                  </form>";
        }
        else {
            //user is in another user's profile
            if($isFriendRequestSentQueryRow['row_count'] == 0 && $isFriendRequestReceivedQueryRow['row_count'] == 0){
                //They are not friends and no friend request is sent nor recieved show normal ADD AS FRIEND BUTTON
                echo "<form method=\"post\">
                        <button name='addFriendRequest' class='btn'> ADD AS FRIEND </button>
                      </form>";
            }
            else if($isFriendRequestSentQueryRow['row_count'] == 1 && $isFriendRequestReceivedQueryRow['row_count'] == 0){
                //This user has sent friend request and reciever didn't accept yet Disable add as friend button
                echo "<button name='disabledAddFriend' class='addFriendButton-disable'> PENDING FRIEND REQUEST... </button>";
            }
            else if($isFriendRequestSentQueryRow['row_count'] == 0 && $isFriendRequestReceivedQueryRow['row_count'] == 1){
                //The searched user has previously sent friend request and this user has not accepted yet
                echo "<form method=\"post\">
                        <button name='acceptFriendRequest' class='btn'> ACCEPT FRIEND REQUEST </button>
                      </form>";
            }
            else {
                //users are friends show recommend book button
                echo "<form method=\"post\" action=\"searchBooks.php?recommendBookTo=". urlencode($searchedUserID) . "\">"."
                    <button name='recommendBook' class='btn'> RECOMMEND BOOK </button>
                      </form>";
            }
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
<style>
    .btn {
        background-color: cadetblue;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
    .addFriendButton-disable
    {
        cursor: not-allowed;
        pointer-events: none;
        background-color: cadetblue;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;

    }
</style>
