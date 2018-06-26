<?php
require_once("./routine.php");
require_once("./renderer.php");
error_reporting(E_ALL & ~E_NOTICE);
?>
<html>
  <head>
    <meta charset="UTF8" />
    <title>
    <?php
        if(!$_REQUEST["id"]) {
            echo renderTitle();
        } else {
            echo renderTitle_Singl(intval($_REQUEST["id"]));
        }
        ?></title>
    <?php
    dispStylesheetLink();
    ?>
  </head>
  <body>
      <?php
      dispHeader();
      dispSectionDivider();
      ?>
  <div class="body">
      <?php
      if(!$_REQUEST["id"]) {
          dispBlogpostEntryWrapperWith('dispBlogpostEntry');
      } else {
          dispBlogpostWrapperWith(intval($_REQUEST["id"]),'dispBlogpost');
          # insert comment!
          if(strcmp($_REQUEST["mode"],"comment")==0)
              insertComment($_REQUEST["id"],date("Y-m-d H:i:s"),$_REQUEST["comment_name"],$_REQUEST["comment_body"]);
          dispCommentsWrapperWith(intval($_REQUEST["id"]),'dispComment');
          dispCommentFormFor(intval($_REQUEST["id"]));
      }
      ?>
  </div>
  <?php
  dispSectionDivider();
  dispFooter();
  ?>
  </body>
</html>
<?php
    // Escape也是我做的所以这不是直接挪用他人成果这是代码重用
?>