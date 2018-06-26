<?php
require_once("./routine.php");
if(hashvalValid()){
  if(isset($_REQUEST["mode"])){
    echo '<h3>Escape backup utility</h3><hr>';
    if(strcmp($_REQUEST["mode"],"export")==0){
      echo 'Right click on <a href="./backup.php?export_output=">this</a> link and choose "Save as..." to save your backup file.';
    } else if(strcmp($_REQUEST["mode"],"import")==0){
      echo '
      <form action="./backup.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mode" value="import" />
      <input type="hidden" name="action" value="upload" />
      <input type="file" name="import_file" id="import_file" accept=".xml" />
      <input type="submit" value="Import" />
      </form>
      ';
      if(isset($_REQUEST["action"])){
        if(strcmp($_REQUEST["action"],"upload")==0){
          $xmlString = trim(file_get_contents($_FILES["import_file"]["tmp_name"]));
          $xml = simplexml_load_string($xmlString);
          // config.
          $new_blog_name = $xml->Config->Blogname;
          $new_current_template = $xml->Config->CurrentTemplate;
          echo 'Recovering blog name... '; setBlogName($new_blog_name); echo 'done.<br />';
          echo 'Recovering default theme... '; setCurrentTemplate($new_current_template); echo 'done.<br />';
          // posts.
          echo 'Recovering posts... ';
          foreach($xml->Posts->children() as $post){
            insertBlogpost_Exact($post["ID"],$post["Date"],$post["UserID"],$post->Title,$post->Body);
          }
          echo 'done.<br />';
          // comments.
          echo 'Recovering comments... ';
          foreach($xml->Comments->children() as $comment){
            insertComment_Exact($comment["ID"],$comment["BlogpostID"],$comment["Date"],$comment->Name,$comment->Body);
          }
          echo 'done.<br />';
          // users.
          echo 'Recovering users... ';
          foreach($xml->Users->children() as $user){
            insertUser_Exact($user["ID"],$user["Username"],$user["Password"]);
          }
          echo 'done.<br />';

          echo 'Recovering done. Click <a href="./admin.php">here</a> to go back.';
        }
      }
    }
  } else if(isset($_REQUEST["export_output"])){
      $xmlWriter = new XMLWriter();
      $xmlWriter->openMemory();
      $xmlWriter->startDocument("1.0","UTF-8");

      $xmlWriter->setIndent(true);
      $xmlWriter->setIndentString("    ");
  
      $xmlWriter->startElement("Escape");
      $xmlWriter->startElement("Config");
      $xmlWriter->startElement("Blogname");
      $xmlWriter->text(retrBlogname());
      $xmlWriter->endElement();
      $xmlWriter->startElement("CurrentTemplate");
      $xmlWriter->text(retrCurrentTemplate());
      $xmlWriter->endElement();
      $xmlWriter->endElement();
      $xmlWriter->startElement("Posts");
  
      forBlogposts(
        function($id,$date,$userid,$title,$body){
          global $xmlWriter;
          $xmlWriter->startElement("Post");
          $xmlWriter->startAttribute("ID"); $xmlWriter->text($id); $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("Date"); $xmlWriter->text($date); $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("UserID"); $xmlWriter->text($userid); $xmlWriter->endAttribute();
          $xmlWriter->startElement("Title"); $xmlWriter->text($title); $xmlWriter->endElement();
          $xmlWriter->startElement("Body"); $xmlWriter->text($body); $xmlWriter->endElement();
          $xmlWriter->endElement();
        }
      );  
      $xmlWriter->endElement();
      $xmlWriter->startElement("Comments");
      forBlogposts(function($id,$date,$userid,$title,$body){
        global $xmlWriter;
        forCommentsAtBlogID($id,function($commentid,$blogpostid,$date,$name,$body){
          global $xmlWriter;
          $xmlWriter->startElement("Comment");
          $xmlWriter->startAttribute("ID"); $xmlWriter->text($commentid); $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("BlogpostID"); $xmlWriter->text($blogpostid); $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("Date"); $xmlWriter->text($date); $xmlWriter->endAttribute();
          $xmlWriter->startElement("Name"); $xmlWriter->text($name); $xmlWriter->endElement();
          $xmlWriter->startElement("Body"); $xmlWriter->text($body); $xmlWriter->endElement();
          $xmlWriter->endElement();
        });
      });
      $xmlWriter->endElement();
      $xmlWriter->startElement("Users");
      forUsers(
        function($userid,$username,$userpassword){
          global $xmlWriter;
          $xmlWriter->startElement("User");
          $xmlWriter->startAttribute("ID");
          $xmlWriter->text($userid);
          $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("Username");
          $xmlWriter->text($username);
          $xmlWriter->endAttribute();
          $xmlWriter->startAttribute("Password");
          $xmlWriter->text($userpassword);
          $xmlWriter->endAttribute();
          $xmlWriter->endElement();
        }
      );
      
      $xmlWriter->endElement();
      $xmlWriter->endElement();
      $xmlWriter->endDocument();
      echo $xmlWriter->outputMemory();
  } else {
    echo '<h3>Escape backup utility</h3><hr>';
    echo '<a href="./backup.php?mode=import">Import</a> <a href="./backup.php?mode=export">Export</a>';
  }
} else {
  echo '<h3>Escape backup utility</h3><hr>';
  echo 'You shouldn\'t be here. Click <a onclick="history.back()">here</a> to go back.';
}
?>