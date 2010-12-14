<?php
/* run from the command line.
*
* To run the sample:
* php xfer_to_blogger.php --user=email@email.com --pass=password
*/

ini_set('include_path', '/path/to/Zend/library/:' . ini_get('include_path'));
define('dbName', 'xxxxxxxx');
define('dbUser', 'xxxxxxxx');
define('dbPass', 'xxxxxxxx');
define('dbHost', 'xxxxxxxx');

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';
require_once 'Zend/Date.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Feed');
Zend_Loader::loadClass('Zend_Gdata_Query');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');


/**
 * Class that contains all simple CRUD operations for Blogger.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SimpleCRUD
{
    /**
     * $blogID - Blog ID used for demo operations
     *
     * @var string
     */
    public $blogID;

    /**
     * $gdClient - Client class used to communicate with the Blogger service
     *
     * @var Zend_Gdata_Client
     */
    public $gdClient;


    /**
     * Constructor for the class. Takes in user credentials and generates the
     * the authenticated client object.
     *
     * @param  string $email    The user's email address.
     * @param  string $password The user's password.
     * @return void
     */
    public function __construct($email, $password)
    {
        $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password, 'blogger');
        $this->gdClient = new Zend_Gdata($client);
    }

    /**
     * This function retrieves all the blogs associated with the authenticated
     * user and prompts the user to choose which to manipulate.
     *
     * Once the index is selected by the user, the corresponding blogID is
     * extracted and stored for easy access.
     *
     * @return void
     */
    public function promptForBlogID()
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs');
        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
        $input = getInput("\nSelection");

        //id text is of the form: tag:blogger.com,1999:user-blogID.blogs
        $idText = explode('-', $feed->entries[$input]->id->text);
        $this->blogID = $idText[2];
    }

    /**
     * This function creates a new Zend_Gdata_Entry representing a blog
     * post, and inserts it into the user's blog. It also checks for
     * whether the post should be added as a draft or as a published
     * post.
     *
     * @param  string  $title   The title of the blog post.
     * @param  string  $content The body of the post.
     * @param  array   $tags    List of tag names that we're using as Google labels
     * @return string The newly created post's ID
     */
    public function createPost($title, $content, $tags, $date = null)
    {
        // We're using the magic factory method to create a Zend_Gdata_Entry.
        // http://framework.zend.com/manual/en/zend.gdata.html#zend.gdata.introdduction.magicfactory
        $entry = $this->gdClient->newEntry();
        // echo '<pre>' . print_r(get_class_methods($entry), true) . '</pre>';
        $entry->title = $this->gdClient->newTitle(trim($title));
        $entry->content = $this->gdClient->newContent(trim($content));
        $entry->content->setType('text');
        $uri = "http://www.blogger.com/feeds/" . $this->blogID . "/posts/default";

        // add labels (tags/categories)
        $scheme = "http://www.blogger.com/atom/ns#";
        $categories = array();
        foreach($tags as $tag)
        {
            $categories[] = new Zend_Gdata_App_Extension_Category($tag, $scheme);
        }
        $entry->setCategory($categories);

        // set publish date
        if (isset($date))
        {
          $published = new Zend_Gdata_App_Extension_Published($date);
          $entry->setPublished($published);
        }
 
        $createdPost = $this->gdClient->insertEntry($entry, $uri);
        //format of id text: tag:blogger.com,1999:blog-blogID.post-postID
        $idText = explode('-', $createdPost->id->text);
        $postID = $idText[2];

        return $postID;
    }

    /**
     * Prints the titles of all the posts in the user's blog.
     *
     * @return void
     */
    public function printAllPosts()
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default');
        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * Retrieves the specified post and updates the title and body. Also sets
     * the post's draft status.
     *
     * @param string  $postID         The ID of the post to update. PostID in <id> field:
     *                                tag:blogger.com,1999:blog-blogID.post-postID
     * @param string  $updatedTitle   The new title of the post.
     * @param string  $updatedContent The new body of the post.
     * @param boolean $isDraft        Whether the post will be published or saved as a draft.
     * @return Zend_Gdata_Entry The updated post.
     */
    public function updatePost($postID, $updatedTitle, $updatedContent, $isDraft)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default/' . $postID);
        $postToUpdate = $this->gdClient->getEntry($query);
        $postToUpdate->title->text = $this->gdClient->newTitle(trim($updatedTitle));
        $postToUpdate->content->text = $this->gdClient->newContent(trim($updatedContent));

        if ($isDraft) {
            $draft = $this->gdClient->newDraft('yes');
        } else {
            $draft = $this->gdClient->newDraft('no');
        }

        $control = $this->gdClient->newControl();
        $control->setDraft($draft);
        $postToUpdate->control = $control;
        $updatedPost = $postToUpdate->save();

        return $updatedPost;
    }

    /**
     * This function uses query parameters to retrieve and print all posts
     * within a specified date range.
     *
     * @param  string $startDate Beginning date, inclusive. Preferred format is a RFC-3339 date,
     *                           though other formats are accepted.
     * @param  string $endDate   End date, exclusive.
     * @return void
     */
    public function printPostsInDateRange($startDate, $endDate)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default');
        $query->setParam('published-min', $startDate);
        $query->setParam('published-max', $endDate);

        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * This function creates a new comment and adds it to the specified post.
     * A comment is created as a Zend_Gdata_Entry.
     *
     * @param  string $postID      The ID of the post to add the comment to. PostID
     *                             in the <id> field: tag:blogger.com,1999:blog-blogID.post-postID
     * @param  string $commentText The text of the comment to add.
     * @return string The ID of the newly created comment.
     */
    public function createComment($postID, $commentText)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default';

        $newComment = $this->gdClient->newEntry();
        $newComment->content = $this->gdClient->newContent($commentText);
        $newComment->content->setType('text');
        $createdComment = $this->gdClient->insertEntry($newComment, $uri);

        echo 'Added new comment: ' . $createdComment->content->text . "\n";
        // Edit link follows format: /feeds/blogID/postID/comments/default/commentID
        $editLink = explode('/', $createdComment->getEditLink()->href);
        $commentID = $editLink[8];

        return $commentID;
    }

    /**
     * This function prints all comments associated with the specified post.
     *
     * @param  string $postID The ID of the post whose comments we'll print.
     * @return void
     */
    public function printAllComments($postID)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default');
        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * This function deletes the specified comment from a post.
     *
     * @param  string $postID    The ID of the post where the comment is. PostID in
     *                           the <id> field: tag:blogger.com,1999:blog-blogID.post-postID
     * @param  string $commentID The ID of the comment to delete. The commentID
     *                           in the editURL: /feeds/blogID/postID/comments/default/commentID
     * @return void
     */
    public function deleteComment($postID, $commentID)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default/' . $commentID;
        $this->gdClient->delete($uri);
    }

    /**
     * This function deletes the specified post.
     *
     * @param  string $postID The ID of the post to delete.
     * @return void
     */
    public function deletePost($postID)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/posts/default/' . $postID;
        $this->gdClient->delete($uri);
    }

    /**
     * Helper function to print out the titles of all supplied Blogger
     * feeds.
     *
     * @param  Zend_Gdata_Feed The feed to print.
     * @return void
     */
    public function printFeed($feed)
    {
        $i = 0;
        foreach($feed->entries as $entry)
        {
            echo "\t" . $i ." ". $entry->title->text . "\n";
            $i++;
        }
    }

    /**
     * Runs the sample.
     *
     * @return void
     */
    public function run()
    {
        echo "Note: This will create new blog posts from an exported WordPress blog " .
             "stored in the account provided.  Please exit now if you provided " .
             "an account which contains important data.\n\n";
        $this->promptForBlogID();

        echo "Fetching WordPress Posts.\n";

        echo "Creating posts.\n";
        $posts = $this->getPosts();
        foreach($posts as  $num => $post)
        {
          $date = new Zend_Date();
          $date->set($post['post_date']);
          $post_date = $date->get(Zend_Date::ATOM);

          $title = $post['post_title'];
          $content = $post['post_content'];
          $tags = $this->getTagsByPost($post['id']);
          echo "Date:  "  . $post_date . "\n";
          echo "Title: "  . $title . "\n";
          echo "  Tags: " . trim(implode(', ', $tags), ', ') . "\n";
          echo " ---- \n";
          $this->createPost($title, $content, $tags, $post_date);
        }
    }

    /**
     *
     * Get the posts from WordPress
     *  we use a limit because Blogger rate limits you to 50 new posts per day
     * @return array    List of WordPress posts with dates, title, content 
     */
    function getPosts()
    {
      $posts = array();
      $sql = "SELECT ID AS id, UNIX_TIMESTAMP(post_date) AS post_date, post_title, post_content FROM wp_posts ";
      $sql .= "WHERE post_type = 'post' AND post_status='publish' ORDER BY post_date LIMIT 50 ";
      $result = mysql_query($sql);
      while ($row = mysql_fetch_assoc($result))
      {
        $posts[] = $row;
      } 
      return $posts;
    }

    /**
     * Get the tags for the relevant WordPress post
     *
     * @param  int     $pid    The id of the WordPress post 
     * @return array           List of tags used for that post, all in lowercase 
     */
    function getTagsByPost($pid)
    {
        $terms = array();

        $sql = "SELECT d.name ";
        $sql .= "FROM wp_posts a, wp_term_relationships b, wp_term_taxonomy c, wp_terms d ";
        $sql .= "WHERE a.post_type='post' ";
        $sql .= "AND  a.ID = b.object_id ";
        $sql .= "AND b.term_taxonomy_id = c.term_taxonomy_id ";
        $sql .= "AND c.term_id = d.term_id ";
        $sql .= "AND a.ID = " . $pid;

        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result))
        {   
          $terms[] = strtolower($row['name']);
        } 
        return $terms;
    }


}

/**
 * Gets credentials from user.
 *
 * @param  string $text
 * @return string Index of the blog the user has chosen.
 */
function getInput($text)
{
    echo $text.': ';
    return trim(fgets(STDIN));
}

$user = null;
$pass = null;

// process command line options
foreach ($argv as $argument) {
    $argParts = explode('=', $argument);
    if ($argParts[0] == '--user') {
        $user = $argParts[1];
    } else if ($argParts[0] == '--pass') {
        $pass = $argParts[1];
    }
}

if (($user == null) || ($pass == null)) {
    exit("php xfer_to_blogger.php --user=[username] --pass=[password]\n");
}



$dbh = mysql_connect(dbHost, dbUser, dbPass) OR die(mysql_error());
mysql_select_db(dbName) OR die(mysql_error());  
$sample = new SimpleCRUD($user, $pass);
$sample->run();

mysql_close($dbh);

?>
