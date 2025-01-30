<!DOCTYPE html>
<html>
<head>
    <title>Share Document</title>
</head>
<body>
<div>
    <h1>Share a document with you from {{config("app.name")}}</h1>
    <p>{{$content}}</p>
    <p>You can access the document using the following link:</p>
    <a href="{{ $link }}" target="_blank" style="width: 100%; position: relative">Click Here to view document</a>
</div>
</body>
</html>
