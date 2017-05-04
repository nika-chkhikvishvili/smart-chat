<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Socket.IO Chat Example</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="asarchevi">
    <select id="select_theme"></select>
    <input type="text" id="first_name" value="" placeholder="First Name">
    <input type="text" id="last_name" value="" placeholder="Last Name">
    <button id="begin_btn">საუბრის დაწყება</button>
</div>

<div id="wrapper">
    <div id="menu">
        <p class="welcome">მოგესალმებით, <span id="saxeli_span"></span>
            <span id="operator_is_working"> &nbsp;&nbsp;&nbsp;გთხოვთ დაელოდოთ</span>
            <span id="operator_is_writing"> <img src="/assets/images/typing.gif" style="height: 16px;margin-left: 10px;" > </span></p>
        <p class="logout"><a id="exit" href="#" >Exit Chat</a></p>
        <div style="clear:both"></div>
    </div>

    <div id="chatbox"></div>
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <button  id="submitmsg" >Send</button>
</div>

<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<script src="main_client.js?<?=rand(); ?>"></script>
</body>
</html>