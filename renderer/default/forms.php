<?php



function dispLoginForm() {
    echo '
    <div class="login_form">
    <form action="./admin.php" method="POST">
    <input type="hidden" name="method" value="login" />
    UserID:<br />
    <input size="64" name="login_name" /><br />
    Password:<br />
    <input type="password" name="login_password" /><br />
    <input type="submit" value="Login" />
    </form>
    </div>
    ';
}

function dispEditingToolBoxWith($textarea_id){
    $str_target = 'document.getElementById("' . $textarea_id . '").value';
    echo '
    <script>
    var editor_bold_added = false,
        editor_italic_added = false,
        editor_strike_added = false,
        editor_u_added = false,
        editor_sub_added = false,
        editor_sup_added = false,
        editor_em_added = false,
        editor_strong_added = false;
    function addBold(){
        ' . $str_target . '+="<"+(editor_bold_added?"/":"")+"b>";editor_bold_added = !editor_bold_added;
        document.getElementById("editor_bold_btn").innerHTML = (editor_bold_added?"!":"")+"Bold";
    }
    function addItalic(){
        ' . $str_target . '+="<"+(editor_bold_added?"/":"")+"i>";editor_italic_added = !editor_italic_added;
        document.getElementById("editor_italic_btn").innerHTML = (editor_italic_added?"!":"")+"Italic";
    }
    function addStrikethrough(){
        ' . $str_target . '+="<"+(editor_strike_added?"/":"")+"del>";editor_strike_added = !editor_strike_added;
        document.getElementById("editor_strike_btn").innerHTML = (editor_strike_added?"!":"")+"Strike-through";
    }
    function addUnderscore(){
        ' . $str_target . '+="<"+(editor_u_added?"/":"")+"u>";editor_u_added = !editor_u_added;
        document.getElementById("editor_underscore_btn").innerHTML = (editor_u_added?"!":"")+"Underscore";
    }
    function addSub(){
        ' . $str_target . '+="<"+(editor_sub_added?"/":"")+"sub>";editor_sub_added = !editor_sub_added;
        document.getElementById("editor_sub_btn").innerHTML = (editor_sub_added?"!":"")+"Sub";
    }
    function addSup(){
        ' . $str_target . '+="<"+(editor_sup_added?"/":"")+"sup>";editor_sup_added = !editor_sup_added;
        document.getElementById("editor_sup_btn").innerHTML = (editor_sup_added?"!":"")+"Sup";
    }
    function addEmphasis(){
        ' . $str_target . '+="<"+(editor_em_added?"/":"")+"em>";editor_em_added = !editor_em_added;
        document.getElementById("editor_em_btn").innerHTML = (editor_em_added?"!":"")+"Emphasis";
    }
    function addStrong(){
        ' . $str_target . '+="<"+(editor_strong_added?"/":"")+"strong>";editor_strong_added = !editor_strong_added;
        document.getElementById("editor_strong_btn").innerHTML = (editor_strong_added?"!":"")+"Strong";
    }
    function addLink(){
        var linkText = prompt("linkText:","");
        var linkAddr = prompt("linkAddr:","");
        ' . $str_target . '+="<a href=\""+linkAddr+"\">"+linkText+"</a>"; 
    }
    function addBreak(){' . $str_target . '+="<br />";}
    </script>
    <b><a onclick="addBold()" id="editor_bold_btn">Bold</a></b>
    <i><a onclick="addItalic()" id="editor_italic_btn">Italic</a></i>
    <del><a onclick="addStrikethrough()" id="editor_strike_btn">Strike-through</a></del>
    <u><a onclick="addUnderscore()" id="editor_underscore_btn">Underscore</a></u>
    <sub><a onclick="addSub()" id="editor_sub_btn">Sub</a></sub>
    <sup><a onclick="addSup()" id="editor_sup_btn">Sup</a></sup>
    <em><a onclick="addEmphasis()" id="editor_em_btn">Emphasis</a></em>
    <strong><a onclick="addStrong()" id="editor_strong_btn">Strong</a></strong>
    <b><u><a onclick="addLink()">Hyperlink</a></u></b>
    <a onclick="addBreak()">&lt;break /&gt;</a>
    ';
}
function displayEditor($id,$attr){
    echo '<textarea id="' . $id . '" ' . $attr . ' ></textarea>';
}

function dispCommentFormFor($id) {
    echo '
    <div class="comment_form">
    <table>
    <form action="./index.php" method="POST">
    <input type="hidden" name="id" value="' . $id . '" />
    <input type="hidden" name="mode" value="comment" />
    <tr><td>Name: </td><td><input size="64" name="comment_name" style="width:100%"/></td></tr>
    <tr><td>Comment body: </td><td>'; dispEditingToolBoxWith("comment_body_editor"); echo '</td></tr>
    <tr><td /><td>'; displayEditor("comment_body_editor",'rows="10" cols="64" name="comment_body" style="width:100%"'); echo '</td></tr>
    <tr><td /><td><input type="submit" value="Submit" /></td></tr>
    </table>
    </div>
    ';
}

function dispBlogpostEditingForm($id,$date,$userid,$title,$body) {
    echo '
    You are editing ID' . $id . ', ' . $date . '.<br />
    <table>
    <form method="POST">
    <input type="hidden" name="mode" value="manage" />
    <input type="hidden" name="submod" value="editpost" />
    <tr><td>Title: </td><td><input name="title" size="128" value="' . $title . '" style="width:100%"/></td>
    <tr><td>Body: </td><td>'; dispEditingToolBoxWith("blogpost_body_textarea"); echo '</td></tr>
    <tr><td /><td><textarea name="body" rows="20" cols="128" id="blogpost_body_textarea">' . $body . '</textarea></td></tr>
    <tr><td /><td><input type="submit" value="Modify" /></td></tr>
    </form>
    </table>
    ';
}
function dispBlogpostEditingFormWith($title,$body) {
    echo '
    <table>
      <form method="POST">
        <input type="hidden" name="mode" value="post" />
        <tr><td>Title: </td><td><input name="title" value="' . $title . '" size="128"/></td></tr>
        <tr><td>Body: </td><td>'; dispEditingToolboxWith("blogpost_editing_textarea"); echo '</td></tr>
        <tr><td /><td><textarea id="blogpost_editing_textarea" name="body" rows="20" cols="128">' . $body . '</textarea></td></tr>
        <tr><td /><td><input type="button" onclick="history.back()" value="Back"/><input type="submit" value="Post" /></td></tr>
      </form>
      </table>
      ';
}
function dispCommentEditingForm($commentid,$blogpostid,$date,$name,$body) {
    echo 'You are editing CID' . $commentid . ' under ID' . $blogpostid . ', ' . $date .'.<br />';
    echo '
    <table>
    <form action="./admin.php" method="POST">
    <input type="hidden" name="mode" value="commentm" />
    <input type="hidden" name="submod" value="edit" />
    <input type="hidden" name="id" value="' . $commentid . '" />
    <tr><td>Name: </td><td><input name="comment_name" value="' . $name . '" size="128" style="width:100%" /></td></tr>
    <tr><td>Comment body: </td><td>'; dispEditingToolBoxWith("comment_editing_textarea"); echo '</td></tr>
    <tr><td /><td><textarea name="comment_body" id="comment_editing_textarea" rows="20" cols="128">' . $body . '</textarea></td></tr>
    <tr><td /><td><input type="button" onclick="history.back()" value="Back" /><input type="submit" value="Modify" /></td></tr>
    </form>
    </table>
    ';
}
function dispNewBlogpostForm() {
    echo '
    <table>
    <form method="POST">
      <input type="hidden" name="mode" value="preview" />
      <tr><td>Title: </td><td><input name="title" size="128" style="width:100%" /></td></tr>
      <tr><td>Body: </td><td>'; dispEditingToolboxWith("blogpost_editing_textarea"); echo '</td></tr>
      <tr><td /><td><textarea name="body" rows="20" cols="128" id="blogpost_editing_textarea"></textarea></td></tr>
      <tr><td /><td><input type="submit" value="Preview" /></td></tr>
    </form>
    </table>
    ';
}


function dispNewUserForm_Backstage() {
    echo '
    <form method="POST">
    <input type="hidden" name="mode" value="userm" />
    <input type="hidden" name="submod" value="newuser" />
    <input type="hidden" name="state" value="phase1" />
    New user name:<br />
    <input name="newuser_name" size="64" /><br />
    New user password:<br />
    <input type="password" name="newuser_password" /><br />
    <input type="submit" value="Register" />
    </form>
    ';
}

function dispUserEditingForm($id) {
    echo '
    <form method="POST">
    <input type="hidden" name="mode" value="userm" />
    <input type="hidden" name="id" value="' . $id . '" />
    <input type="hidden" name="state" value="phase1" />
    You are editing UID' . $id . '.
    <a href="./admin.php?mode=userm&submod=deluser&id=' . $id . '">Delete this user</a><br />
    Username:<br />
    <input name="user_name" size="64" value="' . retrUsername($id) . '" /><br />
    Password:<br />
    <input type="password" name="user_password" /><br />
    <input type="submit" value="Modify" />
    </form>
    ';
}

function dispUploadForm($action,$name,$vars){
    echo '
    <form method="post" id="' . $name . '_form" action="' . $action . '" enctype="multipart/form-data">
    ' . $vars . '
    <input type="file" name="' . $name . '" accept=".xml" />
    <input type="submit" value="Import" />
    </form>
    ';
}


?>